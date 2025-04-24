<?php

namespace App\Model;

class File
{
    public function __construct(
        public string $mode,
        public string $path,
        public string $object,
    ) {}
}
