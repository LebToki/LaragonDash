<?php
	require_once 'includes/functions.php';
	
	$projects = getProjectTiles();
	$system = getSystemInfo();
	$colors = ['#FCE7F3', '#DCFCE7', '#E0F2FE', '#FEF9C3', '#FFEDD5', '#EDE9FE'];
?>

<div class="container-fluid px-4 py-4">
	<div class="row gx-4 gy-4">
		<!-- Project Tiles Section -->
		<div class="col-12">
			<div class="row g-3">
				<?php foreach ($projects as $i => $project): ?>
					<?php $bg = $colors[$i % count($colors)]; ?>
					<div class="col-6 col-sm-4 col-md-3 col-xl-2">
						<div class="card text-center shadow-sm border-0 h-100" style="background-color: <?= $bg ?>;" title="<?= $project['name'] ?>">
							<div class="card-body d-flex flex-column justify-content-center align-items-center" style="height: 100px;">
								<iconify-icon icon="<?= $project['icon'] ?>" width="40" height="40"></iconify-icon>
							</div>
							<div class="card-footer bg-white fw-semibold text-truncate">
								<a href="<?= $project['link'] ?>" class="text-decoration-none text-dark" target="_blank"><?= $project['name'] ?></a>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
				
				<!-- Optional: Add Tile (can be future pagination or placeholder) -->
				<div class="col-6 col-sm-4 col-md-3 col-xl-2">
					<div class="card text-center shadow-sm border-dashed bg-light h-100">
						<div class="card-body d-flex justify-content-center align-items-center" style="height: 100px;">
							<iconify-icon icon="mdi:arrow-right-bold-box-outline" width="36" height="36"></iconify-icon>
						</div>
						<div class="card-footer bg-white text-muted small">More Projects</div>
					</div>
				</div>
			
			</div>
		</div>
	</div>
</div>
