<?php
use Yii;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var insolita\widgetman\models\Widgetman $model
 * @var insolita\widgetman\WidgetmanModule $module
 * @var yii\widgets\ActiveForm $form
 */

$module = Yii::$app->getModule('widgetman');
?>

<div class="widgetman-form">

    <?php     $form = ActiveForm::begin(
        [
            'type' => ActiveForm::TYPE_VERTICAL,
            'id' => 'modalform',
            'enableClientValidation' => false,
            'enableAjaxValidation' => false,
            'action' => ($model->isNewRecord
                    ? \yii\helpers\Url::to(['create'])
                    : \yii\helpers\Url::to(
                        ['update', 'id' => $model->{$model->getPk()}]
                    ))
        ]
    );
    ?>

    <?php  \insolita\supergrid\panels\Panel::begin(
        [
            'on_lookmod'=>['newpage'],
            'title' => ($model->isNewRecord
                    ?
                    \insolita\things\helpers\Helper::Fa('plus-circle', 'lg') . ' Добавление'
                    :
                    \insolita\things\helpers\Helper::Fa('pencil-square-o', 'lg') . ' Редактирование "'
                    . $model->{$model::$titledAttribute} . '"'),
            'footer' => '<span class="pull-right">'
                . Html::submitButton(
                    \insolita\things\helpers\Helper::Fa('check-circle', 'lg')
                    . 'Сохранить',
                    [
                        'class' => 'btn btn-success',
                        'title' => 'Сохранить запись (Enter)',
                        'id' => 'dirsubmit_' . ($model->isNewRecord ? 'addmodal' : 'updmodal')
                    ]
                )
                . '</span>'
                . Html::a(
                    \insolita\things\helpers\Helper::Fa('times-circle', 'lg')
                    . 'Отмена',
                    ['index'],
                    [
                        'class' => 'btn btn-danger',
                        'title' => 'Отмена (Esc)',
                        'id' => 'cancel_' . ($model->isNewRecord ? 'addmodal' : 'updmodal')
                    ]
                ),

        ]
    );

    echo $form->errorSummary([$model]);
    ?>
    <div id="resp_success" style="display: none" class="alert alert-success"></div>
    <div id="resp_error" class="alert alert-danger" style="display: none"></div>
    <?php echo ($model->isNewRecord)
        ? $form->field($model, 'class')->dropDownList($module->getWidgetList())
        : $form->field($model, 'class')->textInput(
            ['value' => $module->getWidgetList($model->class), 'disabled' => true]
        );?>
    <?php echo $form->field($model, 'name')->textInput(['placeholder' => ' Название...', 'maxlength' => 100])->hint(
        'Условное название, при отображении на сайте использваться не будет'
    ); ?>
    <?php echo $form->field($model, 'cachetime')->dropDownList($module->cachetimes); ?>

    <?php
    \insolita\supergrid\panels\Panel::end();
    ?>

    <?php ActiveForm::end(); ?>

</div>
