
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
            type: 'mysteryBox',
            x: 352,
            y: 79,
            scale: 0.3,
            height: -3,

            frameCount: 37,
            frameSpeed: 0.1
        },
        {
            type: 'mysteryBox',
            x: 336,
            y: 63,
            scale: 0.3,
            height: -3,

            frameCount: 37,
            frameSpeed: 0.1
        },
        {
            type: 'mysteryBox',
            x: 336,
            y: 95,
            scale: 0.3,
            height: -3,

            frameCount: 37,
            frameSpeed: 0.1
        },
        {
            type: 'mysteryBox',
            x: 320,
            y: 79,
            scale: 0.3,
            height: -3,

            frameCount: 37,
            frameSpeed: 0.1
        },
        {
            type: 'mysteryBox',
            x: 296,
            y: 103,
            scale: 0.3,
            height: -3,

            frameCount: 37,
            frameSpeed: 0.1
        },
        {
            type: 'mysteryBox',
            x: 296,
            y: 87,
            scale: 0.3,
            height: -3,

            frameCount: 37,
            frameSpeed: 0.1
        },
        {
            type: 'mysteryBox',
            x: 296,
            y: 71,
            scale: 0.3,
            height: -3,
        
            frameCount: 37,
            frameSpeed: 0.1
        },
        {
            type: 'mysteryBox',
            x: 296,
            y: 55,
            scale: 0.3,
            height: -3,
        
            frameCount: 37,
            frameSpeed: 0.1
        }
    ]
};