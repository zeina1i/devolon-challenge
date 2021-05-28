<?php


namespace App\Exceptions;


class EntityExistsException extends \Exception
{
    public function __construct(string $entity, int $id)
    {
        parent::__construct(sprintf("entity %s with id %s exists", $entity, $id));
    }
}