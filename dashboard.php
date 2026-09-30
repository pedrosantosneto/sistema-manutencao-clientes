<?php
require_once __DIR__ . '/includes/auth.php';
exigirLogin();
require_once __DIR__ . '/config/database.php';

$totalClientes = $pdo->query('SELECT COUNT(*) AS total FROM clientes')->fetch()['total'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Manutenção</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="topbar">
        <h1>Sistema de Manutenção</h1>
        <div class="user-info">
            <span>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></span>
            <a href="logout.php" class="btn btn-outline">Sair</a>
        </div>
    </header>

    <main class="container">
        <div class="card">
            <h2>Bem-vindo(a) ao painel</h2>
            <p>Total de clientes cadastrados: <strong><?= (int) $totalClientes ?></strong></p>
            <div class="actions">
                <a href="clientes/listar.php" class="btn btn-primary">Ver Clientes</a>
                <a href="clientes/cadastrar.php" class="btn btn-secondary">Novo Cliente</a>
            </div>
        </div>
    </main>
    <script src="assets/js/script.js"></script>
</body>
</html>
