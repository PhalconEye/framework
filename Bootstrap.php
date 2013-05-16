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

namespace Engine;

use Phalcon\Mvc\View\Engine\Volt,
    Phalcon\Mvc\View,
    Phalcon\DiInterface;

abstract class Bootstrap implements BootstrapInterface
{

    /**
     * @var string
     */
    protected $_moduleName = "";

    /**
     * @var \Phalcon\Config
     */
    protected $_config;

    /**
     * @var \Phalcon\DiInterface
     */
    protected $_di;

    public function __construct()
    {
        $this->_di = \Phalcon\DI::getDefault();
        $this->_config = $this->_di->get('config');
    }

    public static function dependencyInjection(DiInterface $di){

    }

    public function registerAutoloaders()
    {

    }

    /**
     * Register the services here to make them general or register in the ModuleDefinition to make them module-specific
     */
    public function registerServices($di)
    {

        if (empty($this->_moduleName)) {
            $class = new \ReflectionClass($this);
            throw new \Engine\Exception('Bootstrap has no module name: ' . $class->getFileName());
        }

        $moduleDirectory = $this->getModuleDirectory();

        $config = $this->_config;

        //Create an event manager
        $eventsManager = new EventsManager($config);

        /*************************************************/
        //  Initialize view
        /*************************************************/
        $di->set('view', function() use ($moduleDirectory, $eventsManager, $config) {

            $view = new View();
            $view->setViewsDir($moduleDirectory . '/View/');

            $view->registerEngines(array(
                ".volt" => function ($view, $di) use ($config) {

                    $volt = new Volt($view, $di);

                    $volt->setOptions(array(
                        "compiledPath" => $config->application->view->compiledPath,
                        "compiledExtension" => $config->application->view->compiledExtension,
                        'compiledSeparator' => '_'
                    ));

                    $compiler = $volt->getCompiler();

                    //register helper
                    $compiler->addFunction('helper', function ($resolvedArgs) use ($di) {
                        return '(new \Engine\Helper(' . $resolvedArgs . '))';
                    });

                    // register translation filter
                    $compiler->addFilter('trans', function ($resolvedArgs) {
                        return '$this->trans->query(' . $resolvedArgs . ')';
                    });

                    return $volt;
                }
            ));

            //Attach a listener for type "view"
            if (!$config->application->debug) {

                $eventsManager->attach("view", function ($event, $view) {
                    if ($event->getType() == 'notFoundView') {
                        \Phalcon\DI::getDefault()->get('logger')->error('View not found - "' . $view->getActiveRenderPath().'"');
                    }
                });

                $view->setEventsManager($eventsManager);
            }

            return $view;
        });

        /*************************************************/
        //  Initialize dispatcher
        /*************************************************/
        if (!$config->application->debug) {
            $eventsManager->attach("dispatch:beforeException", new \Engine\Plugin\NotFound());
            $eventsManager->attach('dispatch:beforeExecuteRoute', new \Engine\Plugin\CacheAnnotation());
        }

        /**
         * Listening to events in the dispatcher using the
         * Acl plugin
         */
        $eventsManager->attach('dispatch', $di->get(Application::$defaultModule)->acl());

        // Create dispatcher
        $dispatcher = new \Phalcon\Mvc\Dispatcher();
        $dispatcher->setEventsManager($eventsManager);
        $di->set('dispatcher', $dispatcher);

    }

    public function getModuleName()
    {
        return $this->_moduleName;
    }

    public function getModuleDirectory()
    {
        return $this->_config->application->modulesDir . $this->_moduleName;
    }

}