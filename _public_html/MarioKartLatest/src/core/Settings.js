
export const Settings = {
    canvas: {
        width: 255,
        height: 224
    },

    player: {
        startX: 936,
        startY: 645,
        startAngle: 3.14
    },

    aiRacers: [
        { x: 904, y: 450, angle: 3.14, character: 'luigi' },
        { x: 936, y: 475, angle: 3.14, character: 'bowser' },
        { x: 904, y: 500, angle: 3.14, character: 'peach' },
        { x: 904, y: 595, angle: 3.14, character: 'dk' },
        { x: 936, y: 525, angle: 3.14, character: 'koopa' },
        { x: 904, y: 545, angle: 3.14, character: 'toad' },
        { x: 936, y: 570, angle: 3.14, character: 'yoshi' }
    ],

    ai: {
        difficulty: 0.5,
        // Individual behavior parameters (adjusted by difficulty)
        baseSpeed: 0.82,              // Multiplier for max speed (0.7-0.95)
        steerAccuracy: 0.80,          // Steering precision (0.1-0.95)
        mistakeFrequency: 0.08,       // How often they make errors (0.4-0.03)
        reactionTime: 0.15,           // Delay in responding (0.8-0.05)
        turnSharpness: 0.75,          // Turn angle multiplier (0.6-0.9)
        lateralWander: 1,             // Side-to-side movement (20-1)
        racingLineVariation: 5,       // Deviation from ideal line (10-3)
        
        // Randomization per AI racer (adds variety)
        speedVariation: 0.1,          // ±10% speed variation per racer
        accuracyVariation: 0.15,      // ±15% accuracy variation per racer
        
        // Collision avoidance
        avoidanceDistance: 25,        // How far to detect other racers
        avoidanceStrength: 0.4,       // How strongly to steer away
        minimumSeparation: 8          // Minimum distance between racers
    },

    camera: {
        height: 15,
        pitch: -30,
        pitchSpeed: 2,
        heightSpeed: 2,
        rotationEasing: 0.15
    },

    movement: {
        maxSpeed: 1.4,
        maxReverseSpeed: 1,
        acceleration: 0.01,
        deceleration: 0.001,
        friction: 0.99,

        baseTurnSpeed: 0.0285,
        turnAcceleration: 0.0005,
        minSpeedToTurn: 0.1,
        turnDrag: 0.99,
        turnSpeedEasing: 0.9,

        driftThreshold: 0.3,
        driftTurnBoost: 0.7,
        driftDrag: 0.998,
        driftSlide: 0.035,

        offTrackDrag: 0.985,
        offTrackMinSpeedToTurn: 0.05
    },

    rendering: {
        skyColor: 'lightyellow',
        horizonRatio: 0.5,
        grassColor: { r: 34, g: 139, b: 34 },

        backgroundSpeed: 0.05,
        treeSpeed: 0.1,

        backgroundEasing: 0.15,
        treesEasing: 0.08,

        turnAnimationEaseSpeed: 0.15
    },

    sprite: {
        verticalOffset: -30,

        scale: 1.0
    },

    obstacles: [
        {
            type: 'mysteryBox',
            x: 376,
            y: 104,
            scale: 0.3,
            height: -3,

            frameCount: 37,
            frameSpeed: 0.1
        },
        {
            type: 'mysteryBox',
            x: 376,
            y: 87,
            scale: 0.3,
            height: -3,

            frameCount: 37,
            frameSpeed: 0.1
        },
        {
            type: 'mysteryBox',
            x: 376,
            y: 71,
            scale: 0.3,
            height: -3,

            frameCount: 37,
            frameSpeed: 0.1
        },
        {
            type: 'mysteryBox',
            x: 376,
            y: 55,
            scale: 0.3,
            height: -3,

            frameCount: 37,
            frameSpeed: 0.1
        },
        {
            type: 'coin',
            x: 859,
            y: 347,
            scale: 0.5,
            height: -2,

            frameCount: 3,
            frameSpeed: 0.2
        },
        {
            type: 'coin',
            x: 875,
            y: 331,
            scale: 0.5,
            height: -2,
            frameCount: 3,
            frameSpeed: 0.2
        },
        {
            type: 'coin',
            x: 891,
            y: 315,
            scale: 0.5,
            height: -2,
            frameCount: 3,
            frameSpeed: 0.2
        }
    ]
};