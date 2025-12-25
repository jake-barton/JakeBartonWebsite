import { Settings } from "../core/Settings.js";
import { Player } from "../core/Player.js";
import { Camera } from "../core/Camera.js";
import { Renderer } from "../core/Renderer.js";
import { MapLoader } from "../core/MapLoader.js";
import { HUD } from "../ui/HUD.js";
import { Sprite } from "../utils/Sprite.js";
import { MYSTERY_BOX_SPRITES } from "../data/mystery_box_sprites.js";
import { NUMBER_SPRITES } from "../data/NumberSprites.js";
import { ITEM_SPRITES } from "../data/ItemSprites.js";
import { RANKING_SPRITES } from "../data/RankingSprites.js";
import { CollisionManager } from "../utils/CollisionManager.js";
import { MinimapRenderer } from "../ui/MinimapRenderer.js";
import { MysteryBox } from "../objects/MysteryBox.js";
import { Coin } from "../objects/Coin.js";
import { Banana } from "../objects/Banana.js";
import { GreenShell } from "../objects/GreenShell.js";
import { RedShell } from "../objects/RedShell.js";
import { AIRacer } from "../core/AIRacer.js";
import { CharacterPool } from "../utils/CharacterPool.js";
import { FontRenderer } from "../utils/FontRenderer.js";
import { ParticleSystem } from "../utils/ParticleSystem.js";

export class RaceScreen {
    constructor(screenManager) {
        this.screenManager = screenManager;
        this.player = null;
        this.camera = null;
        this.renderer = null;
        this.hud = new HUD();
        this.aiRacers = [];
        this.characterPool = new CharacterPool(); // Character pool for NPC selection
        this.fontRenderer = new FontRenderer(); // Custom font renderer

        this.numberSprite = new Sprite('assets/sprites/ui/Numbers.png');
        this.lapPositionSprite = new Sprite('assets/sprites/ui/LapPlaces.png');
        this.itemSprite = new Sprite('assets/sprites/ui/ItemRoulette.png');
        this.worldItemsSprite = new Sprite('assets/sprites/objects/items.png'); // For world coins
        this.particleSprite = new Sprite('assets/sprites/particles/particles.png'); // For particles
        this.lakituSprite = new Sprite('assets/sprites/lakitu/lakitu.png');
        this.rankingSprite = new Sprite('assets/sprites/ui/rankings.png');
        
        // Load background for loading screen
        this.backgroundImage = new Image();
        this.backgroundImage.src = './spritesheets/UI/titlecard.png';

        this.isLoaded = false;
        this.raceCountdown = 3;
        this.countdownTimer = 0;
        this.raceStarted = false;
        this.raceTimer = 0;
        
        // Intro camera sequence
        this.introCameraActive = false;
        this.introCameraTimer = 0;
        this.introCameraDuration = 5.0; // 5 seconds for the intro sequence
        this.introCameraProgress = 0;
        this.introCameraStartPos = null;
        this.introCameraStartAngle = null;

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
        this.coins = [];
        this.bananas = []; // Active bananas on the track
        this.greenShells = []; // Active green shells flying around
        this.redShells = []; // Active red shells tracking targets
        this.mysteryBoxBaseSprite = null;
        
        // Particle system
        this.particleSystem = new ParticleSystem();
        
        // Fake banana drop animation (visual effect in viewport)
        this.fakeBananaDrop = null; // { y, alpha, timer }
        this.mysteryBoxShaderSprite = null;
        
        // Item system
        this.availableItems = ['mushroom', 'star', 'bannana', 'green_shell', 'red_shell', 'ghost', 'lightning', 'coin', 'feather'];
        
        // Lightning flash effect
        this.lightningFlashActive = false;
        this.lightningFlashTimer = 0;
        this.lightningFlashCount = 0;
        this.lightningFlashDuration = 0.15; // Each flash lasts 0.15s (total 0.9s for 6 flashes - 3 cycles)
        
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
        
        // Performance: Reuse this array instead of creating new one every frame
        this.worldObjects = [];
        
        this.victoryCameraActive = false;
        this.victoryCameraPhase = 'rotating';
        this.victoryCameraTimer = 0;
        this.victoryCameraRotationStart = 0;
        this.victoryCameraRotationTarget = 0;
        this.victoryDriveTimer = 0;
        this.victoryCelebrationTimer = 0;
        this.victoryCelebrationState = 0;
        this.victoryDriveAwayOffset = 0;
        
        // Results screen state
        this.resultsScreenActive = false;
        this.resultsScreenTimer = 0;
        this.resultsScreenPhase = 'hud-exit'; // 'hud-exit', 'panel-enter', 'display', 'buttons', 'exit'
        this.hudExitOffset = 0; // For sliding HUD elements off screen
        this.rankingsExitOffset = 0; // For sliding rankings left
        this.resultsPanelOffset = 0; // For sliding results panel in
        this.playerFinishTime = 0;
        this.playerFinishPlace = 0;
        this.playerPoints = 0;
        
        // Results panel animations
        this.resultsRowAnimations = []; // Array to track each row's slide-in progress
        this.resultsPointsAnimations = []; // Array to track point counting for each row
        this.resultsRowExitAnimations = []; // Array to track exit animations
        
        // Results navigation buttons
        this.resultsButtons = ['NEXT RACE', 'QUIT'];
        this.resultsSelectedButton = 0;
        this.resultsButtonsVisible = false;
        this.resultsButtonAnimProgress = 0;
        
        // Scrolling background for results screen
        this.resultsScrollOffset = 0;
        this.resultsScrollSpeed = 20;
        this.resultsScrollBg = new Image();
        this.resultsScrollBg.src = 'MARIO KART SNES/images/scroll.png';
        
        // Point system (1st = 15, 2nd = 12, 3rd = 10, 4th = 8, 5th = 6, 6th = 4, 7th = 2, 8th = 1)
        this.pointsTable = [15, 12, 10, 8, 6, 4, 2, 1];
    }

