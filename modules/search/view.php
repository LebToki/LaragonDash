<?php
	require_once '../../includes/functions.php';
	require_once '../../includes/lang.php';
	
	$query = $_GET['query'] ?? '';
	$projects = getProjectTiles();
	
	// Simple filter (case-insensitive search in name or path)
	$filtered = array_filter($projects, function ($p) use ($query) {
		return stripos($p['name'], $query) !== false || stripos($p['path'], $query) !== false;
	});
?>

<div class="container-fluid px-3 py-4">
	<div class="d-flex justify-content-between align-items-center mb-4">
		<h4 class="mb-0">
			<iconify-icon icon="ic:round-search" class="me-1"></iconify-icon>
			<?php if ($query): ?>
				<?= t('search.results_for', ['query' => htmlspecialchars($query)], 'Results for: {query}') ?>
			<?php else: ?>
				<?= t('search.no_query', [], 'No search query entered.') ?>
			<?php endif; ?>
		</h4>
	</div>
	
	<div class="row g-3">
		<?php if (empty($filtered)): ?>
			<div class="col-12 text-center text-muted">
				<p>
					<?= t('search.no_results_for', ['query' => '<strong>' . htmlspecialchars($query) . '</strong>'], 'No projects found for {query}.') ?>
				</p>
			</div>
		<?php else: ?>
			<?php
			$colors = ['#FCE7F3', '#DCFCE7', '#E0F2FE', '#FEF9C3', '#FFEDD5', '#EDE9FE'];
			foreach (array_values($filtered) as $i => $project):
				$link = htmlspecialchars($project['link']);
				$icon = htmlspecialchars($project['icon']);
				$name = htmlspecialchars($project['name']);
				$bg = $colors[$i % count($colors)];
				?>
				<div class="col-12 col-sm-6 col-md-4 col-xl-3 col-xxl-2">
					<a href="<?= $link ?>" target="_blank" rel="noopener"
					   class="card shadow-sm border-0 text-decoration-none h-100"
					   style="background-color: <?= $bg ?>;">
						<div class="card-body d-flex justify-content-center align-items-center" style="height: 100px;">
							<iconify-icon icon="<?= $icon ?>" width="36" height="36"></iconify-icon>
						</div>
						<div class="card-footer text-center fw-semibold small text-truncate">
							<?= $name ?>
						</div>
					</a>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>
