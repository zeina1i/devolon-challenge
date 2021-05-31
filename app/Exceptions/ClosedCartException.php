<?php


namespace App\Exceptions;


use Throwable;

class ClosedCartException extends \Exception
{
    public function __construct()
    {
        parent::__construct("you are not allowed to do an action on closed cart");
    }
}