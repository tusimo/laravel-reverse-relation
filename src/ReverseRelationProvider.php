<?php

namespace Tusimo\ReverseRelation;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class ReverseRelationProvider extends ServiceProvider
{
    public function register()
    {
        Relation::macro('withReverse', function ($relation) {
            $this->reverseRelation = $relation;
            return $this;
        });
        Relation::macro('getReverse', function () {
            return $this->reverseRelation ?? null;
        });
    }
}
