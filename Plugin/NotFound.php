<?php

/**
 * PhalconEye
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to phalconeye@gmail.com so we can send you a copy immediately.
 *
 */

namespace Engine\Plugin;

class NotFound extends \Phalcon\Mvc\User\Plugin
{

    public function beforeException($dispatcher, $exception)
    {
        switch ($exception->getCode()) {
            case \Phalcon\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
            case \Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                            $dispatcher->forward(array(
                                'module' => Application::$defaultModule,
                                'namespace' => ucfirst(Application::$defaultModule) . '\Controller',
                                'controller' => 'Error',
                                'action' => 'show404'
                            ));
                            return false;
                    }
    }

}