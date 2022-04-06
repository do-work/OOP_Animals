<?php

namespace App\Animals;

abstract class AbstractAnimal implements AnimalInterface
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function talk(): string
    {
        return "{$this->name} says \"{$this->getSound()}\"";
    }

    abstract protected function getSound(): string;
}
