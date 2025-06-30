<?php
	include 'includes/header.php';  // <html><head><body> starts
	include 'includes/navbar.php';  // Top nav bar
?>

<div class="container-fluid">
	<div class="row">
		
		<!-- Sidebar -->
		<div class="col-auto p-0">
			<?php include 'includes/sidebar.php'; ?>
		</div>
		
		<!-- Main Content -->
		<div class="col ps-md-4 pt-4">
			<?php
                               $module = basename($_GET['module'] ?? 'dashboard');
                               $baseDir = realpath(__DIR__ . '/modules');
                               $path = realpath(__DIR__ . "/modules/$module/view.php");

                               if ($path && strpos($path, $baseDir) === 0 && file_exists($path)) {
                                       include $path;
                               } else {
                                       echo "<div class='alert alert-danger'>Module not found.</div>";
                               }
			?>
		</div>
	
	</div>
</div>

<?php include 'includes/footer.php'; ?>
