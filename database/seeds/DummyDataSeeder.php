<?php

use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                'id' => 1,
                'title' => 'Samsung',
                'unit_price' => 1000,
            ],
            [
                'id' => 2,
                'title' => 'IPhone',
                'unit_price' => 2500,
            ],
            [
                'id' => 3,
                'title' => 'Huawei',
                'unit_price' => 2500,
            ],
        ];
        foreach ($products as $product) {
            \App\Model\Product::create($product);
        }

        $specialPrices = [
            [
                'product_id' => 2,
                'price' => 1500,
                'quantity' => 2,
            ],
            [
                'product_id' => 2,
                'price' => 2500,
                'quantity' => 3,
            ],
            [
                'product_id' => 2,
                'price' => 3500,
                'quantity' => 4,
            ],
        ];

        foreach ($specialPrices as $specialPrice) {
            \App\Model\SpecialPrice::create($specialPrice);
        }
    }
}
