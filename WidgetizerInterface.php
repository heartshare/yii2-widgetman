<?php
/**
 * Created by PhpStorm.
 * User: Insolita
 * Date: 24.08.14
 * Time: 21:09
 */

namespace insolita\widgetman;


/**
 * Interface WidgetizerInterface
 *
 * @package insolita\widgetizer
 */
interface WidgetizerInterface {
    /**
     * @method getFriendlyName()
     * @return string Friendly name of your widget for user interface
    **/
    public function getFriendlyName();

    /**
     * @method getActionRoute()
     * @return mixed (string|bool) route to you contoller where you render form with settings and save it with WidgetizerModel or false if
     * your widget don`t needed in settings
     * @example '/admin/widgets/search-widget'  - first slash required!!!
     */
    public function getActionRoute();
    /**
     * @method getIsScript()
     * @return bool - if widget for script places, return true, if for content - return false
     *
     */
    public function getIsScript();

    /**
     * @method allowCache()
     * @return bool - if allow cache widget content (if widget don`t use scripts)
     *
     */
    public function allowCache();

} 