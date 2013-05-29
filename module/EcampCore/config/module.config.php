<?php
return array(
	
	'doctrine' => array(
		'driver' => array(
			'ecamp_entities' => array(
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => array(__DIR__ . '/../src/EcampCore/Entity')
			),
			
			'orm_default' => array(
				'drivers' => array(
					'EcampCore\Entity' => 'ecamp_entities'
				)
			)
		),
	),
	
);