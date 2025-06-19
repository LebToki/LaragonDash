<?php
	
	require_once __DIR__ . '/../../includes/functions.php'; // adjust if needed
	
	/**
	 * Get dashboard project tiles with optional type and search filters.
	 *
	 * @param string $type
	 * @param string $search
	 * @return array
	 */
	function getDashboardProjects(string $type = '', string $search = ''): array {
		$projects = getProjectTiles(); // Assumes global helper returns full list
		$filtered = [];
		
		foreach ($projects as $project) {
			$projectType = strtolower($project['type'] ?? '');
			$projectName = strtolower($project['name'] ?? '');
			
			// Filter by type if specified
			if ($type && $projectType !== strtolower($type)) {
				continue;
			}
			
			// Filter by search if specified
			if ($search && strpos($projectName, strtolower($search)) === false) {
				continue;
			}
			
			// Determine admin link if applicable
			$link = rtrim($project['link'], '/');
			$adminLink = '';
			
			if (strpos($projectType, 'wordpress') !== false) {
				$adminLink = $link . '/wp-admin/';
			} elseif (strpos($projectType, 'laravel') !== false) {
				$adminLink = $link . '/public/';
			}
			
			$filtered[] = [
				'name'  => $project['name'],
				'link'  => $project['link'],
				'icon'  => $project['icon'],
				'type'  => $projectType,
				'admin' => $adminLink
			];
		}
		
		return $filtered;
	}
	
	/**
	 * Get unique project types.
	 *
	 * @return array
	 */
	function getProjectTypes(): array {
		$projects = getProjectTiles();
		$types = array_unique(array_column($projects, 'type'));
		sort($types);
		return $types;
	}