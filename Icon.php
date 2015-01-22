<?php

namespace leandrogehlen\querybuilder;


use yii\base\Object;

/**
 * The icon object representation
 *
 * @see http://mistic100.github.io/jQuery-QueryBuilder/#icons
 * @author Leandro Gehlen <leandrogehlen@gmail.com>
 */
class Icon extends Object implements Optionable
{
    use OptionTrait;

    /**
     * @var string Add group icon
     */
    public $addGroup;

    /**
     * @var string Add rule icon
     */
    public $addRule;

    /**
     * @var string Remove group icon
     */
    public $removeGroup;

    /**
     * @var string Remove rule icon
     */
    public $removeRule;

    /**
     * @var string Error icon
     */
    public $error;

} 