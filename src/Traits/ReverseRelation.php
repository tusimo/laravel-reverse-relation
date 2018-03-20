<?php

namespace Tusimo\ReverseRelation\Traits;

use DeepCopy\DeepCopy;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait ReverseRelation
 * @package Tusimo\ReverseEloquent\Traits
 */
trait ReverseRelation
{
    /**
     * Set the specific relationship in the model.
     *
     * @param  string  $relation
     * @param  mixed  $value
     * @return $this
     */
    public function setRelation($relation, $value)
    {
        $this->relations[$relation] = $value;
        $this->setReverseRelations($relation, $value);

        return $this;
    }

    /**
     * detect if we need set reverse relation
     * @param $relation
     * @param $value
     */
    protected function setReverseRelations($relation, $value)
    {
        if (!$value) {
            return;
        }
        if ($reverseRelation = $this->$relation()->getReverse()) {
            if ($value instanceof Model) {
                $value->setRelation($reverseRelation, $this->cloneModelWithoutRelation($this));
            } else {
                $value->each(function ($model) use ($reverseRelation) {
                    $model->setRelation($reverseRelation, $this->cloneModelWithoutRelation($this));
                });
            }
        }
    }

    /**
     * use deep copy set reverse relation and we will keep some more relation to it
     * @param Model $model
     * @return mixed
     */
    protected function cloneModelWithoutRelation(Model $model)
    {
        $copier = new DeepCopy();
        $newModel = $copier->copy($model);
        return $newModel;
    }
}
