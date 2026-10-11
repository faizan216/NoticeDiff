<?php
require_once __DIR__ . '/includes/auth.php';
header('Location: ' . BASE_URL . (isLoggedIn() ? '/home.php' : '/auth/login.php'));
exit;
