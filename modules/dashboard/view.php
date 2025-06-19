<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	require_once __DIR__ . '/../../includes/functions.php';
	
	try {
		$projects = getProjectTiles();
		$system = getSystemInfo();
	} catch (Exception $e) {
		echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
		echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
		die();
	}
	
	if (empty($projects)) {
		echo '<div class="alert alert-warning">No projects found. Make sure your projects are in the Laragon www directory.</div>';
	}
	
	$colors = ['#FCE7F3', '#DCFCE7', '#E0F2FE', '#FEF9C3', '#FFEDD5', '#EDE9FE'];
?>

<div class="container-fluid px-4">
	<div class="row">
		<!-- Left Column: Projects -->
		<div class="col-lg-9 col-12">
			<div class="row g-3">
				<?php foreach ($projects as $i => $project): ?>
					<?php $bg = $colors[$i % count($colors)]; ?>
					<div class="col-6 col-sm-4 col-md-3 col-xl-2">
						<div class="card text-center shadow-sm border-0 h-100" style="background-color: <?= $bg ?>;" title="<?= $project['name'] ?>">
							<div class="card-body d-flex flex-column justify-content-center" style="height: 100px;">
								<iconify-icon icon="<?= $project['icon'] ?>" width="40" height="40"></iconify-icon>
							</div>
							<div class="card-footer bg-white fw-semibold text-truncate">
								<a href="<?= $project['link'] ?>" class="text-decoration-none text-dark"><?= $project['name'] ?></a>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
				<!-- Add New Tile -->
				<div class="col-6 col-sm-4 col-md-3 col-xl-2">
					<div class="card text-center shadow-sm border-dashed bg-light h-100" title="Add New Project">
						<div class="card-body d-flex justify-content-center align-items-center" style="height: 100px;">
							<iconify-icon icon="mdi:arrow-right-bold-box-outline" width="36" height="36"></iconify-icon>
						</div>
						<div class="card-footer bg-white text-muted small">More Projects</div>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Right Column: System Info -->
		<div class="col-lg-3 col-12 mt-4 mt-lg-0">
			<div class="bg-white border rounded p-3 shadow-sm" style="font-family: Poppins,sans-serif;">
				<h6 class="fw-bold mb-3">
					<iconify-icon icon="mdi:server" class="me-1"></iconify-icon> System Information
				</h6>
				<ul class="list-unstyled small lh-lg">
					<?php foreach ($system as $label => $value): ?>
						<li><strong><?= $label ?>:</strong> <?= $value ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</div>
