<?php
/**
 * qr.php — Printable QR business card
 * Generates a real QR PNG server-side (PHP + GD). Zero JS required.
 */

// ── Pure-PHP QR Matrix Generator (byte mode, EC level M) ─────────────────

class QRMatrix {
    private array $modules   = [];
    private array $reserved  = [];
    private int   $n         = 0;

    public function getModuleCount(): int { return $this->n; }
    public function isDark(int $r, int $c): bool { return $this->modules[$r][$c] ?? false; }

    // GF(2^8) tables
    private static function exp(): array {
        static $t = null;
        if ($t) return $t;
        $t = array_fill(0, 256, 0);
        for ($i = 0; $i < 8; $i++)  $t[$i] = 1 << $i;
        for ($i = 8; $i < 256; $i++) $t[$i] = $t[$i-4]^$t[$i-5]^$t[$i-6]^$t[$i-8];
        return $t;
    }
    private static function log(): array {
        static $t = null;
        if ($t) return $t;
        $e = self::exp(); $t = array_fill(0, 256, 0);
        for ($i = 0; $i < 255; $i++) $t[$e[$i]] = $i;
        return $t;
    }
    private static function gexp(int $n): int { $e=self::exp(); while($n<0)$n+=255; while($n>=256)$n-=255; return $e[$n]; }
    private static function glog(int $n): int { if($n<1)throw new \RuntimeException("glog($n)"); return self::log()[$n]; }

    // RS block table — [count, totalCW, dataCW] for typeNum 1-10, ecLevel M(1)
    // ecLevel: 0=L,1=M,2=Q,3=H  — we only need M here
    private static array $RSM = [
        1=>[[1,26,16]],   2=>[[1,44,28]],   3=>[[1,70,44]],
        4=>[[2,50,32]],   5=>[[2,67,43]],   6=>[[4,43,27]],
        7=>[[4,49,31]],   8=>[[2,60,38],[2,61,39]],
        9=>[[3,58,36],[2,59,37]], 10=>[[4,69,43],[1,70,44]],
    ];

    private static function rsBlocks(int $t): array {
        if (!isset(self::$RSM[$t])) throw new \RuntimeException("Type $t not supported");
        $out = [];
        foreach (self::$RSM[$t] as $row) { [$cnt,$tot,$dat] = $row; for($i=0;$i<$cnt;$i++) $out[]=[$tot,$dat]; }
        return $out;
    }

    private static function typeFor(int $len): int {
        for ($t = 1; $t <= 10; $t++) {
            $cap = 0; foreach(self::rsBlocks($t) as $b) $cap += $b[1];
            if ($cap >= $len + 3) return $t; // +3 for mode+len overhead in bytes
        }
        throw new \RuntimeException("String too long");
    }

    // EC polynomial
    private static function ecPoly(int $n): array {
        $p = [1];
        for ($i = 0; $i < $n; $i++) {
            $r = [1, self::gexp($i)];
            $out = array_fill(0, count($p)+count($r)-1, 0);
            foreach ($p as $pi => $pv) foreach ($r as $ri => $rv)
                $out[$pi+$ri] ^= self::gexp((self::glog($pv)+self::glog($rv))%255);
            $p = $out;
        }
        return $p;
    }

    private static function polyMod(array $a, array $b): array {
        while (count($a) >= count($b)) {
            if ($a[0] === 0) { array_shift($a); continue; }
            $r = self::glog($a[0]);
            for ($i = 0; $i < count($b); $i++)
                if ($b[$i] !== 0) $a[$i] ^= self::gexp((self::glog($b[$i])+$r)%255);
            array_shift($a);
        }
        return $a;
    }

    // BCH helpers
    private static function bchDig(int $d): int { $n=0; while($d){$n++;$d>>=1;} return $n; }
    private static function bchFmt(int $d): int {
        $g=0x537; $b=$d<<10;
        while(self::bchDig($b)-self::bchDig($g)>=0) $b^=$g<<(self::bchDig($b)-self::bchDig($g));
        return(($d<<10)|$b)^0x5412;
    }

    // Mask functions
    private static function applyMask(int $m, int $r, int $c): bool {
        switch($m){
            case 0: return ($r+$c)%2===0;     case 1: return $r%2===0;
            case 2: return $c%3===0;           case 3: return ($r+$c)%3===0;
            case 4: return (intdiv($r,2)+intdiv($c,3))%2===0;
            case 5: return $r*$c%2+$r*$c%3===0;
            case 6: return ($r*$c%2+$r*$c%3)%2===0;
            case 7: return ($r*$c%3+($r+$c)%2)%2===0;
        }
        return false;
    }

    private static array $ALIGN = [
        1=>[],2=>[6,18],3=>[6,22],4=>[6,26],5=>[6,30],6=>[6,34],
        7=>[6,22,38],8=>[6,24,42],9=>[6,26,46],10=>[6,28,50],
    ];

