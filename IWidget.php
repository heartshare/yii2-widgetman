<?php
/**
 * Created by PhpStorm.
 * User: Insolita
 * Date: 28.09.14
 * Time: 2:35
 */

namespace insolita\widgetman;


use yii\bootstrap\Widget;

class IWidget extends Widget
{
    const MODE_BOX = 'box';
    const MODE_PANEL = 'panel';
    const MODE_FLAT = 'flat';
    const MODE_INFO = 'info';

    const TYPE_SUCCESS = 'success';
    const TYPE_INFO = 'info';
    const TYPE_PRIMARY = 'primary';
    const TYPE_DEFAULT = 'default';
    const TYPE_DANGER = 'danger';
    const TYPE_WARNING = 'warning';

    public static $modes
        = [
            self::MODE_FLAT => 'Без обрамления', self::MODE_BOX => 'Блок', self::MODE_PANEL => 'Панель',
            self::MODE_INFO => 'ИнфоБлок'
        ];

    public static $types
        = [
            self::TYPE_SUCCESS => 'Зеленый', self::TYPE_INFO => 'Голубой', self::TYPE_PRIMARY => 'Синий',
            self::TYPE_DEFAULT => 'Серый', self::TYPE_DANGER => 'Красный', self::TYPE_WARNING => 'Оранжевый'
        ];

    public $title = '';

    public $icon = '';

    public $text = '';

    public $type = self::TYPE_DEFAULT;

    public $mode = self::MODE_PANEL;

    public function run()
    {
        if ($this->mode == self::MODE_BOX) {
            return $this->renderBox();
        } elseif ($this->mode == self::MODE_PANEL) {
            return $this->renderPanel();
        }elseif ($this->mode == self::MODE_INFO) {
            return $this->renderPanel();
        } else {
            return $this->renderFlat();
        }
    }

    public function renderBox()
    {
        return $this->render('box_tpl',['type' => $this->type,'icon'=>$this->icon ,'title' => $this->title, 'content' => $this->text]);
    }

    public function renderPanel()
    {
        return $this->render('panel_tpl',['type' => $this->type,'icon'=>$this->icon ,'title' => $this->title, 'content' => $this->text]);

    }

    public function renderFlat()
    {
        return $this->render('flat_tpl',['type' => $this->type,'icon'=>$this->icon ,'title' => $this->title, 'content' => $this->text]);
    }
    public function renderInfo()
    {
        return $this->render('info_tpl',['type' => $this->type,'icon'=>$this->icon ,'title' => $this->title, 'content' => $this->text]);
    }

} 