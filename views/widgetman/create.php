<?php

use yii\helpers\Html;

/**
 * @var yii\web\View                        $this
 * @var insolita\widgetman\models\Widgetman $model
 */

$this->title = 'Добавить ' . $model::modelTitle('vin');
$this->params['breadcrumbs'][] = ['label' => $model::modelTitle(), 'url' => ['index']];
$this->params['breadcrumbs'][] = Html::encode($this->title);
?>
<div class="widgetman-create">
    <div class="page-header">
        <h1><?= \insolita\things\helpers\Helper::Fa($this->context->icon, 'lg') . Html::encode($this->title) ?></h1>
    </div>
    <?=
    $this->render(
        '_form',
        [
            'model' => $model,
        ]
    ) ?>

</div>
