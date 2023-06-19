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

    /**
     * @throws ProductNotFoundException
     */
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
                $query->orWhereRaw("CONCAT(' ', name, ' ') LIKE '% $name %'");
            }
        });

        return $query
            ->orderByRaw('RAND() * (1 + popularity) DESC')
            ->limit($limit)
            ->get(['id']);
    }

    public function getProductsWithPopularity(array $ignoreIds, int $limit): Collection
    {
        return $this->query()
            ->whereNotIn('id', $ignoreIds)
            ->where('popularity', '!=' , 0)
            ->orderByRaw('RAND() * popularity DESC')
            ->limit($limit)
            ->get(['id']);
    }

    public function getRandomProducts(array $ignoreIds, int $limit): Collection
    {
        return $this->query()
            ->whereNotIn('id', $ignoreIds)
            ->inRandomOrder()
            ->limit($limit)
            ->get(['id']);
    }
}
