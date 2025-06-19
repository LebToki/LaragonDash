<?php
	header('Content-Type: application/json');
	
	function safeExec($command): string {
		$output = shell_exec($command);
		return $output !== null ? trim($output) : "Unavailable";
	}
	
	function parseMemInfo($meminfo): array {
		$lines = explode("\n", $meminfo);
		$data = [];
		foreach ($lines as $line) {
			if (preg_match('/^(\w+):\s+(\d+)\s+(\w+)$/', $line, $matches)) {
				$data[$matches[1]] = [
					'value' => (int)$matches[2],
					'unit' => $matches[3]
				];
			}
		}
		return $data;
	}
	
	function parseDiskUsage($diskinfo): array {
		$lines = explode("\n", $diskinfo);
		$data = [];
		foreach ($lines as $line) {
			if (preg_match('/^\/dev\/\w+\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)%\s+(.+)$/', $line, $matches)) {
				$data[] = [
					'filesystem' => trim($matches[5]),
					'total' => (int)$matches[1],
					'used' => (int)$matches[2],
					'available' => (int)$matches[3],
					'use_percent' => (int)$matches[4]
				];
			}
		}
		return $data;
	}
	
	try {
		$isWindows = PHP_OS_FAMILY === 'Windows';
		
		$uptime = $isWindows ? "Use Task Manager" : safeExec('uptime -p');
		$cpuUsage = $isWindows
			? "Unavailable on Windows"
			: safeExec("top -bn1 | grep 'Cpu(s)' | sed 's/.*, *\\([0-9.]*\\)%* id.*/\\1/' | awk '{print 100 - $1\"%\"}'");
		
		$memoryInfo = $isWindows ? "" : safeExec('cat /proc/meminfo');
		$parsedMemInfo = $isWindows ? [] : parseMemInfo($memoryInfo);
		
		$totalMem = $parsedMemInfo['MemTotal']['value'] ?? 0;
		$freeMem = $parsedMemInfo['MemFree']['value'] ?? 0;
		$usedMem = $totalMem - $freeMem;
		$memoryUsagePercent = $totalMem > 0 ? round(($usedMem / $totalMem) * 100, 2) : 0;
		
		$diskInfo = $isWindows ? "" : safeExec('df -k');
		$parsedDiskInfo = $isWindows ? [] : parseDiskUsage($diskInfo);
		
		$currentTime = date('H:i:s');
		$uptimeData = [$cpuUsage];
		$uptimeLabels = [$currentTime];
		
		$memoryUsageData = [$totalMem, $usedMem, $freeMem];
		$memoryUsageLabels = ['Total', 'Used', 'Free'];
		
		$diskUsageData = array_map(fn($disk) => $disk['use_percent'], $parsedDiskInfo);
		$diskUsageLabels = array_map(fn($disk) => $disk['filesystem'], $parsedDiskInfo);
		
		echo json_encode([
			'meta' => [
				'timestamp' => date('Y-m-d H:i:s'),
				'platform' => PHP_OS_FAMILY
			],
			'stats' => [
				'uptime' => $uptime,
				'cpuUsage' => $cpuUsage,
				'memoryUsagePercent' => "$memoryUsagePercent%",
				'memoryDetails' => [
					'total' => $totalMem,
					'used' => $usedMem,
					'free' => $freeMem
				],
				'diskUsage' => $parsedDiskInfo
			],
			'charts' => [
				'uptimeData' => $uptimeData,
				'uptimeLabels' => $uptimeLabels,
				'memoryUsageData' => $memoryUsageData,
				'memoryUsageLabels' => $memoryUsageLabels,
				'diskUsageData' => $diskUsageData,
				'diskUsageLabels' => $diskUsageLabels
			]
		]);
		
	} catch (Exception $e) {
		http_response_code(500);
		echo json_encode(['error' => $e->getMessage()]);
	}
