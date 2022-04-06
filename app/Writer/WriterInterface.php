<?php

namespace App\Writer;

interface WriterInterface
{
    public function write(string $message): void;
}