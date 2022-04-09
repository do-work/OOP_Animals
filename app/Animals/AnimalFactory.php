<?php

namespace App\Animals;

use App\Exceptions\MissingAnimalException;

class AnimalFactory
{
    /**
     * @throws MissingAnimalException
     */
    public static function getAnimal(string $animal, string $name): AnimalInterface
    {
        switch (strtolower($animal)) {
            case 'cat':
                return new Cat($name);
            case 'dog':
                return new Dog($name);
            case 'cow':
                return new Cow($name);
            case 'unicorn':
                return new Unicorn($name);
            default:
                throw new MissingAnimalException("Could not determine animal type.");
        }
    }
