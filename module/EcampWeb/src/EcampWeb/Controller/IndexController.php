<?php

namespace EcampWeb\Controller;

use Zend\View\Model\ViewModel;

class IndexController 
	extends BaseController
{

	public function indexAction(){
		
		/* @var EcampCore\Service\CampService */
		$campService = $this->getServiceLocator()->get('EcampCore\Service\Camp');
		die( $campService->Get() );
		
		return new ViewModel();
	}
	
}