    async enter(gameData) {
        // Track loading start time for minimum display duration
        const loadingStartTime = Date.now();
        const minimumLoadingTime = 1500; // Show loading screen for at least 1.5 seconds
        
        // Get player's selected character from gameData, default to mario
        const playerCharacterId = gameData?.selectedCharacter || 'mario';
        const playerCharacterData = this.characterPool.getCharacterById(playerCharacterId);
        
        // Reset pool and reserve player's character so NPCs don't use it
        this.characterPool.reset(playerCharacterId);
        
        this.player = new Player(
            Settings.player.startX,
            Settings.player.startY,
            Settings.player.startAngle
        );
        
        // Store player's character data on player object for rendering
        this.player.characterData = playerCharacterData;
        this.player.characterSprites = playerCharacterData.sprites;
        this.player.characterSprite = null; // Will be lazy-loaded by renderer
        this.player.minimapCharacterSprite = null;

        this.camera = new Camera(this.player);

        const mapLoader = new MapLoader();
        const mapData = await mapLoader.load('marioCircuit');

        this.maskData = mapData.maskData;
        this.collisionManager = new CollisionManager(this.maskData, this.player);

        // Create AI racers with random unique characters from the pool
        for (const aiStart of Settings.aiRacers) {
            // Get a unique random character for this NPC
            const character = this.characterPool.getUniqueRandomCharacter();
            
            const aiRacer = new AIRacer(
                aiStart.x,
                aiStart.y,
                aiStart.angle,
                mapData.waypoints,
                mapData.maskData,
                character.id // Use the character ID from the pool
            );
            aiRacer.currentLap = 1;
            aiRacer.hasPassedCheckpoint = false;
            aiRacer.onFinishLine = false;
            aiRacer.onCheckpoint = false;
            aiRacer.raceFinished = false;
            aiRacer.characterData = character; // Store full character data for rendering
            // Cache sprite references for performance (avoids lookups every frame)
            aiRacer.characterSprites = character.sprites;
            aiRacer.characterSprite = null; // Will be lazy-loaded by renderer
            this.aiRacers.push(aiRacer);
        }
        
        // Set up AI collision avoidance - each AI needs to know about the others
        for (const aiRacer of this.aiRacers) {
            aiRacer.setOtherRacers(this.aiRacers);
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
        
        // Ensure minimum loading screen display time
        const loadingElapsed = Date.now() - loadingStartTime;
        const remainingTime = minimumLoadingTime - loadingElapsed;
        
        if (remainingTime > 0) {
            await new Promise(resolve => setTimeout(resolve, remainingTime));
        }
        
        this.isLoaded = true;
        
        // Start intro camera sequence
        this.startIntroCameraSequence();

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

        this.coins = Settings.obstacles
            .filter(obstacle => obstacle.type === 'coin')
            .map(obstacle => new Coin(obstacle.x, obstacle.y, obstacle.height || 0, obstacle.scale || 1.0));

            this.updatePlayerPlacement(0);
    }

    update(keys, deltaTime) {
        if (!this.isLoaded) return;
        
        // Handle results screen input
        if (this.resultsScreenActive) {
            // If displaying results, wait for Enter to start exit animation
            if (this.resultsScreenPhase === 'display') {
                if (keys['Enter'] && !this.resultsEnterPressed) {
                    this.resultsEnterPressed = true;
                    this.resultsScreenPhase = 'exit';
                    this.resultsScreenTimer = 0;
                    this.resultsRowExitAnimations = new Array(8).fill(0);
                }
                if (!keys['Enter']) this.resultsEnterPressed = false;
            }
            // If buttons are visible, handle navigation
            else if (this.resultsButtonsVisible) {
                // Arrow key navigation
                if (keys['ArrowUp'] && !this.resultsUpPressed) {
                    this.resultsUpPressed = true;
                    this.resultsSelectedButton = Math.max(0, this.resultsSelectedButton - 1);
                }
                if (!keys['ArrowUp']) this.resultsUpPressed = false;
                
                if (keys['ArrowDown'] && !this.resultsDownPressed) {
                    this.resultsDownPressed = true;
                    this.resultsSelectedButton = Math.min(this.resultsButtons.length - 1, this.resultsSelectedButton + 1);
                }
                if (!keys['ArrowDown']) this.resultsDownPressed = false;
                
                // Enter to select
                if (keys['Enter'] && !this.resultsEnterPressed) {
                    this.resultsEnterPressed = true;
                    this.handleResultsButtonPress();
                }
                if (!keys['Enter']) this.resultsEnterPressed = false;
            }
        }
        
        // Handle intro camera sequence
        if (this.introCameraActive) {
            this.updateIntroCameraSequence(deltaTime);
            this.camera.update(keys); // Update camera during intro
            
            // Don't update AI or mystery boxes during intro - everything frozen
            return;
        }

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
            
            // Emit particles for player
            this.emitPlayerParticles(deltaTime);
            
            for (const ai of this.aiRacers) {
                ai.update(deltaTime);
            }

            this.updateAILapTracking(deltaTime);

            this.updatePlayerPlacement(deltaTime);
            
            this.updateAIItems(deltaTime);
            
            this.checkAICollisions(deltaTime);
            
            this.checkAIObjectCollisions();
            
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
            
            // Update results screen animations
            if (this.resultsScreenActive) {
                this.updateResultsScreen(deltaTime);
            }

            // Only update timer if race is not completed
            if (!this.raceCompleted) {
                this.raceTimer += deltaTime;
                this.hud.updateElement('timer', { text: this.formatTimer(this.raceTimer) }); 
            }
        }

        for (const box of this.mysteryBoxes) {
            box.update(deltaTime);
        }
        
        for (const coin of this.coins) {
            coin.update(deltaTime);
        }
        
        // Update bananas
        for (const banana of this.bananas) {
            banana.update(deltaTime);
        }
        
        // Update green shells
        for (const shell of this.greenShells) {
            shell.update(deltaTime);
            // Check wall bouncing
            if (this.maskData) {
                shell.checkWallBounce(this.maskData);
            }
        }
        
        // Remove inactive shells
        this.greenShells = this.greenShells.filter(s => s.active);
        
        // Update red shells
        for (const shell of this.redShells) {
            shell.update(deltaTime);
            // Check wall collision (red shells break on walls)
            shell.checkWallCollision();
        }
        
        // Remove inactive red shells
        this.redShells = this.redShells.filter(s => s.active);
        
        // Update particle system
        this.particleSystem.update(deltaTime);
        
        // Update fake banana drop animation
        if (this.fakeBananaDrop) {
            // Move down based on player speed
            this.fakeBananaDrop.y += this.fakeBananaDrop.speed * deltaTime;
            
            // Remove when it goes too far off screen
            if (this.fakeBananaDrop.y > 200) {
                this.fakeBananaDrop = null;
            }
        }
        
        // Check mystery box collisions
        this.checkMysteryBoxCollisions();
        
        // Check coin collisions
        this.checkCoinCollisions();
        
        // Check banana collisions
        this.checkBananaCollisions();
        
        // Check green shell collisions
        this.checkGreenShellCollisions();
        
        // Check red shell collisions
        this.checkRedShellCollisions();
        
        // Handle banana placement
        if (this.player.placeBanana) {
            this.placeBanana(this.player);
        }
        
        // Handle green shell throwing
        if (this.player.throwGreenShell) {
            this.throwGreenShell(this.player);
        }
        
        // Handle red shell throwing
        if (this.player.throwRedShell) {
            this.throwRedShell(this.player);
        }
        
        // Handle ghost steal
        if (this.player.ghostStealActive && !this.player.ghostStealTriggered) {
            this.player.ghostStealTriggered = true; // Mark as triggered
            this.handleGhostSteal();
        }
        
        // Handle lightning
        if (this.player.lightningActive && !this.player.lightningTriggered) {
            this.player.lightningTriggered = true; // Mark as triggered
            this.handleLightning();
        }
        
        // Update lightning flash effect
        if (this.lightningFlashActive) {
            this.updateLightningFlash(deltaTime);
        }
        
        // Update item roulette
        if (this.player.isRouletteActive) {
            const displayItem = this.player.updateItemRoulette(deltaTime, this.availableItems, this.playerCurrentPlace);
            if (displayItem) {
                this.hud.updateElement('itemBox', { 
                    sprite: ITEM_SPRITES[displayItem],
                    visible: true
                });
            }
        } else if (this.player.hasItem) {
            const item = this.player.getItemForDisplay();
            this.hud.updateElement('itemBox', { 
                sprite: ITEM_SPRITES[item],
                visible: true
            });
        } else {
            // Show blank when no item
            this.hud.updateElement('itemBox', { 
                sprite: ITEM_SPRITES.blank,
                visible: true
            });
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

    checkMysteryBoxCollisions() {
        // Don't allow collection if race is completed
        if (this.raceCompleted) return;
        
        const collisionDistance = 15; // Reduced from 30 to prevent collecting multiple boxes
        
        // Calculate player kart sprite position (offset in direction of travel)
        // Use 20 units offset to match visual kart position without being too far forward
        const playerOffsetDistance = 20;
        const playerKartX = this.player.x + Math.sin(this.player.angle) * playerOffsetDistance;
        const playerKartY = this.player.y + Math.cos(this.player.angle) * playerOffsetDistance;
        
        for (const box of this.mysteryBoxes) {
            if (!box.active) continue;
            
            // Calculate distance from player kart sprite to box
            const dx = box.x - playerKartX;
            const dy = box.y - playerKartY;
            const distance = Math.sqrt(dx * dx + dy * dy);
            
            if (distance < collisionDistance) {
                // Always break the box on collision
                box.collect();
                
                // Only start item roulette if player doesn't already have an item AND not using star power
                if (!this.player.hasItem && !this.player.isRouletteActive && !this.player.starPowerActive) {
                    this.player.startItemRoulette();
                }
                // If player already has item or is using star power, box still breaks but no new item
                
                break; // Only collect one box per frame
            }
        }
    }

    checkCoinCollisions() {
        const collisionDistance = 10; // Reduced from 15
        
        // Calculate player kart sprite position (offset in direction of travel)
        const playerOffsetDistance = 20;
        const playerKartX = this.player.x + Math.sin(this.player.angle) * playerOffsetDistance;
        const playerKartY = this.player.y + Math.cos(this.player.angle) * playerOffsetDistance;
        
        // Don't allow collection if race is completed
        if (!this.raceCompleted) {
            for (const coin of this.coins) {
                if (!coin.active) continue;
                
                // Calculate distance from player kart sprite to coin
                const dx = coin.x - playerKartX;
                const dy = coin.y - playerKartY;
                const distance = Math.sqrt(dx * dx + dy * dy);
                
                if (distance < collisionDistance) {
                    // Collect the coin
                    coin.collect();
                    
                    // Increment player's coin count directly
                    this.player.coinCount++;
                    
                    // Trigger coin bounce animation
                    this.player.coinAnimationActive = true;
                    this.player.coinAnimationTimer = 0;
                    this.player.coinBounceHeight = 0;
                    this.player.coinSpinRotation = 0;
                    
                    break; // Only collect one coin per frame
                }
            }
        }
    }

    checkAIObjectCollisions() {
        const collisionDistance = 20; // AI collision radius
        
        for (const ai of this.aiRacers) {
            // Calculate AI kart position (offset in direction of travel)
            const aiOffsetDistance = 20;
            const aiKartX = ai.x + Math.sin(ai.angle) * aiOffsetDistance;
            const aiKartY = ai.y + Math.cos(ai.angle) * aiOffsetDistance;
            
            // Check mystery box collisions
            for (const box of this.mysteryBoxes) {
                if (!box.active) continue;
                
                const dx = box.x - aiKartX;
                const dy = box.y - aiKartY;
                const distance = Math.sqrt(dx * dx + dy * dy);
                
                if (distance < collisionDistance) {
                    box.collect();
                    
                    // Give AI an item if they don't have one
                    if (!ai.hasItem) {
                        // Calculate AI placement for appropriate items
                        const allRacers = [this.player, ...this.aiRacers];
                        const sortedRacers = [...allRacers].sort((a, b) => {
                            const aLap = a.currentLap || 0;
                            const bLap = b.currentLap || 0;
                            if (aLap !== bLap) return bLap - aLap;
                            return 0;
                        });
                        
                        const placement = sortedRacers.indexOf(ai) + 1;
                        
                        // Get appropriate items - lightning is rare (only 6-8th place)
                        let availableItems = this.availableItems.filter(item => item !== 'ghost');
                        
                        if (placement === 1) {
                            availableItems = ['coin', 'green_shell', 'feather', 'bannana'];
                        } else if (placement >= 2 && placement <= 5) {
                            availableItems = availableItems.filter(item => !['lightning', 'star'].includes(item));
                        } else if (placement >= 6 && placement <= 7) {
                            // 6-7th place: remove lightning to make it rare
                            availableItems = availableItems.filter(item => item !== 'lightning');
                        }
                        // 8th place gets full item pool including lightning
                        
                        const randomItem = availableItems[Math.floor(Math.random() * availableItems.length)];
                        ai.receiveItem(randomItem);
                    }
                    
                    break;
                }
            }
            
            // Check coin collisions
            for (const coin of this.coins) {
                if (!coin.active) continue;
                
                const dx = coin.x - aiKartX;
                const dy = coin.y - aiKartY;
                const distance = Math.sqrt(dx * dx + dy * dy);
                
                if (distance < collisionDistance) {
                    coin.collect();
                    // AI doesn't track coins, they just collect them
                    break;
                }
            }
        }
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
                // Check if player has star power - if so, AI gets hit instead of bumping
                if (this.player.starPowerActive) {
                    ai.triggerHit(); // AI enters hit state
                    ai.bumpCooldown = bumpCooldown;
                    continue; // Skip normal bump physics
                }
                
                // Check if AI has star power - if so, player gets hit
                if (ai.starPowerActive) {
                    this.player.hit(); // Player enters hit state
                    ai.bumpCooldown = bumpCooldown;
                    continue; // Skip normal bump physics
                }
                
                // Normal bump physics (no star power on either side)
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
        
        // Predict AI finish times based on their position and distance from finish
        this.predictAIFinishTimes();
        
        this.onRacerFinished(this.player, true);
        
        // Store player finish data
        this.playerFinishTime = this.raceTimer;
        this.playerFinishPlace = this.finishedRacers.length; // Already incremented in onRacerFinished
        this.playerPoints = this.pointsTable[this.playerFinishPlace - 1] || 0;
        
        this.victoryCameraActive = true;
        this.victoryCameraPhase = 'rotating';
        this.victoryCameraTimer = 0;
        this.victoryCameraRotationStart = this.camera.getAngle();
        this.victoryCameraRotationTarget = this.player.angle + Math.PI;
        this.player.victoryMode = true;
        
        // Start results screen sequence after victory camera
        this.resultsScreenActive = false; // Will activate after victory sequence
        this.resultsScreenTimer = 0;
        this.resultsScreenPhase = 'hud-exit';
        this.hudExitOffset = 0;
        this.rankingsExitOffset = 0;
        this.resultsPanelOffset = Settings.canvas.width; // Start off-screen right
    }
    
    predictAIFinishTimes() {
        // Predict finish times for all AI racers based on their waypoint progress
        this.aiRacers.forEach((ai, index) => {
            // Simple prediction: AI racers behind get proportionally longer times
            // Use waypoint progress to estimate remaining distance
            const waypointProgress = ai.currentWaypointIndex / ai.waypoints.length;
            const estimatedProgress = waypointProgress; // 0.0 to 1.0
            
            // Calculate estimated time remaining (inverse of progress)
            const avgRaceTime = 120; // Assume average race is ~2 minutes
            const timeRemaining = (1.0 - estimatedProgress) * avgRaceTime;
            
            // Add variance to make it realistic
            const variance = (Math.random() * 10) - 5; // ±5 seconds variance
            const predictedTime = this.raceTimer + timeRemaining + variance;
            
            const characterNames = {
                'mario': 'MARIO', 'luigi': 'LUIGI', 'bowser': 'BOWSER', 
                'koopa': 'KOOPA', 'donkey_kong': 'D.KONG', 'peach': 'PEACH', 
                'toad': 'TOAD', 'yoshi': 'YOSHI'
            };
            const name = characterNames[ai.character] || ai.character.toUpperCase();
            
            this.finishedRacers.push({
                character: ai.character,
                place: this.finishedRacers.length + 1,
                isPlayer: false,
                name: name,
                finishTime: Math.max(this.raceTimer + 0.1, predictedTime) // Ensure positive time
            });
        });
        
        // Sort by finish time to get correct placements
        this.finishedRacers.sort((a, b) => a.finishTime - b.finishTime);
        
        // Update place numbers after sorting
        this.finishedRacers.forEach((racer, index) => {
            racer.place = index + 1;
            if (racer.isPlayer) {
                this.playerFinishPlace = index + 1;
                this.playerPoints = this.pointsTable[this.playerFinishPlace - 1] || 0;
            }
        });
    }

    onRacerFinished(racer, isPlayer) {
        const finishPlace = this.finishedRacers.length + 1;
        
        // Get character ID - for player, use their actual character, for AI use their character
        const characterId = isPlayer 
            ? (this.player.characterData?.id || 'mario') 
            : racer.character;
        
        // Get character name
        const characterNames = {
            'mario': 'MARIO', 'luigi': 'LUIGI', 'bowser': 'BOWSER', 
            'koopa': 'KOOPA', 'donkey_kong': 'D.KONG', 'peach': 'PEACH', 
            'toad': 'TOAD', 'yoshi': 'YOSHI'
        };
        const name = characterNames[characterId] || characterId.toUpperCase();
        
        this.finishedRacers.push({
            character: characterId,
            place: finishPlace,
            isPlayer: isPlayer,
            name: name,
            finishTime: this.raceTimer
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
            
            // After 3 seconds of driving away, trigger results screen
            if (this.victoryCameraTimer >= 3.0 && !this.resultsScreenActive) {
                this.resultsScreenActive = true;
                this.resultsScreenTimer = 0;
                this.resultsScreenPhase = 'hud-exit';
            }
            
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
    }
    
    updateResultsScreen(deltaTime) {
        if (!this.resultsScreenActive) return;
        
        this.resultsScreenTimer += deltaTime;
        
        // Always scroll the background when results screen is active
        this.resultsScrollOffset -= this.resultsScrollSpeed * deltaTime;
        
        if (this.resultsScreenPhase === 'hud-exit') {
            // Slide HUD elements off screen (0.5 second duration)
            const exitDuration = 0.5;
            const exitProgress = Math.min(this.resultsScreenTimer / exitDuration, 1.0);
            
            // Smooth easing
            const eased = exitProgress < 0.5 
                ? 2 * exitProgress * exitProgress 
                : 1 - Math.pow(-2 * exitProgress + 2, 2) / 2;
            
            // Slide lap time/item box to the right
            this.hudExitOffset = eased * 300; // Slide 300px to the right
            
            // Slide rankings to the left
            this.rankingsExitOffset = eased * 100; // Slide 100px to the left
            
            if (exitProgress >= 1.0) {
                this.resultsScreenPhase = 'panel-enter';
                this.resultsScreenTimer = 0;
            }
        } else if (this.resultsScreenPhase === 'panel-enter') {
            // Slide results panel in from right (0.8 second duration)
            const enterDuration = 0.8;
            const enterProgress = Math.min(this.resultsScreenTimer / enterDuration, 1.0);
            
            // Smooth easing
            const eased = enterProgress < 0.5 
                ? 2 * enterProgress * enterProgress 
                : 1 - Math.pow(-2 * enterProgress + 2, 2) / 2;
            
            // Start off-screen right, slide to center (panel width is 220px)
            const startX = Settings.canvas.width;
            const endX = Settings.canvas.width / 2 - 110; // Center the panel (220px / 2)
            this.resultsPanelOffset = startX - (startX - endX) * eased;
            
            if (enterProgress >= 1.0) {
                this.resultsScreenPhase = 'display';
                this.resultsScreenTimer = 0;
                
                // Initialize row animations (staggered slide-in from top)
                this.resultsRowAnimations = [];
                this.resultsPointsAnimations = [];
                for (let i = 0; i < 8; i++) {
                    this.resultsRowAnimations.push(0); // 0 = not started, 1 = fully shown
                    this.resultsPointsAnimations.push(0); // Current displayed points
                }
            }
        } else if (this.resultsScreenPhase === 'display') {
            // Animate rows sliding in one by one from top
            const rowAnimDuration = 0.15; // Each row takes 0.15s to slide in
            const rowStaggerDelay = 0.1; // 0.1s delay between each row
            
            for (let i = 0; i < 8; i++) {
                const rowStartTime = i * rowStaggerDelay;
                const rowProgress = Math.max(0, Math.min(1, (this.resultsScreenTimer - rowStartTime) / rowAnimDuration));
                
                // Ease out
                this.resultsRowAnimations[i] = 1 - Math.pow(1 - rowProgress, 3);
                
                // Start counting points after row is 50% visible
                if (rowProgress > 0.5 && i < this.finishedRacers.length) {
                    const targetPoints = this.pointsTable[this.finishedRacers[i].place - 1] || 0;
                    const pointsSpeed = 30; // Points per second
                    const pointsToAdd = pointsSpeed * deltaTime;
                    
                    this.resultsPointsAnimations[i] = Math.min(targetPoints, this.resultsPointsAnimations[i] + pointsToAdd);
                }
            }
            // Stay in display phase until user presses Enter
        } else if (this.resultsScreenPhase === 'exit') {
            // Staggered exit animation for rows
            const exitAnimDuration = 0.2;
            const exitStaggerDelay = 0.08;
            
            for (let i = 0; i < 8; i++) {
                const exitStartTime = i * exitStaggerDelay;
                const exitProgress = Math.max(0, Math.min(1, (this.resultsScreenTimer - exitStartTime) / exitAnimDuration));
                
                // Ease in (slide up and fade out)
                this.resultsRowExitAnimations[i] = exitProgress < 0.5 
                    ? 2 * exitProgress * exitProgress 
                    : 1 - Math.pow(-2 * exitProgress + 2, 2) / 2;
            }
            
            // Check if exit is complete, then show buttons
            const totalExitTime = (7 * exitStaggerDelay) + exitAnimDuration;
            if (this.resultsScreenTimer >= totalExitTime) {
                this.resultsScreenPhase = 'buttons';
                this.resultsScreenTimer = 0;
                this.resultsButtonsVisible = true;
                this.resultsButtonAnimProgress = 0;
            }
        } else if (this.resultsScreenPhase === 'buttons') {
            // Animate buttons sliding in
            const buttonAnimDuration = 0.4;
            this.resultsButtonAnimProgress = Math.min(1, this.resultsScreenTimer / buttonAnimDuration);
        } else if (this.resultsScreenPhase === 'transition') {
            // Waiting for screen transition (handled by setTimeout in handleResultsButtonPress)
        }
    }
    
    handleResultsButtonPress() {
        const selectedOption = this.resultsButtons[this.resultsSelectedButton];
        
        // Hide buttons and transition
        this.resultsButtonsVisible = false;
        this.resultsScreenPhase = 'transition';
        
        // Small delay for visual feedback, then transition
        setTimeout(() => {
            if (selectedOption === 'NEXT RACE') {
                // Restart the race
                this.screenManager.setScreen(new RaceScreen(this.screenManager));
            } else if (selectedOption === 'QUIT') {
                // Return to main menu
                import('./MainMenuScreen.js').then((module) => {
                    this.screenManager.setScreen(new module.MainMenuScreen(this.screenManager));
                });
            }
        }, 300); // Quick transition
    }
    
    formatRaceTime(timeInSeconds) {
        const totalMs = Math.floor(timeInSeconds * 100);
        const minutes = Math.floor(totalMs / 6000);
        const seconds = Math.floor((totalMs % 6000) / 100);
        const centiseconds = totalMs % 100;
        
        return {
            minutes: minutes.toString().padStart(2, '0'),
            seconds: seconds.toString().padStart(2, '0'),
            centiseconds: centiseconds.toString().padStart(2, '0')
        };
    }
    
    draw(ctx) {
        if (!this.isLoaded) {
            // Draw scrolling background
            ctx.imageSmoothingEnabled = false;
            
            // Use the titlecard background sprite
            if (this.backgroundImage?.complete) {
                const bgImage = this.backgroundImage;
                const bgX = 764; // Background coordinates you specified
                const bgY = 16;
                const bgW = 512;
                const bgH = 256;
                
                const tileWidth = bgW;
                const tileHeight = bgH;
                const scaleY = Settings.canvas.height / tileHeight;
                const scaledTileWidth = tileWidth * scaleY;
                
                // Animate scroll
                const scrollX = (Date.now() / 50) % scaledTileWidth;
                const tilesNeeded = Math.ceil(Settings.canvas.width / scaledTileWidth) + 2;
                
                for (let i = -1; i < tilesNeeded; i++) {
                    const x = -scrollX + (i * scaledTileWidth);
                    ctx.drawImage(
                        bgImage,
                        bgX, bgY, bgW, bgH,
                        x, 0,
                        scaledTileWidth, Settings.canvas.height
                    );
                }
            } else {
                // Fallback solid color
                ctx.fillStyle = '#000030';
                ctx.fillRect(0, 0, Settings.canvas.width, Settings.canvas.height);
            }
            
            // Loading text using custom sprite font
            ctx.save();
            
            const centerX = Settings.canvas.width / 2;
            const centerY = Settings.canvas.height / 2;
            const scale = 2; // Smaller scale: 8px font to 16px
            
            // Draw shadow first
            ctx.globalAlpha = 0.5;
            this.fontRenderer.drawText(ctx, 'LOADING', centerX + 2, centerY + 2, scale, true);
            
            // Draw main text
            ctx.globalAlpha = 1.0;
            this.fontRenderer.drawText(ctx, 'LOADING', centerX, centerY, scale, true);
            
            // Animated dots
            const dots = Math.floor((Date.now() / 400) % 4);
            const dotString = '.'.repeat(dots);
            if (dotString) {
                this.fontRenderer.drawText(ctx, dotString, centerX, centerY + 24, scale, true);
            }
            
            ctx.restore();
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

        // Reuse worldObjects array to avoid GC pressure
        this.worldObjects.length = 0;
        const worldObjects = this.worldObjects;

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

        for (const coin of this.coins) {
            if (!coin.active) continue;
            const distance = Math.hypot(
                coin.x - this.camera.getX(),
                coin.y - this.camera.getY()
            );
            worldObjects.push({ type: 'coin', object: coin, distance });
        }
        
        for (const banana of this.bananas) {
            if (!banana.active) continue;
            const distance = Math.hypot(
                banana.x - this.camera.getX(),
                banana.y - this.camera.getY()
            );
            worldObjects.push({ type: 'banana', object: banana, distance });
        }
        
        for (const shell of this.greenShells) {
            if (!shell.active) continue;
            const distance = Math.hypot(
                shell.x - this.camera.getX(),
                shell.y - this.camera.getY()
            );
            worldObjects.push({ type: 'greenShell', object: shell, distance });
        }
        
        for (const shell of this.redShells) {
            if (!shell.active) continue;
            const distance = Math.hypot(
                shell.x - this.camera.getX(),
                shell.y - this.camera.getY()
            );
            worldObjects.push({ type: 'redShell', object: shell, distance });
        }

        worldObjects.sort((a, b) => b.distance - a.distance);

        for (const item of worldObjects) {
            if (item.type === 'player') {
                // Draw player with appropriate method based on intro state
                if (this.introCameraActive) {
                    // Use screen-space rendering with calculated position/scale during intro
                    this.renderer.drawPlayerIntro(item.object, this.camera);
                } else {
                    // Normal screen-space rendering during gameplay
                    this.renderer.drawMario(item.object, this.camera, this.victoryDriveAwayOffset);
                }
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
            } else if (item.type === 'coin') {
                this.renderer.drawCoin(
                    item.object,
                    this.worldItemsSprite,
                    this.camera
                );
            } else if (item.type === 'banana') {
                this.renderer.drawBanana(
                    item.object,
                    this.worldItemsSprite,
                    this.camera
                );
            } else if (item.type === 'greenShell') {
                this.renderer.drawGreenShell(
                    item.object,
                    this.worldItemsSprite,
                    this.camera
                );
            } else if (item.type === 'redShell') {
                this.renderer.drawRedShell(
                    item.object,
                    this.worldItemsSprite,
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
        
        // Draw lightning flash effect over viewport only
        if (this.lightningFlashActive) {
            // Flash pattern: ON, OFF, ON, OFF, ON, OFF (6 total states = 3 flashes)
            // Even counts = flash ON, odd counts = flash OFF
            const shouldFlash = this.lightningFlashCount % 2 === 0;
            if (shouldFlash) {
                ctx.save();
                // Orange-yellow flash color
                ctx.fillStyle = `rgba(255, 200, 50, 0.75)`; // Orange-yellow with 75% opacity
                // Only flash the viewport area, not the HUD or minimap
                ctx.fillRect(0, this.hudHeight, Settings.canvas.width, this.viewportHeight);
                ctx.restore();
            }
        }
        
        // Draw fake banana drop animation in viewport (clipped by divider/minimap)
        if (this.fakeBananaDrop) {
            // Use LOD1 banana sprite
            const bananaSprite = ITEM_SPRITES.bannana_world.lod1[0];
            const x = Math.floor(Settings.canvas.width / 2 - bananaSprite.width / 2);
            const y = Math.floor(this.hudHeight + this.fakeBananaDrop.y);
            
            // Clip to viewport bounds - let it get cut off by the black line
            ctx.save();
            ctx.beginPath();
            ctx.rect(0, this.hudHeight, Settings.canvas.width, this.viewportHeight);
            ctx.clip();
            
            ctx.drawImage(
                this.worldItemsSprite.image,
                bananaSprite.x, bananaSprite.y,
                bananaSprite.width, bananaSprite.height,
                x, y,
                bananaSprite.width, bananaSprite.height
            );
            
            ctx.restore();
        }

        // Draw particles in viewport (clipped by viewport bounds)
        ctx.save();
        ctx.beginPath();
        ctx.rect(0, this.hudHeight, Settings.canvas.width, this.viewportHeight);
        ctx.clip();
        
        // Temporarily set renderer height to viewport for particle calculations
        const originalRendererHeight = this.renderer.height;
        this.renderer.height = this.viewportHeight;
        
        for (const particle of this.particleSystem.getParticles()) {
            if (!particle.active) continue;
            this.renderer.drawParticleInViewport(particle, this.particleSprite, this.player);
        }
        
        this.renderer.height = originalRendererHeight;
        ctx.restore();

        // Draw HUD (but hide timer/itemBox/lap/position during results screen)
        if (this.resultsScreenActive) {
            // Temporarily hide HUD elements that will be animated separately
            const hudElements = ['timer', 'itemBox', 'lap', 'position'];
            const originalVisibility = {};
            hudElements.forEach(id => {
                const element = this.hud.elements.find(el => el.id === id);
                if (element) {
                    originalVisibility[id] = element.visible;
                    element.visible = false;
                }
            });
            
            this.hud.draw(ctx);
            
            // Restore visibility
            hudElements.forEach(id => {
                const element = this.hud.elements.find(el => el.id === id);
                if (element) {
                    element.visible = originalVisibility[id];
                }
            });
        } else {
            this.hud.draw(ctx);
        }
        
        // Draw results screen if active
        if (this.resultsScreenActive) {
            this.drawResultsScreen(ctx);
        }
    }
    
    drawResultsScreen(ctx) {
        // Draw HUD elements with offset animation
        if (this.resultsScreenPhase === 'hud-exit' || this.resultsScreenPhase === 'panel-enter' || this.resultsScreenPhase === 'display') {
            ctx.save();
            
            // Slide lap time and item box to the right
            const hudElements = ['timer', 'itemBox', 'lap', 'position'];
            hudElements.forEach(id => {
                const element = this.hud.elements.find(el => el.id === id);
                if (element && element.visible) {
                    // Temporarily offset for this frame
                    const originalX = element.x;
                    element.x += this.hudExitOffset;
                    
                    // Draw with offset
                    if (element.type === 'number') {
                        this.hud.drawNumber(ctx, element);
                    } else if (element.type === 'sprite') {
                        this.hud.drawSprite(ctx, element);
                    }
                    
                    // Restore original position
                    element.x = originalX;
                }
            });
            
            ctx.restore();
        }
        
        // Draw results panel (when entering, displaying, showing buttons, or exiting)
        if (this.resultsScreenPhase === 'panel-enter' || this.resultsScreenPhase === 'display' || 
            this.resultsScreenPhase === 'buttons' || this.resultsScreenPhase === 'exit') {
            this.drawResultsPanel(ctx);
        }
        
        // Draw navigation buttons
        if (this.resultsButtonsVisible || this.resultsScreenPhase === 'buttons') {
            this.drawResultsButtons(ctx);
        }
    }
    
    drawResultsPanel(ctx) {
        const canvas = Settings.canvas;
        const panelWidth = 220;
        const panelHeight = 200;
        const panelX = this.resultsPanelOffset;
        const panelY = canvas.height / 2 - panelHeight / 2;
        
        ctx.save();
        ctx.imageSmoothingEnabled = false;
        
        // Character mapping
        const characterMapping = {
            'mario': 'mario', 'luigi': 'luigi', 'bowser': 'bowser', 'koopa': 'koopa',
            'donkey_kong': 'dk', 'peach': 'peach', 'toad': 'toad', 'yoshi': 'yoshi'
        };
        
        // Draw main panel background (glass style like other menus)
        ctx.fillStyle = 'rgba(255, 255, 255, 0.25)';
        ctx.fillRect(panelX, panelY, panelWidth, panelHeight);
        
        // Panel border (golden border like menu selections)
        ctx.strokeStyle = 'rgba(255, 215, 0, 0.6)';
        ctx.lineWidth = 2;
        ctx.strokeRect(panelX + 0.5, panelY + 0.5, panelWidth - 1, panelHeight - 1);
        
        // Only show title and divider when rows are visible (not during button phase)
        if (this.resultsScreenPhase !== 'buttons' && this.resultsScreenPhase !== 'transition') {
            // Title section
            const titleY = panelY + 10;
            this.fontRenderer.drawText(ctx, 'FINAL RESULTS', panelX + panelWidth / 2, titleY, 1, true);
            
            // Divider line
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.3)';
            ctx.lineWidth = 1;
            ctx.beginPath();
            ctx.moveTo(panelX + 10, panelY + 22);
            ctx.lineTo(panelX + panelWidth - 10, panelY + 22);
            ctx.stroke();
        }
        
        // Draw all racers (top 8) with individual white panels and animations (only if not in button phase)
        if (this.resultsScreenPhase !== 'buttons' && this.resultsScreenPhase !== 'transition') {
            const startY = panelY + 28;
            const rowHeight = 20;
            const rowPadding = 2;
            
            // Get animated character frame (same as rankings display)
            const characterFrame = String(this.resultsCharacterState % 3 + 1);
        
        for (let i = 0; i < Math.min(8, this.finishedRacers.length); i++) {
            const racer = this.finishedRacers[i];
            const isPlayer = racer.isPlayer;
            
            // Get animation progress for this row (staggered slide-in or exit)
            let animProgress = this.resultsRowAnimations[i] || 0;
            let yOffset = 0;
            let alpha = 1;
            
            if (this.resultsScreenPhase === 'exit') {
                // Exit animation: slide up and fade out
                const exitProgress = this.resultsRowExitAnimations[i] || 0;
                yOffset = Math.floor(-exitProgress * 40); // Slide up 40px
                alpha = Math.floor((1 - exitProgress) * 10) / 10; // Stepped fade out
                
                if (exitProgress <= 0) continue; // Don't draw if exit hasn't started
                if (alpha <= 0) continue; // Don't draw if fully faded
            } else {
                // Entry animation
                if (animProgress <= 0) continue; // Don't draw if not started
                
                // PIXEL PERFECT: Slide in from top with integer positioning
                yOffset = Math.floor((1 - animProgress) * -30); // Round to whole pixels
                
                // PIXEL PERFECT: Step opacity in 10% increments for retro look
                alpha = Math.floor(animProgress * 10) / 10;
            }
            
            const y = startY + (i * rowHeight) + yOffset;
            ctx.globalAlpha = alpha;
            
            // Draw individual white panel for each racer (like mode selection items)
            const rowPanelX = panelX + 6;
            const rowPanelY = Math.floor(y - 1); // Ensure integer position
            const rowPanelWidth = panelWidth - 12;
            const rowPanelHeight = rowHeight - rowPadding;
            
            // White/glass background
            ctx.fillStyle = isPlayer ? 'rgba(255, 255, 255, 0.4)' : 'rgba(255, 255, 255, 0.2)';
            ctx.fillRect(rowPanelX, rowPanelY, rowPanelWidth, rowPanelHeight);
            
            // Add diagonal stripe pattern
            ctx.save();
            ctx.strokeStyle = isPlayer ? 'rgba(255, 215, 0, 0.1)' : 'rgba(255, 255, 255, 0.08)';
            ctx.lineWidth = 1;
            ctx.beginPath();
            // Draw diagonal lines across the panel
            const stripeSpacing = 4;
            for (let stripe = -rowPanelHeight; stripe < rowPanelWidth; stripe += stripeSpacing) {
                ctx.moveTo(rowPanelX + stripe, rowPanelY + rowPanelHeight);
                ctx.lineTo(rowPanelX + stripe + rowPanelHeight, rowPanelY);
            }
            ctx.stroke();
            ctx.restore();
            
            // Border (golden for player, white for others)
            ctx.strokeStyle = isPlayer ? 'rgba(255, 215, 0, 0.6)' : 'rgba(255, 255, 255, 0.3)';
            ctx.lineWidth = isPlayer ? 2 : 1;
            ctx.strokeRect(rowPanelX + 0.5, rowPanelY + 0.5, rowPanelWidth - 1, rowPanelHeight - 1);
            
            // Place number (small)
            const placeText = String(racer.place);
            this.fontRenderer.drawText(ctx, placeText, rowPanelX + 8, rowPanelY + 3, 1, false);
            
            // Character sprite (animated, same as rankings) - NO STRETCHING
            const rankingSpriteKey = characterMapping[racer.character] || 'mario';
            const characterSprites = RANKING_SPRITES[rankingSpriteKey] || RANKING_SPRITES['mario'];
            const characterSprite = characterSprites[characterFrame];
            
            if (characterSprite) {
                // Draw at original size - no scaling
                ctx.drawImage(
                    this.rankingSprite.image,
                    characterSprite.x, characterSprite.y,
                    characterSprite.width, characterSprite.height,
                    rowPanelX + 16, rowPanelY + 1,
                    characterSprite.width, characterSprite.height // Use original dimensions
                );
            }
            
            // Character name
            const charName = racer.name || racer.character.toUpperCase();
            this.fontRenderer.drawText(ctx, charName, rowPanelX + 42, rowPanelY + 3, 1, false);
            
            // Race time
            const timeData = this.formatRaceTime(racer.finishTime);
            const timeString = `${timeData.minutes}"${timeData.seconds}'${timeData.centiseconds}`;
            this.fontRenderer.drawText(ctx, timeString, rowPanelX + 105, rowPanelY + 3, 1, false);
            
            // Points (animated counting up) - PIXEL PERFECT: Only show whole numbers
            const currentPoints = Math.floor(this.resultsPointsAnimations[i] || 0);
            const pointsString = `${currentPoints}`;
            this.fontRenderer.drawText(ctx, pointsString, rowPanelX + 180, rowPanelY + 3, 1, false);
        }
        } // Close the if statement for drawing racers
        
        ctx.globalAlpha = 1.0; // Reset alpha
        ctx.restore();
    }

    drawResults(ctx) {
        const numberNames = ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight'];
        const flashStates = ['normal', 'orange', 'red'];
        const currentFlashState = flashStates[this.resultsFlashState % 3];
        
        // Map character IDs to ranking sprite keys
        const characterMapping = {
            'mario': 'mario',
            'luigi': 'luigi',
            'bowser': 'bowser',
            'koopa': 'koopa',
            'donkey_kong': 'dk',
            'peach': 'peach',
            'toad': 'toad',
            'yoshi': 'yoshi'
        };
        
        for (let i = 0; i < this.finishedRacers.length; i++) {
            const racer = this.finishedRacers[i];
            const place = racer.place;
            const characterId = racer.character;
            
            // Bounds check for place
            if (place < 1 || place > 8) continue;
            
            // Map character ID to ranking sprite key, fallback to mario if not found
            const rankingSpriteKey = characterMapping[characterId] || 'mario';
            
            // Check if character exists in RANKING_SPRITES, use mario as fallback
            const characterSprites = RANKING_SPRITES[rankingSpriteKey] || RANKING_SPRITES['mario'];
            const characterSprite = characterSprites[String(this.resultsCharacterState % 3 + 1)];
            const numberSprite = RANKING_SPRITES[numberNames[place - 1]][currentFlashState];
            
            // Safety check for sprites
            if (!characterSprite || !numberSprite) continue;
            
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
            
            // Apply rankings exit offset if results screen is active
            if (this.resultsScreenActive && this.rankingsExitOffset > 0) {
                x -= this.rankingsExitOffset;
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
    
    drawResultsButtons(ctx) {
        const canvas = Settings.canvas;
        const buttonWidth = 120;
        const buttonHeight = 30;
        const buttonSpacing = 10;
        
        // Center buttons in the middle of the screen (where the panel is)
        const totalButtonHeight = (this.resultsButtons.length * buttonHeight) + ((this.resultsButtons.length - 1) * buttonSpacing);
        const startY = (canvas.height - totalButtonHeight) / 2;
        
        ctx.save();
        ctx.imageSmoothingEnabled = false;
        
        // Animate buttons fading in (stepped opacity for pixel-perfect look)
        const rawAlpha = this.resultsButtonAnimProgress;
        const steppedAlpha = Math.floor(rawAlpha * 10) / 10;
        ctx.globalAlpha = steppedAlpha;
        
        this.resultsButtons.forEach((buttonText, index) => {
            const isSelected = index === this.resultsSelectedButton;
            const buttonX = (canvas.width - buttonWidth) / 2;
            const buttonY = startY + (index * (buttonHeight + buttonSpacing));
            
            // Glass panel background
            ctx.fillStyle = isSelected ? 'rgba(255, 255, 255, 0.3)' : 'rgba(255, 255, 255, 0.15)';
            ctx.fillRect(buttonX, buttonY, buttonWidth, buttonHeight);
            
            // Border
            ctx.strokeStyle = isSelected ? 'rgba(255, 215, 0, 0.6)' : 'rgba(255, 255, 255, 0.25)';
            ctx.lineWidth = isSelected ? 2 : 1;
            ctx.strokeRect(buttonX + 0.5, buttonY + 0.5, buttonWidth - 1, buttonHeight - 1);
            
            // Button text
            this.fontRenderer.drawText(
                ctx,
                buttonText,
                buttonX + buttonWidth / 2,
                buttonY + 10,
                1,
                true
            );
        });
        
        ctx.globalAlpha = 1.0; // Reset alpha
        ctx.restore();
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
        // Freeze placement when race is completed
        if (this.raceCompleted) return;
        
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
    
    updateAIItems(deltaTime) {
        // Periodically give AI racers items and let them use items
        for (let i = 0; i < this.aiRacers.length; i++) {
            const ai = this.aiRacers[i];
            
            // AIs only get items from mystery boxes (removed random item generation)
            
            // Use items intelligently (20% chance per second if they have one)
            if (ai.hasItem && Math.random() < 0.2 * deltaTime) {
                const itemUsed = ai.useItem();
                
                // Handle banana placement for AI
                if (itemUsed === 'bannana') {
                    this.placeBanana(ai);
                }
                
                // Handle green shell throwing for AI
                if (itemUsed === 'green_shell') {
                    this.throwGreenShell(ai);
                }
                
                // Handle red shell throwing for AI
                if (itemUsed === 'red_shell') {
                    this.throwRedShell(ai);
                }
                
                // Handle lightning for AI
                if (itemUsed === 'lightning') {
                    // Trigger lightning that affects player and other AI
                    this.handleAILightning(ai);
                    ai.hasItem = false;
                    ai.currentItem = null;
                }
            }
        }
    }
    
    startIntroCameraSequence() {
        this.introCameraActive = true;
        this.introCameraTimer = 0;
        this.introCameraProgress = 0;
        
        // Detach camera from player
        this.camera.detach();
        
        // Hide Lakitu during intro camera and move it way off-screen
        this.hud.updateElement('lakitu', { visible: false });
        this.lakituSmooth = -100; // Start way off-screen
        this.lakituY = -100;
        this.lakituTargetY = -100; // Keep it off-screen during intro
        
        // Store the player's spawn location - this is what camera will look at throughout
        this.playerSpawnLocation = {
            x: Settings.player.startX,
            y: Settings.player.startY
        };
        
        // Camera starts at waypoint 0 (start/finish line)
        const startWaypoint = this.waypoints[0];
        
        // Position camera AHEAD of waypoint 0 along the track direction
        // Calculate direction from waypoint 0 to waypoint 1 to understand track flow
        const nextWaypoint = this.waypoints[1];
        const trackDirX = nextWaypoint.x - startWaypoint.x;
        const trackDirY = nextWaypoint.y - startWaypoint.y;
        const trackAngle = Math.atan2(trackDirY, trackDirX);
        
        // Position camera ahead on the track (in front of racers)
        const distanceAhead = 50;
        this.introCameraStartPos = {
            x: startWaypoint.x + 30 + Math.cos(trackAngle) * distanceAhead,
            y: startWaypoint.y + Math.sin(trackAngle) * distanceAhead,
            z: 12 // Elevated view
        };
        
        // Camera should face the OPPOSITE direction of the players (head-on view)
        // Players face Settings.player.startAngle, so camera faces opposite
        this.introCameraStartAngle = Settings.player.startAngle + Math.PI; // Add 180 degrees
        
        // Set camera to start position
        this.camera.x = this.introCameraStartPos.x;
        this.camera.y = this.introCameraStartPos.y;
        this.camera.height = this.introCameraStartPos.z;
        this.camera.angle = this.introCameraStartAngle;
    }
    
    updateIntroCameraSequence(deltaTime) {
        this.introCameraTimer += deltaTime;
        this.introCameraProgress = Math.min(this.introCameraTimer / this.introCameraDuration, 1.0);
        
        const t = this.introCameraProgress;
        
        // Smooth easing function
        const easeInOutQuad = (t) => {
            return t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2;
        };
        
        // Phase 1 (0-0.2): Hold at start position
        // Phase 2 (0.2-0.6): Move straight towards player
        // Phase 3 (0.6-1.0): Circle around player to behind position
        
        if (t < 0.2) {
            // Hold at start position
            this.camera.x = this.introCameraStartPos.x;
            this.camera.y = this.introCameraStartPos.y;
            this.camera.height = this.introCameraStartPos.z;
            this.camera.angle = this.introCameraStartAngle;
        } else if (t < 0.6) {
            // Move straight towards player
            const phaseT = (t - 0.2) / 0.4; // Normalize to 0-1
            const easedT = easeInOutQuad(phaseT);
            
            // Calculate position for start of circle (to the side of player)
            const circleStartAngle = this.playerSpawnLocation.x > this.introCameraStartPos.x 
                ? Settings.player.startAngle + Math.PI / 2  // Approach from right
                : Settings.player.startAngle - Math.PI / 2; // Approach from left
            const circleRadius = 30;
            const circleStartX = this.playerSpawnLocation.x + Math.cos(circleStartAngle) * circleRadius;
            const circleStartY = this.playerSpawnLocation.y + Math.sin(circleStartAngle) * circleRadius;
            
            // Interpolate position
            this.camera.x = this.introCameraStartPos.x + (circleStartX - this.introCameraStartPos.x) * easedT;
            this.camera.y = this.introCameraStartPos.y + (circleStartY - this.introCameraStartPos.y) * easedT;
            this.camera.height = this.introCameraStartPos.z;
            
            // Keep looking opposite of player's direction (head-on view maintained)
            this.camera.angle = Settings.player.startAngle + Math.PI;
        } else {
            // Circle around player (sweep 180 degrees from side to behind)
            const phaseT = (t - 0.6) / 0.4; // Normalize to 0-1
            const easedT = easeInOutQuad(phaseT);
            
            // Radius decreases to 0 (player position) at the end
            const startRadius = 35;
            const endRadius = 0; // End exactly at player position
            const circleRadius = startRadius + (endRadius - startRadius) * easedT;
            
            // Determine which side we approach from
            const onRightSide = this.playerSpawnLocation.x > this.introCameraStartPos.x;
            
            // Start 90° to the side, end 180° behind (sweep 90° arc)
            const startAngle = onRightSide ? Math.PI / 2 : -Math.PI / 2;
            const endAngle = Math.PI;
            const sweepAngle = startAngle + (endAngle - startAngle) * easedT;
            
            // Position camera on circle around player (converging to player position)
            const angleInWorld = Settings.player.startAngle + sweepAngle;
            this.camera.x = this.playerSpawnLocation.x + Math.cos(angleInWorld) * circleRadius;
            this.camera.y = this.playerSpawnLocation.y + Math.sin(angleInWorld) * circleRadius;
            
            // Height decreases smoothly to match normal camera height
            const startHeight = this.introCameraStartPos.z;
            const endHeight = Settings.camera.height;
            this.camera.height = startHeight + (endHeight - startHeight) * easedT;
            
            // Rotate camera angle from opposite (180°) to same (0°) as player
            // Start: player angle + 180° (head-on)
            // End: player angle + 0° (behind, same direction)
            const startCameraAngle = Settings.player.startAngle + Math.PI; // Opposite
            const endCameraAngle = Settings.player.startAngle; // Same direction
            this.camera.angle = startCameraAngle + (endCameraAngle - startCameraAngle) * easedT;
        }
        
        // End sequence
        if (this.introCameraProgress >= 1.0) {
            this.introCameraActive = false;
            
            // Reattach camera to player
            this.camera.attach();
            
            // Keep Lakitu hidden and off-screen briefly
            this.lakituSmooth = -100;
            this.lakituY = -100;
            this.lakituTargetY = 10; // Reset to normal target for countdown drop
            
            // Delay showing Lakitu by a brief moment to avoid flash
            setTimeout(() => {
                if (this.hud) {
                    this.hud.updateElement('lakitu', { visible: true });
                }
            }, 200); // 200ms delay for smooth transition
        }
    }


    handleGhostSteal() {
        // Find an AI racer with an item
        const racersWithItems = this.aiRacers.filter(ai => ai.hasItem && ai.currentItem);
        
        if (racersWithItems.length > 0) {
            // Steal from random AI with item
            const victim = racersWithItems[Math.floor(Math.random() * racersWithItems.length)];
            
            // Give stolen item to player
            this.player.hasItem = true;
            this.player.currentItem = victim.currentItem;
            
            // Clear victim's item
            victim.hasItem = false;
            victim.currentItem = null;
            
            // Flash crazy ghost in UI briefly
            this.hud.updateElement('itemBox', { sprite: ITEM_SPRITES.crazyGhost });
            
            // After flash, show stolen item
            setTimeout(() => {
                if (this.player.currentItem) {
                    this.hud.updateElement('itemBox', { sprite: ITEM_SPRITES[this.player.currentItem] });
                }
            }, 500);
        } else {
            // No items to steal, give player a coin instead
            this.player.hasItem = true;
            this.player.currentItem = 'coin';
            
            // Flash crazy ghost in UI briefly
            this.hud.updateElement('itemBox', { sprite: ITEM_SPRITES.crazyGhost });
            
            // After flash, show coin
            setTimeout(() => {
                this.hud.updateElement('itemBox', { sprite: ITEM_SPRITES.coin });
            }, 500);
        }
    }
    
    handleLightning() {
        // Start lightning flash
        this.lightningFlashActive = true;
        this.lightningFlashTimer = 0;
        this.lightningFlashCount = 0;
        
        // Build racer placement list (same logic as updatePlayerPlacement)
        const racers = [
            {
                entity: this.player,
                isPlayer: true,
                lapsCompleted: this.collisionManager.currentLap,
                currentCheckpointIndex: this.player.currentWaypointIndex,
                x: this.player.x,
                y: this.player.y
            },
            ...this.aiRacers.map(ai => ({
                entity: ai,
                isPlayer: false,
                lapsCompleted: ai.currentLap,
                currentCheckpointIndex: ai.currentWaypointIndex,
                x: ai.x,
                y: ai.y
            }))
        ];

        // Calculate distance to next checkpoint
        racers.forEach(racer => {
            const nextCheckpointIndex = (racer.currentCheckpointIndex + 1) % this.waypoints.length;
            const nextCheckpoint = this.waypoints[nextCheckpointIndex];
            const dx = nextCheckpoint.x - racer.x;
            const dy = nextCheckpoint.y - racer.y;
            racer.distanceToNextCheckpoint = Math.sqrt(dx * dx + dy * dy);
        });

        // Sort by placement (same logic as updatePlayerPlacement)
        racers.sort((racerA, racerB) => {
            if (racerA.lapsCompleted !== racerB.lapsCompleted) {
                return racerB.lapsCompleted - racerA.lapsCompleted;
            }
            if (racerA.currentCheckpointIndex !== racerB.currentCheckpointIndex) {
                return racerB.currentCheckpointIndex - racerA.currentCheckpointIndex;
            }
            return racerA.distanceToNextCheckpoint - racerB.distanceToNextCheckpoint;
        });
        
        // Apply lightning effect to all AI racers with placement-based duration
        racers.forEach((racer, index) => {
            if (!racer.isPlayer) {
                const ai = racer.entity;
                const placement = index + 1; // 1-8
                
                // Remove AI's item if they have one
                if (ai.hasItem) {
                    ai.hasItem = false;
                    ai.currentItem = null;
                }
                
                // Trigger lightning hit (spin, small bounce, slowdown)
                ai.triggerLightningHit();
                
                // 8th place = 4 seconds, 1st place = 10 seconds
                // Linear scale: duration = 10 - (placement - 1) * (6/7)
                const duration = 10 - ((placement - 1) * (6 / 7));
                
                ai.isLightningVictim = true;
                ai.lightningTimer = 0;
                ai.lightningDuration = duration;
            }
        });
        
        // Don't hit the player - they're immune
    }
    
    handleAILightning(aiUser) {
        // AI uses lightning - affects player and other AI (not the user)
        this.lightningFlashActive = true;
        this.lightningFlashTimer = 0;
        this.lightningFlashCount = 0;
        
        // Build racer placement list
        const racers = [
            {
                entity: this.player,
                isPlayer: true,
                lapsCompleted: this.collisionManager.currentLap,
                currentCheckpointIndex: this.player.currentWaypointIndex,
                x: this.player.x,
                y: this.player.y
            },
            ...this.aiRacers.map(ai => ({
                entity: ai,
                isPlayer: false,
                lapsCompleted: ai.currentLap,
                currentCheckpointIndex: ai.currentWaypointIndex,
                x: ai.x,
                y: ai.y
            }))
        ];

        // Calculate distance to next checkpoint
        racers.forEach(racer => {
            const nextCheckpointIndex = (racer.currentCheckpointIndex + 1) % this.waypoints.length;
            const nextCheckpoint = this.waypoints[nextCheckpointIndex];
            const dx = nextCheckpoint.x - racer.x;
            const dy = nextCheckpoint.y - racer.y;
            racer.distanceToNextCheckpoint = Math.sqrt(dx * dx + dy * dy);
        });

        // Sort by placement
        racers.sort((racerA, racerB) => {
            if (racerA.lapsCompleted !== racerB.lapsCompleted) {
                return racerB.lapsCompleted - racerA.lapsCompleted;
            }
            if (racerA.currentCheckpointIndex !== racerB.currentCheckpointIndex) {
                return racerB.currentCheckpointIndex - racerA.currentCheckpointIndex;
            }
            return racerA.distanceToNextCheckpoint - racerB.distanceToNextCheckpoint;
        });
        
        // Apply lightning effect to all racers except the AI who used it
        racers.forEach((racer, index) => {
            const entity = racer.entity;
            if (entity === aiUser) return; // Skip the AI who used lightning
            
            const placement = index + 1;
            
            // Remove items
            if (entity.hasItem) {
                entity.hasItem = false;
                entity.currentItem = null;
            }
            
            // Apply lightning hit
            if (racer.isPlayer) {
                this.player.triggerLightningHit();
                this.player.isLightningVictim = true;
                this.player.lightningTimer = 0;
                const duration = 10 - ((placement - 1) * (6 / 7));
                this.player.lightningDuration = duration;
            } else {
                entity.triggerLightningHit();
                entity.isLightningVictim = true;
                entity.lightningTimer = 0;
                const duration = 10 - ((placement - 1) * (6 / 7));
                entity.lightningDuration = duration;
            }
        });
    }
    
    updateLightningFlash(deltaTime) {
        this.lightningFlashTimer += deltaTime;
        
        // Flash 3 times (in and out, in and out, in and out)
        if (this.lightningFlashTimer >= this.lightningFlashDuration) {
            this.lightningFlashTimer = 0;
            this.lightningFlashCount++;
            
            // Stop after 6 flashes (3 full cycles of in/out)
            if (this.lightningFlashCount >= 6) {
                this.lightningFlashActive = false;
                this.lightningFlashCount = 0; // Reset counter for next lightning
            }
        }
    }
    
    placeBanana(racer) {
        // Spawn banana at player's current position (accounting for their speed)
        // This makes it appear in the viewport and naturally fall behind as the player moves
        const banana = new Banana(racer.x, racer.y, 0, racer);
        this.bananas.push(banana);
        
        // Start fake banana drop animation (only for player)
        if (racer === this.player) {
            this.fakeBananaDrop = {
                y: 85, // Start lower on the kart (closer to bottom of kart)
                speed: Math.max(100, racer.speed * 50) // Drop speed MUCH faster (minimum 100, or based on speed)
            };
        }
        
        // Clear banana placement flag and item
        racer.placeBanana = false;
        racer.hasItem = false;
        racer.currentItem = null;
    }
    
    checkBananaCollisions() {
        const activeBananas = this.bananas.filter(b => b.active);
        
        // Check banana vs shell collisions (they destroy each other)
        const activeShells = this.greenShells.filter(s => s.active && !s.isHit);
        for (const banana of activeBananas) {
            if (!banana.active) continue;
            
            for (const shell of activeShells) {
                const dx = shell.x - banana.x;
                const dy = shell.y - banana.y;
                const distance = Math.sqrt(dx * dx + dy * dy);
                
                if (distance < 15) { // Banana/shell collision
                    banana.hit();
                    shell.hit();
                    break;
                }
            }
        }
        
        // Check player collision
        for (const banana of activeBananas) {
            if (!banana.active) continue;
            
            // Skip if banana still has immunity and player is the placer
            if (banana.immunityTimer > 0 && banana.placedBy === this.player) {
                continue;
            }
            
            // Skip if player has invincibility (star power)
            if (this.player.starPowerActive) {
                continue;
            }
            
            // Skip if player is jumping with feather (dodge items)
            if (this.player.featherJumpActive) {
                continue;
            }
            
            // Use offset collision (20 units ahead of kart position)
            const playerCollisionX = this.player.x + Math.sin(this.player.angle) * 20;
            const playerCollisionY = this.player.y + Math.cos(this.player.angle) * 20;
            
            const dx = playerCollisionX - banana.x;
            const dy = playerCollisionY - banana.y;
            const distance = Math.sqrt(dx * dx + dy * dy);
            
            if (distance < 8) { // Collision threshold - much smaller hitbox
                // Trigger hit state (spin and bounce like lightning, but no shrinking)
                this.player.hit();
                banana.hit();
                break; // Only hit one banana per frame
            }
        }
        
        // Check AI collisions
        for (const ai of this.aiRacers) {
            for (const banana of activeBananas) {
                if (!banana.active) continue;
                
                // Skip if banana still has immunity and AI is the placer
                if (banana.immunityTimer > 0 && banana.placedBy === ai) {
                    continue;
                }
                
                // Use offset collision for AI
                const aiCollisionX = ai.x + Math.sin(ai.angle) * 20;
                const aiCollisionY = ai.y + Math.cos(ai.angle) * 20;
                
                const dx = aiCollisionX - banana.x;
                const dy = aiCollisionY - banana.y;
                const distance = Math.sqrt(dx * dx + dy * dy);
                
                if (distance < 8) { // Much smaller hitbox
                    // Trigger hit state for AI
                    ai.triggerLightningHit();
                    banana.hit();
                    break; // Only hit one banana per AI per frame
                }
            }
        }
        
        // Remove inactive bananas
        this.bananas = this.bananas.filter(b => b.active);
    }
    
    throwGreenShell(racer) {
        // Calculate position in front of the racer - further ahead to avoid self-hit
        const distance = 50; // Throw shell 50 units ahead (increased from 30)
        const shellX = racer.x + Math.sin(racer.angle) * distance;
        const shellY = racer.y + Math.cos(racer.angle) * distance;
        
        // Create new green shell with placedBy reference and slower speed
        const shell = new GreenShell(shellX, shellY, racer.angle, 1.5, racer); // Speed reduced from 4.0 to 1.5
        this.greenShells.push(shell);
        
        
        // Clear shell throwing flag and item
        racer.throwGreenShell = false;
        racer.hasItem = false;
        racer.currentItem = null;
    }
    
    throwRedShell(racer) {
        // Find the racer ahead of the thrower
        const targetRacer = this.findRacerAhead(racer);
        
        if (!targetRacer) {
            // Clear flag even if no target
            racer.throwRedShell = false;
            racer.hasItem = false;
            racer.currentItem = null;
            return;
        }
        
        // Calculate position in front of the racer
        const distance = 50;
        const shellX = racer.x + Math.sin(racer.angle) * distance;
        const shellY = racer.y + Math.cos(racer.angle) * distance;
        
        // Create new red shell with target
        const shell = new RedShell(shellX, shellY, racer.angle, this.waypoints, this.maskData, targetRacer, racer);
        this.redShells.push(shell);
        
        
        // Clear shell throwing flag and item
        racer.throwRedShell = false;
        racer.hasItem = false;
        racer.currentItem = null;
    }
    
    findRacerAhead(racer) {
        // Get all racers (player + AI)
        const allRacers = [this.player, ...this.aiRacers];
        
        // Find racer's current position
        const racerPosition = allRacers.indexOf(racer);
        
        // Sort racers by race progress (laps + checkpoint progress)
        const sortedRacers = allRacers.slice().sort((a, b) => {
            if (b.currentLap !== a.currentLap) {
                return b.currentLap - a.currentLap;
            }
            return b.currentCheckpoint - a.currentCheckpoint;
        });
        
        // Find the racer ahead in the sorted list
        const racerIndex = sortedRacers.indexOf(racer);
        
        // Get the racer directly ahead (lower index = better position)
        if (racerIndex > 0) {
            return sortedRacers[racerIndex - 1];
        }
        
        // If thrower is in first place, target the racer in last place
        return sortedRacers[sortedRacers.length - 1];
    }
    
    checkGreenShellCollisions() {
        const activeShells = this.greenShells.filter(s => s.active && !s.isHit);
        
        // Check player collision
        for (const shell of activeShells) {
            // Skip if shell still has immunity and player is the thrower
            if (shell.immunityTimer > 0 && shell.placedBy === this.player) {
                continue;
            }
            
            // Skip if player has invincibility (star power)
            if (this.player.starPowerActive) {
                continue;
            }
            
            // Skip if player is jumping with feather (dodge items)
            if (this.player.featherJumpActive) {
                continue;
            }
            
            // Use offset collision (20 units ahead of kart position)
            const playerCollisionX = this.player.x + Math.sin(this.player.angle) * 20;
            const playerCollisionY = this.player.y + Math.cos(this.player.angle) * 20;
            
            const dx = playerCollisionX - shell.x;
            const dy = playerCollisionY - shell.y;
            const distance = Math.sqrt(dx * dx + dy * dy);
            
            if (distance < 8) { // Much smaller hitbox (same as banana)
                // Trigger hit state
                this.player.hit();
                shell.hit();
                break; // Only hit one shell per frame
            }
        }
        
        // Check AI collisions
        for (const ai of this.aiRacers) {
            for (const shell of activeShells) {
                if (!shell.active || shell.isHit) continue;
                
                // Skip if shell still has immunity and AI is the thrower
                if (shell.immunityTimer > 0 && shell.placedBy === ai) {
                    continue;
                }
                
                // Use offset collision for AI
                const aiCollisionX = ai.x + Math.sin(ai.angle) * 20;
                const aiCollisionY = ai.y + Math.cos(ai.angle) * 20;
                
                const dx = aiCollisionX - shell.x;
                const dy = aiCollisionY - shell.y;
                const distance = Math.sqrt(dx * dx + dy * dy);
                
                if (distance < 8) { // Much smaller hitbox (same as banana)
                    // Trigger hit state for AI
                    ai.triggerLightningHit();
                    shell.hit();
                    break; // Only hit one shell per AI per frame
                }
            }
        }
    }
    
    checkRedShellCollisions() {
        const activeShells = this.redShells.filter(s => s.active && !s.isBroken);
        
        // Check banana collisions - red shells break on bananas
        for (const shell of activeShells) {
            if (!shell.active || shell.isBroken) continue;
            
            for (const banana of this.bananas) {
                if (!banana.active) continue;
                
                const dx = shell.x - banana.x;
                const dy = shell.y - banana.y;
                const distance = Math.sqrt(dx * dx + dy * dy);
                
                if (distance < 15) {
                    shell.break();
                    banana.hit();
                    break;
                }
            }
        }
        
        // Check green shell collisions - red shells break on green shells
        const activeGreenShells = this.greenShells.filter(s => s.active && !s.isHit);
        for (const redShell of activeShells) {
            if (!redShell.active || redShell.isBroken) continue;
            
            for (const greenShell of activeGreenShells) {
                if (!greenShell.active || greenShell.isHit) continue;
                
                const dx = redShell.x - greenShell.x;
                const dy = redShell.y - greenShell.y;
                const distance = Math.sqrt(dx * dx + dy * dy);
                
                if (distance < 15) {
                    redShell.break();
                    greenShell.hit();
                    console.log('Red shell hit green shell - both destroyed!');
                    break;
                }
            }
        }
        
        // Check other red shell collisions - red shells break on each other
        for (let i = 0; i < activeShells.length; i++) {
            const shell1 = activeShells[i];
            if (!shell1.active || shell1.isBroken) continue;
            
            for (let j = i + 1; j < activeShells.length; j++) {
                const shell2 = activeShells[j];
                if (!shell2.active || shell2.isBroken) continue;
                
                const dx = shell1.x - shell2.x;
                const dy = shell1.y - shell2.y;
                const distance = Math.sqrt(dx * dx + dy * dy);
                
                if (distance < 15) {
                    shell1.break();
                    shell2.break();
                    break;
                }
            }
        }
        
        // Check player collision
        for (const shell of activeShells) {
            if (!shell.active || shell.isBroken) continue;
            
            // Skip if shell still has immunity and player is the thrower
            if (shell.immunityTimer > 0 && shell.placedBy === this.player) {
                continue;
            }
            
            // Skip if player has invincibility (star power)
            if (this.player.starPowerActive) {
                shell.break(); // Red shell breaks when hitting invincible player
                continue;
            }
            
            // Skip if player is jumping with feather (dodge items)
            if (this.player.featherJumpActive) {
                shell.break(); // Red shell breaks when player dodges
                continue;
            }
            
            // Red shells check actual player position (no forward offset)
            // This ensures the shell is visible in viewport when it hits
            const dx = this.player.x - shell.x;
            const dy = this.player.y - shell.y;
            const distance = Math.sqrt(dx * dx + dy * dy);
            
            if (distance < 8) { // Smaller hitbox for more precise collision
                // Trigger hit state
                this.player.hit();
                shell.break();
                break; // Only hit one shell per frame
            }
        }
        
        // Check AI collisions
        for (const ai of this.aiRacers) {
            for (const shell of activeShells) {
                if (!shell.active || shell.isBroken) continue;
                
                // Skip if shell still has immunity and AI is the thrower
                if (shell.immunityTimer > 0 && shell.placedBy === ai) {
                    continue;
                }
                
                // Skip if AI has invincibility (star power)
                if (ai.starPowerActive) {
                    shell.break(); // Red shell breaks on star power
                    continue;
                }
                
                // Skip if AI is jumping with feather
                if (ai.featherJumpActive) {
                    shell.break(); // Red shell breaks on feather jump
                    continue;
                }
                
                // Use offset collision for AI
                const aiCollisionX = ai.x + Math.sin(ai.angle) * 20;
                const aiCollisionY = ai.y + Math.cos(ai.angle) * 20;
                
                const dx = aiCollisionX - shell.x;
                const dy = aiCollisionY - shell.y;
                const distance = Math.sqrt(dx * dx + dy * dy);
                
                if (distance < 8) { // Much smaller hitbox (same as banana)
                    // Trigger hit state for AI
                    ai.triggerHit();
                    shell.break();
                    break; // Only hit one shell per AI per frame
                }
            }
        }
    }

    emitPlayerParticles(deltaTime) {
        if (!this.player || !this.maskData) {
            return;
        }

        // Check surface type for off-road particles
        const playerX = Math.floor(this.player.x);
        const playerY = Math.floor(this.player.y);
        
        let isOffRoad = false;
        
        if (playerX >= 0 && playerX < this.maskData.width && 
            playerY >= 0 && playerY < this.maskData.height) {
            
            const index = (playerY * this.maskData.width + playerX) * 4;
            const r = this.maskData.data[index];
            const g = this.maskData.data[index + 1];
            const b = this.maskData.data[index + 2];
            
            // Off-road detection: ONLY dark colors are off-road
            // White/light colors = road (r > 200, g > 200, b > 200)
            // Dark colors (r < 100, g < 100, b < 100) = off-road
            const isDarkOffRoad = (r < 100 && g < 100 && b < 100);
            
            if (isDarkOffRoad) {
                isOffRoad = true;
            }
        }

        // Particle behavior based on state:
        // 1. Normal driving (on road, not drifting) - NO PARTICLES
        // 2. Drifting (on road only) - ONE spark per wheel that persists
        // 3. Off-road - ONE dirt particle per wheel that persists

        // Disable particles if race is completed
        if (!this.raceCompleted) {
            if (this.player.isDrifting && this.player.speed > 0.5 && !isOffRoad) { // Only drift sparks on road
                // Update drift sparks (creates/updates persistent spark particles)
                this.particleSystem.updateDriftSparks(
                    this.player.x,
                    this.player.y,
                    this.player.angle,
                    true,
                    this.player.driftDirection // Pass drift direction for wheel adjustment
                );
            } else {
                // Not drifting or off-road - remove drift sparks
                this.particleSystem.updateDriftSparks(
                    this.player.x,
                    this.player.y,
                    this.player.angle,
                    false,
                    0 // No drift direction when not drifting
                );
            }

            // Off-road dirt particles - one per wheel that persists
            this.particleSystem.updateDirtParticles(
                this.player.x,
                this.player.y,
                this.player.angle,
                isOffRoad,
                this.player.isJumping,
                this.player.jumpHeight
            );
        } else {
            // Race completed - clear all particles
            this.particleSystem.updateDriftSparks(
                this.player.x,
                this.player.y,
                this.player.angle,
                false,
                0
            );
            this.particleSystem.updateDirtParticles(
                this.player.x,
                this.player.y,
                this.player.angle,
                false,
                this.player.isJumping,
                this.player.jumpHeight
            );
        }
    }
}

