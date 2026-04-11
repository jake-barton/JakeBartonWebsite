<?php
/**
 * qr-henry.php — Printable QR card for Henry Lammons
 * Links to henrylammons.com
 * Styled to match Henry's site: Windows 95 / desktop OS aesthetic,
 * dark navy window chrome, pixel fonts, title-bar buttons
 */

// ── QR Matrix Generator ──────────────────────────────────────────────────
class QRMatrix_Henry {
    public function __construct(private array $modules, private int $moduleCount) {}

    public static function encode(string $text, int $ecLevel = 1): self {
        $data    = array_values(unpack('C*', $text));
        $typeNum = self::getTypeNumber($data, $ecLevel);
        $matrix  = new self([], 0);
        $matrix->build($data, $typeNum, $ecLevel);
        return $matrix;
    }

    public function getModuleCount(): int { return $this->moduleCount; }
    public function isDark(int $r, int $c): bool { return $this->modules[$r][$c] ?? false; }

    private static function expTable(): array {
        static $t; if ($t) return $t;
        $t = array_fill(0, 256, 0);
        for ($i = 0; $i < 8; $i++) $t[$i] = 1 << $i;
        for ($i = 8; $i < 256; $i++) $t[$i] = $t[$i-4]^$t[$i-5]^$t[$i-6]^$t[$i-8];
        return $t;
    }
    private static function logTable(): array {
        static $t; if ($t) return $t;
        $exp = self::expTable(); $t = array_fill(0, 256, 0);
        for ($i = 0; $i < 255; $i++) $t[$exp[$i]] = $i;
        return $t;
    }
    private static function gexp(int $n): int {
        $e = self::expTable();
        while ($n < 0) $n += 255; while ($n >= 256) $n -= 255; return $e[$n];
    }
    private static function glog(int $n): int {
        if ($n < 1) throw new \RuntimeException("glog($n)"); return self::logTable()[$n];
    }
    private static $RS = [
        0=>[[1,26,19]],  1=>[[1,26,16]],  2=>[[1,26,13]],  3=>[[1,26,9]],
        4=>[[1,44,34]],  5=>[[1,44,28]],  6=>[[1,44,22]],  7=>[[1,44,16]],
        8=>[[1,70,55]],  9=>[[1,70,44]], 10=>[[2,35,17]], 11=>[[2,35,13]],
       12=>[[1,100,80]],13=>[[2,50,32]],14=>[[2,50,24]],15=>[[4,25,9]],
       16=>[[1,134,108]],17=>[[2,67,43]],18=>[[2,33,15],[2,34,16]],19=>[[2,33,11],[2,34,12]],
       20=>[[2,86,68]], 21=>[[4,43,27]],22=>[[4,43,19]],23=>[[4,43,15]],
       24=>[[2,98,78]], 25=>[[4,49,31]],26=>[[2,32,14],[4,33,15]],27=>[[4,39,13],[1,40,14]],
       28=>[[2,121,97]],29=>[[2,60,38],[2,61,39]],30=>[[4,40,18],[2,41,19]],31=>[[4,40,14],[2,41,15]],
       32=>[[2,146,116]],33=>[[3,58,36],[2,59,37]],34=>[[4,36,16],[4,37,17]],35=>[[4,36,12],[4,37,13]],
       36=>[[2,86,68],[2,87,69]],37=>[[4,69,43],[1,70,44]],38=>[[6,43,19],[2,44,20]],39=>[[6,43,15],[2,44,16]],
    ];
    private static function getRSBlocks(int $typeNum, int $ecLevel): array {
        $key = ($typeNum-1)*4+$ecLevel;
        if (!isset(self::$RS[$key])) throw new \RuntimeException("No RS block for type $typeNum ec $ecLevel");
        $out = [];
        foreach (self::$RS[$key] as $row) {
            if (count($row)===3){[$cnt,$total,$data]=$row;for($i=0;$i<$cnt;$i++)$out[]=[$total,$data];}
            else $out[]=[$row[0],$row[1]];
        }
        return $out;
    }
    private static function getTypeNumber(array $data, int $ecLevel): int {
        $len = count($data);
        for ($t=1;$t<=10;$t++) {
            $blocks=self::getRSBlocks($t,$ecLevel);$maxData=0;
            foreach ($blocks as $b) $maxData+=$b[1];
            if ($maxData>=$len+2) return $t;
        }
        throw new \RuntimeException("String too long");
    }
    private static function getECPoly(int $n): array {
        $p=[1];
        for ($i=0;$i<$n;$i++){
            $r=[1,self::gexp($i)];$out=array_fill(0,count($p)+count($r)-1,0);
            foreach ($p as $pi=>$pv) foreach ($r as $ri=>$rv) $out[$pi+$ri]^=self::gexp((self::glog($pv)+self::glog($rv))%255);
            $p=$out;
        }
        return $p;
    }
    private static function polyMod(array $a, array $b): array {
        while (count($a)>=count($b)){if($a[0]===0){array_shift($a);continue;}$ratio=self::glog($a[0]);for($i=0;$i<count($b);$i++)if($b[$i]!==0)$a[$i]^=self::gexp((self::glog($b[$i])+$ratio)%255);array_shift($a);}
        return $a;
    }
    private static function createData(array $data, int $typeNum, int $ecLevel): array {
        $blocks=self::getRSBlocks($typeNum,$ecLevel);$totalData=0;foreach($blocks as $b)$totalData+=$b[1];
        $bits=[];self::pushBits($bits,0b0100,4);$lenBits=$typeNum<10?8:16;self::pushBits($bits,count($data),$lenBits);
        foreach($data as $byte)self::pushBits($bits,$byte,8);
        $capacity=$totalData*8;$term=min(4,$capacity-count($bits));if($term>0)self::pushBits($bits,0,$term);
        while(count($bits)%8!==0)$bits[]=0;$padBytes=[0xEC,0x11];$pi=0;while(count($bits)<$capacity)self::pushBits($bits,$padBytes[$pi++%2],8);
        $bytes=[];for($i=0;$i<count($bits);$i+=8){$b=0;for($j=0;$j<8;$j++)$b=($b<<1)|($bits[$i+$j]??0);$bytes[]=$b;}
        $dataBlocks=[];$ecBlocks=[];$offset=0;
        foreach($blocks as[$total,$dataCount]){$ecCount=$total-$dataCount;$block=array_slice($bytes,$offset,$dataCount);$offset+=$dataCount;$ecPoly=self::getECPoly($ecCount);$msg=array_merge($block,array_fill(0,$ecCount,0));$ecWords=self::polyMod($msg,$ecPoly);while(count($ecWords)<$ecCount)array_unshift($ecWords,0);$dataBlocks[]=$block;$ecBlocks[]=$ecWords;}
        $result=[];$maxLen=max(array_map('count',$dataBlocks));for($i=0;$i<$maxLen;$i++)foreach($dataBlocks as $b)if(isset($b[$i]))$result[]=$b[$i];
        $maxEc=max(array_map('count',$ecBlocks));for($i=0;$i<$maxEc;$i++)foreach($ecBlocks as $b)if(isset($b[$i]))$result[]=$b[$i];
        return $result;
    }
    private static function pushBits(array &$bits,int $val,int $n):void{for($i=$n-1;$i>=0;$i--)$bits[]=($val>>$i)&1;}
    private static function bchTypeInfo(int $data):int{$g15=0x537;$g15mask=0x5412;$d=$data<<10;while(self::bchDigit($d)-self::bchDigit($g15)>=0)$d^=$g15<<(self::bchDigit($d)-self::bchDigit($g15));return(($data<<10)|$d)^$g15mask;}
    private static function bchTypeNumber(int $data):int{$g18=0x1F25;$d=$data<<12;while(self::bchDigit($d)-self::bchDigit($g18)>=0)$d^=$g18<<(self::bchDigit($d)-self::bchDigit($g18));return($data<<12)|$d;}
    private static function bchDigit(int $d):int{$n=0;while($d){$n++;$d>>=1;}return $n;}
    private static function mask(int $p,int $i,int $j):bool{switch($p){case 0:return($i+$j)%2===0;case 1:return$i%2===0;case 2:return$j%3===0;case 3:return($i+$j)%3===0;case 4:return(intdiv($i,2)+intdiv($j,3))%2===0;case 5:return($i*$j%2+$i*$j%3)===0;case 6:return($i*$j%2+$i*$j%3)%2===0;case 7:return($i*$j%3+($i+$j)%2)%2===0;}return false;}
    private static $alignTable=[[],[6,18],[6,22],[6,26],[6,30],[6,34],[6,22,38],[6,24,42],[6,26,46],[6,28,50],[6,30,54]];
    private function build(array $data,int $typeNum,int $ecLevel):void{
        $this->moduleCount=$typeNum*4+17;$n=$this->moduleCount;
        $this->modules=array_fill(0,$n,array_fill(0,$n,null));
        $reserved=array_fill(0,$n,array_fill(0,$n,false));
        $this->placeFinderPattern(0,0,$reserved);$this->placeFinderPattern($n-7,0,$reserved);$this->placeFinderPattern(0,$n-7,$reserved);
        for($i=0;$i<=8;$i++){$reserved[$i][8]=true;$reserved[8][$i]=true;}for($i=$n-8;$i<$n;$i++){$reserved[8][$i]=true;$reserved[$i][8]=true;}
        if($typeNum>=2){$pos=self::$alignTable[$typeNum]??[];foreach($pos as $r)foreach($pos as $c){if($reserved[$r][$c])continue;$this->placeAlignPattern($r,$c,$reserved);}}
        for($i=8;$i<$n-8;$i++){if($reserved[$i][6])continue;$this->modules[$i][6]=$i%2===0;$reserved[$i][6]=true;}
        for($i=8;$i<$n-8;$i++){if($reserved[6][$i])continue;$this->modules[6][$i]=$i%2===0;$reserved[6][$i]=true;}
        $this->modules[$n-8][8]=true;$reserved[$n-8][8]=true;
        if($typeNum>=7){$bits=self::bchTypeNumber($typeNum);for($i=0;$i<18;$i++){$v=(($bits>>$i)&1)===1;$r=intdiv($i,3);$c=$i%3+$n-8-3;$this->modules[$r][$c]=$v;$reserved[$r][$c]=true;$this->modules[$c][$r]=$v;$reserved[$c][$r]=true;}}
        $codewords=self::createData($data,$typeNum,$ecLevel);$this->placeData($codewords,$reserved);
        $bestPenalty=PHP_INT_MAX;$bestMask=0;for($m=0;$m<8;$m++){$this->applyFormatInfo($ecLevel,$m,false,$reserved);$pen=$this->calcPenalty();if($pen<$bestPenalty){$bestPenalty=$pen;$bestMask=$m;}}
        $this->applyFormatInfo($ecLevel,$bestMask,true,$reserved);
    }
    private function placeFinderPattern(int $row,int $col,array &$reserved):void{for($r=-1;$r<=7;$r++)for($c=-1;$c<=7;$c++){$rr=$row+$r;$cc=$col+$c;if($rr<0||$rr>=$this->moduleCount||$cc<0||$cc>=$this->moduleCount)continue;$dark=($r>=0&&$r<=6&&($c===0||$c===6))||($c>=0&&$c<=6&&($r===0||$r===6))||($r>=2&&$r<=4&&$c>=2&&$c<=4);$this->modules[$rr][$cc]=$dark;$reserved[$rr][$cc]=true;}}
    private function placeAlignPattern(int $row,int $col,array &$reserved):void{for($r=-2;$r<=2;$r++)for($c=-2;$c<=2;$c++){$dark=($r===-2||$r===2||$c===-2||$c===2||($r===0&&$c===0));$this->modules[$row+$r][$col+$c]=$dark;$reserved[$row+$r][$col+$c]=true;}}
    private function placeData(array $codewords,array &$reserved):void{$n=$this->moduleCount;$bits=[];foreach($codewords as $cw)for($i=7;$i>=0;$i--)$bits[]=($cw>>$i)&1;$idx=0;$up=true;for($col=$n-1;$col>=1;$col-=2){if($col===6)$col--;for($row=0;$row<$n;$row++){$r=$up?$n-1-$row:$row;for($d=0;$d<2;$d++){$c=$col-$d;if($reserved[$r][$c])continue;$this->modules[$r][$c]=isset($bits[$idx])?$bits[$idx++]===1:false;}}$up=!$up;}}
    private function applyFormatInfo(int $ecLevel,int $maskPattern,bool $write,array &$reserved):void{$n=$this->moduleCount;$ecMap=[1=>0,0=>1,3=>2,2=>3];$fi=self::bchTypeInfo(($ecMap[$ecLevel]<<3)|$maskPattern);for($i=0;$i<15;$i++){$v=(($fi>>$i)&1)===1;if($i<6){if($write)$this->modules[$i][8]=$v;}elseif($i<8){if($write)$this->modules[$i+1][8]=$v;}else{if($write)$this->modules[$n-15+$i][8]=$v;}if($i<8){if($write)$this->modules[8][$n-$i-1]=$v;}elseif($i<9){if($write)$this->modules[8][15-$i-1+1]=$v;}else{if($write)$this->modules[8][15-$i-1]=$v;}}if(!$write)return;for($r=0;$r<$n;$r++)for($c=0;$c<$n;$c++)if(!$reserved[$r][$c]&&self::mask($maskPattern,$r,$c))$this->modules[$r][$c]=!$this->modules[$r][$c];}
    private function calcPenalty():int{$n=$this->moduleCount;$pen=0;for($r=0;$r<$n;$r++){$run=1;for($c=1;$c<$n;$c++){if($this->modules[$r][$c]===$this->modules[$r][$c-1]){$run++;if($run===5)$pen+=3;elseif($run>5)$pen++;}else $run=1;}}for($c=0;$c<$n;$c++){$run=1;for($r=1;$r<$n;$r++){if($this->modules[$r][$c]===$this->modules[$r-1][$c]){$run++;if($run===5)$pen+=3;elseif($run>5)$pen++;}else $run=1;}}for($r=0;$r<$n-1;$r++)for($c=0;$c<$n-1;$c++){$v=$this->modules[$r][$c];if($v===$this->modules[$r][$c+1]&&$v===$this->modules[$r+1][$c]&&$v===$this->modules[$r+1][$c+1])$pen+=3;}return $pen;}
}

