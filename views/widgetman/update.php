<?php

use yii\helpers\Html;

/**
 * @var yii\web\View                        $this
 * @var insolita\widgetman\models\Widgetman $model
 */

$this->title = 'Редактирование "' . $model->{$model::$titledAttribute} . '"';
$this->params['breadcrumbs'][] = ['label' => $model::modelTitle(), 'url' => ['index']];
$this->params['breadcrumbs'][] = Html::encode($this->title);
?>
<div class="widgetman-update">

    <h1><?= \insolita\things\helpers\Helper::Fa($this->context->icon, 'lg') . Html::encode($this->title) ?></h1>

    <?=
    $this->render(
        '_form',
        [
            'model' => $model,
        ]
    ) ?>

</div>
