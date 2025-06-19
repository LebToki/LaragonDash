<?php
	// modules/vitals/view.php
?>
<div class="container-fluid py-4">
	<div class="row mb-4">
		<div class="col-12 col-lg-6">
			<div class="card shadow-sm">
				<div class="card-header bg-primary text-white">
					<i class="ri-time-line"></i> Uptime & CPU Usage
				</div>
				<div class="card-body">
					<p><strong>Uptime:</strong> <span id="uptime">Loading...</span></p>
					<p><strong>CPU Usage:</strong> <span id="cpuUsage">Loading...</span></p>
					<canvas id="cpuChart" height="100"></canvas>
				</div>
			</div>
		</div>
		<div class="col-12 col-lg-6">
			<div class="card shadow-sm">
				<div class="card-header bg-success text-white">
					<i class="ri-brain-line"></i> Memory Usage
				</div>
				<div class="card-body">
					<p>Total: <span id="memTotal">-</span> KB</p>
					<p>Used: <span id="memUsed">-</span> KB</p>
					<p>Free: <span id="memFree">-</span> KB</p>
					<canvas id="memChart" height="100"></canvas>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header bg-dark text-white">
					<i class="ri-hard-drive-2-line"></i> Disk Usage
				</div>
				<div class="card-body">
					<canvas id="diskChart" height="100"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
	document.addEventListener("DOMContentLoaded", function () {
		fetch("modules/vitals/data.php")
			.then(response => response.json())
			.then(data => {
				document.getElementById("uptime").textContent = data.uptime || "Unavailable";
				document.getElementById("cpuUsage").textContent = data.cpuUsage || "Unavailable";
				document.getElementById("memTotal").textContent = data.memoryDetails?.total ?? "-";
				document.getElementById("memUsed").textContent = data.memoryDetails?.used ?? "-";
				document.getElementById("memFree").textContent = data.memoryDetails?.free ?? "-";
				
				new Chart(document.getElementById("cpuChart"), {
					type: "bar",
					data: {
						labels: data.uptimeLabels,
						datasets: [{
							label: "CPU Usage %",
							data: data.uptimeData,
							backgroundColor: "rgba(54, 162, 235, 0.6)"
						}]
					}
				});
				
				new Chart(document.getElementById("memChart"), {
					type: "pie",
					data: {
						labels: data.memoryUsageLabels,
						datasets: [{
							label: "Memory (KB)",
							data: data.memoryUsageData,
							backgroundColor: [
								"rgba(75, 192, 192, 0.6)",
								"rgba(255, 99, 132, 0.6)",
								"rgba(255, 206, 86, 0.6)"
							]
						}]
					}
				});
				
				new Chart(document.getElementById("diskChart"), {
					type: "bar",
					data: {
						labels: data.diskUsageLabels,
						datasets: [{
							label: "Disk Usage %",
							data: data.diskUsageData,
							backgroundColor: "rgba(153, 102, 255, 0.6)"
						}]
					}
				});
			})
			.catch(error => {
				console.error("Vitals fetch error:", error);
			});
	});
</script>
