<?php
// verify.php
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$password = $_POST['password'] ?? '';
		$hash     = $_POST['hash'] ?? '';
		
		if (!empty($password) && !empty($hash)) {
			$match = password_verify($password, $hash);
			echo $match
				? "<span style='color: green;'>✅ Password matches the hash.</span>"
				: "<span style='color: red;'>❌ Password does not match.</span>";
		} else {
			echo "<span style='color: orange;'>⚠️ Please provide both password and hash.</span>";
		}
	} else {
		?>
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<title>Verify Bcrypt Hash</title>
			<style>
          body {
              font-family: 'Poppins', sans-serif;
              margin: 2rem;
          }
          input[type="text"] {
              padding: 0.5rem;
              width: 400px;
              margin-bottom: 10px;
          }
          button {
              padding: 0.5rem 1rem;
              margin-top: 5px;
          }
			</style>
		</head>
		<body>
		<h2>Verify Bcrypt Password Hash</h2>
		<form method="POST">
			<div>
				<input type="text" name="password" placeholder="Enter password" required><br>
				<input type="text" name="hash" placeholder="Enter bcrypt hash" required><br>
				<button type="submit">Verify</button>
			</div>
		</form>
		</body>
		</html>
		<?php
	}
