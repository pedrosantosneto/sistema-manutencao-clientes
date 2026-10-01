<?php
require_once __DIR__ . '/../includes/auth.php';
exigirLogin();
require_once __DIR__ . '/../config/database.php';

// Exclusão só é aceita via POST e com um token CSRF válido da sessão.
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !validarTokenCSRF($_POST['csrf_token'] ?? null)) {
    header('Location: listar.php?erro=csrf');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare('DELETE FROM clientes WHERE id = ?');
    $stmt->execute([$id]);
}

header('Location: listar.php?sucesso=1');
exit;
