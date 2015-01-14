<?php

namespace leandrogehlen\querybuilder;


use yii\base\Object;


/**
 * The operator object representation
 *
 * @see http://mistic100.github.io/jQuery-QueryBuilder/#operators
 * @author Leandro Gehlen <leandrogehlen@gmail.com>
 */
class Operator extends Object implements Optionable
{
    use OptionTrait;

    /**
     * @var string	Identifier of the operator
     */
    public $type;

    /**
     * @var int	The number of inputs displayed. Typical values are 0 (is_null & similar operators), 1 (most operators) and 2 (between operator)
     */
    public $acceptValues;

    /**
     * @var array An array containing string, number, datetime.
     */
    public $applyTo;

} 