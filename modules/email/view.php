<?php
	
	// Handle deletion BEFORE any output or includes
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
		$emailDir = 'D:/laragon/bin/sendmail/output/';
		$target = basename($_POST['delete']);
		$fullPath = $emailDir . $target;
		
		if (file_exists($fullPath)) {
			unlink($fullPath);
			echo "<script>location.href='?module=email&deleted=1';</script>";
			exit;
		}
	}
	
	// Now safe to include UI-related files
	require_once 'includes/functions.php';
	
	
	$emailDir = 'D:/laragon/bin/sendmail/output/';
	$emails = glob($emailDir . '*.{eml,txt}', GLOB_BRACE);
	
	usort($emails, fn($a, $b) => strcmp(basename($b), basename($a))); // DESC by filename
	
	$current = $_GET['email'] ?? null;
	
	// Handle deletion
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
		$target = basename($_POST['delete']);
		$fullPath = $emailDir . $target;
		if (in_array($fullPath, $emails) && file_exists($fullPath)) {
			unlink($fullPath);
			header("Location: ?module=email&deleted=1");
			exit;
		}
	}
?>

<div class="container-fluid py-4">
	<div class="row g-3">
		<!-- Sidebar Inbox -->
		<div class="col-md-4 col-lg-3 border-end" style="height: 80vh; overflow-y: auto;">
			<h5 class="mb-3">
				<iconify-icon icon="mdi:email-outline" class="me-1"></iconify-icon> Inbox
			</h5>
			
			<?php if (isset($_GET['deleted'])): ?>
				<div class="alert alert-success small alert-dismissible fade show" role="alert">
					Email deleted.
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			<?php endif; ?>
			
			<?php if (empty($emails)): ?>
				<div class="text-muted small">No emails found in <code><?= $emailDir ?></code>.</div>
			<?php else: ?>
				<div class="list-group small">
					<?php foreach ($emails as $file):
						$name = basename($file);
						$title = ucwords(str_replace(['-', '_'], ' ', pathinfo($name, PATHINFO_FILENAME)));
						$isActive = ($name === $current) ? 'active' : '';
						?>
						<div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= $isActive ?>">
							<a href="?module=email&email=<?= urlencode($name) ?>" class="text-decoration-none text-dark flex-grow-1">
								<?= htmlspecialchars($title) ?>
							</a>
							<form method="post" onsubmit="return confirm('Delete this email?')" class="ms-2">
								<input type="hidden" name="delete" value="<?= htmlspecialchars($name) ?>">
								<button class="btn btn-sm btn-outline-danger" title="Delete">&times;</button>
							</form>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		
		<!-- Email Content -->
		<div class="col-md-8 col-lg-9">
			<?php
				if ($current && file_exists($emailDir . $current)) {
					$content = file_get_contents($emailDir . $current);
					$ext = strtolower(pathinfo($current, PATHINFO_EXTENSION));
					
					echo '<div class="card shadow-sm" style="height: 80vh; overflow-y: auto;"><div class="card-body">';
					
					// Render HTML safely in iframe
					if (stripos($content, '<html') !== false || stripos($content, '<body') !== false) {
						echo '<iframe srcdoc="' . htmlspecialchars($content) . '" style="width:100%; height:70vh; border:none;"></iframe>';
					} else {
						echo '<pre style="white-space: pre-wrap; word-wrap: break-word;">' . htmlspecialchars($content) . '</pre>';
					}
					
					echo '</div></div>';
				} else {
					echo '<div class="text-muted d-flex align-items-center justify-content-center" style="height: 80vh;">
						<em>Select an email to view its contents</em>
					</div>';
				}
			?>
		</div>
	</div>
</div>
