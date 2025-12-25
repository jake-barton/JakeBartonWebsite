import { Settings } from "../Settings.js";
import { Player } from "../Player.js";
import { Camera } from "../Camera.js";
import { Renderer } from "../Renderer.js";
import { MapLoader } from "../MapLoader.js";
import { HUD } from "../HUD.js";
import { Sprite } from "../Sprite.js";
import { MYSTERY_BOX_SPRITES } from "../../spritesheets/MysteryBox/mystery_box_sprites.js";
import { NUMBER_SPRITES } from "../../spritesheets/UI/NumberSprites.js";
import { ITEM_SPRITES } from "../../spritesheets/UI/ItemSprites.js";
import { RANKING_SPRITES } from "../../spritesheets/UI/RankingSprites.js";
import { CollisionManager } from "../modules/CollisionManager.js";
import { MinimapRenderer } from "../modules/MinimapRenderer.js";
import { MysteryBox } from "../objects/MysteryBox.js";
import { AIRacer } from "../AIRacer.js";

export class RaceScreen {
    constructor(screenManager) {
        this.screenManager = screenManager;
        this.player = null;
        this.camera = null;
        this.renderer = null;
        this.hud = new HUD();
        this.aiRacers = [];

        this.numberSprite = new Sprite('spritesheets/UI/Numbers.png');
        this.lapPositionSprite = new Sprite('spritesheets/UI/LapPlaces.png');
        this.itemSprite = new Sprite('spritesheets/UI/ItemRoulette.png');
        this.lakituSprite = new Sprite('spritesheets/Lakitu/lakitu.png');
        this.rankingSprite = new Sprite('spritesheets/UI/rankings.png');

        this.isLoaded = false;
        this.raceCountdown = 3;
        this.countdownTimer = 0;
        this.raceStarted = false;
        this.raceTimer = 0;

        this.currentItem = null;

        this.maskData = null;
        this.collisionManager = null;

        this.lakituLapDisplay = false;
        this.lakituLapTimer = 0;
        this.lakituLapDuration = 3.0;
        this.lakituLapAnimProgress = 0;

        this.lakituY = -100;
        this.lakituTargetY = 10;
        this.lakituSmooth = -100;
        this.lakituFrames = {
            countdown3: { x: 9, y: 102, width: 35, height: 39 },
            countdown2: { x: 53, y: 102, width: 35, height: 39 },
            countdown1: { x: 97, y: 102, width: 35, height: 39 },
            go: { x: 133, y: 101, width: 35, height: 40 },
            lap2: { x: 8, y: 142, width: 24, height: 32 },
            lap3: { x: 41, y: 142, width: 24, height: 32 },
            lap4: { x: 74, y: 142, width: 24, height: 32 },
            finalLap: { x: 102, y: 142, width: 30, height: 32 },

            finish1: { x: 5, y: 1, width: 36, height: 32 },
            finish2: { x: 47, y: 1, width: 35, height: 32 },
            finish3: { x: 91, y: 1, width: 32, height: 32 },
        }

        this.hudHeight = 0;
        this.viewportHeight = 120;
        this.dividerHeight = 10;
        
        this.mapHeight = 94;
        this.minimapRenderer = null;

        this.playerCurrentPlace = 8;
        this.pendingPlace = 8;
        this.placementStabilityTimer = 0;

        this.mysteryBoxes = [];
        this.mysteryBoxBaseSprite = null;
        this.mysteryBoxShaderSprite = null;
        
        // Final lap state
        this.isOnFinalLap = false;
        this.finalLapFlashTimer = 0;
        this.finalLapFlashState = 0;
        
        // Race completion state
        this.raceCompleted = false;
        this.raceCompletionFlashTimer = 0;
        this.raceCompletionFlashState = 0;
        
        // Lakitu finish flag animation
        this.lakituFinishFlagActive = false;
        this.lakituFinishBobTimer = 0;
        this.lakituFinishFlagFrame = 0;
        this.lakituFinishFlagTimer = 0;
        this.lakituStopTime = null;
        
        this.finishedRacers = [];
        this.resultsFlashTimer = 0;
        this.resultsFlashState = 0;
        this.resultsCharacterTimer = 0;
        this.resultsCharacterState = 0;
        
        this.victoryCameraActive = false;
        this.victoryCameraPhase = 'rotating';
        this.victoryCameraTimer = 0;
        this.victoryCameraRotationStart = 0;
        this.victoryCameraRotationTarget = 0;
        this.victoryDriveTimer = 0;
        this.victoryCelebrationTimer = 0;
        this.victoryCelebrationState = 0;
        this.victoryDriveAwayOffset = 0;
    }

