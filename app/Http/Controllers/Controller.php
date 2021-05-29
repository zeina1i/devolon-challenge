<?php

namespace App\Http\Controllers;

use App\Model\Cart;
use App\Model\CartItem;
use App\Service\BasketService;
use App\Service\PricingService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $pricingService;

    public function __construct(
        PricingService $pricingService
    )
    {
        $this->pricingService = $pricingService;
    }

    public function getValidationErrorsString(array $errors) : string
    {
        return implode(', ', $errors);
    }
}
