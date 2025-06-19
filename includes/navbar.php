<nav class="navbar navbar-light bg-white shadow-sm px-4">
	<div class="d-flex align-items-center gap-3">
		<img src="../assets/images/laragondash-logo.png" style="height: 50px;" alt="LaragonDash Logo">
	</div>
	
	<div class="d-flex align-items-center gap-3 ms-auto">
		
		<!-- Search -->
		<form class="d-flex" action="../index.php" method="GET" role="search">
			<input type="hidden" name="module" value="search">
			<input class="form-control me-2 form-control-sm" type="search" id="projectSearch" name="query"
			       placeholder="Search projects..." aria-label="Search"
			       data-i18n="search.placeholder">
			<button class="btn btn-sm btn-outline-secondary" type="submit" title="Search">
				<iconify-icon icon="ic:round-search"></iconify-icon>
			</button>
		</form>
		
		<!-- Theme Toggle -->
		<button class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1" id="themeToggleBtn"
		        aria-label="Toggle Theme">
			<iconify-icon icon="ph:moon-stars-duotone"></iconify-icon>
		</button>

		
		<!-- Language Selector (dynamically populated in app.js) -->
		<select id="lang-select" class="form-select form-select-sm" aria-label="Language Selector" style="width: auto;"></select>
		
		<!-- User Dropdown -->
		<div class="dropdown">
			<button class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
				Tarek
			</button>
			<ul class="dropdown-menu dropdown-menu-end">
				<li><a class="dropdown-item" href="#" data-i18n="buttons.logout">Logout</a></li>
			</ul>
		</div>
	
	</div>
</nav>
