<?php

namespace App\Animals;

use App\Exceptions\MissingAnimalException;

class AnimalFactory
{
    /**
     * Create an instance of a valid Animal class.
     *
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

    /**
     * Create an anonymous class that extends AbstractAnimal.
     */
    public static function createNewAnimal(string $name, string $sound): AnimalInterface
    {
        return new class ($name, $sound) extends AbstractAnimal {
            private string $sound;

            public function __construct(string $name, string $sound)
            {
                parent::__construct($name);
                $this->sound = $sound;
            }

            protected function getSound(): string
            {
                return $this->sound;
            }
        };
    }
}
