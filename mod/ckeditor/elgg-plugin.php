<?php

return [
	'plugin' => [
		'name' => 'CKEditor',
		'activate_on_install' => true,
	],
	'views' => [
		'default' => [
			'ckeditor/' => __DIR__ . '/vendors/ckeditor5-35.2.0/build/',
		],
	],
	'view_extensions' => [
		'input/longtext' => [
			'ckeditor/init' => [],
		],
	],
	'events' => [
		'attributes' => [
			'htmlawed' => [
				'\Elgg\Input\ValidateInputHandler::sanitizeStyles' => ['unregister' => true],
			],
		],
		'elgg.data' => [
			'site' => [
				'\Elgg\CKEditor\Views::setToolbarConfig' => [],
			],
		],
		'view_vars' => [
			'input/longtext' => [
				'Elgg\CKEditor\Views::setInputLongTextIDViewVar' => [],
			],
		],
	],
	'routes' => [
		'default:ckeditor:upload' => [
			'path' => '/ckeditor/upload',
			'controller' => \Elgg\CKEditor\Upload::class,
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
	],
];
