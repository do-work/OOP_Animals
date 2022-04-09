<?php

namespace App\Animals;

class Cow extends AbstractAnimal
{
    private const SOUND = 'moo';

    protected function getSound(): string
    {
        return self::SOUND;
    }
}
