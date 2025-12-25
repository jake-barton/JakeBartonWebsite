
const MAPS = {
    marioCircuit: {
        name: 'Mario Circuit',
        paths: {
            track: 'spritesheets/MarioCircuit/track.png',
            background: 'spritesheets/MarioCircuit/background.png',
            trees: 'spritesheets/MarioCircuit/background_trees.png',
            tiles: 'spritesheets/MarioCircuit/mariocircuit_tiles.png',
            mask: 'spritesheets/MarioCircuit/track copy.png'
        },
    }
};

export class MapLoader {
    constructor() {

    }

    async load(mapName) {
        const mapConfig = MAPS[mapName];
        if (!mapConfig) {
            throw new Error(`Map "${mapName}" not found.`);
        }

        

        const [trackImage, backgroundImage, treesImage, tilesImage, maskImage] = await Promise.all([
            this.loadImage(mapConfig.paths.track),
            this.loadImage(mapConfig.paths.background),
            this.loadImage(mapConfig.paths.trees),
            this.loadImage(mapConfig.paths.tiles),
            this.loadImage(mapConfig.paths.mask)
        ]);
        
        

        const trackWidth = trackImage.width;
        const trackHeight = trackImage.height;

        const maskCanvas = document.createElement('canvas');
        maskCanvas.width = trackWidth;
        maskCanvas.height = trackHeight;
        const maskCtx = maskCanvas.getContext('2d');
        maskCtx.drawImage(maskImage, 0, 0);
        const maskData = maskCtx.getImageData(0, 0, maskCanvas.width, maskCanvas.height);

        const waypoints = this.extractWaypoints(maskData);

        const trackCanvas = document.createElement('canvas');
        trackCanvas.width = trackWidth;
        trackCanvas.height = trackHeight;
        const trackCtx = trackCanvas.getContext('2d');
        trackCtx.drawImage(trackImage, 0, 0);
        const trackData = trackCtx.getImageData(0, 0, trackWidth, trackHeight);

        const tileCanvas = document.createElement('canvas');
        tileCanvas.width = 8;
        tileCanvas.height = 8;
        const tileCtx = tileCanvas.getContext('2d');
        tileCtx.drawImage(tilesImage, 0, 0, 8, 8, 0, 0, 8, 8);
        const grassTileData = tileCtx.getImageData(0, 0, 8, 8);

        return {
            name: mapConfig.name,
            trackData: trackData,
            trackWidth: trackWidth,
            trackHeight: trackHeight,
            grassTileData: grassTileData,
            backgroundImage: backgroundImage,
            treesImage: treesImage,
            maskData: maskData,
            waypoints: waypoints
        };

    }

    loadImage(src) {
        return new Promise((resolve, reject) => {
            const img = new Image();

            img.onload = function() {
                resolve(img);
            }

            img.onerror = function() {
                reject(new Error(`Failed to load image: ${src}`));
            }

            img.src = src;
        });
    }

    extractWaypoints(maskData) {
        const waypoints = [];
        const width = maskData.width;
        const height = maskData.height;
        let startWaypoint = null;

        for (let y = 0; y < height; y++) {
            for (let x = 0; x < width; x++) {
                const index = (y * width + x) * 4;
                const r = maskData.data[index];
                const g = maskData.data[index + 1];
                const b = maskData.data[index + 2];

                
                if (r > 200 && g > 100 && g < 150 && b < 50) {
                    if (!startWaypoint) {
                        startWaypoint = { x, y };
                        
                    }
                }
                
                else if (r > 200 && g < 50 && b > 200) {
                    waypoints.push({ x, y});
                }
            }
        }

        
        
        if (waypoints.length === 0) {
            return [];
        }

        
        const sortedWaypoints = this.sortWaypointsInOrder(waypoints, startWaypoint);
        
        
        // Increase checkpoint density for better placement accuracy
        // More checkpoints = more granular position tracking
        if (sortedWaypoints.length > 100) {
            const step = Math.floor(sortedWaypoints.length / 60);
            const downsampledWaypoints = [];
            for (let i = 0; i < sortedWaypoints.length; i += step) {
                downsampledWaypoints.push(sortedWaypoints[i]);
            }

            
            return downsampledWaypoints;
        }

        return sortedWaypoints;
    }

    sortWaypointsInOrder(waypoints, startWaypoint) {
        if (waypoints.length === 0) return [];

        const sorted = [];
        const remaining = [...waypoints];
        
        
        let current;
        if (startWaypoint) {
            let closestIndex = 0;
            let closestDist = Infinity;
            
            for (let i = 0; i < remaining.length; i++) {
                const dx = remaining[i].x - startWaypoint.x;
                const dy = remaining[i].y - startWaypoint.y;
                const dist = dx * dx + dy * dy;
                
                if (dist < closestDist) {
                    closestDist = dist;
                    closestIndex = i;
                }
            }
            
            current = remaining.splice(closestIndex, 1)[0];
            
        } else {
            
            current = remaining.shift();
        }
        
        sorted.push(current);

        
        while (remaining.length > 0) {
            let nearestIndex = 0;
            let nearestDist = Infinity;

            
            for (let i = 0; i < remaining.length; i++) {
                const dx = remaining[i].x - current.x;
                const dy = remaining[i].y - current.y;
                const dist = dx * dx + dy * dy;

                if (dist < nearestDist) {
                    nearestDist = dist;
                    nearestIndex = i;
                }
            }

            
            current = remaining.splice(nearestIndex, 1)[0];
            sorted.push(current);
        }

        return sorted;
    }
}

