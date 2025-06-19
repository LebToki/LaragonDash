<?php
	header('Content-Type: application/json');
	
	function safeExec($command)
	{
		$output = shell_exec($command);
		return $output !== null ? trim($output) : null;
	}
	
	function parseMemInfo($meminfo)
	{
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
	
	function parseDiskUsage($diskinfo)
	{
		$lines = explode("\n", $diskinfo);
		$data = [];
		foreach ($lines as $line) {
			if (preg_match('/^\/dev\/\w+\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)%\s+(.+)$/', $line, $matches)) {
				$data[] = [
					'filesystem' => $matches[5],
					'total' => (int)$matches[1],
					'used' => (int)$matches[2],
					'available' => (int)$matches[3],
					'use_percent' => (int)$matches[4]
				];
			}
		}
		return $data;
	}
	
	function checkDbStatus()
	{
		try {
			$conn = new PDO('mysql:host=localhost;dbname=your_db_name', 'your_user', 'your_pass');
			return $conn ? 'online' : 'offline';
		} catch (PDOException $e) {
			return 'offline';
		}
	}
	
	function checkServerStatus()
	{
		return @fsockopen("127.0.0.1", 80) ? 'online' : 'offline';
	}
	
	try {
		$uptime = safeExec('uptime -p') ?: 'Unavailable';
		$cpuUsage = safeExec("top -bn1 | grep 'Cpu(s)' | awk '{print 100 - $8}'") . '%' ?: '0%';
		
		$memoryInfo = safeExec('cat /proc/meminfo');
		$parsedMemInfo = parseMemInfo($memoryInfo);
		$totalMem = $parsedMemInfo['MemTotal']['value'] ?? 0;
		$freeMem = $parsedMemInfo['MemFree']['value'] ?? 0;
		$usedMem = $totalMem - $freeMem;
		$memoryUsagePercent = $totalMem > 0 ? round(($usedMem / $totalMem) * 100, 2) : 0;
		
		$diskInfo = safeExec('df -k');
		$parsedDiskInfo = parseDiskUsage($diskInfo);
		
		$currentTime = date('H:i:s');
		$uptimeData = [$cpuUsage];
		$uptimeLabels = [$currentTime];
		$memoryUsageData = [$totalMem, $usedMem, $freeMem];
		$memoryUsageLabels = ['Total', 'Used', 'Free'];
		$diskUsageData = array_map(fn($d) => $d['use_percent'], $parsedDiskInfo);
		$diskUsageLabels = array_map(fn($d) => $d['filesystem'], $parsedDiskInfo);
		
		echo json_encode([
			'uptime' => $uptime,
			'cpuUsage' => $cpuUsage,
			'memoryUsage' => "$memoryUsagePercent%",
			'memoryDetails' => [
				'total' => $totalMem,
				'used' => $usedMem,
				'free' => $freeMem
			],
			'diskUsage' => $parsedDiskInfo,
			'uptimeData' => $uptimeData,
			'uptimeLabels' => $uptimeLabels,
			'memoryUsageData' => $memoryUsageData,
			'memoryUsageLabels' => $memoryUsageLabels,
			'diskUsageData' => $diskUsageData,
			'diskUsageLabels' => $diskUsageLabels,
			'serverStatus' => checkServerStatus(),
			'databaseStatus' => checkDbStatus(),
			'lastChecked' => date('Y-m-d H:i:s'),
		]);
	} catch (Exception $e) {
		http_response_code(500);
		echo json_encode(['error' => $e->getMessage()]);
	}
