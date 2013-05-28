<?php

namespace EcampDev\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\Config\Config;

class ConfigUtil
	implements ServiceLocatorAwareInterface
{
	use \Zend\ServiceManager\ServiceLocatorAwareTrait;
	
	
	public function getAllServices(){
		$config = $this->getConfig();

		$services = array();
		foreach($config->ecamp->modules as $key => $module){
			$services[$key] = $this->getServiceFilesOfModule($key);
		}
		
		return $services;
	}
	
	public function getServiceFilesOfModule($moduleKey){
		$config = $this->getConfig();
		$module = $config->ecamp->modules->$moduleKey;
		
		return glob($module->services->services_path . '*.php');
	}
	
	
	public function existsConfig($service){
		
	}
	
	public function getAlias($service){
		
	}
	
	
	/**
	 * @return \Zend\Config\Config
	 */
	private function getConfig(){
		return new Config($this->getServiceLocator()->get('config'));
	}
}