    async enter(gameData) {
        this.player = new Player(
            Settings.player.startX,
            Settings.player.startY,
            Settings.player.startAngle
        );

        this.camera = new Camera(this.player);

        const mapLoader = new MapLoader();
        const mapData = await mapLoader.load('marioCircuit');

        this.maskData = mapData.maskData;
        this.collisionManager = new CollisionManager(this.maskData, this.player);

        for (const aiStart of Settings.aiRacers) {
            const aiRacer = new AIRacer(
                aiStart.x,
                aiStart.y,
                aiStart.angle,
                mapData.waypoints,
                'easy',
                mapData.maskData,
                aiStart.character || 'mario'
            );
            aiRacer.currentLap = 1;
            aiRacer.hasPassedCheckpoint = false;
            aiRacer.onFinishLine = false;
            aiRacer.onCheckpoint = false;
            aiRacer.raceFinished = false;
            this.aiRacers.push(aiRacer);
        }

        this.renderer = new Renderer(
            this.screenManager.canvas,
            mapData.trackData,
            mapData.trackWidth,
            mapData.trackHeight,
            mapData.grassTileData,
            mapData.backgroundImage,
            mapData.treesImage
        );

        this.isLoaded = true;

        this.waypoints = mapData.waypoints;
        
        this.player.waypoints = mapData.waypoints;
        this.player.currentWaypointIndex = this.findClosestWaypointIndex(this.player.x, this.player.y);
        
        this.minimapRenderer = new MinimapRenderer(this.renderer, Settings.canvas.width, this.mapHeight);
        this.minimapRenderer.generateCache();

        this.hud.addNumber('timer', Settings.canvas.width - 10, 8, "00' 00\" 00", this.numberSprite, {
            scale: 1,
            spacing: 0,
            align: 'right'
        });

        const itemBoxX = Settings.canvas.width - 95;
        this.hud.addSprite(
            'itemBox',
            itemBoxX,
            16,
            ITEM_SPRITES.blank,
            this.itemSprite,
            { scale: 1 }
        );

        const positionY = this.hudHeight + this.viewportHeight - 20;
        this.hud.addSprite(
            'position',
            Settings.canvas.width - 30,
            positionY,
            NUMBER_SPRITES.lapPosition['8'],
            this.lapPositionSprite, 
            { scale: 1 }
        );

        this.hud.addSprite(
            'lakitu',
            Math.floor(Settings.canvas.width - 200),
            20, 
            this.lakituFrames.countdown3,
            this.lakituSprite,
            { scale: 1 }
        );

        this.mysteryBoxBaseSprite = new Sprite(MYSTERY_BOX_SPRITES.base.src);
        this.mysteryBoxShaderSprite = new Sprite(MYSTERY_BOX_SPRITES.shader.src);
        this.mysteryBoxBaseSpriteFrames = MYSTERY_BOX_SPRITES.base.frames;
        this.mysteryBoxShaderSpriteFrames = MYSTERY_BOX_SPRITES.shader.frames;
        await new Promise(resolve => {
            const checkLoaded = () => {
                if (this.mysteryBoxBaseSprite.loaded && this.mysteryBoxShaderSprite.loaded) {
                    resolve();
                } else {
                    setTimeout(checkLoaded, 50);
                }
            };
            checkLoaded();
        });

        this.mysteryBoxes = Settings.obstacles
            .filter(obstacle => obstacle.type === 'mysteryBox')
            .map(obstacle => new MysteryBox(obstacle.x, obstacle.y, obstacle.height || 0, obstacle.scale || 1.0));

            this.updatePlayerPlacement(0);
    }

    update(keys, deltaTime) {
        if (!this.isLoaded) return;

        if (!this.raceStarted) {
            if (this.lakituSmooth < this.lakituTargetY) {
                this.lakituSmooth += 30 * deltaTime;
                this.lakituY = Math.floor(this.lakituSmooth);
                this.hud.animateElement('lakitu', 'offsetY', this.lakituY);
            }

            if (this.lakituY >= this.lakituTargetY) {
                this.countdownTimer += deltaTime;
                if (this.countdownTimer > 1.0) {
                    this.raceCountdown--;
                    this.countdownTimer = 0;

                    if (this.raceCountdown === 2) {
                        this.hud.updateElement('lakitu', { sprite: this.lakituFrames.countdown2 });
                    } else if (this.raceCountdown === 1) {
                        this.hud.updateElement('lakitu', { sprite: this.lakituFrames.countdown1 });
                    } else if (this.raceCountdown === 0) {
                        this.hud.updateElement('lakitu', { sprite: this.lakituFrames.go });
                    } else if (this.raceCountdown < 0) {
                        this.raceStarted = true;
                    }
                }
            }
        }
        if (this.raceStarted) {
            if (this.lakituY > -100 && !this.lakituLapDisplay) {
                this.lakituSmooth -= 30 * deltaTime;
                this.lakituY = Math.floor(this.lakituSmooth);
                this.hud.animateElement('lakitu', 'offsetY', this.lakituY);
            } else if (!this.lakituLapDisplay) {
                this.hud.updateElement('lakitu', { visible: false });
            }
            
            if (this.victoryCameraActive) {
                this.updateVictoryCamera(deltaTime);
            } else {
                this.player.update(keys, deltaTime);
            }
            
            for (const ai of this.aiRacers) {
                ai.update(deltaTime);
            }

            this.updateAILapTracking(deltaTime);

            this.updatePlayerPlacement(deltaTime);
            
            this.checkAICollisions(deltaTime);
            
            if (!this.victoryCameraActive) {
                this.camera.update(keys);
            }

            this.collisionManager.handleCollisions(deltaTime, keys);
            this.collisionManager.checkLapCrossing(lapNumber => {
                // Check if race is complete
                if (lapNumber > this.collisionManager.getTotalLaps()) {
                    this.onRaceComplete();
                } else {
                    this.onLapComplete(lapNumber);
                }
            });

            this.player.isOffTrack = this.collisionManager.getIsOffTrack();

            this.hud.update(deltaTime);

            this.updateLakituLapAnimation(deltaTime);
            
            // Update Lakitu finish flag animation
            if (this.lakituFinishFlagActive) {
                this.updateLakituFinishFlag(deltaTime);
            }
            
            if (this.finishedRacers.length > 0) {
                this.updateResultsFlashing(deltaTime);
            }
            
            if (this.raceCompleted) {
                this.updateRaceCompletionFlashing(deltaTime);
            } else if (this.isOnFinalLap) {
                this.updateFinalLapFlashing(deltaTime);
            }

            this.raceTimer += deltaTime;
            this.hud.updateElement('timer', { text: this.formatTimer(this.raceTimer) }); 
        }

        for (const box of this.mysteryBoxes) {
            box.update(deltaTime);
        }
    }

