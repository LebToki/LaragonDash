<?php
       $module = basename($_GET['module'] ?? 'dashboard');
       $baseDir = realpath(__DIR__ . '/modules');
       $path = realpath(__DIR__ . "/modules/$module/view.php");

       if ($path && strpos($path, $baseDir) === 0 && file_exists($path)) {
               include $path;
       } else {
               echo "<div class='alert alert-danger'>Module not found.</div>";
       }
