(() => {
    'use strict';
    const player = document.querySelector('[data-bbq-player]');
    if (!player) return;
    const stage = player.querySelector('[data-bbq-stage]');
    const fullscreen = player.querySelector('[data-bbq-fullscreen]');
    const status = player.querySelector('[data-bbq-status]');
    const frame = stage.querySelector('iframe');
    window.addEventListener('message', (event) => {
        if (!frame || event.source !== frame.contentWindow || event.origin !== location.origin) return;
        if (event.data?.type === 'bbq-ready') {
            status.textContent = 'Click the game and press Enter to begin. Esc pauses; click the game to resume.';
        } else if (event.data?.type === 'bbq-error') {
            status.textContent = 'BBQ could not start. Try opening the game in a new tab, or use a browser with WebGL 2 support.';
        }
    });
    fullscreen.addEventListener('click', async () => {
        try {
            if (document.fullscreenElement) await document.exitFullscreen();
            else if (stage.requestFullscreen) await stage.requestFullscreen();
            else status.textContent = 'Use “Open game in new tab” for a larger view.';
            frame?.focus();
        } catch {
            status.textContent = 'Use “Open game in new tab” for a larger view.';
        }
    });
    document.addEventListener('fullscreenchange', () => {
        fullscreen.textContent = document.fullscreenElement === stage ? 'Exit fullscreen' : 'Fullscreen';
    });
})();