    formatTimer(seconds) {
        const minutes = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        const millis = Math.floor((seconds % 1) * 100);

        const minStr = String(minutes).padStart(2, '0');
        const secStr = String(secs).padStart(2, '0');
        const millisStr = String(millis).padStart(2, '0');

        return `${minStr}' ${secStr}" ${millisStr}`;
    }

    checkAICollisions(deltaTime) {
        const maxWorldDist = 40; 
        const velocityDecay = 0.85;
        const speedLoss = 0.82;
        const bumpCooldown = 0.18;

        const playerSpriteWidth = 28;
        const playerSpriteHeight = 31;
        const aiSpriteWidth = 28;
        const aiSpriteHeight = 31;

        if (!this.player.bumpVelX) this.player.bumpVelX = 0;
        if (!this.player.bumpVelY) this.player.bumpVelY = 0;

        this.player.x += this.player.bumpVelX * deltaTime * 60;
        this.player.y += this.player.bumpVelY * deltaTime * 60;
        this.player.bumpVelX *= Math.pow(velocityDecay, deltaTime * 60);
        this.player.bumpVelY *= Math.pow(velocityDecay, deltaTime * 60);
        if (Math.abs(this.player.bumpVelX) < 0.01) this.player.bumpVelX = 0;
        if (Math.abs(this.player.bumpVelY) < 0.01) this.player.bumpVelY = 0;

        const projectToScreen = (x, y, camera) => {
            const worldX = x - camera.getX();
            const worldY = y - camera.getY();
            const cos = Math.cos(camera.getAngle());
            const sin = Math.sin(camera.getAngle());
            const distance = worldX * sin + worldY * cos;
            const screenOffsetX = worldX * cos - worldY * sin;
            if (distance <= 1) return null;
            const horizon = Math.floor(this.renderer.height / 2 + camera.getPitch());
            const scale = distance / this.renderer.height;
            const screenX = Math.floor(this.renderer.width / 2 - screenOffsetX / scale);
            const screenY = Math.floor(horizon + (camera.getHeight() * this.renderer.height) / distance - 1);
            return { screenX, screenY, scale };
        };

        for (const ai of this.aiRacers) {
            if (!ai.bumpCooldown) ai.bumpCooldown = 0;
            if (!ai.bumpVelX) ai.bumpVelX = 0;
            if (!ai.bumpVelY) ai.bumpVelY = 0;
            if (ai.bumpCooldown > 0) {
                ai.bumpCooldown -= deltaTime;
                ai.x += ai.bumpVelX * deltaTime * 60;
                ai.y += ai.bumpVelY * deltaTime * 60;
                ai.bumpVelX *= Math.pow(velocityDecay, deltaTime * 60);
                ai.bumpVelY *= Math.pow(velocityDecay, deltaTime * 60);
                if (Math.abs(ai.bumpVelX) < 0.01) ai.bumpVelX = 0;
                if (Math.abs(ai.bumpVelY) < 0.01) ai.bumpVelY = 0;
                continue;
            }
            const dx = ai.x - this.player.x;
            const dy = ai.y - this.player.y;
            const dist = Math.sqrt(dx * dx + dy * dy);
            if (dist > maxWorldDist) {
                ai.x += ai.bumpVelX * deltaTime * 60;
                ai.y += ai.bumpVelY * deltaTime * 60;
                ai.bumpVelX *= Math.pow(velocityDecay, deltaTime * 60);
                ai.bumpVelY *= Math.pow(velocityDecay, deltaTime * 60);
                if (Math.abs(ai.bumpVelX) < 0.01) ai.bumpVelX = 0;
                if (Math.abs(ai.bumpVelY) < 0.01) ai.bumpVelY = 0;
                continue;
            }
            const aiScreen = projectToScreen(ai.x, ai.y, this.camera);
            if (!aiScreen) continue;
            const playerRect = {
                x: this.renderer.width / 2 - playerSpriteWidth / 2,
                y: this.renderer.height - playerSpriteHeight,
                width: playerSpriteWidth,
                height: playerSpriteHeight
            };
            const aiRect = {
                x: aiScreen.screenX - aiSpriteWidth / 2,
                y: aiScreen.screenY - aiSpriteHeight,
                width: aiSpriteWidth,
                height: aiSpriteHeight
            };
            const overlap =
                playerRect.x < aiRect.x + aiRect.width &&
                playerRect.x + playerRect.width > aiRect.x &&
                playerRect.y < aiRect.y + aiRect.height &&
                playerRect.y + playerRect.height > aiRect.y;
            if (overlap) {
                const dx2 = this.player.x - ai.x;
                const dy2 = this.player.y - ai.y;
                let sideAngle = Math.atan2(dy2, dx2) + Math.PI / 2;
                if (Math.abs(Math.cos(Math.atan2(dy2, dx2) - this.player.angle)) > 0.95) {
                    sideAngle += (Math.random() > 0.5 ? 1 : -1) * Math.PI;
                }
                const sideBump = 1.2; 
                this.player.bumpVelX = Math.cos(sideAngle) * sideBump;
                this.player.bumpVelY = Math.sin(sideAngle) * sideBump;
                ai.bumpVelX = -Math.cos(sideAngle) * sideBump * 0.7;
                ai.bumpVelY = -Math.sin(sideAngle) * sideBump * 0.7;
                this.player.speed *= speedLoss;
                ai.bumpCooldown = bumpCooldown;
            }
            ai.x += ai.bumpVelX * deltaTime * 60;
            ai.y += ai.bumpVelY * deltaTime * 60;
            ai.bumpVelX *= Math.pow(velocityDecay, deltaTime * 60);
            ai.bumpVelY *= Math.pow(velocityDecay, deltaTime * 60);
            if (Math.abs(ai.bumpVelX) < 0.01) ai.bumpVelX = 0;
            if (Math.abs(ai.bumpVelY) < 0.01) ai.bumpVelY = 0;
        }
    }

