<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetSimilarProductsRequest;
use App\Services\Product\Actions\GetSimilarProductsAction;

class ProductController extends Controller
{
    public function getSimilars(
        GetSimilarProductsRequest $request,
        GetSimilarProductsAction $getSimilarProductsAction
    ) {
        $productId = $request->getProductId();

        $result = $getSimilarProductsAction->run($productId);

        return $this->response($result);
    }
}
