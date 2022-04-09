# OOP Animals CLI
CLI application that outputs animal sounds.

## Requirements
- PHP 7.4+
- Composer

## Install
```shell
composer install
```

## Run
```shell
php animal-cli.php {name} {animal}

php animal-cli.php "Mr Pickles" cat
php animal-cli.php Ellie Dog Bessie cow
php animal-cli.php Nemo Fish
php animal-cli.php Ellie Dog Nemo Fish
```

## Run Tests
```shell
composer test
composer test-unit
composer test-integration
```

## Run Linter
```
composer lint
```
