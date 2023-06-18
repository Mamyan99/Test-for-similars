<?php

namespace App\Services\Product\Actions;

use App\Models\Product;
use App\Repositories\Read\ProductReadRepositoryInterface;

class GetSimilarProductsAction
{
    const MAX_COUNT = 15;

    public function __construct(private ProductReadRepositoryInterface $productReadRepository)
    {}

    public function run(int  $productId): array
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

            if ($similarsCount < self::MAX_COUNT) {
                $ignoreIds = array_merge($ignoreIds, $productsWithPopularity->pluck('id')->toArray());
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

        return array_filter($words, function($word) use ($ignoredWords) {
            return !in_array($word, $ignoredWords);
        });
    }
}
