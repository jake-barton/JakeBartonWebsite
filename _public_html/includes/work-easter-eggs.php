<?php
/** Small, local interactions for the Work filters. Games uses the original sprite. */
function jb_work_egg_type(string $key): string {
    return ['all'=>'tiles', 'games'=>'kart', 'apps'=>'phone', 'programming'=>'terminal', 'web'=>'browser', 'design'=>'pen', 'art'=>'pen', 'motion'=>'timeline', 'business'=>'case', '3d'=>'cube'][$key] ?? '';
}
function jb_work_egg(string $key): void {
    $type = jb_work_egg_type($key);
    if ($type === 'kart') {
        echo '<span class="jb-kart-track" aria-hidden="true"><svg class="jb-kart-cameo" viewBox="233 0 30 32" focusable="false"><image href="/games/mario-kart/assets/sprites/characters/yoshi.png" width="455" height="300"/></svg></span>';
        return;
    }
    $art = [
        'tiles' => '<rect class="egg-tile" x="17" y="7" width="13" height="13" rx="2" style="--tile-x:-12px;--tile-y:-5px"/><rect class="egg-tile" x="34" y="7" width="13" height="13" rx="2" style="--tile-x:10px;--tile-y:-4px;--egg-delay:.1s"/><rect class="egg-tile" x="17" y="24" width="13" height="13" rx="2" style="--tile-x:-8px;--tile-y:5px;--egg-delay:.2s"/><rect class="egg-tile egg-accent" x="34" y="24" width="13" height="13" rx="2" style="--tile-x:12px;--tile-y:6px;--egg-delay:.3s"/>',
        'phone' => '<rect x="21" y="3" width="23" height="38" rx="5"/><path d="M28 7h9M30 36h5"/><rect class="egg-phone-card egg-accent" x="26" y="17" width="13" height="9" rx="2"/><circle class="egg-phone-dot" cx="44" cy="6" r="3" fill="#23883c" stroke="none"/>',
        'terminal' => '<rect x="10" y="8" width="44" height="29" rx="4"/><path d="M10 15h44"/><circle cx="15" cy="12" r="1" fill="currentColor" stroke="none"/><path class="egg-prompt" pathLength="1" d="m19 22 5 4-5 4m11 0h8"/><path class="egg-cursor egg-accent" d="M42 22v9"/>',
        'browser' => '<rect x="9" y="6" width="46" height="32" rx="4"/><path d="M9 14h46M15 10h9"/><path class="egg-web-link egg-accent" pathLength="1" d="M20 28h12l10-9M32 28l10 5"/><circle cx="20" cy="28" r="2" fill="currentColor"/><circle class="egg-web-node egg-accent" cx="43" cy="19" r="3"/><circle class="egg-web-node egg-accent" cx="43" cy="33" r="3" style="--egg-delay:.25s"/><path class="egg-web-pointer" d="m40 35 1 7 2-3 4-1Z" fill="currentColor"/>',
        'pen' => '<path class="egg-pen-curve egg-accent" pathLength="1" d="M8 30C19 2 37 40 55 10"/><path class="egg-pen-guides" d="m8 30 9-20m38 0-9 18"/><circle class="egg-pen-guides" cx="17" cy="10" r="2"/><circle class="egg-pen-guides" cx="46" cy="28" r="2"/><g class="egg-pen-tip"><path d="m0 0 3-9 5 5Z" fill="currentColor"/><path d="m3-9 3-3 5 5-3 3"/></g>',
        'timeline' => '<path d="M7 34h50M13 31v6M32 31v6M51 31v6"/><path class="egg-motion-arc" d="M10 25Q32-9 54 25"/><circle class="egg-motion-ghost" cx="25" cy="13" r="3"/><circle class="egg-motion-ghost" cx="40" cy="13" r="3" style="--egg-delay:.15s"/><circle class="egg-motion-dot" cx="10" cy="25" r="4" fill="#23883c" stroke="none"/>',
        'case' => '<path d="M12 20v17h40V20M25 11V7h14v4"/><path class="egg-case-paper egg-accent" d="M24 28V14h16v14M28 19h8M28 23h5"/><path class="egg-case-lid" d="M11 12h42v11H11Zm15 11v4h12v-4"/>',
        'cube' => '<path class="egg-cube-face" pathLength="1" d="m32 6 20 11-20 11-20-11Z"/><path class="egg-cube-face" pathLength="1" style="--egg-delay:.2s" d="M12 17v13l20 11V28"/><path class="egg-cube-face egg-accent" pathLength="1" style="--egg-delay:.4s" d="M52 17v13L32 41"/><g class="egg-cube-points" fill="#23883c" stroke="none"><circle cx="32" cy="6" r="2"/><circle cx="12" cy="17" r="2"/><circle cx="52" cy="17" r="2"/><circle cx="32" cy="28" r="2"/></g>',
    ];
    if (!isset($art[$type])) return;
    echo '<span class="jb-filter-cameo" aria-hidden="true"><svg class="jb-egg-' . $type . '" viewBox="0 0 64 44" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" focusable="false">' . $art[$type] . '</svg></span>';
}
