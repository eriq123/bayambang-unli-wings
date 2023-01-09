<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $getSameShopPreviousOrder = Order::where('shop_id', $model->shop_id)->orderByDesc('id')->first();
            $model->uuid = isset($getSameShopPreviousOrder) ? $getSameShopPreviousOrder->uuid + 1 : 1;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products')->using(OrderProduct::class)->withPivot('quantity');
    }
}
