<nav class="navbar navbar-light bg-white shadow-sm px-4">
	<div class="d-flex align-items-center gap-3">
		<img src="../assets/images/laragondash-logo.png" style="height: 50px;" alt="LaragonDash Logo">
	</div>
	
	<div class="d-flex align-items-center gap-3 ms-auto">
		
		<!-- Search Box -->
		<form class="d-flex" action="../index.php" method="GET" role="search">
			<input type="hidden" name="module" value="search">
                        <input class="form-control me-2 form-control-sm" type="search" name="query" placeholder="Search projects..." aria-label="Search" data-i18n-placeholder="search">
			<button class="btn btn-sm btn-outline-secondary" type="submit">
				<iconify-icon icon="ic:round-search"></iconify-icon>
			</button>
		</form>
		
		<!-- Theme Toggle -->
                <button class="btn btn-sm btn-outline-secondary" id="themeToggleBtn" data-theme-toggle>
                        <iconify-icon icon="ph:moon-stars-duotone"></iconify-icon>
                </button>
		
		<!-- Language Dropdown -->
		<select id="lang-select" class="form-select form-select-sm" aria-label="Language Selector" style="width: auto;">
			<option value="en">🇬🇧</option>
			<option value="fr">🇫🇷</option>
			<option value="es">🇪🇸</option>
			<option value="de">🇩🇪</option>
			<option value="pt">🇵🇹</option>
			<option value="id">🇮🇩</option>
			<option value="tl">🇵🇭</option>
			<option value="ar">🇸🇦</option>
		</select>
		
		<!-- User Dropdown -->
		<div class="dropdown">
			<button class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">
				Tarek
			</button>
			<ul class="dropdown-menu dropdown-menu-end">
				<li><a class="dropdown-item" href="#">Logout</a></li>
			</ul>
		</div>
	</div>
</nav>
