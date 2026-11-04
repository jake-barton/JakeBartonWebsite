export class MysteryBox {
    constructor(x,y, height = 0, scale = 1.0) {
        this.x = x;
        this.y = y;
        this.height = height;
        this.scale = scale;

        this.animationTime = 0;
        this.currentFrame = 0;
        this.frameCount = 37;
        this.frameSpeed = 0.05;

        this.active = true;
        this.respawnTimer = 0;
        this.respawnDuration = 1.0; // 1 second to respawn
        this.isRespawning = false;
    }

    update(deltaTime) {
        // Handle respawning
        if (this.isRespawning) {
            this.respawnTimer += deltaTime;
            if (this.respawnTimer >= this.respawnDuration) {
                this.active = true;
                this.isRespawning = false;
                this.respawnTimer = 0;
            }
            return; // Don't animate while respawning
        }
        
        if (!this.active) return;

        this.animationTime += deltaTime;
        if (this.animationTime >= this.frameSpeed) {
            this.currentFrame = (this.currentFrame + 1) % this.frameCount;
            this.animationTime = 0;
        }
    }

    getCurrentFrame() {
        return this.currentFrame;
    }

    collect() {
        this.active = false;
        this.isRespawning = true;
        this.respawnTimer = 0;
    }
}