function qr_henry_png(string $url, int $cellPx = 10, int $margin = 4): string {
    $matrix = QRMatrix_Henry::encode($url, 1);
    $count  = $matrix->getModuleCount();
    $imgSize = ($count + $margin * 2) * $cellPx;
    $img   = imagecreatetruecolor($imgSize, $imgSize);
    $white = imagecolorallocate($img, 255, 255, 255);
    $black = imagecolorallocate($img, 0, 0, 0);
    imagefill($img, 0, 0, $white);
    for ($r = 0; $r < $count; $r++)
        for ($c = 0; $c < $count; $c++)
            if ($matrix->isDark($r, $c)) {
                $x = ($c + $margin) * $cellPx; $y = ($r + $margin) * $cellPx;
                imagefilledrectangle($img, $x, $y, $x + $cellPx - 1, $y + $cellPx - 1, $black);
            }
    ob_start(); imagepng($img); $png = ob_get_clean(); imagedestroy($img);
    return 'data:image/png;base64,' . base64_encode($png);
}

$TARGET_URL  = 'https://www.henrylammons.com';
$qrDataUri   = qr_henry_png($TARGET_URL, 10, 4);
$qrDataUriHD = qr_henry_png($TARGET_URL, 20, 4);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Henry Lammons — QR Card</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    /*
     * Henry's site aesthetic: Windows 95 / retro OS desktop window chrome.
     * Dark navy desktop bg, raised/inset bevel borders, title bar, pixel feel.
     * Colors pulled from henrylammons.com's actual window UI.
     */
    :root {
      --desktop-bg: #1a2744;   /* dark navy — Henry's desktop background */
      --window-bg:  #1e1e2e;   /* window body */
      --titlebar:   #2a3f6f;   /* Win95-style title bar blue */
      --titlebar-t: #ffffff;
      --border-hi:  #5a7acc;   /* highlight bevel edge */
      --border-sh:  #0d1830;   /* shadow bevel edge */
      --chrome:     #2d3d6b;   /* window chrome mid */
      --text:       #e8eaf0;
      --muted:      rgba(232,234,240,0.45);
      --faint:      rgba(255,255,255,0.08);
      --btn-face:   #3a4f8a;   /* Win95 button face */
    }

    body {
      background: var(--desktop-bg);
      color: var(--text);
      font-family: -apple-system, 'Segoe UI', system-ui, sans-serif;
      -webkit-font-smoothing: antialiased;
      min-height: 100dvh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      padding: 2.5rem 1.5rem 4rem;
      gap: 1.5rem;
    }

    /* Desktop label above the window */
    .desktop-label {
      text-align: center;
      animation: fade-up 0.4s cubic-bezier(0.16,1,0.3,1) both;
    }
    .desktop-label .icon-row {
      font-size: 2rem;
      margin-bottom: 0.4rem;
    }
    .desktop-label p {
      font-size: 0.65rem;
      text-transform: uppercase;
      letter-spacing: 0.14em;
      font-weight: 600;
      color: rgba(255,255,255,0.5);
    }

    /* ── Win95-style window chrome ── */
    .win-window {
      width: 100%;
      max-width: 380px;
      background: var(--window-bg);
      /* Win95 raised bevel: 2px inset highlight/shadow */
      border: 2px solid;
      border-color: var(--border-hi) var(--border-sh) var(--border-sh) var(--border-hi);
      border-radius: 0;
      animation: fade-up 0.48s 0.07s cubic-bezier(0.16,1,0.3,1) both;
      box-shadow: 0 8px 32px rgba(0,0,0,0.55), inset 0 0 0 1px rgba(255,255,255,0.04);
    }

    /* Title bar */
    .win-titlebar {
      background: var(--titlebar);
      padding: 0.3rem 0.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.5rem;
      user-select: none;
    }
    .win-titlebar-left {
      display: flex;
      align-items: center;
      gap: 0.4rem;
      font-size: 0.72rem;
      font-weight: 700;
      color: var(--titlebar-t);
      letter-spacing: 0.02em;
    }
    .win-titlebar-left .win-icon {
      width: 14px;
      height: 14px;
      background: #6ab3f8;
      border-radius: 2px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 9px;
      flex-shrink: 0;
    }
    .win-titlebar-btns {
      display: flex;
      gap: 2px;
    }
    .win-titlebar-btn {
      width: 18px;
      height: 14px;
      background: var(--btn-face);
      border: 1px solid;
      border-color: var(--border-hi) var(--border-sh) var(--border-sh) var(--border-hi);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 8px;
      color: var(--text);
      cursor: default;
      font-weight: 700;
      line-height: 1;
    }

    /* Menu bar */
    .win-menubar {
      background: var(--chrome);
      border-bottom: 1px solid var(--border-sh);
      padding: 0.18rem 0.6rem;
      display: flex;
      gap: 1rem;
    }
    .win-menubar span {
      font-size: 0.65rem;
      color: rgba(255,255,255,0.55);
      letter-spacing: 0.03em;
      cursor: default;
    }
    .win-menubar span:first-child { color: rgba(255,255,255,0.8); }

    /* Window content area */
    .win-content {
      padding: 1.5rem 1.5rem 1.75rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 1.25rem;
    }

    /* Profile photo from Henry's actual site */
    .henry-pfp {
      width: 72px;
      height: 72px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid var(--border-hi);
      display: block;
    }

    /* QR */
    .qr-img-wrap {
      background: #fff;
      padding: 13px;
      display: inline-flex;
      line-height: 0;
      /* Win95 inset border */
      border: 2px solid;
      border-color: var(--border-sh) var(--border-hi) var(--border-hi) var(--border-sh);
    }
    .qr-img-wrap img {
      display: block;
      width: 210px;
      height: 210px;
      image-rendering: pixelated;
      image-rendering: crisp-edges;
    }

    /* Identity */
    .qr-identity { text-align: center; }
    .qr-name {
      font-size: 1.1rem;
      font-weight: 700;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      margin-bottom: 0.2rem;
      color: var(--text);
    }
    .qr-role {
      font-size: 0.68rem;
      color: var(--muted);
      font-weight: 500;
      letter-spacing: 0.04em;
    }

    /* URL chip — Win95 status-bar style */
    .qr-url-chip {
      display: flex;
      align-items: center;
      gap: 0.4rem;
      background: rgba(0,0,0,0.3);
      border: 1px solid;
      border-color: var(--border-sh) var(--border-hi) var(--border-hi) var(--border-sh);
      padding: 0.3rem 0.8rem;
      font-size: 0.67rem;
      color: var(--muted);
      font-weight: 500;
      letter-spacing: 0.04em;
      width: 100%;
      justify-content: center;
    }
    .qr-url-chip svg { width: 10px; height: 10px; opacity: 0.5; flex-shrink: 0; }

    /* Status bar at bottom of window */
    .win-statusbar {
      background: var(--chrome);
      border-top: 1px solid var(--border-sh);
      padding: 0.22rem 0.6rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .win-statusbar span {
      font-size: 0.6rem;
      color: rgba(255,255,255,0.4);
      letter-spacing: 0.03em;
    }
    .win-statusbar .status-dot {
      width: 6px; height: 6px; border-radius: 50%;
      background: #4ade80;
      display: inline-block;
      margin-right: 4px;
      vertical-align: middle;
    }

    /* Instruction */
    .qr-instruction {
      text-align: center;
      max-width: 300px;
      font-size: 0.72rem;
      color: rgba(255,255,255,0.38);
      line-height: 1.65;
      animation: fade-up 0.5s 0.14s cubic-bezier(0.16,1,0.3,1) both;
    }
    .qr-instruction strong { color: rgba(255,255,255,0.65); }

    /* Action buttons */
    .qr-actions {
      display: flex;
      flex-direction: column;
      gap: 0.55rem;
      width: 100%;
      max-width: 380px;
      animation: fade-up 0.5s 0.2s cubic-bezier(0.16,1,0.3,1) both;
    }
    .qr-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      padding: 0.8rem 1.2rem;
      font-weight: 700;
      font-size: 0.78rem;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      cursor: pointer;
      transition: opacity 0.15s, transform 0.15s;
      text-decoration: none;
      -webkit-tap-highlight-color: transparent;
      font-family: inherit;
      /* Win95 raised button */
      border: 2px solid;
      border-radius: 0;
    }
    .qr-btn:active { opacity: 0.8; transform: scale(0.98); }
    .qr-btn svg { width: 15px; height: 15px; flex-shrink: 0; }
    .qr-btn-primary {
      background: var(--titlebar);
      color: #fff;
      border-color: var(--border-hi) var(--border-sh) var(--border-sh) var(--border-hi);
    }
    .qr-btn-secondary {
      background: var(--chrome);
      color: rgba(255,255,255,0.6);
      border-color: var(--border-hi) var(--border-sh) var(--border-sh) var(--border-hi);
    }

    @media print {
      body { background: #fff !important; color: #000 !important; padding: 0; }
      .desktop-label, .qr-instruction, .qr-actions { display: none !important; }
      .win-window { background: #fff !important; border-color: #ccc !important; box-shadow: none !important; }
      .win-titlebar { background: #ddd !important; }
      .win-titlebar-left, .win-content { color: #000 !important; }
      .qr-name, .qr-role { color: #000 !important; }
      .win-menubar, .win-statusbar, .win-titlebar-btns { display: none !important; }
    }

    @keyframes fade-up {
      from { opacity: 0; transform: translateY(10px); }
      to   { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

  <!-- Desktop icon label above window -->
  <div class="desktop-label">
    <div class="icon-row">🖥️</div>
    <p>Henry Lammons — Portfolio.exe</p>
  </div>

  <!-- Win95-style window -->
  <div class="win-window">

    <!-- Title bar -->
    <div class="win-titlebar">
      <div class="win-titlebar-left">
        <div class="win-icon">HL</div>
        Henry Lammons — Portfolio
      </div>
      <div class="win-titlebar-btns">
        <div class="win-titlebar-btn">_</div>
        <div class="win-titlebar-btn">□</div>
        <div class="win-titlebar-btn">✕</div>
      </div>
    </div>

    <!-- Menu bar -->
    <div class="win-menubar">
      <span>File</span>
      <span>View</span>
      <span>Contact</span>
      <span>Help</span>
    </div>

    <!-- Content -->
    <div class="win-content">

      <!-- Profile photo sourced from Henry's actual site -->
      <img
        class="henry-pfp"
        src="https://www.henrylammons.com/media/HenryPfp.jpg"
        alt="Henry Lammons"
        loading="eager"
        crossorigin="anonymous"
      >

      <div class="qr-img-wrap">
        <img src="<?= htmlspecialchars($qrDataUri) ?>"
             alt="QR code linking to henrylammons.com"
             id="qr-img">
      </div>

      <div class="qr-identity">
        <div class="qr-name">Henry Lammons</div>
        <div class="qr-role">Animation · Video Editing · Game Design</div>
      </div>

      <div class="qr-url-chip">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
          <circle cx="12" cy="12" r="10"/>
          <path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
        </svg>
        henrylammons.com
      </div>

    </div>

    <!-- Status bar -->
    <div class="win-statusbar">
      <span><span class="status-dot"></span>Ready</span>
      <span>henrylammons.com</span>
    </div>

  </div>

  <p class="qr-instruction">
    Scan to view Henry's full portfolio —<br>
    <strong>3D animation, game design &amp; film</strong><br>
    — Samford University, Class of 2027.
  </p>

  <div class="qr-actions">

    <a href="<?= htmlspecialchars($qrDataUriHD) ?>"
       download="henry-lammons-qr.png"
       class="qr-btn qr-btn-primary">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
        <polyline points="7 10 12 15 17 10"/>
        <line x1="12" y1="15" x2="12" y2="3"/>
      </svg>
      Download QR Code (PNG)
    </a>

    <button class="qr-btn qr-btn-secondary" onclick="window.print()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="6 9 6 2 18 2 18 9"/>
        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
        <rect x="6" y="14" width="12" height="8"/>
      </svg>
      Print QR Card
    </button>

    <a href="https://www.henrylammons.com" target="_blank" class="qr-btn qr-btn-secondary">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
        <polyline points="15 3 21 3 21 9"/>
        <line x1="10" y1="14" x2="21" y2="3"/>
      </svg>
      Visit Portfolio
    </a>

  </div>

</body>
</html>
