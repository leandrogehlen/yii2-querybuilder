<?php

namespace leandrogehlen\querybuilder;

use Yii;
use yii\base\Object;

/**
 * The filter object representation
 *
 * @see http://mistic100.github.io/jQuery-QueryBuilder/#filters
 * @author Leandro Gehlen <leandrogehlen@gmail.com>
 */
class Filter extends Object implements Optionable {

    use OptionTrait;

    /**
     * @var string Unique identifier of the filter
     */
    public $id;

    /**
     * @var string Field used by the filter, multiple filters can use the same field
     */
    public $field;

    /**
     * @var string Label used to display the filter
     */
    public $label;

    /**
     * @var string Optional label of an `<optgroup>` in the filters dropdown.
     */
    public $optgroup;

    /**
     * @var string Type of the field. Available types are: string, integer, double, date, time and datetime
     */
    public $type;

    /**
     * @var string Type of input used. Available types are text, radio, checkbox and select.
     * It can also be a function which returns the HTML of the said input, this function takes 2 parameters:
     * - $rule is the jQuery <li> element of the rule
     * - filter
     */
    public $input;

    /**
     * @var array Required for radio and checkbox inputs. Generally needed for select inputs.
     * Hashmap of options available. Keys are used as values and values are used as labels.
     */
    public $values;

    /**
     * @var mixed The initial value
     */
    public $defaultValue;

    /**
     * @var int Only for text and textarea inputs: horizontal size of the input
     */
    public $size;

    /**
     * @var int Only for textarea inputs: vertical size of the input
     */
    public $rows;

    /**
     * @var bool Only for select inputs: accept multiple values
     */
    public $multiple;

    /**
     * @var string Only for text inputs: placeholder to display (uses HTML5 feature)
     */
    public $placeholder;

    /**
     * @var bool Only for radio and checkbox inputs: display inputs vertically on not horizontally
     */
    public $vertical;

    /**
     * @var array Object of options for rule validation.
     */
    public $validation;

    /**
     * @var array Array of operators types to use for this filter.
     * If empty the filter will use all applicable operators
     */
    public $operators;

    /**
     * @var string Name of a jQuery plugin to apply on the input
     */
    public $plugin;

    /**
     * @var array Object of parameters to pass to the plugin
     */
    public $pluginConfig;

    /**
     * @var array Additionnal data not use by QueryBuilder but that will be added to the output rules object.
     * Use this to store any functional data you need.
     */
    public $data;

    /**
     * @var yii\web\JsExpression Called before the main [[onValidationError]].
     * Params:
     * - rule
     * - value
     */
    public $valueSetter;

    /**
     * @var yii\web\JsExpression Called before the main [[onValidationError]].
     * Params:
     * - rule
     */
    public $valueGetter;

    /**
     * @var yii\web\JsExpression Modifier applied to each value in `getRules` function
     * Params:
     * - $rule
     * - value
     * - filter
     * - operator
     */
    public $valueParser;


    /**
     * This method will instantiate [[validation]] object.
     */
    public function init()
    {
        if ($this->validation !== null) {
            $this->validation['class'] = Validation::className();
            $this->validation = Yii::createObject($this->validation);
        }
    }

} 