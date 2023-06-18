<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetSimilarProductsRequest;
use App\Services\Product\Actions\GetSimilarProductsAction;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function getSimilars(
        GetSimilarProductsRequest $request,
        GetSimilarProductsAction $getSimilarProductsAction
    ): JsonResponse {
        $productId = $request->getProductId();

        $result = $getSimilarProductsAction->run($productId);

        return $this->response($result);
    }
}
