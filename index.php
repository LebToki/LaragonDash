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
				$module = $_GET['module'] ?? 'dashboard';
				$path = "modules/$module/view.php";
				if (file_exists($path)) {
					include $path;
				} else {
					echo "<div class='alert alert-danger'>Module not found.</div>";
				}
			?>
		</div>
	
	</div>
</div>

<?php include 'includes/footer.php'; ?>
