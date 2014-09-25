<?php


/**
 * @var yii\web\View                                       $this
 * @var yii\data\ActiveDataProvider                        $dataProvider
 * @var insolita\widgetman\models\WidgetmanSearch          $searchModel
 * @var insolita\widgetman\controllers\WidgetmanController $context
 * @var insolita\widgetman\WidgetmanModule                 $module
 */
$module = Yii::$app->getModule('widgetman');
$cachetimes = $module->cachetimes;
$widgetList = $module->getWidgetList();
$actionList = $module->getActionList();
$positions = array_merge($module->places, $module->scriptpos);

echo \insolita\supergrid\grid\GridView::widget(
    [
        'id' => 'widgetmangrid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id'],
            [
                'attribute' => 'name',
                'filter' => true,
                'class' => \dosamigos\grid\EditableColumn::className(),
                'url' => ['/widgetman/widgetman/editable'],
                'editableOptions' => ['emptytext' => 'не задано', 'placement' => 'right']
            ],
            [
                'attribute' => 'class',
                'value' => function ($data) use (
                        $widgetList
                    ) {
                        return $widgetList[$data->class];
                    },
                'filter' => $widgetList
            ],
            [
                'attribute' => 'position',
                'value' => function ($data) use ($positions) {
                        return $data->position ? $positions[$data->position] : 'Не задано';
                    },
                'filter' => $positions,
                'class' => \dosamigos\grid\EditableColumn::className(),
                'url' => ['/widgetman/widgetman/editable'],
                'type' => 'select',
                'editableOptions' => function ($data) use ($module) {
                        $places = $module->getWidgetPlaces($data->class);
                        return [
                            'emptytext' => 'не задано',
                            'source' => \yii\helpers\Json::encode($places)
                        ];
                    }
            ],
            [
                'attribute' => 'ord',
                'class' => \dosamigos\grid\EditableColumn::className(),
                'url' => ['/widgetman/widgetman/editable'],
                'editableOptions' => ['emptytext' => 'не задано']
            ],
            [
                'attribute' => 'cachetime',
                'value' => function ($data) use (
                        $cachetimes
                    ) {
                        return $cachetimes[$data->cachetime];
                    },
                'class' => \dosamigos\grid\EditableColumn::className(),
                'url' => ['/widgetman/widgetman/editable'],
                'type' => 'select',
                'editableOptions' => [
                    'emptytext' => 'не задано',
                    'source' => \yii\helpers\Json::encode($cachetimes)
                ]
            ],
            [
                'attribute' => 'active',
                'format' => 'raw',
                'class' => \dosamigos\grid\ToggleColumn::className(),
                'afterToggle' => 'function(r, data){if(r){jQuery.pjax.reload("#gridpjax")};}'
            ],
            [
                'attribute' => 'updated',
                'format' => 'datetime',
                'label' => \insolita\things\helpers\Helper::Fa(
                        'clock-o',
                        2
                    )
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{configure}&nbsp;{copy}&nbsp;{view}&nbsp;{cacheclean}&nbsp;{update}&nbsp;{delete}',
                /*'buttons'=>[
                    'cacheclean' => function ($url, $model) {
                            return  Html::tag('li',Html::a('Очистить кеш',\yii\helpers\Url::toRoute(
                                                                                  ['cacheclean', 'id' => $model->id]
                                    )));

                        },
                    'configure' => function ($url, $model) use ($actionList) {
                            $url = $actionList[$model->class];
                            return  Html::tag('li',Html::a('Настройка виджета',\yii\helpers\Url::toRoute(
                                                                                               [$url, 'id' => $model->id]
                                    )));
                        },
                    'view' => function ($url, $model) {
                            return !(empty($model->position)) ?  Html::tag('li',Html::a('Просмотр виджета',\yii\helpers\Url::toRoute(
                                                                                               ['view', 'id' => $model->id]
                                    ))):'';
                        },
                    'update' => function ($url, $model) {
                            return !(empty($model->position)) ?  Html::tag('li',Html::a('Редактирование',\yii\helpers\Url::toRoute(
                                                                                                                           ['update', 'id' => $model->id]
                                    ))):'';
                        },
                    'delete' => function ($url, $model) {
                            return !(empty($model->position)) ?  Html::tag('li',Html::a('Удалить',\yii\helpers\Url::toRoute(
                                                                                                                         ['delete', 'id' => $model->id]
                                    ))):'';
                        },
                    'copy' => function ($url, $model) {
                            return !(empty($model->position)) ?  Html::tag('li',Html::a('Сделать копию',\yii\helpers\Url::toRoute(
                                                                                                                  ['copy', 'id' => $model->id]
                                    ))):'';
                        },
                ]*/
                'buttons' => [
                    'cacheclean' => function ($url, $model) {
                            return \yii\bootstrap\Button::widget(
                                [
                                    'encodeLabel' => false,
                                    'label' => \insolita\things\helpers\Helper::Fa('eraser'),
                                    'tagName' => 'a',
                                    'options' => [
                                        'title' => 'Очистить кеш',
                                        'class' => 'btn btn-sm btn-warning',
                                        'data-pjax' => 0,
                                        'href' => \yii\helpers\Url::toRoute(
                                                ['cacheclean', 'id' => $model->id]
                                            ),
                                    ]
                                ]
                            );
                        },
                    'configure' => function ($url, $model) use ($actionList) {
                            $url = $actionList[$model->class];
                            return !(empty($url)) ? \yii\bootstrap\Button::widget(
                                [
                                    'encodeLabel' => false,
                                    'label' => \insolita\things\helpers\Helper::Fa(
                                            'cogs'
                                        ),
                                    'tagName' => 'a',
                                    'options' => [
                                        'title' => 'Настройка виджета',
                                        'class' => 'btn btn-sm btn-info',
                                        'data-pjax' => 0,
                                        'href' => \yii\helpers\Url::toRoute(
                                                [$url, 'id' => $model->id]
                                            ),
                                    ]
                                ]
                            ) : '';
                        },
                    'view' => function ($url, $model) {
                            return !(empty($model->position)) ? \yii\bootstrap\Button::widget(
                                [
                                    'encodeLabel' => false,
                                    'label' => \insolita\things\helpers\Helper::Fa(
                                            'eye'
                                        ),
                                    'tagName' => 'a',
                                    'options' => [
                                        'title' => 'Просмотр виджета',
                                        'class' => 'btn btn-sm btn-default',
                                        'data-pjax' => 0,
                                        'target' => '_blank',
                                        'href' => \yii\helpers\Url::toRoute(
                                                ['view', 'id' => $model->id]
                                            ),
                                    ]
                                ]
                            ) : '';
                        },
                    'copy' => function ($url, $model) {
                            return !(empty($model->position)) ? \yii\bootstrap\Button::widget(
                                [
                                    'encodeLabel' => false,
                                    'label' => \insolita\things\helpers\Helper::Fa(
                                            'files-o'
                                        ),
                                    'tagName' => 'a',
                                    'options' => [
                                        'title' => 'Сделать копию',
                                        'class' => 'btn btn-sm btn-default',
                                        'data-pjax' => 0,
                                        'target' => '_blank',
                                        'href' => \yii\helpers\Url::toRoute(
                                                ['copy', 'id' => $model->id]
                                            ),
                                    ]
                                ]
                            ) : '';
                        },
                    'update' => function ($url, $model) {
                            return \yii\bootstrap\Button::widget(
                                [
                                    'encodeLabel' => false,
                                    'label' => \insolita\things\helpers\Helper::Fa('pencil'),
                                    'tagName' => 'a',
                                    'options' => [
                                        'title' => 'Редактировать',
                                        'class' => 'btn btn-sm btn-default',
                                        'data-pjax' => 0,
                                        'href' => \yii\helpers\Url::toRoute(
                                                ['update', 'id' => $model->id]
                                            ),
                                    ]
                                ]
                            );
                        },
                    'delete' => function ($url, $model) {
                            return \yii\bootstrap\Button::widget(
                                [
                                    'encodeLabel' => false,
                                    'label' => \insolita\things\helpers\Helper::Fa('trash-o'),
                                    'tagName' => 'a',
                                    'options' => [
                                        'class' => 'btn btn-sm btn-danger',
                                        'data-deleter' => 1,
                                        'data-pjax' => 0,
                                        'data-confirm' => 'Вы уверены что хотите совершить это действие?',
                                        'data-method' => 'post',
                                        'pjaxtarget' => '#gridpjax',
                                        'href' => \yii\helpers\Url::toRoute(
                                                ['delete', 'id' => $model->id]
                                            )
                                    ]
                                ]
                            );
                        }
                ]
            ]
        ],
    ]
);?>