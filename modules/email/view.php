<?php
	// modules/email/view.php
	
	require_once 'includes/functions.php';
	
	$emailDir = 'emails/';
	$emails = glob($emailDir . '*.html');
	$current = $_GET['email'] ?? null;
	
	// Handle email deletion (POST)
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

<div class="container-fluid py-3">
	<div class="row">
		<!-- Sidebar: Inbox List -->
		<div class="col-md-4 col-lg-3 border-end">
                        <h5 class="mb-3">📥 <span data-i18n="email.inbox">Inbox</span></h5>
                        <?php if (count($emails) === 0): ?>
                                <div class="alert alert-warning small" data-i18n="email.no_emails">No emails found.</div>
			<?php else: ?>
				<div class="list-group small">
					<?php foreach ($emails as $emailFile):
						$filename = basename($emailFile);
						$subject = ucwords(str_replace(['-', '_'], ' ', pathinfo($filename, PATHINFO_FILENAME)));
						$isActive = ($filename === $current) ? 'active' : '';
						?>
                                                <a href="?module=email&email=<?= urlencode($filename) ?>" class="list-group-item list-group-item-action <?= $isActive ?>">
                                                        <?= htmlspecialchars($subject) ?>
                                                        <form method="post" class="d-inline float-end" onsubmit="return confirm('<?= t('email.confirm_delete') ?>');">
                                                                <input type="hidden" name="delete" value="<?= htmlspecialchars($filename) ?>">
                                                                <button class="btn btn-sm btn-link text-danger p-0 ms-2" title="<?= t('buttons.delete') ?>">&times;</button>
                                                        </form>
                                                </a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		
		<!-- Email Viewer -->
		<div class="col-md-8">
                        <?php if (isset($_GET['deleted'])): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <?= t('email.deleted_success') ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                        <?php endif; ?>
			
			<?php
				if ($current && file_exists($emailDir . $current)) {
					echo "<div class='card shadow-sm'><div class='card-body'>";
					include $emailDir . $current;
					echo "</div></div>";
                                } else {
                                        echo "<div class='text-muted'>" . t('email.select_prompt') . "</div>";
                                }
			?>
		</div>
	</div>
</div>
