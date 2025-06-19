<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'], $_POST['hash'])) {
    echo password_verify($_POST['password'], $_POST['hash']) ? 'Match' : 'No match';
} else {
    echo 'Provide password and hash.';
}
