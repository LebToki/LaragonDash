<?php
	$query = $_GET['query'] ?? '';
        $projects = getProjectTiles();
        $filtered = searchAndRankProjects($projects, $query);
?>
<style>
    body.dark .card-footer {
        background-color: #1e1e2f !important;
        color: #eee;
    }

</style>
<div class="container-fluid px-3 py-4">
        <h4 class="mb-3">
                <iconify-icon icon="ic:round-search" class="me-1"></iconify-icon>
                Search Results for “<?= htmlspecialchars($query) ?>”
        </h4>
        <form class="mb-4" method="get" action="?module=search">
                <input type="text" class="form-control" name="query" value="<?= htmlspecialchars($query) ?>" placeholder="Search projects...">
        </form>
	
	<div class="row">
		<?php if (empty($filtered)): ?>
			<div class="col-12 text-center text-muted mt-5">
				<p>No results found for "<strong><?= htmlspecialchars($query) ?></strong>".</p>
			</div>
		<?php else: ?>
			<?php
			$colors = ['#FCE7F3', '#DCFCE7', '#E0F2FE', '#FEF9C3', '#FFEDD5', '#EDE9FE'];
			foreach (array_values($filtered) as $i => $project):
				$bg = $colors[$i % count($colors)];
				?>
				<div class="col-6 col-sm-4 col-md-3 col-xl-2 mb-3">
					<a href="<?= $project['link'] ?>" class="card h-100 text-center text-decoration-none shadow-sm border-0" style="background-color: <?= $bg ?>;">
						<div class="card-body d-flex align-items-center justify-content-center" style="height: 100px;">
							<iconify-icon icon="<?= $project['icon'] ?>" width="40" height="40"></iconify-icon>
						</div>
						<div class="card-footer bg-white text-dark fw-semibold small text-truncate">
							<?= $project['name'] ?>
						</div>
					</a>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>
