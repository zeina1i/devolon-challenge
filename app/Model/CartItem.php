<?php


namespace App\Model;


class CartItem extends BaseModel
{
    protected $table = 'cart_items';

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
