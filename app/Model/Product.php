<?php


namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    public function specialPrices()
    {
        return $this->hasMany(SpecialPrice::class);
    }
}
