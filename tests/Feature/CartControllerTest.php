<?php

namespace Tests\Feature;

use App\Enum\CartEnums;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(\DummyDataSeeder::class);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCartScenario()
    {
//        1-create a cart
        $response = $this->postJson('/api/v1/cart/create');
        $cart = $response->getOriginalContent()['data'];

        $response->assertStatus(200);
        $this->assertEquals(true, $response->getOriginalContent()['status']);
        $this->assertEquals(CartEnums::CART_STATUS_OPEN, $cart['cart_status']);
        $this->assertEquals(0, count($cart['cart_items']));
        $this->assertEquals(0, $cart['payable_price']);

//        2-add item product_id = 1
        $response = $this->postJson('/api/v1/cart/add-item', [
                'product_id' => 1,
                'cart_id' => $cart['id']
            ]);
        $cart = $response->getOriginalContent()['data'];
        $response->assertStatus(200);
        $this->assertEquals(true, $response->getOriginalContent()['status']);
        $this->assertEquals(1, count($cart['cart_items']));
        $this->assertEquals(1000, $cart['payable_price']);

//        3-change item quantity product_id = 1 to 3
        $response = $this->postJson('/api/v1/cart/change-quantity', [
            'product_id' => 1,
            'cart_id' => $cart['id'],
            'quantity' => 3,
        ]);
        $cart = $response->getOriginalContent()['data'];
        $response->assertStatus(200);
        $this->assertEquals(true, $response->getOriginalContent()['status']);
        $this->assertEquals(1, count($cart['cart_items']));
        $this->assertEquals(3000, $cart['payable_price']);

//        4-add unavailable item to the cart (eg. a product that doesn't exist)
        $response = $this->postJson('/api/v1/cart/add-item', [
            'product_id' => 5,
            'cart_id' => $cart['id']
        ]);
        $response->assertStatus(\Illuminate\Http\Response::HTTP_NOT_FOUND);

//        5-add another available item to our cart
        $response = $this->postJson('/api/v1/cart/add-item', [
            'product_id' => 2,
            'cart_id' => $cart['id']
        ]);
        $cart = $response->getOriginalContent()['data'];
        $response->assertStatus(200);
        $this->assertEquals(true, $response->getOriginalContent()['status']);
        $this->assertEquals(2, count($cart['cart_items']));
        $this->assertEquals(5500, $cart['payable_price']);

        //6-change item quantity product_id = 2 to 9 which has special prices 2*3500+2500=8500
        $response = $this->postJson('/api/v1/cart/change-quantity', [
            'product_id' => 2,
            'cart_id' => $cart['id'],
            'quantity' => 9,
        ]);
        $cart = $response->getOriginalContent()['data'];
        $response->assertStatus(200);
        $this->assertEquals(true, $response->getOriginalContent()['status']);
        $this->assertEquals(2, count($cart['cart_items']));
       /*-------------------------------------
        9500 for 9 items of product_id=2
        plus 3000 for 1 item of product_id=1
        So 9500+3000=12500
       --------------------------------------*/
        $this->assertEquals(12500, $cart['payable_price']);

//        7-remove product_id=1 from the cart
        $response = $this->deleteJson('/api/v1/cart/remove-item', [
            'product_id' => 1,
            'cart_id' => $cart['id'],
        ]);
        $cart = $response->getOriginalContent()['data'];

        $response->assertStatus(200);
        $this->assertEquals(true, $response->getOriginalContent()['status']);
        $this->assertEquals(1, count($cart['cart_items']));
        $this->assertEquals(1, count($cart['cart_items']));
        /*-------------------------------------
        12500-3000=9500
       --------------------------------------*/
        $this->assertEquals(9500, $cart['payable_price']);

//        8-remove product_id=1 again which is not available in the cart
        $response = $this->deleteJson('/api/v1/cart/remove-item', [
            'product_id' => 1,
            'cart_id' => $cart['id'],
        ]);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
