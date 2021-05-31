<?php

namespace leandrogehlen\querybuilder;

use Yii;
use yii\helpers\StringHelper;
use yii\web\AssetBundle;

/**
 * This asset bundle provides the [jquery QueryBuilder library](https://github.com/mistic100/jQuery-QueryBuilder)
 *
 * @author Leandro Gehlen <leandrogehlen@gmail.com>
 */
class QueryBuilderAsset extends AssetBundle {

    public $sourcePath = '@bower/jquery-querybuilder/dist';

    public $js = [
        'js/query-builder.standalone.min.js',
    ];

    public $css = [
        'css/query-builder.default.min.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'leandrogehlen\querybuilder\BootstrapAsset',
    ];

    /**
     * check wether this app has bsVersion and use bs4 as default bsVersion
     * because many yii2 user use kartik-v ekstension, we follow his conventions in set the bsVersion
     * 
     * @return bool
     * @author @hoaah (hoaaah.arief@gmail.com)
     */
    public function isBs4()
    {
        if(isset(Yii::$app->params['bsVersion'])){
            return (StringHelper::startsWith(Yii::$app->params['bsVersion'], '4'));
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->isBs4()) {
            $this->depends = [
                'yii\web\JqueryAsset',
                'yii\bootstrap4\BootstrapAsset'
            ];
        }

        parent::init();
    }
} 
