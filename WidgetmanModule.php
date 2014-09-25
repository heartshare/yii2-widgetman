<?php
/**
 * Created by PhpStorm.
 * User: Insolita
 * Date: 24.08.14
 * Time: 19:22
 */

namespace insolita\widgetman;


 use insolita\things\helpers\Helper;
use insolita\widgetman\models\Widgetman;
use yii\base\InvalidConfigException;
use yii\base\Module;
use yii\caching\DbDependency;
 use yii\helpers\Json;

class WidgetmanModule extends Module
{
    public $controllerNamespace = 'insolita\widgetman\controllers';
    public $defaultRoute = 'widgetman/index';

    public $places
        = [
            'top' => 'Шапка сайта',
            'beforecontent' => 'Перед контентом - Весь сайт',
            'beforecontent_main' => 'Перед контентом - Главная',
            'beforecontent_page' => 'Перед контентом - Страницы',
            'beforecontent_news' => 'Перед контентом - Новости',
            'beforecontent_art' => 'Перед контентом - Статьи',
            'beforecontent_gallery' => 'Перед контентом - Альбомы',
            'aftercontent' => 'После контента - Весь сайт',
            'aftercontent_main' => 'После контента - Главная',
            'aftercontent_page' => 'После контента - Страницы',
            'aftercontent_news' => 'После контента - Новости',
            'aftercontent_art' => 'После контента - Статьи',
            'aftercontent_gallery' => 'После контента - Альбомы',
            'leftcol' => 'Левая колонка - Весь сайт',
            'leftcol_main' => 'Левая колонка - Главная',
            'leftcol_page' => 'Левая колонка - Страницы',
            'leftcol_news' => 'Левая колонка - Новости',
            'leftcol_art' => 'Левая колонка - Статьи',
            'leftcol_gallery' => 'Левая колонка - Альбомы',
            'rightcol' => 'Правая колонка - Весь сайт',
            'rightcol_main' => 'Правая колонка - Главная',
            'rightcol_page' => 'Правая колонка - Страницы',
            'rightcol_news' => 'Правая колонка - Новости',
            'rightcol_art' => 'Правая колонка - Статьи',
            'rightcol_gallery' => 'Правая колонка - Альбомы',
            'footer' => 'Подвал сайта',
        ];

    public $dyn_places=['beforecontent','aftercontent','leftcol','rightcol'];

    public $scriptpos = ['POS_HEAD' => 'POS_HEAD', 'POS_BEGIN' => 'POS_BEGIN', 'POS_END' => 'POS_END'];

    public $cachetimes
        = [
            0 => 'Не кешировать',
            3600 => 'час',
            14400 => '4 часа',
            86400 => '1 день',
            999999 => 'до изменения'
        ];

    public $routes_pref=[
        'site/index'=>'_main',
        'content/front/newslist'=>'_news',
        'content/front/news'=>'_news',
        'content/front/news-bydate'=>'_news',
        'content/front/novosti'=>'_news',
        'content/front/feedback'=>'_page',
        'content/front/page'=>'_page',
        'content/front/category'=>'_art',
        'content/front/article'=>'_art',
        'content/front/articles-bydate'=>'_art',
        'gallery/front/all'=>'_gallery',
        'gallery/front/album'=>'_gallery',
        'gallery/front/pic'=>'_gallery',

    ];

    /**
     * @var array $accessRoles = who can view widgets
     */
    public $accessRoles = [];
    /**
     * @var array $manageRoles = who can manage widgets
     */
    public $manageRoles = [];
    /**
     * @var array $widgetClasses = list widgetClasses implements Widgetizer interface
     */
    public $widgetClasses = [];

    private $widgetList = [];
    private $actionList = [];
    private $cachedList = [];

    private $fullCache=[];

    public function init()
    {
        parent::init();
        if (empty($this->accessRoles) or !is_array($this->accessRoles)) {
            $this->accessRoles = ['@'];
        }
        if (empty($this->manageRoles) or !is_array($this->manageRoles)) {
            $this->manageRoles = ['admin'];
        }
        if (!is_array($this->places) or !is_array($this->scriptpos) or empty($this->places)) {
            throw new InvalidConfigException(\Yii::t('app', 'Bad configuration. Places and positions must by arrays'));
        }
        if (empty($this->widgetClasses) or !is_array($this->widgetClasses)) {
            throw new InvalidConfigException(\Yii::t('app', 'Bad configuration. Please set widgetClasses'));
        }
        $this->getWidgetList();
        $this->getFullCache();
    }

