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
                const cpuCtx = document.getElementById("cpuChart");
                const memCtx = document.getElementById("memChart");
                const diskCtx = document.getElementById("diskChart");

                let cpuChart = new Chart(cpuCtx, { type: "line", data: { labels: [], datasets: [{ label: "CPU %", data: [], borderColor: "#36A2EB", tension: 0.3 }] }});
                let memChart = new Chart(memCtx, { type: "doughnut", data: { labels: ["Total","Used","Free"], datasets: [{ data: [0,0,0], backgroundColor:["#4BC0C0","#FF6384","#FFCE56"] }] }});
                let diskChart = new Chart(diskCtx, { type: "bar", data: { labels: [], datasets: [{ label: "Disk %", data: [], backgroundColor: "#9966FF" }] }});

                function updateCharts(data) {
                        document.getElementById("uptime").textContent = data.uptime || "Unavailable";
                        document.getElementById("cpuUsage").textContent = data.cpuUsage || "Unavailable";
                        document.getElementById("memTotal").textContent = data.memoryDetails?.total ?? "-";
                        document.getElementById("memUsed").textContent = data.memoryDetails?.used ?? "-";
                        document.getElementById("memFree").textContent = data.memoryDetails?.free ?? "-";

                        cpuChart.data.labels.push(data.charts.uptimeLabels[0]);
                        cpuChart.data.datasets[0].data.push(parseFloat(data.charts.uptimeData[0]));
                        if (cpuChart.data.labels.length > 10) {
                                cpuChart.data.labels.shift();
                                cpuChart.data.datasets[0].data.shift();
                        }
                        cpuChart.update();

                        memChart.data.datasets[0].data = data.charts.memoryUsageData;
                        memChart.update();

                        diskChart.data.labels = data.charts.diskUsageLabels;
                        diskChart.data.datasets[0].data = data.charts.diskUsageData;
                        diskChart.update();
                }

                function fetchVitals() {
                        fetch("includes/api/vitals.php")
                                .then(r => r.json())
                                .then(updateCharts)
                                .catch(err => console.error("Vitals fetch error:", err));
                }

                fetchVitals();
                setInterval(fetchVitals, 5000);
        });
</script>
