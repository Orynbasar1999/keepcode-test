<?php

namespace App\Serializers;

use App\Responses\ProductCheckStatusResponse;

class ProductCheckStatusResponseSerializer
{
    public function __construct(private readonly ProductCheckStatusResponse $response)
    {
    }

    public function serialize(): array
    {
        return [
            'status' => $this->response->getStatus(),
            'code' => $this->response->getProductCode(),
        ];
    }
}