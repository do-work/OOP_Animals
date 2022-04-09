<?php

use App\Animals\AbstractAnimal;
use App\Animals\Cat;
use App\Animals\Dog;
use App\Cli;
use App\Exceptions\InvalidArgumentQuantity;
use App\Exceptions\MissingAnimalException;
use App\Exceptions\UserInputException;
use App\Writer\CliWriter;
use PHPUnit\Framework\TestCase;

class CliTest extends TestCase
{
    public function testGetArgumentsWithSingleAnimal()
    {
        $sut = $this->getMockBuilder(CliTestAdapter::class)
            ->setConstructorArgs([new CliWriter()])
            ->onlyMethods(['validateArguments'])
            ->getMock();

        $args = [
            '/path/animal-cli.php',
            'Ellie',
            'Dog',
        ];
        $expected = [
            [
                'name' => 'Ellie',
                'animal' => 'Dog',
            ],
        ];

        $actual = $sut->getArguments($args);

        $this->assertEquals($expected, $actual);
    }

    public function testGetArgumentsWithMultipleAnimals()
    {
        $sut = $this->getMockBuilder(CliTestAdapter::class)
            ->setConstructorArgs([new CliWriter()])
            ->onlyMethods(['validateArguments'])
            ->getMock();

        $args = [
            '/path/animal-cli.php',
            'Ellie',
            'Dog',
            'Mozzie',
            'Cat'
        ];
        $expected = [
            [
                'name' => 'Ellie',
                'animal' => 'Dog'
            ],
            [
                'name' => 'Mozzie',
                'animal' => 'Cat'
            ]
        ];

        $actual = $sut->getArguments($args);

        $this->assertEquals($expected, $actual);
    }

    public function testValidateArgumentsWithOneValidSet()
    {
        $sut = new CliTestAdapter(new CliWriter());

        $this->assertNull($sut->validateArguments(['path', 'Ellie', 'Dog']));
    }

    public function testValidateArgumentsWithTwoValidSets()
    {
        $sut = new CliTestAdapter(new CliWriter());

        $this->assertNull($sut->validateArguments(['path', 'Ellie', 'Dog', 'Mozzie', 'cat']));
    }

    public function testValidateArgumentsRaisesExceptionWhenTooFewArgumentsPassed()
    {
        $sut = new CliTestAdapter(new CliWriter());

        $this->expectException(InvalidArgumentQuantity::class);
        $sut->validateArguments(['path', 'Ellie']);
    }

    public function testValidateArgumentsRaisesExceptionWhenInvalidArgumentPairing()
    {
        $sut = new CliTestAdapter(new CliWriter());

        $this->expectException(InvalidArgumentQuantity::class);
        $sut->validateArguments(['path', 'Ellie', 'dog', 'Mozzie']);
    }

    public function testGetAnimalsWithSingleValidAnimal()
    {
        $sut = $this->getMockBuilder(CliTestAdapter::class)
            ->setConstructorArgs([new CliWriter()])
            ->onlyMethods([])
            ->getMock();
        $animalsArgs = [
            [
                'name' => 'Ellie',
                'animal' => 'Dog',
            ],
        ];

        $actual = $sut->getAnimals($animalsArgs);

        $this->assertCount(1, $actual);
        $this->assertInstanceOf(Dog::class, $actual[0]);
    }

    public function testGetAnimalsWithTwoValidAnimals()
    {
        $sut = $this->getMockBuilder(CliTestAdapter::class)
            ->setConstructorArgs([new CliWriter()])
            ->onlyMethods([])
            ->getMock();
        $animalsArgs = [
            [
                'name' => 'Ellie',
                'animal' => 'Dog'
            ],
            [
                'name' => 'Mozzie',
                'animal' => 'Cat'
            ]
        ];

        $actual = $sut->getAnimals($animalsArgs);

        $this->assertCount(2, $actual);
        $this->assertInstanceOf(Dog::class, $actual[0]);
        $this->assertInstanceOf(Cat::class, $actual[1]);
    }

    public function testGetAnimalsWithOneValidAnimalAndOneNewAnimal()
    {
        $sut = $this->getMockBuilder(CliTestAdapter::class)
            ->setConstructorArgs([new CliWriter()])
            ->onlyMethods(['promptToCreate'])
            ->getMock();
        $sut->expects($this->once())
            ->method('promptToCreate')
            ->willReturn(['name' => 'Nemo', 'talk' => 'Bubble...']);

        $animalsArgs = [
            [
                'name' => 'Ellie',
                'animal' => 'Dog'
            ],
            [
                'name' => 'Nemo',
                'animal' => 'Fish'
            ]
        ];

        $actual = $sut->getAnimals($animalsArgs);

        $this->assertCount(2, $actual);
        $this->assertInstanceOf(Dog::class, $actual[0]);
        $this->assertInstanceOf(AbstractAnimal::class, $actual[1]);
    }

