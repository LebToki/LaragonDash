<?php
	$current = $_GET['module'] ?? 'dashboard';
	function isActive($name, $current) {
		return $name === $current ? 'active text-primary' : 'text-dark';
	}
?>

<div class="sidebar d-flex flex-column bg-light align-items-center py-4 border-end" style="width: 60px; min-height: 100vh; font-size: 0.75rem;">
	<ul class="nav flex-column text-center gap-4">
		<li class="nav-item">
			<a class="nav-link <?= isActive('dashboard', $current) ?>" href="?module=dashboard" title="Dashboard">
				<iconify-icon icon="mdi:view-dashboard-outline" width="24"></iconify-icon>
				<small class="d-block">Dash</small>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link <?= isActive('search', $current) ?>" href="?module=search" title="Search">
				<iconify-icon icon="mdi:magnify" width="24"></iconify-icon>
				<small class="d-block">Search</small>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link <?= isActive('project', $current) ?>" href="?module=project" title="Projects">
				<iconify-icon icon="mdi:folder-outline" width="24"></iconify-icon>
				<small class="d-block">Projects</small>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link <?= isActive('email', $current) ?>" href="?module=email" title="Email">
				<iconify-icon icon="mdi:email-outline" width="24"></iconify-icon>
				<small class="d-block">Email</small>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link <?= isActive('vitals', $current) ?>" href="?module=vitals" title="Vitals">
				<iconify-icon icon="mdi:heart-pulse" width="24"></iconify-icon>
				<small class="d-block">Vitals</small>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link <?= isActive('bcrypt', $current) ?>" href="?module=bcrypt" title="Bcrypt">
				<iconify-icon icon="mdi:lock-outline" width="24"></iconify-icon>
				<small class="d-block">Bcrypt</small>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link <?= isActive('settings', $current) ?>" href="?module=settings" title="Settings">
				<iconify-icon icon="mdi:cog" width="24"></iconify-icon>
				<small class="d-block">Settings</small>
			</a>
		</li>
	</ul>
</div>
