<?php

namespace App\Helpers\Lists;

/**
 * Список доступных часов для аренды
 */
final class ProductRentHoursList
{
    public static function getAvailable(): array
    {
        return [4, 8, 12, 24];
    }
}