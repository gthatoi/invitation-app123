<?php

namespace App\Http\Traits;

trait BuildTrait
{
    public static function buildEmptyEntity()
    {
        return static::buildEntityFromArray([]);
    }

    public static function buildEntityFromArray($data)
    {
        return new static($data);
    }
}
