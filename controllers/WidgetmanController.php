<?php

namespace insolita\widgetman\controllers;

use insolita\things\ccactions\ToggleAction;
use dosamigos\editable\EditableAction;
use Yii;
use insolita\widgetman\models\Widgetman;
use insolita\widgetman\models\WidgetmanSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * WidgetmanController implements the CRUD actions for Widgetman model.
 */
class WidgetmanController extends Controller
{
    public $title = '';
    public $icon = 'puzzle-piece';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'create',
                            'update',
                            'view',
                            'toggle',
                            'delete',
                            'mass',
                            'editable',
                            'cacheclean',
                            'copy'
                        ],
                        'roles' => ['widgetman'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['remove'],
                        'roles' => ['admin'],
                    ],
                ]

            ],

        ];
    }

    public function actions()
    {
        $actions = [
            'toggle' => [
                'class' => ToggleAction::className(),
                'modelClass' => Widgetman::className(),
                'scenario' => 'toggle',
                'toggleType'=>ToggleAction::TOGGLE_ANY,
                'onValue' => 1,
                'offValue' => 0
            ],
            'editable' => [
                'class' => EditableAction::className(),
                'modelClass' => Widgetman::className(),
                'scenario' => 'editable',
                'forceCreate' => false
            ],
        ];
        return $actions;
    }

    public function  actionIndex()
    {
        $searchModel = new WidgetmanSearch();
        $dp = $searchModel->search(\Yii::$app->request->getQueryParams());

        return $this->render(
            'index',
            [
                'dataProvider' => $dp,
                'searchModel' => $searchModel,
            ]
        );
    }

    public function actionCreate()
    {
        $model = new Widgetman();
        $model->scenario = "create";
        if (\Yii::$app->request->isPost) {
            if ($model->load(\Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = "update";
        if (\Yii::$app->request->isPost) {
            if ($model->load(\Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        }
        return $this->render('update', ['model' => $model]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $model->options = Json::decode($model->options);

        return $this->render('view', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->delete()) {
            \Yii::$app->session->setFlash('success', \Yii::t('app', 'Запись удалена'));
        } else {
            \Yii::$app->session->setFlash('error', \Yii::t('app', 'Запись не найдена'));
        }
        return $this->redirect(['index']);
    }

    public function actionCacheclean($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            $cc=Yii::$app->getCache()->deleteDirect('@frontend/runtime/cache','','widgetman_' . $model->position . $model->id);
            \Yii::$app->session->setFlash('success', \Yii::t('app', 'Кеш очищен'));
        } else {
            \Yii::$app->session->setFlash('error', \Yii::t('app', 'Кеш не очищен, виджет не найден'));
        }
        return $this->redirect(['index']);
    }

    public function  actionCopy($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            $nmodel = new Widgetman();
            $nmodel->scenario = 'copy';
            $nmodel->setAttributes($model->getAttributes());
            $nmodel->name = 'Копия ' . $model->name;
            $nmodel->save(false);
            \Yii::$app->session->setFlash('success', \Yii::t('app', 'Создана копия виджета ' . $model->name));
            $module = Yii::$app->getModule('widgetman');
            $actionList = $module->getActionList();
            $url = $actionList[$model->class];
            return $this->redirect([$url, 'id' => $nmodel->id]);
        } else {
            \Yii::$app->session->setFlash('error', \Yii::t('app', 'Виджет не найден'));
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Widgetman model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Widgetman the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Widgetman::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
