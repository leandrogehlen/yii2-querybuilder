jQuery QueryBuilder Extension for Yii 2
=======================================

This is the jQuery QueryBuilder extension for Yii 2. It encapsulates QueryBuilder component in terms of Yii widgets, 
and thus makes using QueryBuilder component in Yii applications extremely easy


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist leandrogehlen/yii2-querybuilder "*"
```

or add

```
"leandrogehlen/yii2-querybuilder": "*"
```

to the require section of your `composer.json` file.

How to use
----------

**View**:

```php

use leandrogehlen\querybuilder\QueryBuilderForm;

<?php QueryBuilderForm::begin([
    'rules' => $rules,
    'builder' => [
        'id' => 'query-builder',
        'filters' => [
            ['id' => 'id', 'label' => 'Id', 'type' => 'integer'],
            ['id' => 'name', 'label' => 'Name', 'type' => 'string'],
            ['id' => 'lastName', 'label' => 'Last Name', 'type' => 'string']
        ]
    ]
 ])?>
 
      <?= Html::submitButton('Apply'); ?>
      <?= Html::resetButton('Reset'); ?>
      
 <?php QueryBuilderForm::end() ?>
```

**Controller**:

```php

use leandrogehlen\querybuilder\Translator;

public function actionIndex()
{
      $query = Customer::find();
      $rules = Json::decode(Yii::$app->request->get('rules'));
      if ($rules) {
          $translator = new Translator($rules);
          $query
            ->andWhere($translator->where())
            ->addParams($translator->params());
      }
      
      $dataProvider = new ActiveDataProvider([
          'query' => $query,
      ]);
    
      return $this->render('index', [
          'dataProvider' => $dataProvider,
          'rules' => $rules
      ]);
}
```


