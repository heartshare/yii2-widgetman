<?php

namespace insolita\widgetman\models;

 use insolita\things\components\SActiveRecord;
 use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "vg_widgetman".
 *
 * @property integer $id
 * @property string  $name
 * @property string  $class
 * @property string  $options
 * @property string  $content
 * @property integer $position
 * @property integer $cachetime
 * @property integer $active
 * @property string  $updated
 *
 */
class Widgetman extends SActiveRecord
{
    public static $titledAttribute = 'name';
    public  $gridDefaults = ['name', 'class', 'cachetime', 'active', 'position'];
    public  $ignoredAttributes = ['id', 'content', 'options',];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%widgetman}}';
    }

    public static function modelTitle($type = 'plural')
    {
        $titles = [
            'single' => 'Виджет',
            'plural' => 'Виджеты',
            'rod' => 'Виджета',
            'vin' => 'Виджет'
        ];
        return isset($titles[$type]) ? $titles[$type] : $titles['plural'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'class'], 'required', 'on' => 'create,update'],
            [['options', 'position', 'ord'], 'required', 'on' => 'configure'],
            [['ord', 'cachetime', 'active'], 'integer'],
            [['options', 'content', 'position'], 'string'],
            [['updated'], 'safe'],
            [['position'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100],
            [['class'], 'string', 'max' => 200]
        ];
    }

    public function scenarios()
    {
        return [
            'create' => ['id', 'name', 'class', 'cachetime', 'active', 'updated'],
            'update' => ['id', 'name', 'class', 'cachetime', 'active', 'updated'],
            'copy' => ['name', 'class', 'cachetime', 'active', 'options', 'content', 'position', 'ord'],
            'configure' => ['options', 'content', 'position', 'ord'],
            'toggle' => ['active'],
            'editable' => ['name', 'ord', 'position']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'name' => 'Название',
            'class' => 'Тип',
            'options' => 'Опции',
            'content' => 'Содержание',
            'position' => 'Расположение',
            'cachetime' => 'Время кеширования',
            'active' => 'Активно?',
            'updated' => 'Обновлено',
            'ord' => 'Порядковый номер'
        ];
    }

    public function beforeValidate()
    {
        if ($this->scenario == 'configure') {
            $this->options = Json::encode($this->options);
        }
        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {

        }
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {

        return parent::afterSave($insert, $changedAttributes);
    }


}
