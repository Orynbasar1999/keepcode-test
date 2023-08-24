<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property Carbon $rent_end_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ProductRent extends Model
{
    use HasFactory;
}
