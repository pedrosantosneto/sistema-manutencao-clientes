<?php
require_once __DIR__ . '/../includes/auth.php';
exigirLogin();
require_once __DIR__ . '/../config/database.php';

$clientes = $pdo->query('SELECT * FROM clientes ORDER BY id DESC')->fetchAll();
$csrfToken = gerarTokenCSRF();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="topbar">
        <div class="brand">
            <span class="brand-icon">🛠️</span>
            <h1>Sistema de Manutenção</h1>
        </div>
        <div class="user-info">
            <span>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></span>
            <a href="../logout.php" class="btn btn-outline">Sair</a>
        </div>
    </header>

    <main class="container">
        <div class="card">
            <div class="card-header">
                <h2>Clientes Cadastrados</h2>
                <a href="cadastrar.php" class="btn btn-primary">+ Novo Cliente</a>
            </div>

            <?php if (isset($_GET['sucesso'])): ?>
                <div class="alert alert-success">Operação realizada com sucesso!</div>
            <?php endif; ?>

            <?php if (isset($_GET['erro']) && $_GET['erro'] === 'csrf'): ?>
                <div class="alert alert-error">Sessão expirou ou token CSRF inválido. Faça a ação novamente.</div>
            <?php endif; ?>

            <?php if (empty($clientes)): ?>
                <p class="empty-state">Nenhum cliente cadastrado ainda.</p>
            <?php else: ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Cidade</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clientes as $c): ?>
                                <tr>
                                    <td><?= (int) $c['id'] ?></td>
                                    <td><?= htmlspecialchars($c['nome']) ?></td>
                                    <td><?= htmlspecialchars($c['email']) ?></td>
                                    <td><?= htmlspecialchars($c['telefone'] ?: '-') ?></td>
                                    <td><?= htmlspecialchars($c['cidade']) ?></td>
                                    <td class="col-actions">
                                        <a href="editar.php?id=<?= (int) $c['id'] ?>" class="btn btn-small btn-secondary">Editar</a>
                                        <form method="POST" action="excluir.php" class="form-excluir">
                                            <input type="hidden" name="id" value="<?= (int) $c['id'] ?>">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                            <button type="submit" class="btn btn-small btn-danger">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <script src="../assets/js/script.js"></script>
</body>
</html>
