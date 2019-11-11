<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class Module
{ 
    public function onBootstrap(MvcEvent $e)
    {
        $app = $e->getParam('application');
        //$app->getEventManager()->attach('render', array($this, 'setLayoutTitle'));
          
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        
        $sm = $e->getApplication()->getServiceManager();
        $router = $sm->get('router');
        $request = $sm->get('request');
        $matchedRoute = $router->match($request);
        $controller = $matchedRoute->getParam('controller');
        $params = $matchedRoute->getParams();
        $lang = @$params['lang'];
        if(isset($lang) && $lang !== '') 
        {
            $translator = $e->getApplication()->getServiceManager()->get('MvcTranslator');
            if($lang == 'th')
            {
                $translator->setLocale('th_TH');
            }
            else
            {
                $translator->setLocale('en_US');
            }
        }
        $viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
        $viewModel->action = $params['action']; 
        $viewModel->id = $params['id']; 
        $viewModel->lang = $lang;     
        $viewModel->controller = $controller; 
        $viewModel->baseUrl = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST']; 
        $viewModel->FullLink = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $viewModel->page_name = ucfirst($params['action']); 
        
        $controller = @str_replace('\\', '/', $controller);
        $controller = @strtolower(str_replace('Application/Controller/', '', $controller)); 
        $viewModel->controller = $controller;  
        
    } 
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
     
    
    public function setLayoutTitle($e)
    {
        $matches    = $e->getRouteMatch();
        $action     = $matches->getParam('action');
        $controller = $matches->getParam('controller');
        $module     = __NAMESPACE__;
        $siteName   = 'Zend Framework';

        // Getting the view helper manager from the application service manager
        $viewHelperManager = $e->getApplication()->getServiceManager()->get('viewHelperManager');

        // Getting the headTitle helper from the view helper manager
        $headTitleHelper   = $viewHelperManager->get('headTitle');

        // Setting a separator string for segments
        $headTitleHelper->setSeparator(' - ');

        // Setting the action, controller, module and site name as title segments
        $headTitleHelper->append($action);
        $headTitleHelper->append($controller);
        $headTitleHelper->append($module);
        $headTitleHelper->append($siteName);
    }  
    
    
    public function getServiceConfig()
    {
        return array( 
            'aliases' => array(
                'viewhelpermanager' =>'ViewHelperManager'
             )
        );
    }
 
     
}
