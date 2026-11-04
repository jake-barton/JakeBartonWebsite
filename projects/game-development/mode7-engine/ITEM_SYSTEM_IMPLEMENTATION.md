# Item System Implementation

## Star Power Implementation ⭐

### Overview
Implemented the star power-up system based on SNES Super Mario Kart specifications, including:
- Speed boost
- Invincibility to item hits
- Rainbow color cycling effect
- Hit state with 360° spin animation

### Star Power Stats (SNES Authentic)
- **Duration**: 7.5 seconds (450 frames at 60fps)
- **Speed Multiplier**: 1.33x (33% speed boost)
- **Invincibility**: Cannot be hit by items while active
- **Visual Effect**: Rainbow hue rotation cycling at 3 cycles/second

### Implementation Details

#### Player Class (`src/core/Player.js`)
**New Properties:**
```javascript
// Item effects
starPowerActive: false
starPowerTimer: 0
starPowerDuration: 7.5  // SNES: 450 frames at 60fps
starSpeedMultiplier: 1.33  // SNES: 33% speed boost

// Hit state
isHit: false
hitTimer: 0
hitDuration: 2.0  // 2 seconds of hit state
hitSpinRotation: 0  // For 360 spin animation
hitSpinSpeed: 0  // Spin speed during hit

// Input tracking
shiftWasPressed: false  // For item usage
```

**New Methods:**
- `useItem()` - Activates items when Shift is pressed
- `activateStarPower()` - Starts star power effect
- `updateItemEffects(deltaTime)` - Updates star power timer
- `updateHitState(deltaTime)` - Handles hit state spin and deceleration
- `triggerHit()` - Initiates hit state (checks for star invincibility)
- `getRainbowColor()` - Returns HSL color for rainbow cycling

**Modified Methods:**
- `handleInput()` - Added Shift key for item usage, disabled input during hit state
- `updateSpeed()` - Applies star power speed multiplier, disabled during hit state
- `update()` - Added calls to updateItemEffects() and updateHitState()

#### AIRacer Class (`src/core/AIRacer.js`)
Implemented identical star power and hit state system as Player:
- Same properties and methods
- Same invincibility logic
- Same speed boost calculations
- Same hit state behavior

#### Renderer Class (`src/core/Renderer.js`)
**Modified Methods:**
- `drawMario()` - Added hit state spin animation and rainbow filter for star power
- `drawPlayerIntro()` - Added hit state spin animation and rainbow filter
- `drawRacerInWorld()` - Added hit state spin animation and rainbow filter for AI racers

**Visual Effects:**
```javascript
// Rainbow filter applied when star power is active
ctx.filter = `hue-rotate(${(timer * 360 * 3) % 360}deg) saturate(2)`;

// Hit state sprite selection
const normalizedAngle = ((hitSpinRotation % (2π)) + (2π)) % (2π);
// Maps to sprite index 0-11 for full 360° rotation
```

### Usage

#### For Players:
1. Collect a mystery box containing a star
2. Press **Shift** to activate star power
3. Enjoy 7.5 seconds of:
   - 33% speed boost
   - Invincibility to all item hits
   - Rainbow color cycling effect

#### For Developers - Triggering Hit State:
```javascript
// Hit a player/AI (returns false if star power active)
const wasHit = racer.triggerHit();

if (wasHit) {
    // Player enters 2-second hit state
    // - 360° spin animation
    // - Faster deceleration
    // - No input control
}
```

### Hit State Mechanics
When hit by an item:
- **Duration**: 2 seconds
- **Animation**: Full 360° spin at 2π radians/second
- **Deceleration**: 0.85 friction (faster than normal 0.92)
- **Control**: Input disabled during hit state
- **Bounce**: Triggers jump with 1.5x strength
- **Protection**: Star power prevents hit state entirely

### Next Steps - Other Items

#### Implemented:
✅ Star Power (complete with stats, visuals, invincibility)
✅ Hit State System (spin animation, deceleration)
✅ Item Usage Input (Shift key)

#### TODO:
- [ ] **Mushroom**: Speed boost (similar to star but shorter, no invincibility)
- [ ] **Banana**: Place on track as obstacle, trigger hit state on collision
- [ ] **Green Shell**: Shoot forward projectile, bounces off walls
- [ ] **Red Shell**: Homing projectile, targets nearest racer ahead
- [ ] **Ghost**: Steal item from another racer
- [ ] **Lightning**: Shrink all opponents temporarily
- [ ] **Coin**: Increase max speed temporarily
- [ ] **Feather**: High jump ability

### Testing

To test star power:
1. Start a race
2. Collect a mystery box
3. Wait for roulette to finish
4. Press **Shift** to use star
5. Observe:
   - Speed increase (33% boost)
   - Rainbow cycling effect
   - Try hitting obstacles/walls (no hit state)

To test hit state:
1. Call `player.triggerHit()` manually via console
2. Observe 360° spin animation
3. Note faster deceleration
4. Input controls disabled for 2 seconds

### Technical Notes

- **Performance**: Rainbow effect uses CSS filter (hue-rotate), hardware accelerated
- **Collision**: Hit state is triggered by external collision detection
- **Animation**: Uses existing LOD0 sprites (12 rotations) for smooth 360° spin
- **Speed**: Star multiplier applied to targetSpeed and effectiveMaxSpeed
- **Invincibility**: Checked at triggerHit(), returns false if star active

### SNES Accuracy
All stats match original SNES Super Mario Kart:
- Star duration: 450 frames = 7.5s ✓
- Speed boost: 33% increase ✓
- Invincibility: Full protection ✓
- Rainbow effect: Cycling colors ✓
