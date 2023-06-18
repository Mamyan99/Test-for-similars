<?php

namespace App\Exceptions\Product;

use App\Exceptions\BusinessLogicException;
use Exception;

class ProductNotFoundException extends BusinessLogicException
{

    public function getStatus(): int
    {
        return BusinessLogicException::PRODUCT_NOT_FOUND;
    }

    public function getStatusMessage(): string
    {
        return __('errors.product_not_found');
    }
}
