<?php

namespace App\Animals;

use PHPUnit\Framework\TestCase;

class AbstractAnimalTest extends TestCase
{
    public function testTalk()
    {
        $abstractAnimal = $this->getMockForAbstractClass(AbstractAnimal::class, ['Tester']);
        $abstractAnimal->expects($this->any())
            ->method('getSound')
            ->will($this->returnValue('testingSound'));
        $expected = 'Tester says "testingSound"';

        $actual = $abstractAnimal->talk();

        $this->assertEquals($expected, $actual);
    }
}