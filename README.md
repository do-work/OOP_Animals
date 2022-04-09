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
vendor/bin/phpunit tests
```

## Run Linter
```
vendor/bin/phpcs --standard=PSR12 app
```
