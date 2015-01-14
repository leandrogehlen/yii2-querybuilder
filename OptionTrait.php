<?php

namespace leandrogehlen\querybuilder;


use yii\helpers\Inflector;

/**
 * OptionTrait implements the method [[toOption()]] for Optionable classes.
 *
 * @author Leandro Gehlen <leandrogehlen@gmail.com>
 */
trait OptionTrait
{

    /**
     * Converts the model into an options array.
     * @return array the array representation of the object
     */
    public function toOptions()
    {
        $options = [];

        foreach ($this as $prop => $value)
        {
            $key = (strpos($prop, "on") !== 0) ? Inflector::underscore($prop) : $prop;

            if ($value instanceof Optionable){
                $value = $value->toOptions();
            } elseif (is_array($value)) {
                foreach ($value as $k => $v) {
                    if ($v instanceof Optionable) {
                        $value[$k] = $v->toOptions();
                    }
                }
            }

            if (!is_array($value) && $value !== null || !empty($value)) {
                $options[$key] = $value;
            }
        }
        return $options;
    }

} 