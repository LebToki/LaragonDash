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
					<?php
					$bg = $colors[$i % count($colors)];
					$title = htmlspecialchars($project['name']);
					?>
					<div class="col-6 col-sm-4 col-md-3 col-xl-2">
						<div class="card text-center shadow-sm border-0 h-100" style="background-color: <?= $bg ?>;" title="<?= $title ?>">
							<div class="card-body d-flex flex-column justify-content-center align-items-center" style="height: 100px;">
								<iconify-icon icon="<?= htmlspecialchars($project['icon']) ?>" width="40" height="40" aria-label="<?= $title ?>"></iconify-icon>
							</div>
							<div class="card-footer bg-white fw-semibold text-truncate">
								<a href="<?= htmlspecialchars($project['link']) ?>" class="text-decoration-none text-dark" target="_blank" rel="noopener noreferrer">
									<?= $title ?>
								</a>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
				
                                <!-- Optional: More Projects Tile -->
                                <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                        <div class="card text-center shadow-sm border border-dashed bg-light h-100" title="<?= t('projects.explore_more') ?>">
                                                <div class="card-body d-flex justify-content-center align-items-center" style="height: 100px;">
                                                        <iconify-icon icon="mdi:arrow-right-bold-box-outline" width="36" height="36"></iconify-icon>
                                                </div>
                                                <div class="card-footer bg-white text-muted small" data-i18n="dashboard.more_projects">More Projects</div>
                                        </div>
                                </div>
			
			</div>
		</div>
	</div>
</div>