    onLapComplete(lapNumber) {
        this.lakituLapDisplay = true;
        this.lakituLapTimer = 0;
        this.lakituLapAnimProgress = 0;

        this.hud.animateElement('lakitu', 'offsetY', 0);

        let lapSprite;
        if (lapNumber === 2) {
            lapSprite = this.lakituFrames.lap2;
        } else if (lapNumber === 3) {
            lapSprite = this.lakituFrames.lap3;
        } else if (lapNumber === 4) {
            lapSprite = this.lakituFrames.lap4;
        } else if (lapNumber === this.collisionManager.getTotalLaps()) {
            lapSprite = this.lakituFrames.finalLap;
            // Trigger final lap sequence
            this.isOnFinalLap = true;
            this.finalLapFlashTimer = 0;
            this.finalLapBigSpriteTimer = 0;
        }

        this.hud.updateElement('lakitu', {
            sprite: lapSprite,
            visible: true
        });
    }

    onRaceComplete() {
        this.raceCompleted = true;
        this.raceCompletionFlashTimer = 0;
        this.raceCompletionFlashState = 0;
        
        this.lakituFinishFlagActive = true;
        this.lakituFinishBobTimer = 0;
        this.lakituFinishFlagTimer = 0;
        
        this.hud.updateElement('lakitu', { visible: false });
        
        this.onRacerFinished(this.player, true);
        
        this.victoryCameraActive = true;
        this.victoryCameraPhase = 'rotating';
        this.victoryCameraTimer = 0;
        this.victoryCameraRotationStart = this.camera.getAngle();
        this.victoryCameraRotationTarget = this.player.angle + Math.PI;
        this.player.victoryMode = true;
    }

    onRacerFinished(racer, isPlayer) {
        const finishPlace = this.finishedRacers.length + 1;
        
        this.finishedRacers.push({
            character: isPlayer ? 'mario' : racer.character,
            place: finishPlace,
            isPlayer: isPlayer
        });
    }

    getOrdinalSuffix(num) {
        const j = num % 10;
        const k = num % 100;
        if (j === 1 && k !== 11) return 'st';
        if (j === 2 && k !== 12) return 'nd';
        if (j === 3 && k !== 13) return 'rd';
        return 'th';
    }

    updateFinalLapFlashing(deltaTime) {
        const place = this.playerCurrentPlace;
        
        // Define color sequences based on placement
        let colorSequence;
        if (place >= 1 && place <= 4) {
            // 1st-4th: Blue gradient
            colorSequence = [
                '#0000f8', '#0010f8', '#0020f8', '#0030f8', '#0040f8',
                '#0050f8', '#0060f8', '#0070f8', '#0080f8', '#0090f8',
                '#00a0f8', '#00b0f8', '#00c0f8', '#00d0f8', '#00e0f8',
                '#00f0f8', '#00f8f8'
            ];
        } else {
            // 5th-8th: Flash between normal and red
            colorSequence = [null, '#f80000']; // null = no overlay (normal color)
        }
        
        // Flash regular sprite during final lap (faster flashing)
        this.finalLapFlashTimer += deltaTime;
        
        // Flash every 0.05 seconds (faster)
        if (this.finalLapFlashTimer > 0.01) {
            this.finalLapFlashState = (this.finalLapFlashState + 1) % colorSequence.length;
            this.finalLapFlashTimer = 0;
            
            const currentColor = colorSequence[this.finalLapFlashState];
            this.hud.updateElement('position', {
                sprite: NUMBER_SPRITES.lapPosition[String(place)],
                colorOverlay: currentColor
            });
        }
    }