    public function getWidgetList($widget = '')
    {
        if (empty($this->widgetList)) {
            foreach ($this->widgetClasses as $className) {
                $class = \Yii::$container->get($className);
               /* if (!($class instanceof WidgetizerInterface)) {
                    throw new InvalidConfigException('Widget ' . $className . ' must implements WidgetizerInterface ');
                }*/
                $this->widgetList[$className] = $class->getFriendlyName();
                $this->actionList[$className] = $class->getActionRoute();
                if ($class->allowCache()) {
                    $this->cachedList[] = $className;
                }
            }
        }
        return (!$widget) ? $this->widgetList : $this->widgetList[$widget];
    }

    public function getActionList()
    {
        if (empty($this->actionList)) {
            foreach ($this->widgetClasses as $className) {
                $class = \Yii::$container->get($className);
                if (!($class instanceof WidgetizerInterface)) {
                    throw new InvalidConfigException('Widget ' . $className . ' must implements WidgetizerInterface ');
                }
                $this->actionList[$className] = $class->getActionRoute();
            }
        }
        return $this->actionList;
    }

    public function getWidgetPlaces($className)
    {
        $class = \Yii::$container->get($className);
        return ($class->getIsScript()==false? $this->places : $this->scriptpos);
    }

    public function getCachedWidget()
    {
        if (empty($this->cachedList)) {
            foreach ($this->widgetClasses as $className) {
                $class = \Yii::$container->get($className);
                if ($class->allowCache()) {
                    $this->cachedList[] = $className;
                }
            }
        }
        return $this->cachedList;
    }

    public function getFullCache(){
        if(!$this->fullCache){
            $db=\Yii::$app->getDb();
            $dep = new DbDependency(['sql'=>"SELECT MAX(updated) FROM {{%widgetman}}",'reusable'=>true]);
            //Helper::logs($dep);
            $cache = $db->cache(
                function ($db){
                    return Widgetman::find()->where(['active' => 1])->orderBy(['ord' => SORT_ASC])->all();
                },
                3600,
                $dep
            );
            foreach($cache as $widget){
                /** @var Widgetman $widget **/
                $this->fullCache[$widget->position][]=$widget->getAttributes();
            }
            foreach($this->fullCache as $pos=>$widgets){
                 if(strpos($pos,'_')!==false && !in_array($pos, $this->scriptpos)){
                     $c=explode('_',$pos);
                     $base=$c[0];
                     if(isset($this->fullCache[$base])){
                         $attrs=array_merge($this->fullCache[$pos],$this->fullCache[$base]);
                         usort($attrs, function($a,$b){return ($a['ord'] < $b['ord']) ? -1 : 1;});
                         Helper::logs(Helper::cmap($attrs,'name',['class','ord']));
                         $this->fullCache[$pos]=$attrs;
                     }
                }
            }

        }
       return $this->fullCache;

    }

    public function showWidgets($pos, $route='')
    {
        if(in_array($pos,$this->dyn_places)){
            if(isset($this->routes_pref[$route])){
                $pos=$pos.$this->routes_pref[$route];
            }
        }
        if (isset($this->fullCache[$pos])) {
            $widgets=$this->fullCache[$pos];
            $db=\Yii::$app->getDb();
            $dep = new DbDependency(['sql'=>"SELECT MAX(updated) FROM {{%widgetman}}",'reusable'=>true]);
             foreach ($widgets as $widget) {
                $class = $widget['class'];
                /**@var Widgetman $model * */
                $cache = \Yii::$app->cache->get('widgetman_' . $pos . $widget['id']);
                if (!$cache || $widget['cachetime'] == 0 || !in_array($class, $this->getCachedWidget())) {
                    $widget['options'] = Json::decode($widget['options']);
                    $cache = $class::widget($widget['options']);
                    if ($widget['cachetime'] > 0 && in_array($class, $this->cachedList)) {
                        $duration = $widget['cachetime'] == 999999 ? 0 : $widget['cachetime'];
                         \Yii::$app->cache->set('widgetman_' . $pos . $widget['id'], $cache, $duration,$dep);
                    }
                }
                echo $cache;
            }
        }else{
            echo '';
        }

    }
} 