<?php

namespace App\Repositories\Read;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductReadRepositoryInterface
{
    public function getById(int $productId): Product;

    public function getSimilarsByName(array $ignoreIds, array $names, int $limit): Collection;
    public function getProductsWithPopularity(array $ignoreIds, int $limit): Collection;
    public function getRandomProducts(array $ignoreIds, int $limit): Collection;

}
