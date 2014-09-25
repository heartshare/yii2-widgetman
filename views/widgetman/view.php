<?php

use yii\helpers\Html;

/**
 * @var yii\web\View                        $this
 * @var insolita\widgetman\models\Widgetman $model
 */

$this->title = 'Просмотр виджета - ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => $model::modelTitle(), 'url' => ['index']];
$this->params['breadcrumbs'][] = Html::encode($this->title);
?>
<div class="widgetman-view">

    <h1><?= \insolita\things\helpers\Helper::Fa($this->context->icon, 'lg') . Html::encode($this->title) ?></h1>

    <p>Это примерное отображение содержания виджета! Реальный результат смотрите непосредственно на странице сайта</p>
    <?php
    $class = $model->class;
    echo $class::widget($model->options);

    ?>

</div>
