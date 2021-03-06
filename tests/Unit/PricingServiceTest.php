<?php


namespace Tests\Unit;


use App\Model\Product;
use App\Model\SpecialPrice;
use App\Service\DTO\PricingRuleDTO;
use App\Service\PricingService;
use PHPUnit\Framework\TestCase;

class PricingServiceTest extends TestCase
{
    /** @var PricingService $pricingService */
    private $pricingService;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->pricingService = new PricingService(new Product(), new SpecialPrice());

    }

    public function testCalculate()
    {
        $priceArray = $this->pricingService->calculate(1, 5, [
            1 => new PricingRuleDTO(1, 1, 1000),
            2 => new PricingRuleDTO(1, 2, 1500),
            7 => new PricingRuleDTO(1, 7, 5000),
            10 => new PricingRuleDTO(1, 10, 5000),
        ]);

//        Total price: 2*1500+1000=4000
        $this->assertEquals(4000, $priceArray[0]);
    }
}