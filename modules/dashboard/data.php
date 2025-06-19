<?php
	require_once 'functions.php';
	
	header('Content-Type: application/json');

// Get filters from request
	$type = $_GET['type'] ?? '';
	$search = $_GET['search'] ?? '';
	
	$projects = getDashboardProjects($type, $search);
	
	echo json_encode([
		'success'  => true,
		'count'    => count($projects),
		'projects' => $projects
	]);
