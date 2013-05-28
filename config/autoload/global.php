<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    // ...
	'service_manager' => array(
		'lazy_services' => array(
			// set the names of your lazily loaded services here
			'ecampcore.repo.user'
		),
	),
		
	'ocra_service_manager' => array(
		'logged_service_manager' => true,
	)
	
);
