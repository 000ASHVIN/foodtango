<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentsToRestaurant extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function discount()
    {
        return $this->hasOne(Order::class, 'restaurant_discount_amount');
    }
}