    public static function encode(string $text): self {
        $bytes = array_values(unpack('C*', $text));
        $typeNum = self::typeFor(count($bytes));
        $obj = new self();
        $obj->build($bytes, $typeNum);
        return $obj;
    }

    private function build(array $bytes, int $typeNum): void {
        $this->n = $typeNum * 4 + 17;
        $n = $this->n;
        $this->modules  = array_fill(0, $n, array_fill(0, $n, false));
        $this->reserved = array_fill(0, $n, array_fill(0, $n, false));

        $this->finder(0,0); $this->finder($n-7,0); $this->finder(0,$n-7);
        $this->reserveFormat($n);

        foreach ((self::$ALIGN[$typeNum] ?? []) as $ar)
            foreach ((self::$ALIGN[$typeNum] ?? []) as $ac)
                if (!$this->reserved[$ar][$ac]) $this->alignment($ar,$ac);

        for($i=8;$i<$n-8;$i++){if(!$this->reserved[$i][6]){$this->modules[$i][6]=$i%2===0;$this->reserved[$i][6]=true;}}
        for($i=8;$i<$n-8;$i++){if(!$this->reserved[6][$i]){$this->modules[6][$i]=$i%2===0;$this->reserved[6][$i]=true;}}

        $this->modules[$n-8][8]=true; $this->reserved[$n-8][8]=true;

        $codewords = $this->buildCodewords($bytes, $typeNum);
        $this->placeData($codewords);

        $bestPen = PHP_INT_MAX; $bestMask = 0;
        for($m=0;$m<8;$m++){
            $this->formatInfo(1,$m,false); // EC level M = 1
            $p=$this->penalty();
            if($p<$bestPen){$bestPen=$p;$bestMask=$m;}
        }
        $this->formatInfo(1,$bestMask,true);
    }

    private function finder(int $row, int $col): void {
        $n=$this->n;
        for($r=-1;$r<=7;$r++) for($c=-1;$c<=7;$c++){
            $rr=$row+$r; $cc=$col+$c;
            if($rr<0||$rr>=$n||$cc<0||$cc>=$n) continue;
            $dark=($r>=0&&$r<=6&&($c===0||$c===6))||($c>=0&&$c<=6&&($r===0||$r===6))||($r>=2&&$r<=4&&$c>=2&&$c<=4);
            $this->modules[$rr][$cc]=$dark; $this->reserved[$rr][$cc]=true;
        }
    }

    private function alignment(int $row, int $col): void {
        for($r=-2;$r<=2;$r++) for($c=-2;$c<=2;$c++){
            $dark=($r===-2||$r===2||$c===-2||$c===2||($r===0&&$c===0));
            $this->modules[$row+$r][$col+$c]=$dark; $this->reserved[$row+$r][$col+$c]=true;
        }
    }

    private function reserveFormat(int $n): void {
        for($i=0;$i<=8;$i++){$this->reserved[$i][8]=true;$this->reserved[8][$i]=true;}
        for($i=$n-8;$i<$n;$i++){$this->reserved[8][$i]=true;$this->reserved[$i][8]=true;}
    }

    private function formatInfo(int $ecLevel, int $mask, bool $write): void {
        $n=$this->n;
        $ecMap=[1=>0,0=>1,3=>2,2=>3]; // M,L,H,Q -> format bits
        $fi=self::bchFmt(($ecMap[$ecLevel]<<3)|$mask);
        for($i=0;$i<15;$i++){
            $v=(($fi>>$i)&1)===1;
            if($write){
                if($i<6)$this->modules[$i][8]=$v;
                elseif($i<8)$this->modules[$i+1][8]=$v;
                else$this->modules[$n-15+$i][8]=$v;
                if($i<8)$this->modules[8][$n-$i-1]=$v;
                elseif($i<9)$this->modules[8][15-$i]=$v;
                else$this->modules[8][15-$i-1]=$v;
            }
        }
        if(!$write) return;
        for($r=0;$r<$n;$r++) for($c=0;$c<$n;$c++)
            if(!$this->reserved[$r][$c]&&self::applyMask($mask,$r,$c))
                $this->modules[$r][$c]=!$this->modules[$r][$c];
    }

