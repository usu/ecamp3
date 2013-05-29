<?php

namespace EcampWeb\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\ViewModel;

abstract class BaseController 
	extends AbstractActionController
	implements ServiceLocatorAwareInterface
{
	
	protected $service_manager;
	
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
	{
		$this->service_manager = $serviceLocator;
	}
	
	public function getServiceLocator()
	{
		return $this->service_manager;
	}
	
}
