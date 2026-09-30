<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';

// Se já estiver logado, vai direto para o dashboard.
if (!empty($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($login === '' || $senha === '') {
        $erro = 'Preencha usuário e senha.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE login = ?');
        $stmt->execute([$login]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            header('Location: dashboard.php');
            exit;
        } else {
            $erro = 'Usuário ou senha inválidos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Manutenção</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
    <div class="login-box">
        <h1>Acesso ao Sistema</h1>
        <p class="subtitle">Informe suas credenciais para continuar</p>

        <?php if ($erro): ?>
            <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php" novalidate>
            <div class="form-group">
                <label for="login">Usuário</label>
                <input type="text" id="login" name="login" placeholder="Digite seu usuário" required autofocus>
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
        </form>

        <p class="hint">Usuário padrão: <strong>admin</strong> / Senha: <strong>admin123</strong></p>
    </div>
    <script src="assets/js/script.js"></script>
</body>
</html>
