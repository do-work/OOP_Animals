<?php

namespace App\Animals;

use App\Exceptions\MissingAnimalException;
use PHPUnit\Framework\TestCase;

class AnimalFactoryTest extends TestCase
{
    public function testGetAnimalWithValidAnimal()
    {
        $actual = AnimalFactory::getAnimal('dog', 'Ellie');

        $this->assertInstanceOf(Dog::class, $actual);
        $this->assertEquals('Ellie', $actual->getName());
    }

    public function testGetAnimalRaisesExceptionWithInvalidAnimal()
    {
        $this->expectException(MissingAnimalException::class);
        AnimalFactory::getAnimal('gator', 'Ellie');
    }

    public function testCreateNewAnimal()
    {
        $animal = AnimalFactory::createNewAnimal('Ellie', 'Roar');

        $this->assertInstanceOf(AbstractAnimal::class, $animal);
        $this->assertEquals('Ellie', $animal->getName());
        $this->assertEquals('Ellie says "Roar"', $animal->talk());
    }
}