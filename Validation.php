<?php

namespace leandrogehlen\querybuilder;


use yii\base\BaseObject;

/**
 * The validation object representation
 *
 * @see http://mistic100.github.io/jQuery-QueryBuilder/#validation
 * @author Leandro Gehlen <leandrogehlen@gmail.com>
 */
class Validation extends BaseObject implements Optionable
{
    use OptionTrait;

    /**
     * @var string Performs validation according to the specified format
     * - For `date`, `time`, `datetime`: a valid MomentJS string format
     * - For `string`: a regular expression (plain or RegExp object)
     */
    public $format;

    /**
     * @var integer|float|string upper limit of the number
     * - For `integer`, `double`: maximum value
     * - For `date`, `time`, `datetime`: maximum value, respecting format
     * - For `string`: maximum length
     */
    public $max;
    /**
     * @var integer|float|string lower limit of the number
     * - For `integer`, `double`: minimum value
     * - For `date`, `time`, `datetime`: minimum value, respecting format
     * - For `string`: minimum length
     */
    public $min;

    /**
     * @var integer|double The step value
     * For double you should always provide this value in order to pass the browser validation on number inputs
     */
    public $step;

    /**
     * @var yii\web\JsExpression A function used to perform the validation.
     * If provided, the default validation will not be performed. It must returns true if the value is valid
     * or an error string otherwise. It takes 4 parameters:
     * value
     * filter
     * operator
     * $rule, the jQuery <li> element of the rule
     */
    public $callback;

} 