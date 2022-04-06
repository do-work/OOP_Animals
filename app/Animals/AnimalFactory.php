<?php

namespace App\Animals;

use App\Exceptions\MissingAnimalException;

class AnimalFactory
{
    /**
     * @throws MissingAnimalException
     */
    public function getAnimal(string $animal, string $name): AnimalInterface
    {
        $animal = strtolower($animal);
        if ($animal === 'cat') {
            return new Cat($name);
        }
        if ($animal === 'dog') {
            return new Dog($name);
        }
        if ($animal === 'cow') {
            return new Cow($name);
        }
        if ($animal === 'unicorn') {
            return new Unicorn($name);
        }

        throw new MissingAnimalException("Could not determine animal type.");
    }
}
