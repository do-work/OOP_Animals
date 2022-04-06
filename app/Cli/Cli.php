<?php

namespace App\Cli;

use App\Animals\AnimalFactory;
use App\Animals\AnimalInterface;
use App\Exceptions\InvalidArgumentQuantity;
use App\Exceptions\MissingAnimalException;
use App\Writer\WriterInterface;
use Throwable;

class Cli
{
    private WriterInterface $writer;

    public function __construct(WriterInterface $writer)
    {
        $this->writer = $writer;
    }

    public function run(array $argv)
    {
        try {
            $arguments = $this->getArguments($argv);
            $animal = (new AnimalFactory())->getAnimal($arguments['animal'], $arguments['name']);
        } catch (InvalidArgumentQuantity|MissingAnimalException $e) {
            $this->writer->write($this->buildErrorMessage($e));
            return;
        }

        $this->writer->write($this->buildSuccessMessage($animal));
    }

    /**
     * @throws InvalidArgumentQuantity
     */
    private function getArguments(array $args): array
    {
        $this->validateArguments($args);

        return [
            'name' => $args[1],
            'animal' => $args[2],
        ];
    }

    /**
     * @throws InvalidArgumentQuantity
     */
    private function validateArguments(array $argv): void
    {
        // subtract one to remove the first argument which is the filepath.
        $argument_qty = count($argv) - 1;
        if (count($argv) <= 2) {
            throw new InvalidArgumentQuantity("Too few arguments passed.");
        }

        if ($argument_qty % 2 === 1) {
            throw new InvalidArgumentQuantity("Mismatched name and animal pairing. Verify quotes on multi words.");
        }
    }

    private function buildSuccessMessage(AnimalInterface $animal): string
    {
        return $animal->talk();
    }

    private function buildErrorMessage(Throwable $e): string
    {
        return "Failed to parse CLI arguments. {$e->getMessage()}";
    }
}
