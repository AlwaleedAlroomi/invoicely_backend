<?php

namespace App\Traits;


use Illuminate\Database\Eloquent\Casts\Attribute;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Log;

trait HasHashids
{
    protected function hashid(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->id ? Hashids::encode($this->id) : null,
        );
    }

    public function resolveRouteBinding($value, $field = null)
    {

        $decode = Hashids::decode($value);

        if (empty($decode)) {
            abort(404, 'Resource not found');
        }

        return $this->where('id', $decode[0])->firstOrFail();
    }
}
