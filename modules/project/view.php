<?php
	require_once 'includes/functions.php';
	require_once 'includes/lang.php';
	
        // Projects are cached by getProjectTiles()
        $projects = getProjectTiles();
	$types = array_unique(array_column($projects, 'type'));
	sort($types);
	$colors = ['#FCE7F3', '#DCFCE7', '#E0F2FE', '#FEF9C3', '#FFEDD5', '#EDE9FE'];
?>

<div class="container-fluid px-4 py-4">
	<div class="row mb-4">
		<div class="col-md-6 mb-2">
			<input type="text" id="searchInput" class="form-control" placeholder="<?= t('projects.search_placeholder', [], 'Search projects...') ?>">
		</div>
		<div class="col-md-6 mb-2">
			<select id="typeFilter" class="form-select">
				<option value=""><?= t('projects.all_types', [], 'All Types') ?></option>
				<?php foreach ($types as $type): ?>
					<option value="<?= htmlspecialchars($type) ?>"><?= ucfirst($type) ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	
	<div class="row g-3" id="projectGrid">
		<?php foreach ($projects as $i => $project): ?>
			<?php
			$bg    = $colors[$i % count($colors)];
			$name  = htmlspecialchars($project['name']);
			$link  = htmlspecialchars($project['link']);
			$icon  = htmlspecialchars($project['icon']);
			$type  = htmlspecialchars($project['type']);
			
			// Detect platform
			$isWP = stripos($type, 'wordpress') !== false;
			$isLaravel = stripos($type, 'laravel') !== false;
			
			$adminLink = '';
			$adminIcon = '';
			if ($isWP) {
				$adminLink = rtrim($link, '/') . '/wp-admin/';
				$adminIcon = '<a href="' . $adminLink . '" class="ms-1 text-muted" title="Admin" target="_blank" rel="noopener">
						<iconify-icon icon="mdi:cog" width="16" height="16"></iconify-icon>
					</a>';
			} elseif ($isLaravel) {
				$adminLink = rtrim($link, '/') . '/public/';
				$adminIcon = '<a href="' . $adminLink . '" class="ms-1 text-muted" title="Public Folder" target="_blank" rel="noopener">
						<iconify-icon icon="mdi:folder-open" width="16" height="16"></iconify-icon>
					</a>';
			}
			?>
			<div class="col-6 col-sm-4 col-md-3 col-xl-2 project-card" data-name="<?= strtolower($name) ?>" data-type="<?= strtolower($type) ?>">
				<div class="card text-center shadow-sm border-0 h-100" style="background-color: <?= $bg ?>;" title="<?= $name ?>">
					<div class="card-body d-flex flex-column justify-content-center align-items-center" style="height: 100px;">
						<iconify-icon icon="<?= $icon ?>" width="40" height="40" aria-label="<?= $name ?>"></iconify-icon>
					</div>
					<div class="card-footer bg-white fw-semibold small d-flex justify-content-center align-items-center text-truncate">
						<a href="<?= $link ?>" class="text-decoration-none text-dark flex-grow-1 text-truncate" target="_blank" rel="noopener"><?= $name ?></a>
						<?= $adminIcon ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		
		<!-- Extra card -->
		<div class="col-6 col-sm-4 col-md-3 col-xl-2">
			<div class="card text-center shadow-sm border border-dashed bg-light h-100">
				<div class="card-body d-flex justify-content-center align-items-center" style="height: 100px;">
					<iconify-icon icon="mdi:arrow-right-bold-box-outline" width="36" height="36"></iconify-icon>
				</div>
				<div class="card-footer bg-white text-muted small">
					<?= t('dashboard.more_projects', [], 'More Projects') ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	document.getElementById('searchInput').addEventListener('input', filterProjects);
	document.getElementById('typeFilter').addEventListener('change', filterProjects);
	
	function filterProjects() {
		const query = document.getElementById('searchInput').value.toLowerCase();
		const selectedType = document.getElementById('typeFilter').value.toLowerCase();
		const cards = document.querySelectorAll('.project-card');
		
		cards.forEach(card => {
			const name = card.getAttribute('data-name');
			const type = card.getAttribute('data-type');
			const matchesQuery = name.includes(query);
			const matchesType = !selectedType || type === selectedType;
			
			card.style.display = (matchesQuery && matchesType) ? '' : 'none';
		});
	}
</script>