    updateRaceCompletionFlashing(deltaTime) {
        const place = this.playerCurrentPlace;
        
        let colorSequence;
        let flashSpeed;
        
        if (place === 1) {
            colorSequence = ['#f80000', '#f8f800', '#00f800', '#00f8f8', '#0000f8', '#f800f8'];
            flashSpeed = 0.05;
        } else if (place >= 2 && place <= 4) {
            colorSequence = [
                '#0000f8', '#0010f8', '#0020f8', '#0030f8', '#0040f8',
                '#0050f8', '#0060f8', '#0070f8', '#0080f8', '#0090f8',
                '#00a0f8', '#00b0f8', '#00c0f8', '#00d0f8', '#00e0f8',
                '#00f0f8', '#00f8f8'
            ];
            flashSpeed = 0.05;
        } else {
            colorSequence = [null, '#f80000'];
            flashSpeed = 0.15;
        }
        
        this.raceCompletionFlashTimer += deltaTime;
        
        if (this.raceCompletionFlashTimer > flashSpeed) {
            this.raceCompletionFlashState = (this.raceCompletionFlashState + 1) % colorSequence.length;
            this.raceCompletionFlashTimer = 0;
            
            const currentColor = colorSequence[this.raceCompletionFlashState];
            const positionY = this.hudHeight + this.viewportHeight - 40;
            
            this.hud.updateElement('position', {
                sprite: NUMBER_SPRITES.lapPositionBig[String(place)],
                colorOverlay: currentColor,
                y: positionY
            });
        }
    }

    updateResultsFlashing(deltaTime) {
        this.resultsFlashTimer += deltaTime;
        if (this.resultsFlashTimer > 0.08) {
            this.resultsFlashState = (this.resultsFlashState + 1) % 3;
            this.resultsFlashTimer = 0;
        }
        
        this.resultsCharacterTimer += deltaTime;
        if (this.resultsCharacterTimer > 0.2) {
            this.resultsCharacterState = (this.resultsCharacterState + 1) % 3;
            this.resultsCharacterTimer = 0;
        }
    }

    updateVictoryCamera(deltaTime) {
        if (this.victoryCameraPhase === 'rotating') {
            this.victoryCameraTimer += deltaTime;
            const rotationDuration = 1.5;
            const progress = Math.min(this.victoryCameraTimer / rotationDuration, 1.0);
            
            const easeProgress = progress < 0.5 
                ? 2 * progress * progress 
                : 1 - Math.pow(-2 * progress + 2, 2) / 2;
            
            let targetAngle = this.victoryCameraRotationStart + 
                (this.victoryCameraRotationTarget - this.victoryCameraRotationStart) * easeProgress;
            
            while (targetAngle - this.victoryCameraRotationStart > Math.PI) targetAngle -= 2 * Math.PI;
            while (targetAngle - this.victoryCameraRotationStart < -Math.PI) targetAngle += 2 * Math.PI;
            
            // Rotate camera independently of player
            this.camera.angle = targetAngle;
            
            // Keep player moving forward during rotation
            this.player.speed = 0.8;
            this.player.x += Math.sin(this.player.angle) * this.player.speed;
            this.player.y += Math.cos(this.player.angle) * this.player.speed;
            
            // Camera follows player position
            this.camera.x = this.player.x;
            this.camera.y = this.player.y;
            
            if (progress >= 1.0) {
                this.victoryCameraPhase = 'celebrating';
                this.victoryCameraTimer = 0;
                this.player.celebrating = true;
            }
        } else if (this.victoryCameraPhase === 'celebrating') {
            this.victoryDriveTimer += deltaTime;
            
            this.victoryCelebrationTimer += deltaTime;
            if (this.victoryCelebrationTimer >= 0.2) {
                this.victoryCelebrationState = (this.victoryCelebrationState + 1) % 2;
                this.victoryCelebrationTimer = 0;
                // Toggle between celebration sprite (state 0) and facing camera sprite (state 1)
                this.player.celebrating = (this.victoryCelebrationState === 0);
            }
            
            // Follow waypoints like AI racers
            const lookAheadCount = 2;
            const targetWaypointIndex = (this.player.currentWaypointIndex + lookAheadCount - 1) % this.player.waypoints.length;
            const targetWaypoint = this.player.waypoints[targetWaypointIndex];
            
            const dx = targetWaypoint.x - this.player.x;
            const dy = targetWaypoint.y - this.player.y;
            const targetAngle = Math.atan2(dx, dy);
            
            // Smoothly turn towards target
            let angleDiff = targetAngle - this.player.angle;
            while (angleDiff > Math.PI) angleDiff -= 2 * Math.PI;
            while (angleDiff < -Math.PI) angleDiff += 2 * Math.PI;
            
            const turnSpeed = 0.05;
            this.player.angle += angleDiff * turnSpeed;
            
            // Move forward
            this.player.speed = 0.8;
            this.player.x += Math.sin(this.player.angle) * this.player.speed;
            this.player.y += Math.cos(this.player.angle) * this.player.speed;
            
            // Update waypoint progress
            this.player.updateWaypointProgress();
            
            // Camera follows player but maintains 180° offset to face them
            this.camera.x = this.player.x;
            this.camera.y = this.player.y;
            this.camera.angle = this.player.angle + Math.PI;
            
            if (this.victoryDriveTimer >= 8.0) {
                this.victoryCameraPhase = 'driving-away';
                this.victoryCameraTimer = 0;
                this.victoryDriveAwayOffset = 0;
                // Mark when to stop Lakitu (after one more full swing cycle)
                this.lakituStopTime = this.lakituFinishBobTimer + (Math.PI / 0.8);
            }
        } else if (this.victoryCameraPhase === 'driving-away') {
            this.victoryCameraTimer += deltaTime;
            
            // Animate player driving down and off screen
            // Increase Y offset to move them down the viewport
            this.victoryDriveAwayOffset += deltaTime * 150; // Speed of driving away
            
            // Continue moving forward in world space
            const lookAheadCount = 2;
            const targetWaypointIndex = (this.player.currentWaypointIndex + lookAheadCount - 1) % this.player.waypoints.length;
            const targetWaypoint = this.player.waypoints[targetWaypointIndex];
            
            const dx = targetWaypoint.x - this.player.x;
            const dy = targetWaypoint.y - this.player.y;
            const targetAngle = Math.atan2(dx, dy);
            
            // Smoothly turn towards target
            let angleDiff = targetAngle - this.player.angle;
            while (angleDiff > Math.PI) angleDiff -= 2 * Math.PI;
            while (angleDiff < -Math.PI) angleDiff += 2 * Math.PI;
            
            const turnSpeed = 0.05;
            this.player.angle += angleDiff * turnSpeed;
            
            // Move forward
            this.player.speed = 0.8;
            this.player.x += Math.sin(this.player.angle) * this.player.speed;
            this.player.y += Math.cos(this.player.angle) * this.player.speed;
            
            // Update waypoint progress
            this.player.updateWaypointProgress();
            
            // Keep celebration animation going
            this.victoryCelebrationTimer += deltaTime;
            if (this.victoryCelebrationTimer >= 0.2) {
                this.victoryCelebrationState = (this.victoryCelebrationState + 1) % 2;
                this.victoryCelebrationTimer = 0;
                this.player.celebrating = (this.victoryCelebrationState === 0);
            }
        }
    }

