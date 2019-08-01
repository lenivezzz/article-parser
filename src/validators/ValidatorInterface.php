<?php
declare(strict_types=1);

namespace php_part\validators;

interface ValidatorInterface
{
    public function fails(array $attributes) : bool;

    public function getErrors() : array;
}
