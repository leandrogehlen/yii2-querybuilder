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
     * @var string Type of the field. Available types are: string, integer, double, date, time and datetime
     */
    public $type;

    /**
     * @var string Optional label of an `<optgroup>` in the filters dropdown.
     */
    public $optgroup;

    /**
     * @var string Type of input used. Available types are text, radio, checkbox and select.
     * It can also be a function which returns the HTML of the said input, this function takes 2 parameters:
     * - $rule is the jQuery <li> element of the rule
     * - filter
     */
    public $input;

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
     * @var array Required for radio and checkboxe inputs. Generally needed for select inputs.
     * Hashmap of options available. Keys are used as values and values are used as labels.
     */
    public $values;

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
     * @var string Object of parameters to pass to the plugin
     */
    public $pluginOptions;

    /**
     * @var yii\web\JsExpression Called before the main [[onValidationError]].
     * Params:
     * - $rule
     * - error
     * - value
     * - filter
     * - operator
     */
    public $onValidationError;

    /**
     * @var yii\web\JsExpression Called after creating the input of a rule, typically at rule addition and filter change
     * Params:
     * - $rule
     * - filter
     */
    public $onAfterCreateRuleInput;

    /**
     * @var yii\web\JsExpression Called after changing the rule operator
     * Params:
     * - $rule
     * - filter
     * - operator
     */
    public $onAfterChangeOperator;

    /**
     * @var yii\web\JsExpression Called after setting the value of the rule in `setRules` function
     * Params:
     * - $rule
     * - value
     * - filter
     * - operator
     */
    public $onAfterSetValue;

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