    updateLakituFinishFlag(deltaTime) {
        this.lakituFinishBobTimer += deltaTime;
        const swingSpeed = 0.8;
        const swingProgress = this.lakituFinishBobTimer * swingSpeed;
        
        const startX = 30;
        const endX = Settings.canvas.width - 60;
        
        const xProgress = (Math.sin(swingProgress - Math.PI / 2) + 1) / 2;
        const lakituX = startX + (endX - startX) * xProgress;
        
        const minY = -30;
        const maxY = 40;
        
        const yProgress = Math.pow((xProgress - 0.5) * 2, 2);
        const lakituY = minY + (maxY - minY) * (1 - yProgress);
        
        // During driving-away phase, hide Lakitu once it reaches the top of its arc (edges)
        // At edges, lakituY = -30, so check if it's close to that minimum
        if (this.lakituStopTime !== null) {
            if (lakituY <= -25) {
                this.lakituFinishFlagActive = false;
                return;
            }
        }
        
        this.lakituFinishFlagTimer += deltaTime;
        if (this.lakituFinishFlagTimer >= 0.25) {
            this.lakituFinishFlagTimer = 0;
            this.lakituFinishFlagFrame = (this.lakituFinishFlagFrame + 1) % 4;
        }
        
        this.lakituFinishPosition = { x: lakituX, y: lakituY };
    }    draw(ctx) {
        if (!this.isLoaded) {
            ctx.fillStyle = 'black';
            ctx.fillRect(0, 0, Settings.canvas.width, Settings.canvas.height);
            ctx.fillStyle = 'white';
            ctx.font = '24px Monospace';
            ctx.textAlign = 'center';
            ctx.fillText('Loading...', Settings.canvas.width / 2, Settings.canvas.height / 2);
            return;
        }
        ctx.imageSmoothingEnabled = false;

        ctx.clearRect(0, 0, Settings.canvas.width, Settings.canvas.height);

        this.minimapRenderer.draw(ctx, this.player, this.hudHeight, this.viewportHeight, this.dividerHeight, this.aiRacers, this.waypoints);

        ctx.save();
        ctx.beginPath();
        ctx.rect(0, this.hudHeight, Settings.canvas.width, this.viewportHeight);
        ctx.clip();

        const originalHeight = this.renderer.height;
        this.renderer.height = this.viewportHeight;

        this.renderer.drawSky(this.camera, Math.floor(this.viewportHeight / 2 + this.camera.getPitch()));
        this.renderer.drawGround(this.camera, Math.floor(this.viewportHeight / 2 + this.camera.getPitch()));

        const worldObjects = [];

        const playerDistance = Math.hypot(
            this.player.x - this.camera.getX(),
            this.player.y - this.camera.getY()
        );
        worldObjects.push({ type: 'player', object: this.player, distance: playerDistance });

        for (const ai of this.aiRacers) {
            const distance = Math.hypot(
                ai.x - this.camera.getX(),
                ai.y - this.camera.getY()
            );
            worldObjects.push({ type: 'racer', object: ai, distance });
        }

        for (const box of this.mysteryBoxes) {
            if (!box.active) continue;
            const distance = Math.hypot(
                box.x - this.camera.getX(),
                box.y - this.camera.getY()
            );
            worldObjects.push({ type: 'mysteryBox', object: box, distance });
        }

        worldObjects.sort((a, b) => b.distance - a.distance);

        for (const item of worldObjects) {
            if (item.type === 'player') {
                this.renderer.drawMario(item.object, this.camera, this.victoryDriveAwayOffset);
            } else if (item.type === 'racer') {
                this.renderer.drawRacerInWorld(item.object, this.camera);
            } else if (item.type === 'mysteryBox') {
                this.renderer.drawMysteryBox(
                    item.object,
                    this.mysteryBoxBaseSprite,
                    this.mysteryBoxShaderSprite,
                    this.mysteryBoxBaseSpriteFrames,
                    this.mysteryBoxShaderSpriteFrames,
                    this.camera
                );
            }
        }

        this.renderer.height = originalHeight;

        ctx.restore();

        ctx.fillStyle = 'black';
        ctx.fillRect(
            0, 
            this.hudHeight + this.viewportHeight,
            Settings.canvas.width,
            this.dividerHeight
        );

        // Draw Lakitu finish flag if active
        if (this.lakituFinishFlagActive && this.lakituFinishPosition) {
            // Map frame index to sprite frame name: 0->finish1, 1->finish2, 2->finish1, 3->finish3
            const frameSequence = ['finish1', 'finish2', 'finish1', 'finish3'];
            const frameName = frameSequence[this.lakituFinishFlagFrame];
            const frame = this.lakituFrames[frameName];
            
            ctx.drawImage(
                this.lakituSprite.image,
                frame.x,
                frame.y,
                frame.width,
                frame.height,
                this.lakituFinishPosition.x - frame.width / 2,
                this.lakituFinishPosition.y - frame.height / 2,
                frame.width,
                frame.height
            );
        }

        if (this.finishedRacers.length > 0) {
            this.drawResults(ctx);
        }

        this.hud.draw(ctx);
    }

