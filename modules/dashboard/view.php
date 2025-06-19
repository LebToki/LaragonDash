<?php
	require_once 'includes/functions.php';
	require_once 'includes/lang.php';
	require_once 'functions.php';
	
	$types = getProjectTypes(); // e.g., from scanning project folders
	
	// Get filters from request
	$type = $_GET['type'] ?? '';
	$search = $_GET['search'] ?? '';
	
	// Get projects
	$projects = getProjectTiles();
	
	// Pagination
//	$itemsPerPage = 24;
//	$totalProjects = count($projects);
//	$totalPages = ceil($totalProjects / $itemsPerPage);
//	$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
//	$start = ($currentPage - 1) * $itemsPerPage;
//	$projects = array_slice($projects, $start, $itemsPerPage);
?>

<div class="container-fluid px-4 py-4">
	<div class="row mb-3">
		<div class="col-md-4">
			<input type="text" id="searchInput" class="form-control" placeholder="<?= t('projects.search_placeholder', [], 'Search...') ?>">
		</div>
		<div class="col-md-4">
			<select id="typeFilter" class="form-select">
				<option value=""><?= t('projects.all_types', [], 'All Types') ?></option>
				<?php foreach ($types as $type): ?>
					<option value="<?= htmlspecialchars($type) ?>"><?= ucfirst($type) ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	
	<div class="row" id="projectGrid">
		<!-- Projects will load here via JS -->
	</div>
	
	<div class="text-center mt-3">
		<nav>
			<ul class="pagination justify-content-center" id="pagination">
				<!-- Pagination will be injected via JS -->
			</ul>
		</nav>
	</div>
</div>

<script>
	const perPage = 24;
	let allProjects = [];
	
	function loadProjects() {
		const search = document.getElementById('searchInput').value;
		const type = document.getElementById('typeFilter').value;
		
		fetch(`modules/dashboard/data.php?search=${encodeURIComponent(search)}&type=${encodeURIComponent(type)}`)
			.then(res => res.json())
			.then(data => {
				allProjects = data.projects || [];
				renderProjects(1);
			});
	}
	
	function renderProjects(page = 1) {
		const grid = document.getElementById('projectGrid');
		const pagination = document.getElementById('pagination');
		grid.innerHTML = '';
		pagination.innerHTML = '';
		
		const start = (page - 1) * perPage;
		const pageItems = allProjects.slice(start, start + perPage);
		
		if (pageItems.length === 0) {
			grid.innerHTML = `<div class="col-12 text-center text-muted"><p><?= t('search.no_results_for', [], 'No projects found.') ?></p></div>`;
			return;
		}
		
		pageItems.forEach((project, i) => {
			const bgColors = ['#FCE7F3', '#DCFCE7', '#E0F2FE', '#FEF9C3', '#FFEDD5', '#EDE9FE'];
			const bg = bgColors[i % bgColors.length];
			
			const adminLink = project.type.includes('wordpress')
				? `<a href="${project.link.replace(/\/$/, '')}/wp-admin/" class="ms-1 text-muted" title="Admin" target="_blank" rel="noopener">
					<iconify-icon icon="mdi:cog" width="16" height="16"></iconify-icon></a>`
				: project.type.includes('laravel')
					? `<a href="${project.link.replace(/\/$/, '')}/public/" class="ms-1 text-muted" title="Public" target="_blank" rel="noopener">
					<iconify-icon icon="mdi:folder-open" width="16" height="16"></iconify-icon></a>`
					: '';
			
			const col = document.createElement('div');
			col.className = 'col-6 col-sm-4 col-md-3 col-xl-2';
			col.innerHTML = `
				<div class="card text-center shadow-sm border-0 h-100" style="background-color: ${bg};" title="${project.name}">
					<div class="card-body d-flex justify-content-center align-items-center" style="height: 100px;">
						<iconify-icon icon="${project.icon}" width="40" height="40"></iconify-icon>
					</div>
					<div class="card-footer bg-white fw-semibold small d-flex justify-content-center align-items-center text-truncate">
						<a href="${project.link}" target="_blank" class="text-decoration-none text-dark flex-grow-1 text-truncate">${project.name}</a>
						${adminLink}
					</div>
				</div>`;
			grid.appendChild(col);
		});
		
		// Pagination
		const totalPages = Math.ceil(allProjects.length / perPage);
		for (let i = 1; i <= totalPages; i++) {
			const li = document.createElement('li');
			li.className = 'page-item' + (i === page ? ' active' : '');
			li.innerHTML = `<button class="page-link">${i}</button>`;
			li.addEventListener('click', () => renderProjects(i));
			pagination.appendChild(li);
		}
	}
	
	document.getElementById('searchInput').addEventListener('input', () => setTimeout(loadProjects, 300));
	document.getElementById('typeFilter').addEventListener('change', loadProjects);
	
	window.addEventListener('DOMContentLoaded', loadProjects);
</script>
