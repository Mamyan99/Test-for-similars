<?php

namespace App\Services\Product\Actions;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\Read\ProductReadRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetSimilarProductsAction
{
    const MAX_COUNT = 15;
    private ProductReadRepositoryInterface $productReadRepository;

    public function __construct(ProductReadRepositoryInterface $productReadRepository)
    {
        $this->productReadRepository = $productReadRepository;
    }

    public function run(int  $productId)
    {
        $product = $this->productReadRepository->getById($productId);

        $ignoreIds = [$product->getId()];

        $searchKeyWords = $this->filterName($product->getName());

        $similars = $this->productReadRepository->getSimilarsByName($ignoreIds, $searchKeyWords, self::MAX_COUNT);
        $similarsCount = $similars->count();

        if ($similarsCount < self::MAX_COUNT) {
            $limitForPopulars = self::MAX_COUNT - $similarsCount;

            $ignoreIds = array_merge($ignoreIds, $similars->pluck('id')->toArray());
            $productsWithPopularity = $this->productReadRepository->getProductsWithPopularity($ignoreIds, $limitForPopulars);
            $similars = $similars->merge($productsWithPopularity);
            $similarsCount = $similars->count();
            $ignoreIds = array_merge($ignoreIds, $productsWithPopularity->pluck('id')->toArray());

            if ($similarsCount < self::MAX_COUNT) {
                $randomProductsLimit = self::MAX_COUNT - $similarsCount;
                $randomProducts = $this->productReadRepository->getRandomProducts($ignoreIds, $randomProductsLimit);
                $similars = $similars->merge($randomProducts);
            }
        }

        return $similars->pluck('id')->toArray();
    }

    private function filterName(string $name): array
    {
        $ignoredWords = Product::IGNORE_WORDS;
        $inputString = strtolower($name);

        $pattern = '/[^a-zA-Z\s]/u';
        $inputString = preg_replace($pattern, '', $inputString);
        $words = preg_split('/\s+/', $inputString);

        $filteredWords = array_filter($words, function($word) use ($ignoredWords) {
            return !in_array($word, $ignoredWords);
        });

        return $filteredWords;
    }
}
