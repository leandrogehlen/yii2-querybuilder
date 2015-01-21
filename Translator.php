<?php

namespace leandrogehlen\querybuilder;

use yii\base\Object;
use yii\helpers\ArrayHelper;

/**
 * Translator is used to build WHERE clauses from rules configuration
 *
 * The typical usage of Translator is as follows,
 *
 * ```php
 * public function actionIndex()
 * {
 *     $query = Customer::find();
 *     $rules = Yii::$app->request->post('rules');
 *
 *     if ($rules) {
 *         $translator = new Translator(Json::decode($rules));
 *         $query->andWhere($translator->where())
 *               ->addParams($translator->where());
 *     }
 *
 *     $dataProvider = new ActiveDataProvider([
 *         'query' => $query,
 *     ]);
 *
 *     return $this->render('index', [
 *         'dataProvider' => $dataProvider,
 *     ]);
 * }
 * ```
 * @author Leandro Gehlen <leandrogehlen@gmail.com>
 */
class Translator extends Object
{
    const KEY_RULES = 'rules';
    const KEY_OPERATOR = 'operator';
    const KEY_CONDITION = 'condition';
    const KEY_FIELD = 'field';
    const KEY_VALUE = 'value';

    private $_where;
    private $_params = [];
    private $_operators;

    /**
     * Constructors.
     * @param array $data Rules configuraion
     * @param array $config the configuration array to be applied to this object.
     */
    public function __construct($data, $config = [])
    {
        parent::__construct($config);
        $this->_where = $this->buildWhere($data);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->_operators = [
            'equal' =>            '= ?',
            'not_equal' =>        '<> ?',
            'in' =>               ['op' => 'IN (?)',     'list' => true, 'sep' => ', ' ],
            'not_in' =>           ['op' => 'NOT IN (?)', 'list' => true, 'sep' => ', '],
            'less' =>             '< ?',
            'less_or_equal' =>    '<= ?',
            'greater' =>          '> ?',
            'greater_or_equal' => '>= ?',
            'between' =>          ['op' => 'BETWEEN ?',   'list' => true, 'sep' => ' AND '],
            'begins_with' =>      ['op' => 'LIKE ?',     'fn' => function($value){ return "$value%"; } ],
            'not_begins_with' =>  ['op' => 'NOT LIKE ?', 'fn' => function($value){ return "$value%"; } ],
            'contains' =>         ['op' => 'LIKE ?',     'fn' => function($value){ return "%$value%"; } ],
            'not_contains' =>     ['op' => 'NOT LIKE ?', 'fn' => function($value){ return "%$value%"; } ],
            'ends_with' =>        ['op' => 'LIKE ?',     'fn' => function($value){ return "%$value"; } ],
            'not_ends_with' =>    ['op' => 'NOT LIKE ?', 'fn' => function($value){ return "%$value"; } ],
            'is_empty' =>         '= ""',
            'is_not_empty' =>     '<> ""',
            'is_null' =>          'IS NULL',
            'is_not_null' =>      'IS NOT NULL'
        ];
    }


    /**
     * Encodes filter rule into SQL condition
     * @param string $field field name
     * @param string|array $type operator type
     * @param string|array $params query parameters
     * @return string encoded rule
     */
    protected function encodeRule($field, $type, $params)
    {
        $pattern = $this->_operators[$type];
        $keys = array_keys($params);

        if (is_string($pattern)) {
            $replacement = !empty($keys) ? $keys[0] : null;
        } else {
            $op = ArrayHelper::getValue($pattern, 'op');
            $list = ArrayHelper::getValue($pattern, 'list');
            if ($list){
                $sep = ArrayHelper::getValue($pattern, 'sep');
                $replacement = implode($sep, $keys);
            } else {
                $fn = ArrayHelper::getValue($pattern, 'fn');
                $replacement = key($params);
                $params[$replacement] = call_user_func($fn, $params[$replacement]);
            }
            $pattern = $op;
        }

        $this->_params = array_merge($this->_params, $params);
        return $field . " " . ($replacement ? str_replace("?", $replacement, $pattern) : $pattern);
    }

    /**
     * @param array $data rules configuration
     * @return string the WHERE clause
     */
    protected function buildWhere($data)
    {
        $where = [];
        $condition = " " . $data[self::KEY_CONDITION] . " ";

        foreach ($data[self::KEY_RULES] as $rule) {

            if (isset($rule['condition'])) {
                $where[] = $this->buildWhere($rule);
            } else {
                $params = [];
                $oper = $rule[self::KEY_OPERATOR];
                $field = $rule[self::KEY_FIELD];
                $value = ArrayHelper::getValue($rule, self::KEY_VALUE);

                if ($value !== null) {
                    $i = count($this->_params);

                    if (!is_array($value)) {
                        $value = [$value];
                    }

                    foreach ($value as $v) {
                        $params[":p$i"] = $v;
                        $i++;
                    }
                }

                $where[] = $this->encodeRule($field, $oper, $params);
            }

        }

        return "(" . implode($condition, $where) . ")";
    }

    /**
     * Returns query WHERE condition.
     * @return string
     */
    public function where()
    {
        return $this->_where;
    }

    /**
     * Returns the parameters to be bound to the query.
     * @return array
     */
    public function params()
    {
        return $this->_params;
    }
} 