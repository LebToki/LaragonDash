<?php
// generate.php
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$password = trim($_POST['password'] ?? '');
		
		if (!empty($password)) {
			$hash = password_hash($password, PASSWORD_BCRYPT);
			echo "<strong>Generated Bcrypt Hash:</strong><br><code>$hash</code>";
		} else {
			echo "<span style='color: red;'>❗ Please enter a password to generate a hash.</span>";
		}
	} else {
		?>
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<title>Generate Bcrypt Hash</title>
			<style>
          body {
              font-family: 'Poppins', sans-serif;
              margin: 2rem;
          }
          input[type="text"] {
              padding: 0.5rem;
              width: 300px;
          }
          button {
              padding: 0.5rem 1rem;
              margin-left: 10px;
          }
			</style>
		</head>
		<body>
		<h2>Generate Bcrypt Password Hash</h2>
		<form method="POST">
			<input type="text" name="password" placeholder="Enter password" required>
			<button type="submit">Generate</button>
		</form>
		</body>
		</html>
		<?php
	}
