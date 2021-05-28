<?php


namespace App\Model;


class Product extends BaseModel
{
    protected $table = 'products';

    public function specialPrices()
    {
        return $this->hasMany(SpecialPrice::class);
    }
}
