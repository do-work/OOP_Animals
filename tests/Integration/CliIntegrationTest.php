<?php

namespace Tests\Integration;

use App\Cli;
use App\Writer\WriterInterface;
use PHPUnit\Framework\TestCase;

class CliIntegrationTest extends TestCase
{
    public function testCliRunWithOneAnimal()
    {
        $writerTestAdapter = new WriterTestAdapter();
        $sut = new Cli($writerTestAdapter);
        $args = ['path', 'Ellie', 'Dog'];
        $expected = ['Ellie says "woof"'];

        $sut->run($args);

        $this->assertEquals($expected, $writerTestAdapter->getMessage());
    }

    public function testCliRunWithTwoAnimals()
    {
        $writerTestAdapter = new WriterTestAdapter();
        $sut = new Cli($writerTestAdapter);
        $expected = ['Ellie says "woof"','Mozzie says "meow"'];

        $sut->run(['path', 'Ellie', 'Dog', 'Mozzie', 'cat']);

        $this->assertEquals($expected, $writerTestAdapter->getMessage());
    }

    public function testCliRunCreatesNewAnimal()
    {
        $writerTestAdapter = new WriterTestAdapter();

        $sut = $this->getMockBuilder(CliIntegrationTestAdapter::class)
            ->setConstructorArgs([$writerTestAdapter])
            ->onlyMethods(['promptToCreate'])
            ->getMock();
        $sut->expects($this->once())
            ->method('promptToCreate')
            ->willReturn(['name' => 'Nemo', 'talk' => 'Bubble...']);
        $expected = ['Nemo says "Bubble..."'];

        $sut->run(['path', 'Nemo', 'Bubble...']);

        $this->assertEquals($expected, $writerTestAdapter->getMessage());
    }
}

class WriterTestAdapter implements WriterInterface
{
    private array $message;

    public function write(string $message): void
    {
        $this->message[] = $message;
    }

    public function getMessage(): array
    {
        return $this->message;
    }
}

class CliIntegrationTestAdapter extends Cli
{
    public function promptToCreate(string $name): array
    {
        return parent::promptToCreate($name);
    }
}
