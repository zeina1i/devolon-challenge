<?php


namespace App\Model;


class Cart extends BaseModel
{
    protected $table = 'carts';

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
