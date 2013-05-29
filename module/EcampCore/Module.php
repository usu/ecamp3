<?php
namespace EcampCore;

//use Zend\Stdlib\ArrayUtils;
//use Zend\Mvc\MvcEvent;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

//use EcampCore\EntityUtil\ServiceLocatorAwareEventListener;

class Module implements 
	AutoloaderProviderInterface,
	ServiceProviderInterface,
	ConfigProviderInterface 
{
    public function getConfig(){
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig(){
    	
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getServiceConfig()
    {
    	return include __DIR__  . '/config/service.config.php';
    }
}
