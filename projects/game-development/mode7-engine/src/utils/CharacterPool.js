import { MARIO_SPRITES } from '../data/mario_sprites.js';
import { LUIGI_SPRITES } from '../data/luigi_sprites.js';
import { BOWSER_SPRITES } from '../data/bowser_sprites.js';
import { KOOPA_SPRITES } from '../data/koopa_sprites.js';
import { DONKEYKONG_SPRITES } from '../data/donkeykong_sprites.js';
import { PEACH_SPRITES } from '../data/peach_sprites.js';
import { TOAD_SPRITES } from '../data/toad_sprites.js';
import { YOSHI_SPRITES } from '../data/yoshi_sprites.js';


export class CharacterPool {
    constructor() {
        // Define all available characters with their sprite data and image paths
        this.availableCharacters = [
            {
                id: 'mario',
                name: 'Mario',
                sprites: MARIO_SPRITES,
                imagePath: 'assets/sprites/characters/mario.png'
            },
            {
                id: 'luigi',
                name: 'Luigi',
                sprites: LUIGI_SPRITES,
                imagePath: 'assets/sprites/characters/luigi.png'
            },
            {
                id: 'bowser',
                name: 'Bowser',
                sprites: BOWSER_SPRITES,
                imagePath: 'assets/sprites/characters/bowser.png'
            },
            {
                id: 'koopa',
                name: 'Koopa',
                sprites: KOOPA_SPRITES,
                imagePath: 'assets/sprites/characters/koopa.png'
            },
            {
                id: 'donkey_kong',
                name: 'Donkey Kong',
                sprites: DONKEYKONG_SPRITES,
                imagePath: 'assets/sprites/characters/donkeykong.png'
            },
            {
                id: 'peach',
                name: 'Peach',
                sprites: PEACH_SPRITES,
                imagePath: 'assets/sprites/characters/peach.png'
            },
            {
                id: 'toad',
                name: 'Toad',
                sprites: TOAD_SPRITES,
                imagePath: 'assets/sprites/characters/toad.png'
            },
            {
                id: 'yoshi',
                name: 'Yoshi',
                sprites: YOSHI_SPRITES,
                imagePath: 'assets/sprites/characters/yoshi.png'
            }
        ];

        this.reset();
    }

    /**
     * Reset the pool to make all characters available again
     * @param {string} playerCharacterId - Optional: Reserve a character for the player
     */
    reset(playerCharacterId = null) {
        this.usedCharacters = new Set();
        this.remainingCharacters = [...this.availableCharacters];
        this.playerCharacterId = playerCharacterId;
        
        // If player has selected a character, remove it from available pool
        if (playerCharacterId) {
            this.reserveCharacter(playerCharacterId);
        }
    }

    /**
     * Reserve a character (e.g., for the player) so NPCs don't use it
     * @param {string} characterId - The character ID to reserve
     * @returns {boolean} True if successfully reserved, false if not found
     */
    reserveCharacter(characterId) {
        const index = this.remainingCharacters.findIndex(char => char.id === characterId);
        if (index !== -1) {
            this.remainingCharacters.splice(index, 1);
            this.usedCharacters.add(characterId);
            return true;
        }
        return false;
    }

    /**
     * Get a random character from the pool (without removing it)
     * @returns {Object} Character data with id, name, sprites, and imagePath
     */
    getRandomCharacter() {
        if (this.remainingCharacters.length === 0) {
            // If pool is empty, reset it
            this.reset();
        }

        const randomIndex = Math.floor(Math.random() * this.remainingCharacters.length);
        return this.remainingCharacters[randomIndex];
    }

    /**
     * Get a random unique character and remove it from the pool
     * This ensures no duplicate characters
     * @returns {Object} Character data with id, name, sprites, and imagePath
     */
    getUniqueRandomCharacter() {
        if (this.remainingCharacters.length === 0) {
            console.warn('Character pool exhausted! Resetting pool.');
            this.reset();
        }

        const randomIndex = Math.floor(Math.random() * this.remainingCharacters.length);
        const character = this.remainingCharacters[randomIndex];
        
        // Remove from remaining and mark as used
        this.remainingCharacters.splice(randomIndex, 1);
        this.usedCharacters.add(character.id);

        return character;
    }

    /**
     * Get a specific character by ID
     * @param {string} characterId - The character ID (e.g., 'mario', 'luigi')
     * @returns {Object|null} Character data or null if not found
     */
    getCharacterById(characterId) {
        return this.availableCharacters.find(char => char.id === characterId) || null;
    }

    /**
     * Get all available characters (not yet used)
     * @returns {Array} Array of available character data
     */
    getAvailableCharacters() {
        return [...this.remainingCharacters];
    }

    /**
     * Check if a character is available
     * @param {string} characterId - The character ID to check
     * @returns {boolean} True if character is available
     */
    isCharacterAvailable(characterId) {
        return !this.usedCharacters.has(characterId);
    }

    /**
     * Get the total number of characters in the pool
     * @returns {number} Total number of characters
     */
    getTotalCharacterCount() {
        return this.availableCharacters.length;
    }

    /**
     * Get the number of remaining available characters
     * @returns {number} Number of remaining characters
     */
    getRemainingCharacterCount() {
        return this.remainingCharacters.length;
    }
}
