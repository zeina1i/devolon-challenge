<?php


namespace App\Exceptions;


class EntityNotFoundException extends \Exception
{
    public function __construct(string $entity, int $id)
    {
        parent::__construct(sprintf("entity %s with id %s is not found", $entity, $id));
    }
}