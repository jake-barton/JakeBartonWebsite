export class Banana {
    constructor(x, y, height = 0, placedBy = null) {
        this.x = x;
        this.y = y;
        this.height = height;
        
        this.active = true;
        this.placedBy = placedBy; // Track who placed it (player or AI reference)
        
        // Immunity timer - don't hit the placer immediately
        this.immunityTimer = 1.0; // 1 second of immunity
    }
    
    update(deltaTime) {
        // Update immunity timer
        if (this.immunityTimer > 0) {
            this.immunityTimer -= deltaTime;
        }
    }

    hit() {
        this.active = false;
    }
}
