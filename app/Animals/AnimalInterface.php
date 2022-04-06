<?php

namespace App\Animals;

interface AnimalInterface
{
    public function getName(): string;
    public function talk(): string;
}