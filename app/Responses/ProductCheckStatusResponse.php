<?php

namespace App\Responses;

use App\Enums\ProductStatusEnum;

class ProductCheckStatusResponse
{
    public function __construct(
        private readonly ProductStatusEnum $status,
        private readonly string $productCode,
    ) {
    }

    public function getStatus(): ProductStatusEnum
    {
        return $this->status;
    }

    public function getProductCode(): string
    {
        return $this->productCode;
    }
}