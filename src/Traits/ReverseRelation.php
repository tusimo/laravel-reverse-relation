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
        //do not set many to many relations cause we will miss some data
        if (in_array(class_basename($this->$relation()), self::$manyMethods)) {
            return;
        }
        if ($reverseRelation = $this->$relation()->getReverse()) {
            if ($reverseRelation = $this->$relation()->getReverse()) {
                if ($value instanceof Model && !$value->relationLoaded($reverseRelation)) {
                    $value->setRelation($reverseRelation, $this->cloneModelWithRelation($this));
                } else {
                    $value->each(function ($model) use ($reverseRelation) {
                        !$model->relationLoaded($reverseRelation) &&
                        $model->setRelation($reverseRelation, $this->cloneModelWithRelation($this));
                    });
                }
            }
        }
    }

    /**
     * use deep copy set reverse relation and we will keep some more relation to it
     * @param Model $model
     * @return mixed
     */
    protected function cloneModelWithRelation(Model $model)
    {
        $copier = new DeepCopy();
        $newModel = $copier->copy($model);
        return $newModel;
    }
}
