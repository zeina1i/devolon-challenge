<?php


namespace App\Model;


class SpecialPrice extends BaseModel
{
    protected $table = 'special_prices';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
