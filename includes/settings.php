<?php
	return [
		'ignored_dirs' => [
			'.', '..', 'logs', 'access-logs', 'vendor', 'favicon_io', 'ablepro-90', 'assets'
		],
		'platforms' => [
			'wordpress' => [
				'check' => 'wp-admin',
				'icon' => 'logos:wordpress-icon',
				'admin' => 'wp-admin'
			],
			'joomla' => [
				'check' => 'administrator',
				'icon' => 'logos:joomla',
				'admin' => 'administrator'
			],
			'drupal' => [
				'check' => 'core',
				'icon' => 'logos:drupal',
				'admin' => 'user'
			],
			'laravel' => [
				'check' => '.env',
				'icon' => 'logos:laravel',
				'admin' => ''
			],
			'symfony' => [
				'check' => 'bin/console',
				'icon' => 'logos:symfony',
				'admin' => 'admin'
			],
			'cakephp' => [
				'check' => 'bin/cake',
				'icon' => 'logos:cakephp',
				'admin' => 'admin'
			],
			'python' => [
				'check' => 'app.py',
				'icon' => 'logos:python',
				'admin' => 'public'
			]
		],
	];
