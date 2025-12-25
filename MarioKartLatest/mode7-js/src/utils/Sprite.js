export class Sprite {
    constructor(imagePath) {
        this.image = new Image();
        this.loaded = false;

        this.image.onload = () => {
            this.loaded = true;
            // console.log('Sprite loaded:', imagePath);  // Disabled to prevent console spam
        };

        this.image.src = imagePath;
    }

    draw(ctx, sx, sy, sw, sh, dx, dy, dw, dh) {
        if (!this.loaded) return;

        if (dw === undefined) dw = sw;
        if (dh === undefined) dh = sh;

        ctx.drawImage(
            this.image,
            sx, sy, sw, sh,
            dx, dy, dw, dh
        );
    }
}