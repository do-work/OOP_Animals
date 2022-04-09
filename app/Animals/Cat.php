<?php

namespace App\Animals;

class Cat extends AbstractAnimal
{
    private const SOUND = 'meow';

    protected function getSound(): string
    {
        return self::SOUND;
    }
}
