<?php

namespace App\Traits;


use Illuminate\Database\Eloquent\Casts\Attribute;
use Vinkla\Hashids\Facades\Hashids;

trait HasHashids
{
    protected function hashid(): Attribute
    {
        return Attribute::make(
            get: fn() => Hashids::encode($this->id),
        );
    }
}
