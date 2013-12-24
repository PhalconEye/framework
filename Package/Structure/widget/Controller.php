<?php
/*
  +------------------------------------------------------------------------+
  | PhalconEye CMS                                                         |
  +------------------------------------------------------------------------+
  | Copyright (c) 2013 PhalconEye Team (http://phalconeye.com/)            |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file LICENSE.txt.                             |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconeye.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
*/
%header %
namespace Widget\%nameUpper %;

use Engine\Form as Form;

/**
 * Widget %nameUpper%.
 *
 * @category PhalconEye\Widget
 * @package  Widget
 */
class Controller extends \Engine\Widget\Controller
{
    /**
     * Index action.
     *
     * @return void
     */
    public function indexAction()
    {

    }

    /**
     * Action for management from admin panel.
     *
     * @return Form
     */
    public function adminAction()
    {
        $form = new Form();

        return $form;
    }

    /**
     * Check if this widget must be cached.
     *
     * @return bool
     */
    public function isCached()
    {
        return false;
    }

    /**
     * What cache lifetime will be for this widget.
     *
     * @return int
     */
    public function cacheLifeTime()
    {
        return 300;
    }
}