    public function testPromptToCreateWithOneWordTalk()
    {
        $sut = $this->getMockBuilder(CliTestAdapter::class)
            ->setConstructorArgs([new CliWriter()])
            ->onlyMethods(['getUserInput'])
            ->getMock();
        $sut->expects($this->exactly(2))
            ->method('getUserInput')
            ->willReturnOnConsecutiveCalls('y', 'blub');
        $expected = [
            'name' => 'Nemo',
            'talk' => 'blub'
        ];

        $actual = $sut->promptToCreate('Nemo');

        $this->assertEquals($expected, $actual);
    }

    public function testPromptToCreateWithMultiWordTalk()
    {
        $sut = $this->getMockBuilder(CliTestAdapter::class)
            ->setConstructorArgs([new CliWriter()])
            ->onlyMethods(['getUserInput'])
            ->getMock();
        $sut->expects($this->exactly(2))
            ->method('getUserInput')
            ->willReturnOnConsecutiveCalls('y', 'blub blub');
        $expected = [
            'name' => 'Nemo',
            'talk' => 'blub blub'
        ];

        $actual = $sut->promptToCreate('Nemo');

        $this->assertEquals($expected, $actual);
    }

    public function testPromptToCreateRaisesExceptionWhenUserDoesNotCreateNewAnimal()
    {
        $sut = $this->getMockBuilder(CliTestAdapter::class)
            ->setConstructorArgs([new CliWriter()])
            ->onlyMethods(['getUserInput'])
            ->getMock();
        $sut->expects($this->once())
            ->method('getUserInput')
            ->willReturn('n');

        $this->expectException(MissingAnimalException::class);
        $sut->promptToCreate('Nemo');
    }

    public function testPromptToCreateRaisesExceptionWhenInvalidSayIsEntered()
    {
        $sut = $this->getMockBuilder(CliTestAdapter::class)
            ->setConstructorArgs([new CliWriter()])
            ->onlyMethods(['getUserInput'])
            ->getMock();
        $sut->expects($this->exactly(2))
            ->method('getUserInput')
            ->willReturnOnConsecutiveCalls('y', 'blub&^^^');

        $this->expectException(UserInputException::class);
        $sut->promptToCreate('Nemo');
    }

    public function testGetUserInput()
    {
        $sut = $this->getMockBuilder(CliTestAdapter::class)
            ->setConstructorArgs([new CliWriter()])
            ->onlyMethods(['getUserInputFromStdIn'])
            ->getMock();
        $sut->expects($this->once())
            ->method('getUserInputFromStdIn')
            ->willReturn('testing');
        $expected = 'testing';

        $actual = $sut->getUserInput();

        $this->assertEquals($expected, $actual);
    }

    public function testGetUserInputReturnsEmptyStringWhenInputIsFalse()
    {
        $sut = $this->getMockBuilder(CliTestAdapter::class)
            ->setConstructorArgs([new CliWriter()])
            ->onlyMethods(['getUserInputFromStdIn'])
            ->getMock();
        $sut->expects($this->once())
            ->method('getUserInputFromStdIn')
            ->willReturn(false);
        $expected = '';

        $actual = $sut->getUserInput();

        $this->assertEquals($expected, $actual);
    }

    public function testGetUserInputRaisesExceptionWhenInputIsTooLong()
    {
        $sut = $this->getMockBuilder(CliTestAdapter::class)
            ->setConstructorArgs([new CliWriter()])
            ->onlyMethods(['getUserInputFromStdIn'])
            ->getMock();
        $sut->expects($this->once())
            ->method('getUserInputFromStdIn')
            ->willReturn('testingtestingtestingtestingtesting');

        $this->expectException(UserInputException::class);
        $sut->getUserInput();
    }
}

class CliTestAdapter extends Cli
{
    public function getArguments(array $args): array
    {
        return parent::getArguments($args);
    }

    public function validateArguments(array $argv): void
    {
        parent::validateArguments($argv);
    }

    public function getAnimals(array $args): array
    {
        return parent::getAnimals($args);
    }

    public function promptToCreate(string $name): array
    {
        return parent::promptToCreate($name);
    }

    public function getUserInput(): string
    {
        return parent::getUserInput();
    }
}