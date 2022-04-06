<?php

namespace App\Animals;

class Dog extends AbstractAnimal
{
    const SOUND = 'woof';

    protected function getSound(): string
    {
        return self::SOUND;
    }
}