    private function buildCodewords(array $bytes, int $typeNum): array {
        $blocks=$this->rsBlocks($typeNum);
        $totalData=0; foreach($blocks as $b) $totalData+=$b[1];

        $bits=[];
        self::pb($bits,0b0100,4);          // byte mode
        self::pb($bits,count($bytes),8);   // length (typeNum<10 = 8 bits)
        foreach($bytes as $byte) self::pb($bits,$byte,8);
        $cap=$totalData*8;
        $term=min(4,$cap-count($bits)); if($term>0)self::pb($bits,0,$term);
        while(count($bits)%8!==0)$bits[]=0;
        $pads=[0xEC,0x11]; $pi=0;
        while(count($bits)<$cap)self::pb($bits,$pads[$pi++%2],8);

        $byt=[];
        for($i=0;$i<count($bits);$i+=8){$b=0;for($j=0;$j<8;$j++)$b=($b<<1)|($bits[$i+$j]??0);$byt[]=$b;}

        $dataBl=[]; $ecBl=[]; $off=0;
        foreach($blocks as [$tot,$dc]){
            $ec=$tot-$dc; $block=array_slice($byt,$off,$dc); $off+=$dc;
            $poly=self::ecPoly($ec);
            $msg=array_merge($block,array_fill(0,$ec,0));
            $rem=self::polyMod($msg,$poly);
            while(count($rem)<$ec)array_unshift($rem,0);
            $dataBl[]=$block; $ecBl[]=$rem;
        }
        $res=[]; $maxD=max(array_map('count',$dataBl));
        for($i=0;$i<$maxD;$i++) foreach($dataBl as $b) if(isset($b[$i]))$res[]=$b[$i];
        $maxE=max(array_map('count',$ecBl));
        for($i=0;$i<$maxE;$i++) foreach($ecBl as $b) if(isset($b[$i]))$res[]=$b[$i];
        return $res;
    }

    private static function pb(array &$bits, int $val, int $n): void {
        for($i=$n-1;$i>=0;$i--)$bits[]=($val>>$i)&1;
    }

    private function placeData(array $codewords): void {
        $n=$this->n; $bits=[];
        foreach($codewords as $cw) for($i=7;$i>=0;$i--)$bits[]=($cw>>$i)&1;
        $idx=0; $up=true;
        for($col=$n-1;$col>=1;$col-=2){
            if($col===6)$col--;
            for($row=0;$row<$n;$row++){
                $r=$up?$n-1-$row:$row;
                for($d=0;$d<2;$d++){
                    $c=$col-$d;
                    if($this->reserved[$r][$c])continue;
                    $this->modules[$r][$c]=isset($bits[$idx])&&$bits[$idx++]===1;
                }
            }
            $up=!$up;
        }
    }

    private function penalty(): int {
        $n=$this->n; $pen=0;
        for($r=0;$r<$n;$r++){$run=1;for($c=1;$c<$n;$c++){if($this->modules[$r][$c]===$this->modules[$r][$c-1]){$run++;if($run===5)$pen+=3;elseif($run>5)$pen++;}else$run=1;}}
        for($c=0;$c<$n;$c++){$run=1;for($r=1;$r<$n;$r++){if($this->modules[$r][$c]===$this->modules[$r-1][$c]){$run++;if($run===5)$pen+=3;elseif($run>5)$pen++;}else$run=1;}}
        for($r=0;$r<$n-1;$r++)for($c=0;$c<$n-1;$c++){$v=$this->modules[$r][$c];if($v===$this->modules[$r][$c+1]&&$v===$this->modules[$r+1][$c]&&$v===$this->modules[$r+1][$c+1])$pen+=3;}
        return $pen;
    }
}

function qr_png(string $url, int $cell=10, int $margin=4): string {
    $m   = QRMatrix::encode($url);
    $cnt = $m->getModuleCount();
    $sz  = ($cnt + $margin*2) * $cell;
    $img = imagecreatetruecolor($sz,$sz);
    $w   = imagecolorallocate($img,255,255,255);
    $b   = imagecolorallocate($img,0,0,0);
    imagefill($img,0,0,$w);
    for($r=0;$r<$cnt;$r++) for($c=0;$c<$cnt;$c++) if($m->isDark($r,$c)){
        $x=($c+$margin)*$cell; $y=($r+$margin)*$cell;
        imagefilledrectangle($img,$x,$y,$x+$cell-1,$y+$cell-1,$b);
    }
    ob_start(); imagepng($img); $png=ob_get_clean(); imagedestroy($img);
    return $png;
}

