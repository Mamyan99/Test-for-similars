<?php

namespace App\Repositories\Read;

use App\Exceptions\Product\ProductNotFoundException;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ProductReadRepository implements ProductReadRepositoryInterface
{
    private function query(): Builder
    {
        return Product::query();
    }

    public function getById(int $productId): Product
    {
        $product = $this->query()
            ->find($productId);

        if (is_null($product)) {
            throw new ProductNotFoundException();
        }

        return $product;
    }

    public function getSimilarsByName(array $ignoreIds, array $names, int $limit): Collection
    {
        $query = $this->query()
            ->whereNotIn('id', $ignoreIds);

        $query->where(function (Builder $query) use ($names) {
            foreach ($names as $name) {
                $query->orWhere('name', 'like', "%$name%");
            }
        });

        return $query->inRandomOrder()
            ->orderByDesc('popularity')
            ->limit($limit)
            ->select('id')
            ->get();
    }

    public function getProductsWithPopularity(array $ignoreIds, int $limit): Collection
    {
        return $this->query()
            ->whereNotIn('id', $ignoreIds)
            ->where('popularity', '!=' , 0)
            ->inRandomOrder()
            ->orderByDesc('popularity')
            ->limit($limit)
            ->select('id')
            ->get();
    }

    public function getRandomProducts(array $ignoreIds, int $limit): Collection
    {
        return $this->query()
            ->whereNotIn('id', $ignoreIds)
            ->inRandomOrder()
            ->limit($limit)
            ->select('id')
            ->get();
    }
}
