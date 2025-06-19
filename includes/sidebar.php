<?php
        $current = $_GET['module'] ?? 'dashboard';

        require_once __DIR__ . '/lang.php';
        $trans = getTranslations();
	
	$modules = [
		'dashboard' => ['icon' => 'mdi:view-dashboard-outline', 'label' => $trans['navigation']['dashboard'] ?? 'Dashboard'],
		'search'    => ['icon' => 'mdi:magnify',                'label' => $trans['navigation']['search'] ?? 'Search'],
		'project'   => ['icon' => 'mdi:folder-outline',         'label' => $trans['navigation']['projects'] ?? 'Projects'],
		'email'     => ['icon' => 'mdi:email-outline',          'label' => $trans['navigation']['email'] ?? 'Email'],
		'vitals'    => ['icon' => 'mdi:heart-pulse',            'label' => $trans['navigation']['vitals'] ?? 'Vitals'],
		'bcrypt'    => ['icon' => 'mdi:lock-outline',           'label' => $trans['navigation']['bcrypt'] ?? 'Bcrypt'],
		'settings'  => ['icon' => 'mdi:cog',                    'label' => $trans['navigation']['settings'] ?? 'Settings'],
	];
	
	function isActive($key, $current) {
		return $key === $current ? 'active text-primary' : 'text-dark';
	}
?>

<div class="sidebar d-flex flex-column bg-light align-items-center py-4 border-end" style="width: 60px; min-height: 100vh; font-size: 0.75rem;">
	<ul class="nav flex-column text-center gap-4">
		<?php foreach ($modules as $key => $data): ?>
			<li class="nav-item">
				<a class="nav-link <?= isActive($key, $current) ?>" href="?module=<?= $key ?>" title="<?= $data['label'] ?>">
					<iconify-icon icon="<?= $data['icon'] ?>" width="24"></iconify-icon>
					<small class="d-block"><?= $data['label'] ?></small>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