$TARGET = 'https://jakebartoncreative.com/card';
$qrSmall = 'data:image/png;base64,' . base64_encode(qr_png($TARGET, 10, 4));
$qrBig   = 'data:image/png;base64,' . base64_encode(qr_png($TARGET, 20, 4));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jake Barton — QR Business Card</title>
  <link rel="icon" type="image/png" href="assets/images/jb-logo.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    :root{--bg:#0a0a0a;--card:#141414;--border:rgba(255,255,255,0.09);--text:#f5f5f5;--muted:rgba(255,255,255,0.4);--faint:rgba(255,255,255,0.12)}
    body{background:var(--bg);color:var(--text);font-family:'Inter',system-ui,sans-serif;-webkit-font-smoothing:antialiased;min-height:100dvh;display:flex;flex-direction:column;align-items:center;justify-content:flex-start;padding:3rem 1.5rem 4rem;gap:2.5rem}
    .qr-header{text-align:center;animation:fade-up .5s cubic-bezier(.16,1,.3,1) both}
    .qr-eyebrow{font-size:.65rem;text-transform:uppercase;letter-spacing:.12em;font-family:'Syne',sans-serif;font-weight:700;color:var(--muted);margin-bottom:.5rem}
    .qr-header h1{font-family:'Syne',sans-serif;font-weight:800;font-size:clamp(1.6rem,5vw,2.2rem);letter-spacing:-.03em}
    .qr-card{background:var(--card);border:1px solid var(--border);border-radius:24px;padding:2rem 2rem 1.8rem;width:100%;max-width:360px;display:flex;flex-direction:column;align-items:center;gap:1.5rem;animation:fade-up .55s .08s cubic-bezier(.16,1,.3,1) both}
    .qr-img-wrap{background:#fff;border-radius:16px;padding:16px;display:inline-flex;line-height:0}
    .qr-img-wrap img{display:block;width:220px;height:220px;image-rendering:pixelated;image-rendering:crisp-edges}
    .qr-identity{text-align:center}
    .qr-name{font-family:'Syne',sans-serif;font-size:1.25rem;font-weight:800;letter-spacing:-.02em;margin-bottom:.2rem}
    .qr-role{font-size:.75rem;color:var(--muted);font-weight:500;text-transform:uppercase;letter-spacing:.05em}
    .qr-url{display:flex;align-items:center;gap:.4rem;background:rgba(255,255,255,.05);border:1px solid var(--faint);border-radius:999px;padding:.35rem .9rem;font-size:.7rem;color:var(--muted);font-weight:500;letter-spacing:.03em}
    .qr-url svg{width:11px;height:11px;opacity:.5;flex-shrink:0}
    .qr-note{text-align:center;max-width:280px;font-size:.75rem;color:var(--muted);line-height:1.6;animation:fade-up .55s .14s cubic-bezier(.16,1,.3,1) both}
    .qr-note strong{color:rgba(255,255,255,.75)}
    .qr-actions{display:flex;flex-direction:column;gap:.65rem;width:100%;max-width:360px;animation:fade-up .55s .2s cubic-bezier(.16,1,.3,1) both}
    .qr-btn{display:flex;align-items:center;justify-content:center;gap:.55rem;padding:.9rem 1.25rem;border-radius:14px;font-family:'Syne',sans-serif;font-weight:700;font-size:.88rem;cursor:pointer;border:none;transition:opacity .15s,transform .15s;text-decoration:none;-webkit-tap-highlight-color:transparent}
    .qr-btn:active{opacity:.8;transform:scale(.98)}
    .qr-btn svg{width:17px;height:17px;flex-shrink:0}
    .qr-btn-primary{background:#fff;color:#0a0a0a}
    .qr-btn-secondary{background:transparent;color:rgba(255,255,255,.7);border:1px solid var(--border)}
    @media print{body{background:#fff!important;color:#000!important;padding:0}.qr-header,.qr-note,.qr-actions{display:none!important}.qr-card{background:#fff!important;border:none!important;padding:1rem;margin:0 auto;border-radius:0}.qr-name,.qr-role{color:#000!important}.qr-url{color:#555!important;border-color:#ddd!important;background:#f9f9f9!important}}
    @keyframes fade-up{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
  </style>
</head>
<body>

<div class="qr-header">
  <p class="qr-eyebrow">Digital Business Card</p>
  <h1>Jake Barton</h1>
</div>

<div class="qr-card">
  <div class="qr-img-wrap">
    <img src="<?= $qrSmall ?>" alt="QR code — jakebartoncreative.com/card" id="qr-img">
  </div>
  <div class="qr-identity">
    <div class="qr-name">Jake Barton</div>
    <div class="qr-role">Gameplay Programmer · Technical Designer</div>
  </div>
  <div class="qr-url">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
    jakebartoncreative.com/card
  </div>
</div>

<p class="qr-note">
  Point any camera at the QR code to open<br>
  <strong>Jake's interactive digital business card</strong><br>
  — works on iPhone, Android, and desktop.
</p>

<div class="qr-actions">
  <a href="<?= $qrBig ?>" download="jake-barton-qr.png" class="qr-btn qr-btn-primary">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
    Download QR Code (PNG)
  </a>
  <button class="qr-btn qr-btn-secondary" onclick="window.print()">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
    Print QR Card
  </button>
  <a href="/card" class="qr-btn qr-btn-secondary">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
    Preview Digital Card
  </a>
</div>

</body>
</html>
