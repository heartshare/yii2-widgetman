<?php

namespace insolita\widgetman\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use insolita\widgetman\models\Widgetman;

/**
 * WidgetmanSearch represents the model behind the search form about `insolita\widgetman\models\Widgetman`.
 */
class WidgetmanSearch extends Widgetman
{
    public function rules()
    {
        return [
            [['id', 'cachetime', 'active'], 'integer'],
            [['position', 'class'], 'string'],
            [['name', 'class', 'options', 'content', 'updated'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    public function behaviors(){
        return [
            'customizer'=>[
                'class'=>'insolita\supergrid\behaviors\CustomizeModelBehavior',
                'scenarios'=>['default']
            ]
        ];
    }

    public function search($params)
    {
        $query = Widgetman::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $this->pageSize,
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(
            [
                'id' => $this->id,
                'position' => $this->position,
                'cachetime' => $this->cachetime,
                'active' => $this->active,
                'updated' => $this->updated,
            ]
        );

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'class', $this->class]);

        return $dataProvider;
    }
}
