<?php

namespace App\Writer;

class CliWriter implements WriterInterface
{

    public function write(string $message): void
    {
        echo $message;
    }
}
