<?php

namespace App\Http\Controllers;

use App\Helpers\Lists\ProductRentHoursList;
use App\Serializers\ProductCheckStatusResponseSerializer;
use App\Services\ProductPurchaseCreator;
use App\Services\ProductRentHandler;
use App\Services\ProductStatusGetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductPurchaseCreator $purchaseCreator,
        private readonly ProductRentHandler $rentHandler,
        private readonly ProductStatusGetter $statusGetter,
    )
    {
    }

    public function buy(Request $request): array
    {
        $request->validate([
            'product_id' => 'required|int|exists:products',
        ]);

        $user = Auth::user();
        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        $isSuccess = $this->purchaseCreator
            ->create(productId: $request->get('product_id'), userId: $user->getKey());

        return ['success' => $isSuccess];
    }

    public function rent(Request $request): array
    {
        $request->validate([
            'product_id' => 'required|int|exists:products',
            'hours' => [
                Rule::in(ProductRentHoursList::getAvailable())
            ]
        ]);

        $user = Auth::user();
        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }
        return [
            'success' => $this->rentHandler->create(
                $request->get('product_id'),
                $user->getKey(),
                $request->get('hours')
            )
        ];
    }

    public function extendRent(Request $request): array
    {
        $request->validate([
            'product_id' => 'required|int|exists:products',
            'hours' => [
                Rule::in(ProductRentHoursList::getAvailable())
            ]
        ]);

        $user = Auth::user();
        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }
        return [
            'success' => $this->rentHandler->extendRent(
                $request->get('product_id'),
                $user->getKey(),
                $request->get('hours')
            )
        ];
    }

    public function checkStatus(Request $request): array
    {
        $request->validate([
            'product_id' => 'required|int|exists:products',
        ]);

        $user = Auth::user();
        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        $response = $this->statusGetter->get($request->get('product_id'), $user->getKey());

        return (new ProductCheckStatusResponseSerializer($response))->serialize();
    }
}