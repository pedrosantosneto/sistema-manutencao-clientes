<?php
require_once __DIR__ . '/includes/auth.php';
header('Location: ' . (empty($_SESSION['usuario_id']) ? 'login.php' : 'dashboard.php'));
exit;
