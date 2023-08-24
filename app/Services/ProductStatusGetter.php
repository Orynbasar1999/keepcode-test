<?php

namespace App\Services;

use App\Enums\ProductStatusEnum;
use App\Helpers\Lists\DateTimeFormatList;
use App\Models\Product;
use App\Models\ProductPurchase;
use App\Models\ProductRent;
use App\Responses\ProductCheckStatusResponse;
use DateTime;
use Illuminate\Support\Str;

class ProductStatusGetter
{
    private const PRODUCT_CODE_LENGTH = 6;

    public function get(int $productId, int $userId): ProductCheckStatusResponse
    {
        /** @var Product $product */
        $product = Product::query()->where(['product_id' => $productId])->first();
        if (!$product) {
            abort(404, 'Product not found');
        }

        $code = $product->code ?: null;
        if (!$code) {
            $code = $this->generateUniqueCode($product);
        }

        if ($this->isProductSold($productId, $userId)) {
            return new ProductCheckStatusResponse(
                status: ProductStatusEnum::SOLD,
                productCode: $code,
            );
        }

        if ($this->isProductInRent($productId, $userId)) {
            return new ProductCheckStatusResponse(
                status: ProductStatusEnum::IN_RENT,
                productCode: $code,
            );
        }
    }

    private function generateUniqueCode(Product $product): string
    {
        do {
            $code = strtoupper(Str::random(self::PRODUCT_CODE_LENGTH));
        } while (!$this->updateProductCode($product, $code));

        return $code;
    }

    private function updateProductCode(Product $product, string $code): bool
    {
        return $product->update(['code' => $code]);
    }

    private function isProductInRent(int $productId, int $userId): bool
    {
        $now = new DateTime();

        return ProductRent::query()
            ->where([
                'product_id' => $productId,
                'user_id' => $userId,
            ])
            ->where('rent_end_at', '>', $now->format(DateTimeFormatList::DEFAULT))
            ->exists();
    }

    private function isProductSold(int $productId, int $userId): bool
    {
        return ProductPurchase::query()
            ->where([
                'product_id' => $productId,
                'user_id' => $userId,
            ])
            ->exists();
    }
}