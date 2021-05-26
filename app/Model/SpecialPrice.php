<?php


namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class SpecialPrice extends Model
{
    protected $table = 'special_prices';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
