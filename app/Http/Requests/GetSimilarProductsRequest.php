<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetSimilarProductsRequest extends FormRequest
{
    const PRODUCT_ID = 'id';
    public function authorize()
    {
        // No need yet
        return true;
    }

    public function rules()
    {
        return [
            //
        ];
    }

    public function getProductId(): int
    {
        return $this->route(self::PRODUCT_ID);
    }
}
