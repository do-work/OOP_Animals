<?php

namespace App\Animals;

class Cat extends AbstractAnimal
{
    const SOUND = 'meow';

    protected function getSound(): string
    {
        return self::SOUND;
    }
}
