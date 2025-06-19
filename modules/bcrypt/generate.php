<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['password'])) {
    echo password_hash($_POST['password'], PASSWORD_BCRYPT);
} else {
    echo "Enter a password to generate hash.";
}
