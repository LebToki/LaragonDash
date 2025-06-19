<?php
	// modules/email/view.php
	// Combined inbox + email view in a cleaner UI
	
	require_once 'includes/functions.php';
	
	$emails = glob('emails/*.html');
	$current = $_GET['email'] ?? null;
?>

<div class="container-fluid py-3">
	<div class="row">
		<!-- Sidebar: Email list -->
		<div class="col-md-4 col-lg-3 border-end">
			<h5 class="mb-3">📥 Inbox</h5>
			<div class="list-group small">
				<?php foreach ($emails as $emailFile):
					$filename = basename($emailFile);
					$subject = preg_replace('/[-_]/', ' ', pathinfo($filename, PATHINFO_FILENAME));
					$active = ($filename === $current) ? 'active' : '';
					?>
					<a href="?module=email&email=<?= urlencode($filename) ?>" class="list-group-item list-group-item-action <?= $active ?>">
						<?= htmlspecialchars($subject) ?>
						<form method="post" action="?module=email" class="d-inline float-end" onsubmit="return confirm('Delete this email?');">
							<input type="hidden" name="delete" value="<?= htmlspecialchars($filename) ?>">
							<button class="btn btn-sm btn-link text-danger p-0 ms-2" title="Delete">&times;</button>
						</form>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
		
		<!-- Email Viewer -->
		<div class="col-md-8">
			<?php
				if (isset($_POST['delete']) && in_array("emails/" . $_POST['delete'], $emails)) {
					unlink("emails/" . $_POST['delete']);
					echo "<div class='alert alert-success'>Email deleted.</div>";
				}
				
				if ($current && file_exists("emails/" . $current)) {
					echo "<div class='card shadow-sm'><div class='card-body'>";
					include "emails/" . $current;
					echo "</div></div>";
				} else {
					echo "<div class='text-muted'>Select an email to view.</div>";
				}
			?>
		</div>
	</div>
</div>
