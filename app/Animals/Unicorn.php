<?php

namespace App\Animals;

class Unicorn extends AbstractAnimal
{
    protected function getSound(): string
    {
        return '';
    }

    public function talk(): string
    {
        return 'Unicorns are too fabulous for labels and words';
    }
}