    drawResults(ctx) {
        const numberNames = ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight'];
        const flashStates = ['normal', 'orange', 'red'];
        const currentFlashState = flashStates[this.resultsFlashState % 3];
        
        for (let i = 0; i < this.finishedRacers.length; i++) {
            const racer = this.finishedRacers[i];
            const place = racer.place;
            const character = racer.character;
            
            const characterSprite = RANKING_SPRITES[character][String(this.resultsCharacterState % 3 + 1)];
            const numberSprite = RANKING_SPRITES[numberNames[place - 1]][currentFlashState];
            
            let x, y;
            if (place <= 4) {
                const offsetFromDivider = (4 - place) * 17;
                y = this.hudHeight + this.viewportHeight - 1 - 16 - offsetFromDivider;
                x = 5;
            } else {
                const offsetFromDivider = (place - 5) * 17;
                y = this.hudHeight + this.viewportHeight + this.dividerHeight + 1 + offsetFromDivider;
                x = 5;
            }
            
            ctx.drawImage(
                this.rankingSprite.image,
                numberSprite.x, numberSprite.y, numberSprite.width, numberSprite.height,
                x, y, numberSprite.width, numberSprite.height
            );
            
            ctx.drawImage(
                this.rankingSprite.image,
                characterSprite.x, characterSprite.y, characterSprite.width, characterSprite.height,
                x + numberSprite.width, y, characterSprite.width, characterSprite.height
            );
        }
    }

    updateLakituLapAnimation(deltaTime) {
        if (!this.lakituLapDisplay) return;

        this.lakituLapTimer += deltaTime;
        this.lakituLapAnimProgress = Math.min(this.lakituLapTimer / this.lakituLapDuration, 1.0);

        let x, y;

        if (this.lakituLapAnimProgress < 0.5) {
            const t = this.lakituLapAnimProgress * 2;
            
            const startX = -50;
            const endX = Settings.canvas.width / 2 - 30;
            x = startX + (endX - startX) * t;
            
            const startY = 20;
            const bottomY = 60;
            const angle = t * Math.PI;
            y = startY + (bottomY - startY) * t - Math.sin(angle) * 30;
            
        } else {
            const t = (this.lakituLapAnimProgress - 0.5) * 2;
            
            const startX = Settings.canvas.width / 2 - 30;
            const endX = -50;
            x = startX + (endX - startX) * t;
            
            const startY = 60;
            const endY = 20;
            const angle = t * Math.PI;
            y = startY + (endY - startY) * t + Math.sin(angle) * 30;
        }

        this.hud.updateElement('lakitu', {
            x: Math.floor(x),
            y: Math.floor(y)
        });

        if (this.lakituLapAnimProgress >= 1.0) {
            this.lakituLapDisplay = false;
            this.hud.updateElement('lakitu', { visible: false });
        }
    }

