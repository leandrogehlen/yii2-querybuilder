<?php

namespace leandrogehlen\querybuilder;

/**
 * QueryBuilder renders a jQuery QueryBuilder component.
 *
 * @see http://mistic100.github.io/jQuery-QueryBuilder/
 * @author Leandro Gehlen <leandrogehlen@gmail.com>
 */
class QueryBuilder extends \soluto\plugin\Widget {

    /**
     * @inheridoc
     */
    public $pluginName = 'queryBuilder';

    /**
     * @inheritdoc
     */
    protected function assets()
    {
        return [
            QueryBuilderAsset::className()
        ];
    }

}
