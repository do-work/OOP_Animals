<?php

namespace App;

use App\Animals\AnimalFactory;
use App\Animals\AnimalInterface;
use App\Exceptions\InvalidArgumentQuantity;
use App\Exceptions\InvalidPropertyValue;
use App\Exceptions\MissingAnimalException;
use App\Writer\WriterInterface;
use Throwable;

class Cli
{
    private const MAX_USER_INPUT_LENGTH = 20;

    private WriterInterface $writer;

    public function __construct(WriterInterface $writer)
    {
        $this->writer = $writer;
    }

    public function run(array $argv): void
    {
        try {
            $arguments = $this->getArguments($argv);
            $animals = $this->getAnimals($arguments);
        } catch (MissingAnimalException | InvalidArgumentQuantity | UserInputException $e) {
            $this->writer->write($this->buildErrorMessage($e));
            return;
        }
        $this->writeMessages($animals);
    }

    /**
     * Get CLI arguments grouped into objects with name and animal.
     *
     * @throws InvalidArgumentQuantity
     */
    protected function getArguments(array $args): array
    {
        $this->validateArguments($args);
        return array_map(function (array $argPairing) {
            return [
                'name' => $argPairing[0],
                'animal' => $argPairing[1],
            ];
        }, array_chunk(array_slice($args, 1), 2));
    }

    /**
     * Validate that the correct amount of arguments are passed and that there is a name and animal type.
     *
     * @throws InvalidArgumentQuantity
     */
    protected function validateArguments(array $argv): void
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

    /**
     * @return AnimalInterface[]
     * @throws MissingAnimalException
     * @throws InvalidPropertyValue
     */
    protected function getAnimals(array $args): array
    {
        $animals = [];
        foreach ($args as $arg) {
            try {
                $animals[] = AnimalFactory::getAnimal($arg['animal'], $arg['name']);
            } catch (MissingAnimalException $e) {
                $animal = $this->promptToCreate($arg['name']);
                $animals[] = AnimalFactory::createNewAnimal($animal['name'], $animal['talk']);
            }
        }
        return $animals;
    }

    /**
     * @throws InvalidPropertyValue
     * @throws MissingAnimalException
     */
    protected function promptToCreate(string $name): array
    {
        $this->writer->write("Animal does not exist. Would you like to create it? ((Y)es or (N)o)\n");
        $shouldCreate = $this->getUserInput();
        if (!in_array(strtolower(rtrim($shouldCreate)), ['yes', 'y'])) {
            throw new MissingAnimalException("User did not want to create a new animal");
        }

        $this->writer->write("What should the animal say? (alpha numeric only)\n");
        $animalSay = $this->getUserInput();

        if (
            !$animalSay ||
            !preg_match("/^[\w\d\s]{1,20}$/", $animalSay)
        ) {
            throw new InvalidPropertyValue("Invalid characters in animal say string.");
        }

        return [
            'name' => $name,
            'talk' => $animalSay
        ];
    }

    /**
     * Get the user input.
     *
     * Remove new lines.
     */
    protected function getUserInput(): string
    {
        $userInput = fgets(STDIN);
        if ($userInput === false) {
            return '';
        }

        return $this->sanitizeUserInput($userInput);
    }

    protected function sanitizeUserInput(string $userInput): string
    {
        return trim(preg_replace('/\s+/', '', $userInput));
    }

    /**
     * @param AnimalInterface[] $animals
     * @return void
     */
    private function writeMessages(array $animals): void
    {
        foreach ($animals as $animal) {
            $this->writer->write($this->buildSuccessMessage($animal));
        }
    }

    private function buildSuccessMessage(AnimalInterface $animal): string
    {
        return "{$animal->talk()}\n";
    }

    private function buildErrorMessage(Throwable $e): string
    {
        return "Failed to parse CLI arguments. {$e->getMessage()}";
    }
}
