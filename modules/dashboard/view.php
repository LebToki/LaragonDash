<?php
        require_once 'includes/functions.php';
        require_once 'includes/lang.php';
        $projects = getProjectTiles();
        $system = getSystemInfo();
        $colors = ['#FCE7F3', '#DCFCE7', '#E0F2FE', '#FEF9C3', '#FFEDD5', '#EDE9FE'];
?>

<div class="container-fluid px-4">
	<div class="row">
		<!-- Left Column: Project Tiles -->
		<div class="col-lg-9 col-12">
			<div class="row g-3">
				
				<?php foreach ($projects as $i => $project): ?>
					<?php $bg = $colors[$i % count($colors)]; ?>
					<div class="col-6 col-sm-4 col-md-3 col-xl-2">
						<div class="card text-center shadow-sm border-0 h-100" style="background-color: <?= $bg ?>;" title="<?= $project['name'] ?>">
							<div class="card-body d-flex flex-column justify-content-center align-items-center" style="height: 100px;">
								<iconify-icon icon="<?= $project['icon'] ?>" width="40" height="40"></iconify-icon>
							</div>
							<div class="card-footer bg-white fw-semibold text-truncate">
								<a href="<?= $project['link'] ?>" class="text-decoration-none text-dark"><?= $project['name'] ?></a>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
				
                                <!-- Optional: "Add More Projects" Tile -->
                                <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                        <div class="card text-center shadow-sm border border-dashed bg-light h-100" title="<?= t('dashboard.add_project') ?>">
                                                <div class="card-body d-flex justify-content-center align-items-center" style="height: 100px;">
                                                        <iconify-icon icon="mdi:plus-circle-outline" width="36" height="36"></iconify-icon>
                                                </div>
                                                <div class="card-footer bg-white text-muted small" data-i18n="dashboard.more_projects">More Projects</div>
                                        </div>
                                </div>
			
			</div>
		</div>
		
		<!-- Right Column: System Info -->
		<div class="col-lg-3 col-12 mt-4 mt-lg-0">
			<div class="bg-white border rounded p-3 shadow-sm" style="font-family: Poppins, sans-serif;">
                                <h6 class="fw-bold mb-3">
                                        <iconify-icon icon="mdi:server" class="me-1"></iconify-icon>
                                        <span data-i18n="dashboard.system_info">System Information</span>
                                </h6>
                                <ul class="list-unstyled small lh-lg mb-0">
                                        <?php
                                        $labelMap = [
                                                'PHP Version'     => t('system.php_version'),
                                                'Server Software' => t('system.server_software'),
                                                'Document Root'   => t('system.document_root'),
                                                'Date'            => t('system.date'),
                                        ];
                                        foreach ($system as $label => $value): ?>
                                                <li><strong><?= $labelMap[$label] ?? $label ?>:</strong> <?= $value ?></li>
                                        <?php endforeach; ?>
                                </ul>
			</div>
		</div>
	</div>
</div>
