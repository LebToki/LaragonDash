<?php
	return [
		'SSLEnabled' => 1,
		'Port'       => 443,
		'ProjectPath' => 'D:/laragon/www/',  // <- Adjust to YOUR project base directory
       'IgnoreDirs' => ['.', '..', 'includes', 'modules', '.idea', 'logs', 'vendor', 'assets'],
       // Path where Laragon stores outgoing emails
       'email_output_path' => 'D:/laragon/bin/sendmail/output/',
	];
?>