<?php
        require_once 'includes/functions.php';
        $query = $_GET['query'] ?? '';
        $projects = getProjectTiles();
	
	$filtered = array_filter($projects, function ($p) use ($query) {
		return stripos($p['name'], $query) !== false;
	});
?>

<style>
    body.dark .card-footer {
        background-color: #1e1e2f !important;
        color: #eee !important;
    }
    body.dark .card {
        background-color: #2b2b3d !important;
    }
</style>

<div class="container-fluid px-3 py-4">
        <?php if (!empty($query)): ?>
                <h4 class="mb-3">
                        <iconify-icon icon="ic:round-search" class="me-1"></iconify-icon>
                        <?= t('search.results_for', ['query' => htmlspecialchars($query)]) ?>
                </h4>
        <?php else: ?>
                <h4 class="mb-3 text-muted" data-i18n="search.no_query">No search query entered.</h4>
        <?php endif; ?>
	
	<div class="row">
                <?php if (empty($filtered)): ?>
                        <div class="col-12 text-center text-muted mt-5">
                                <p><?= t('search.no_results_for', ['query' => '<strong>' . htmlspecialchars($query) . '</strong>']) ?></p>
                        </div>
		<?php else: ?>
			<?php
			$colors = ['#FCE7F3', '#DCFCE7', '#E0F2FE', '#FEF9C3', '#FFEDD5', '#EDE9FE'];
			foreach (array_values($filtered) as $i => $project):
				$bg = $colors[$i % count($colors)];
				?>
				<div class="col-6 col-sm-4 col-md-3 col-xl-2 mb-3">
					<a href="<?= htmlspecialchars($project['link']) ?>" class="card h-100 text-center text-decoration-none shadow-sm border-0" style="background-color: <?= $bg ?>;" target="_blank" rel="noopener">
						<div class="card-body d-flex align-items-center justify-content-center" style="height: 100px;">
							<iconify-icon icon="<?= htmlspecialchars($project['icon']) ?>" width="40" height="40"></iconify-icon>
						</div>
						<div class="card-footer fw-semibold small text-truncate">
							<?= htmlspecialchars($project['name']) ?>
						</div>
					</a>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>
