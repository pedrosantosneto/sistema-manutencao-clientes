<?php
require_once __DIR__ . '/../includes/auth.php';
exigirLogin();
require_once __DIR__ . '/../config/database.php';

// PONTO DE MANUTENÇÃO: exclusão executada diretamente via GET, sem
// confirmação (JS confirm) e sem proteção contra requisição forjada (CSRF).
$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare('DELETE FROM clientes WHERE id = ?');
    $stmt->execute([$id]);
}

header('Location: listar.php?sucesso=1');
exit;
