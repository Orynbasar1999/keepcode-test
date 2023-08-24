<?php

namespace App\Services;

use App\Helpers\Lists\DateTimeFormatList;
use App\Models\ProductRent;
use DateInterval;
use DateTime;

class ProductRentHandler
{
    private const RENT_HOURS_LIMIT = 24;

    public function create(int $productId, int $userId, int $hours): bool
    {
        $rentEndDate = (new DateTime("+$hours hours"));

        $rent = new ProductRent(
            [
                'product_id' => $productId,
                'user_id' => $userId,
                'rest_end_date' => $rentEndDate,
            ]
        );

        return $rent->save();
    }

    public function extendRent(int $productId, int $userId, int $hours): bool
    {
        $now = new DateTime();
        /** @var ProductRent $rent */
        $rent = ProductRent::query()
            ->where([
                'product_id' => $productId,
                'user_id' => $userId,
            ])
            ->where('rent_end_at', '>', $now->format(DateTimeFormatList::DEFAULT))
            ->orderBy('created_at', 'desc')
            ->first();
        if (!$rent) {
            return false;
        }

        $rentEndDate = new DateTime($rent->rent_end_at);
        $resultRentHours = $rentEndDate->diff($now)->h + $hours;
        if ($resultRentHours >= self::RENT_HOURS_LIMIT) {
            return false;
        }

        $rentEndDate->add(new DateInterval("PT{$hours}H"));

        return $rent->update([
            'rest_end_at' => $rentEndDate,
            'updated_at' => $now->format(DateTimeFormatList::DEFAULT),
        ]);
    }
}