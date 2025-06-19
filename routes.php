<?php
	$module = $_GET['module'] ?? 'dashboard';
	include "modules/$module/view.php";
