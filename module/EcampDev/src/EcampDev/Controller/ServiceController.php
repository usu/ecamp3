<?php

namespace EcampDev\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class ServiceController 
	extends AbstractActionController
{
	
	public function indexAction(){
		
	}
	
	public function rebuildAction(){
		$configUtil = $this->getServiceLocator()->get('ecampdev.service.configutil');
		
		var_dump($configUtil->getAllServices());
		
		die();
	}
	
}
