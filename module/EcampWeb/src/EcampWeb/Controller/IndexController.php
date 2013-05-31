<?php

namespace EcampWeb\Controller;

use Zend\View\Model\ViewModel;
use EcampCore\Service\CampService;

class IndexController 
	extends BaseController
{

	public function indexAction(){
		die( $this->getServiceLocator()->get('EcampCore\Service\Camp')->Get() );
		
		return new ViewModel();
	}
	
}
