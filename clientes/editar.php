<?php
require_once __DIR__ . '/../includes/auth.php';
exigirLogin();
require_once __DIR__ . '/../config/database.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM clientes WHERE id = ?');
$stmt->execute([$id]);
$cliente = $stmt->fetch();

if (!$cliente) {
    header('Location: listar.php');
    exit;
}

$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');

    if ($nome === '') {
        $erros[] = 'O campo Nome é obrigatório.';
    }
    if ($email === '') {
        $erros[] = 'O campo E-mail é obrigatório.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Informe um e-mail válido.';
    }
    if ($cidade === '') {
        $erros[] = 'O campo Cidade é obrigatório.';
    }
    if ($telefone !== '' && !preg_match('/^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/', $telefone)) {
        $erros[] = 'Informe um telefone válido, ex: (00) 00000-0000.';
    }

    if (empty($erros)) {
        $update = $pdo->prepare('UPDATE clientes SET nome = ?, email = ?, telefone = ?, cidade = ? WHERE id = ?');
        $update->execute([$nome, $email, $telefone, $cidade, $id]);
        header('Location: listar.php?sucesso=1');
        exit;
    }

    $cliente = compact('nome', 'email', 'telefone', 'cidade');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
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
            <h2>Editar Cliente</h2>

            <?php if (!empty($erros)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($erros as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="editar.php?id=<?= (int) $id ?>" novalidate>
                <div class="form-group">
                    <label for="nome">Nome *</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($cliente['nome']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail *</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($cliente['email']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($cliente['telefone']) ?>" placeholder="(00) 00000-0000" pattern="\(?\d{2}\)?\s?\d{4,5}-?\d{4}" title="Formato esperado: (00) 00000-0000">
                </div>
                <div class="form-group">
                    <label for="cidade">Cidade *</label>
                    <input type="text" id="cidade" name="cidade" value="<?= htmlspecialchars($cliente['cidade']) ?>" required>
                </div>
                <div class="actions">
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    <a href="listar.php" class="btn btn-outline">Cancelar</a>
                </div>
            </form>
        </div>
    </main>
    <script src="../assets/js/script.js"></script>
</body>
</html>
