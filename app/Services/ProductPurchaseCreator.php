<?php

namespace App\Services;

use App\Models\ProductPurchase;

class ProductPurchaseCreator
{
    public function create(int $productId, int $userId): bool
    {
        $purchase = new ProductPurchase(
            [
                'product_id' => $productId,
                'user_id' => $userId,
            ]
        );

        return $purchase->save();
    }
}