<?php


namespace leandrogehlen\querybuilder\tests\unit;

use leandrogehlen\querybuilder\Translator;

class TranslatorTest extends TestCase
{

    protected function rulesProvider()
    {
        return [
            [
                ['condition' => "and", 'rules' => [
                    [ 'field' => 'name', 'type' => 'string', 'operator' => 'equal', 'value' => 'joe'],
                    [ 'field' => 'name', 'type' => 'string', 'operator' => 'not_equal', 'value' => 'bruce']
                ]],
                ['(name = :p0 and name <> :p1)', [':p0' => 'joe', ':p1' => 'bruce']]
            ],
            [
                ['condition' => "and", 'rules' => [
                    [ 'field' => 'id', 'type' => 'integer', 'operator' => 'in', 'value' => [1,2,3]],
                    [ 'field' => 'id', 'type' => 'integer', 'operator' => 'not_in', 'value' => [4,5]]
                ]],
                ['(id IN (:p0, :p1, :p2) and id NOT IN (:p3, :p4))', [':p0'=>1, ':p1'=>2, ':p2'=>3, ':p3'=>4, ':p4'=>5]]
            ],
            [
                ['condition' => "and", 'rules' => [
                    [ 'field' => 'id', 'type' => 'integer', 'operator' => 'less', 'value' => 100],
                    [ 'field' => 'id', 'type' => 'integer', 'operator' => 'less_or_equal', 'value' => 50],
                ]],
                ['(id < :p0 and id <= :p1)', [':p0'=>100, ':p1'=>50]]
            ],
            [
                ['condition' => "and", 'rules' => [
                    [ 'field' => 'id', 'type' => 'integer', 'operator' => 'greater', 'value' => 10],
                    [ 'field' => 'id', 'type' => 'integer', 'operator' => 'greater_or_equal', 'value' => 20],
                ]],
                ['(id > :p0 and id >= :p1)', [':p0'=>10, ':p1'=>20]]
            ],
            [
                ['condition' => "and", 'rules' => [
                    [ 'field' => 'date', 'type' => 'date', 'operator' => 'between', 'value' => ['2015-01-01','2015-01-30']],
                ]],
                ['(date BETWEEN :p0 AND :p1)', [':p0'=>'2015-01-01', ':p1'=>'2015-01-30']]
            ],
            [
                ['condition' => "and", 'rules' => [
                    [ 'field' => 'name', 'type' => 'string', 'operator' => 'begins_with', 'value' => 'joe'],
                    [ 'field' => 'name', 'type' => 'string', 'operator' => 'not_begins_with', 'value' => 'bruce'],
                ]],
                ['(name LIKE :p0 and name NOT LIKE :p1)', [':p0'=>'joe%', ':p1'=> 'bruce%']]
            ],
            [
                ['condition' => "and", 'rules' => [
                    [ 'field' => 'name', 'type' => 'string', 'operator' => 'contains', 'value' => 'thomas'],
                    [ 'field' => 'name', 'type' => 'string', 'operator' => 'not_contains', 'value' => 'paul'],
                ]],
                ['(name LIKE :p0 and name NOT LIKE :p1)', [':p0'=>'%thomas%', ':p1'=> '%paul%']]
            ],
            [
                ['condition' => "and", 'rules' => [
                    [ 'field' => 'name', 'type' => 'string', 'operator' => 'ends_with', 'value' => 'brian'],
                    [ 'field' => 'name', 'type' => 'string', 'operator' => 'not_ends_with', 'value' => 'david'],
                ]],
                ['(name LIKE :p0 and name NOT LIKE :p1)', [':p0'=>'%brian', ':p1'=> '%david']]
            ],
            [
                ['condition' => "or", 'rules' => [
                    [ 'field' => 'name', 'type' => 'string', 'operator' => 'is_empty'],
                    [ 'field' => 'name', 'type' => 'string', 'operator' => 'is_not_empty'],
                    [ 'field' => 'name', 'type' => 'string', 'operator' => 'is_null'],
                    [ 'field' => 'name', 'type' => 'string', 'operator' => 'is_not_null'],
                ]],
                ['(name = "" or name <> "" or name IS NULL or name IS NOT NULL)', []]
            ],
            [
                ['condition' => "and", 'rules' => [
                    [ 'field' => 'name', 'type' => 'string', 'operator' => 'begins_with', 'value' => 'kurt'],
                    [ 'field' => 'name', 'type' => 'string', 'operator' => 'ends_with', 'value' => 'cobain'],
                    ['condition' => 'or', 'rules'=>[
                        [ 'field' => 'name', 'type' => 'string', 'operator' => 'equal', 'value' => 'joe'],
                        [ 'field' => 'name', 'type' => 'string', 'operator' => 'equal', 'value' => 'paul'],
                        ['condition' => 'and', 'rules'=>[
                            [ 'field' => 'id', 'type' => 'integer', 'operator' => 'equal', 'value' => 10],
                        ]]
                    ]]
                ]],
                ['(name LIKE :p0 and name LIKE :p1 and (name = :p2 or name = :p3 or (id = :p4)))', [
                    ':p0'=>'kurt%',':p1' =>'%cobain', ':p2' => 'joe', ':p3' => 'paul', ':p4' => 10
                ]]
            ]

        ];
    }


    public function testRules()
    {
        foreach ($this->rulesProvider() as $rule) {
            $translator = new Translator($rule[0]);
            $expected = $rule[1];

            $this->assertEquals($expected[0], $translator->where());

            $params = $translator->params();
            foreach ($expected[1] as $key => $value) {
                $this->assertArrayHasKey($key, $params);
                $this->assertEquals($value, $params[$key]);
            }
        }
    }
} 