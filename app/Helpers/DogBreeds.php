<?php

namespace App\Helpers;

class DogBreeds
{
    /**
     * Complete list of recognized dog breeds
     * Source: AKC (American Kennel Club) standard breeds
     */
    public static function getAll(): array
    {
        return [
            'Shih Tzu',
            'Pomeranian',
            'Labrador Retriever',
            'Golden Retriever',
            'Siberian Husky',
            'Beagle',
            'Pug',
            'Chihuahua',
            'French Bulldog',
            'American Bulldog',
            'German Shepherd',
            'Belgian Malinois',
            'American Bully',
            'Pocket Bully',
            'Doberman Pinscher',
            'Rottweiler',
            'Border Collie',
            'Cocker Spaniel',
            'Chow Chow',
            'Dalmatian',
        ];
    }

    /**
     * Check if a breed is a valid dog breed
     */
    public static function isValid(string $breed): bool
    {
        $breed = trim($breed);
        $validBreeds = self::getAll();
        
        // Check for exact match (case-insensitive)
        foreach ($validBreeds as $validBreed) {
            if (strtolower($validBreed) === strtolower($breed)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get similar breeds (for suggestions)
     */
    public static function getSimilar(string $breed, int $limit = 5): array
    {
        $breed = strtolower(trim($breed));
        $validBreeds = self::getAll();
        $similar = [];

        foreach ($validBreeds as $validBreed) {
            if (stripos($validBreed, $breed) !== false) {
                $similar[] = $validBreed;
            }
        }

        return array_slice($similar, 0, $limit);
    }
}
