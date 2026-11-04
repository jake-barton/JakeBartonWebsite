export const ITEM_SPRITES = {
    blank: {x: 27, y: 0, width: 26, height: 18},
    star: {x: 0, y: 19, width: 26, height: 18},
    bannana: {x: 27, y: 19, width: 26, height: 18},
    green_shell: {x: 54, y: 19, width: 26, height: 18},
    red_shell: {x: 0, y: 38, width: 26, height: 18},
    ghost: {x: 27, y: 38, width: 26, height: 18},
    crazyGhost: {x: 54, y: 38, width: 26, height: 18},
    coin: {x: 0, y: 57, width: 26, height: 18},
    lightning: {x: 27, y: 57, width: 26, height: 18},
    mushroom: {x: 54, y: 57, width: 26, height: 18},
    feather: {x: 27, y: 76, width: 26, height: 18},

    green_shell_world: { // Constantly cycling animation
        // Color changing shell:
        // Green 008800 -> Red a00000
        // Green 40e040 -> f84848
        lod0: [
            { x: 7, y: 6, width: 16, height: 16 }, // Straight (facing camera) 0°
            { x: 26, y: 6, width: 16, height: 16 }, // Turned left slightly ~45 left°
            { x: 45, y: 6, width: 16, height: 16 }, // Turned right ~45 right°
        ],
        lod1: [
            { x: 67, y: 7, width: 14, height: 14 }, // Straight (facing camera) 0°
            { x: 84, y: 7, width: 14, height: 15 }, // Turned left slightly ~45 left°
            { x: 101, y: 7, width: 14, height: 15 }, // Turned right ~45 right°
        ],
        lod2: [
            { x: 121, y: 9, width: 13, height: 12 }, // Straight (facing camera) 0°
            { x: 137, y: 9, width: 13, height: 13 }, // Turned left slightly ~45 left°
            { x: 153, y: 9, width: 13, height: 13 }, // Turned right ~45 right°
        ],
        lod3: [
            { x: 172, y: 11, width: 11, height: 10 }, // Straight (facing camera) 0°
            { x: 186, y: 10, width: 11, height: 12 }, // Turned left slightly ~45 left°
            { x: 200, y: 10, width: 11, height: 12 }, // Turned right ~45 right°
        ],
        lod4: [
            { x: 217, y: 14, width: 8, height: 8 }, // Straight (facing camera) 0°
            { x: 228, y: 14, width: 8, height: 8 }, // Turned left slightly ~45 left°
            { x: 239, y: 14, width: 8, height: 8 }, // Turned right ~45 right°
        ],
        lod5: [
            { x: 253, y: 16, width: 6, height: 6 }, // Straight (facing camera) 0°
            { x: 262, y: 16, width: 7, height: 6 }, // Turned left slightly ~45 left°
            { x: 272, y: 16, width: 7, height: 6 }, // Turned right ~45 right°
        ],
        lod6: [
            { x: 285, y: 18, width: 4, height: 4 }, // Straight (facing camera) 0°
            { x: 292, y: 18, width: 6, height: 4 }, // Turned right ~90°
        ],
        lod7: [
            { x: 304, y: 19, width: 2, height: 3 }, // Straight (facing camera) 0°
            { x: 309, y: 19, width: 2, height: 3 }, // Turned left slightly ~45 left°
            { x: 314, y: 19, width: 2, height: 3 }, // Turned right ~45 right°
        ]
    },

    bannana_world: {
        lod0: [
            { x: 7, y: 28, width: 16, height: 15 }, 
        ],
        lod1: [
            { x: 26, y: 30, width: 15, height: 13 }, 
        ],
        lod2: [
            { x: 44, y: 32, width: 13, height: 11 },
        ],
        lod3: [
            { x: 60, y: 34, width: 10, height: 9 },
        ],
        lod4: [
            { x: 73, y: 35, width: 8, height: 8 },
        ],
        lod5: [
            { x: 84, y: 36, width: 8, height: 7 },
        ],
        lod6: [
            { x: 95, y: 37, width: 6, height: 6 },
        ],
        lod7: [
            { x: 104, y: 39, width: 6, height: 4 },
        ]
    },

    coin_world: [
        { x: 9, y: 49, width: 10, height: 16 }, // Front
        { x: 22, y: 49, width: 8, height: 16 }, // 45° turn
        { x: 33, y: 49, width: 4, height: 16 }, // Side
    ]     
}