<?php
	require_once 'includes/functions.php';
	
	header('Content-Type: application/json');
	
        $query = strtolower(trim($_GET['q'] ?? ''));
        // use cached project list
        $projects = getProjectTiles();
	
	$filtered = array_filter($projects, function ($project) use ($query) {
		if ($query === '') return false;
		return stripos($project['name'], $query) !== false;
	});
	
	$results = array_map(function ($p) {
		return [
			'name' => htmlspecialchars($p['name']),
			'link' => htmlspecialchars($p['link']),
			'icon' => htmlspecialchars($p['icon']),
		];
	}, array_values($filtered));
	
	echo json_encode($results);
