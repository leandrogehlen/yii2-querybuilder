<?php

namespace leandrogehlen\querybuilder;


use yii\base\InvalidConfigException;
use yii\base\Widget;
use Yii;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Json;


/**
 * QueryBuilderForm renders a form for to submit rule information.
 *
 * This form renders hidden input with name defined into [[rulesParam]].
 * The hidden input will be used to send JSON rules into string format.
 *
 * The typical usage of QueryBuilderForm is as follows,
 *
 * ```php
 * <?php QueryBuilderForm::begin([
 *     'rules' => $rules,
 *     'builder' => [
 *         'id' => 'query-builder',
 *         'filters' => [
 *             ['id' => 'id', 'label' => 'Id', 'type' => 'integer'],
 *             ['id' => 'name', 'label' => 'Name', 'type' => 'string'],
 *             ['id' => 'lastName', 'label' => 'Last Name', 'type' => 'string']
 *         ]
 *     ]
 * ])?>
 *
 *     <?= Html::submitButton('Apply'); ?>
 *
 * <?php QueryBuilderForm::end() ?>
 * ```
 *
 * @author Leandro Gehlen <leandrogehlen@gmail.com>
 */
class QueryBuilderForm extends Widget
{
    /**
     * @param array|string $action the form action URL. This parameter will be processed by [[\yii\helpers\Url::to()]].
     * @see method for specifying the HTTP method for this form.
     */
    public $action = [''];

    /**
     * @var string the form submission method. This should be either 'post' or 'get'. Defaults to 'get'.
     *
     * When you set this to 'get' you may see the url parameters repeated on each request.
     * This is because the default value of [[action]] is set to be the current request url and each submit
     * will add new parameters instead of replacing existing ones.
     */
    public $method = 'get';

    /**
     * @var array the HTML attributes (name-value pairs) for the form tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];

    /**
     * @var string the hidden input name that will be used to send JSON rules into string format
     */
    public $rulesParam = 'rules';

    /**
     * @var array|QueryBuilder QueryBuilder column configuration.
     * For example,
     *
     * ```php
     * <?= QueryBuilderForm::widget([
     *    'builder' => [
     *        'id' => 'query-builder',
     *        'filters' => [
     *            ['id' => 'id', 'label' => 'Id', 'type' => 'integer'],
     *            ['id' => 'name', 'label' => 'Name', 'type' => 'string'],
     *            ['id' => 'lastName', 'label' => 'Last Name', 'type' => 'string']
     *        ]
     *    ]
     *]) ?>
     * ```
     */
    public $builder;

    /**
     * @var string JSON rules representation into array format
     */
    public $rules;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (is_array($this->builder)) {
            $this->builder = Yii::createObject(array_merge([
                    'class' => QueryBuilder::className()
                ], $this->builder)
            );
        }

        if (!$this->builder instanceof QueryBuilder) {
            throw new InvalidConfigException('The "builder" property must be instance of "QueryBuilder');
        }

        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        echo $this->builder->run();
        echo Html::beginForm($this->action, $this->method, $this->options);
        echo Html::hiddenInput($this->rulesParam);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        echo Html::endForm();

        $id = $this->options['id'];
        $builderId = $this->builder->getId();
        $view = $this->getView();

        if ($this->rules) {
            $rules = Json::encode($this->rules);
            $view->registerJs("$('#{$builderId}').queryBuilder('setRules', {$rules});");
        }

        $frm = Inflector::variablize("frm-$id-querybuilder");
        $btn = Inflector::variablize("btn-$id-querybuilder-reset");

        $view->registerJs("var $frm = $('#{$id}');");
        $view->registerJs(<<<JS
    var $btn = {$frm}.find('button:reset:first');
    if ($btn.length){
        $btn.on('click', function(){
            $('#{$builderId}').queryBuilder('reset');
        });
    }
JS
        );

        $view->registerJs(<<<JS
{$frm}.on('submit', function(){
    var rules = $('#{$builderId}').queryBuilder('getRules');
    if ($.isEmptyObject(rules)) {
        return false;
    } else {
        var input = $(this).find("input[name='{$this->rulesParam}']:first");
        input.val(JSON.stringify(rules));
    }
});
JS
        );
    }
}
