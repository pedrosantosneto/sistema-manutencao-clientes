<?php
require_once __DIR__ . '/../includes/auth.php';
exigirLogin();
require_once __DIR__ . '/../config/database.php';

$erros = [];
$dados = ['nome' => '', 'email' => '', 'telefone' => '', 'cidade' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados['nome'] = trim($_POST['nome'] ?? '');
    $dados['email'] = trim($_POST['email'] ?? '');
    $dados['telefone'] = trim($_POST['telefone'] ?? '');
    $dados['cidade'] = trim($_POST['cidade'] ?? '');

    // Validação básica dos campos obrigatórios.
    if ($dados['nome'] === '') {
        $erros[] = 'O campo Nome é obrigatório.';
    }
    if ($dados['email'] === '') {
        $erros[] = 'O campo E-mail é obrigatório.';
    } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Informe um e-mail válido.';
    }
    if ($dados['cidade'] === '') {
        $erros[] = 'O campo Cidade é obrigatório.';
    }
    // Telefone é opcional, mas quando informado precisa seguir um formato válido.
    if ($dados['telefone'] !== '' && !preg_match('/^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/', $dados['telefone'])) {
        $erros[] = 'Informe um telefone válido, ex: (00) 00000-0000.';
    }

    if (empty($erros)) {
        $stmt = $pdo->prepare('INSERT INTO clientes (nome, email, telefone, cidade) VALUES (?, ?, ?, ?)');
        $stmt->execute([$dados['nome'], $dados['email'], $dados['telefone'], $dados['cidade']]);
        header('Location: listar.php?sucesso=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Cliente</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="topbar">
        <h1>Sistema de Manutenção</h1>
        <div class="user-info">
            <span>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></span>
            <a href="../logout.php" class="btn btn-outline">Sair</a>
        </div>
    </header>

    <main class="container">
        <div class="card">
            <h2>Cadastrar Cliente</h2>

            <?php if (!empty($erros)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($erros as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="cadastrar.php" novalidate>
                <div class="form-group">
                    <label for="nome">Nome *</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($dados['nome']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail *</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($dados['email']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($dados['telefone']) ?>" placeholder="(00) 00000-0000" pattern="\(?\d{2}\)?\s?\d{4,5}-?\d{4}" title="Formato esperado: (00) 00000-0000">
                </div>
                <div class="form-group">
                    <label for="cidade">Cidade *</label>
                    <input type="text" id="cidade" name="cidade" value="<?= htmlspecialchars($dados['cidade']) ?>" required>
                </div>
                <div class="actions">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="listar.php" class="btn btn-outline">Cancelar</a>
                </div>
            </form>
        </div>
    </main>
    <script src="../assets/js/script.js"></script>
</body>
</html>