    updatePlayerPlacement(deltaTime) {
        const playerOffsetDistance = Math.abs(this.player.speed) > 0.5 ? 30 : 0;
        const aiOffsetDistance = 5; 
        
        const racers = [
            {
                isPlayer: true,
                lapsCompleted: this.collisionManager.currentLap,
                currentCheckpointIndex: this.player.currentWaypointIndex,
                x: this.player.x + Math.sin(this.player.angle) * playerOffsetDistance,
                y: this.player.y + Math.cos(this.player.angle) * playerOffsetDistance
            },
            ...this.aiRacers.map(ai => ({
                isPlayer: false,
                lapsCompleted: ai.currentLap,
                currentCheckpointIndex: ai.currentWaypointIndex,
                x: ai.x + Math.sin(ai.angle) * aiOffsetDistance,
                y: ai.y + Math.cos(ai.angle) * aiOffsetDistance
            }))
        ];

        racers.forEach(racer => {
            const nextCheckpointIndex = (racer.currentCheckpointIndex + 1) % this.waypoints.length;
            const nextCheckpoint = this.waypoints[nextCheckpointIndex];
            const dx = nextCheckpoint.x - racer.x;
            const dy = nextCheckpoint.y - racer.y;
            racer.distanceToNextCheckpoint = Math.sqrt(dx * dx + dy * dy);
        });

        racers.sort((racerA, racerB) => {
            if (racerA.lapsCompleted !== racerB.lapsCompleted) {
                return racerB.lapsCompleted - racerA.lapsCompleted;
            }

            if (racerA.currentCheckpointIndex !== racerB.currentCheckpointIndex) {
                const checkpointA = racerA.currentCheckpointIndex;
                const checkpointB = racerB.currentCheckpointIndex;
                const totalCheckpoints = this.waypoints.length;
                
                const diff = Math.abs(checkpointA - checkpointB);
                if (diff > totalCheckpoints / 2) {
                    return checkpointA - checkpointB;
                } else {
                    return checkpointB - checkpointA;
                }
            }

            return racerA.distanceToNextCheckpoint - racerB.distanceToNextCheckpoint;
        });

        const playerIndex = racers.findIndex(r => r.isPlayer);
        const playerPlace = playerIndex + 1;

        if (playerPlace !== this.pendingPlace) {
            this.pendingPlace = playerPlace;
            this.placementStabilityTimer = 0;
        } else {
            this.placementStabilityTimer += deltaTime;
        }

        if (this.placementStabilityTimer >= 0.25 && playerPlace !== this.playerCurrentPlace) {
            this.playerCurrentPlace = playerPlace;
            
            if (!this.raceCompleted) {
                this.hud.updateElement('position', {
                    sprite: NUMBER_SPRITES.lapPosition[String(this.playerCurrentPlace)]
                });
            }
        }
    }

    findClosestWaypointIndex(x, y) {
        let closestIndex = 0;
        let closestDistance = Infinity;
        
        for (let i = 0; i < this.waypoints.length; i++) {
            const wp = this.waypoints[i];
            const dx = wp.x - x;
            const dy = wp.y - y;
            const distance = Math.sqrt(dx * dx + dy * dy);
            
            if (distance < closestDistance) {
                closestDistance = distance;
                closestIndex = i;
            }
        }
        
        return closestIndex;
    }

    updateAILapTracking(deltaTime) {
        for (const ai of this.aiRacers) {
            const collisionDistance = 27;
            const checkX = ai.x + Math.sin(ai.angle) * collisionDistance;
            const checkY = ai.y + Math.cos(ai.angle) * collisionDistance;

            const pixelType = this.getPixelTypeForAI(checkX, checkY);

            if (pixelType === 'finishLine') {
                if (!ai.onFinishLine && ai.hasPassedCheckpoint) {
                    ai.currentLap++;
                    ai.hasPassedCheckpoint = false;
                    
                    if (ai.currentLap > this.collisionManager.getTotalLaps() && !ai.raceFinished) {
                        ai.raceFinished = true;
                        this.onRacerFinished(ai, false);
                    }
                }
                ai.onFinishLine = true;
            } else {
                ai.onFinishLine = false;
            }

            if (pixelType === 'checkpoint') {
                if (!ai.onCheckpoint) {
                    ai.hasPassedCheckpoint = true;
                }
                ai.onCheckpoint = true;
            } else {
                ai.onCheckpoint = false;
            }
        }
    }

    getPixelTypeForAI(x, y) {
        const maskData = this.maskData;
        const index = (Math.floor(y) * maskData.width + Math.floor(x)) * 4;

        const r = maskData.data[index];
        const g = maskData.data[index + 1];
        const b = maskData.data[index + 2];

        if (r > 200 && g < 50 && b < 50) return 'finishLine';
        if (r < 50 && g > 200 && b < 50) return 'checkpoint';
        if (r < 50 && g < 50 && b > 200) return 'wall';
        if (r > 200 && g > 200 && b < 50) return 'itemBox';
        if (r > 200 && g > 200 && b > 200) return 'road';
        if (r < 50 && g < 50 && b < 50) return 'offTrack';

        return 'track';
    }

    exit() {
        
    }
}