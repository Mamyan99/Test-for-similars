<?php

namespace App\Exceptions;

use Exception;

abstract class BusinessLogicException extends Exception
{
    const PRODUCT_NOT_FOUND = 600;

    abstract public function getStatus(): int;
    abstract public function getStatusMessage(): string;
}
