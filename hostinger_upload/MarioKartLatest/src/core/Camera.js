import { Settings } from "./Settings.js";

export class Camera {
    constructor(player) {
        this.player = player;

        this.x = player.x;
        this.y = player.y;
        this.angle = player.angle;

        this.height = Settings.camera.height;
        this.pitch = Settings.camera.pitch;
        
        this.isDetached = false; // Allow camera to be detached from player
    }

    update(keys) {
        // Only follow player if camera is attached
        if (!this.isDetached) {
            this.x = this.player.x;
            this.y = this.player.y;
        }
        
        const targetAngle = this.player.angle;
        const angleDiff = targetAngle - this.angle;

        let shortestDiff = angleDiff;
        if (angleDiff > Math.PI) {
            shortestDiff = angleDiff - 2 * Math.PI;
        } else if (angleDiff < -Math.PI) {
            shortestDiff = angleDiff + 2 * Math.PI;
        }

        if (!this.isDetached) {
            this.angle += shortestDiff * Settings.camera.rotationEasing;
        }
    }
    
    detach() {
        this.isDetached = true;
    }
    
    attach() {
        this.isDetached = false;
        this.x = this.player.x;
        this.y = this.player.y;
        this.angle = this.player.angle;
    }

    getX() {
        return this.x;
    }

    getY() {
        return this.y;
    }

    getAngle() {
        return this.angle;
    }

    getHeight() {
        return this.height;
    }

    getPitch() {
        return this.pitch;
    }
}