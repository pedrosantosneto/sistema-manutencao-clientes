<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';

if (!empty($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}

$erro = '';
$modo = 'login';
$nomeCadastro = '';
$loginCadastro = '';
$loginLogin = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? 'login';
    $modo = $acao === 'cadastro' ? 'cadastro' : 'login';

    if ($acao === 'cadastro') {
        $nomeCadastro = trim($_POST['nome'] ?? '');
        $loginCadastro = trim($_POST['login'] ?? '');
        $senhaCadastro = $_POST['senha'] ?? '';
        $confirmarSenha = $_POST['confirmar_senha'] ?? '';

        if ($nomeCadastro === '' || $loginCadastro === '' || $senhaCadastro === '' || $confirmarSenha === '') {
            $erro = 'Preencha nome, usuário, senha e confirmação.';
        } elseif (strlen($senhaCadastro) < 6) {
            $erro = 'A senha deve ter pelo menos 6 caracteres.';
        } elseif ($senhaCadastro !== $confirmarSenha) {
            $erro = 'As senhas não conferem.';
        } else {
            $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE login = ?');
            $stmt->execute([$loginCadastro]);

            if ($stmt->fetch()) {
                $erro = 'Este usuário já existe.';
            } else {
                $senhaHash = password_hash($senhaCadastro, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare('INSERT INTO usuarios (nome, login, senha) VALUES (?, ?, ?)');
                $stmt->execute([$nomeCadastro, $loginCadastro, $senhaHash]);

                $_SESSION['usuario_id'] = (int) $pdo->lastInsertId();
                $_SESSION['usuario_nome'] = $nomeCadastro;
                header('Location: dashboard.php');
                exit;
            }
        }
    } else {
        $loginLogin = trim($_POST['login'] ?? '');
        $senhaLogin = $_POST['senha'] ?? '';

        if ($loginLogin === '' || $senhaLogin === '') {
            $erro = 'Preencha usuário e senha.';
        } else {
            $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE login = ?');
            $stmt->execute([$loginLogin]);
            $usuario = $stmt->fetch();

            if ($usuario && password_verify($senhaLogin, $usuario['senha'])) {
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
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Manutenção</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page" data-auth-mode="<?= htmlspecialchars($modo, ENT_QUOTES, 'UTF-8') ?>">
    <div class="auth-shell">
        <div class="auth-card">
            <div class="login-logo">SM</div>

            <div class="auth-tabs">
                <button type="button" class="auth-tab <?= $modo === 'login' ? 'active' : '' ?>" data-auth-toggle="login">Entrar</button>
                <button type="button" class="auth-tab <?= $modo === 'cadastro' ? 'active' : '' ?>" data-auth-toggle="register">Cadastrar</button>
            </div>

            <?php if ($erro): ?>
                <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <div class="auth-panel <?= $modo === 'login' ? 'active' : '' ?>" data-auth-panel="login">
                <h1>Acesso ao Sistema</h1>
                <p class="subtitle">Informe suas credenciais para continuar</p>

                <form method="POST" action="login.php" novalidate>
                    <input type="hidden" name="acao" value="login">
                    <div class="form-group">
                        <label for="login-user">Usuário</label>
                        <input type="text" id="login-user" name="login" value="<?= htmlspecialchars($loginLogin, ENT_QUOTES, 'UTF-8') ?>" placeholder="Digite seu usuário" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="login-password">Senha</label>
                        <div class="password-wrap">
                            <input type="password" id="login-password" name="senha" placeholder="Digite sua senha" required>
                            <button type="button" class="btn-show-password" data-password-field="login-password">Mostrar</button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                </form>
            </div>

            <div class="auth-panel <?= $modo === 'cadastro' ? 'active' : '' ?>" data-auth-panel="register">
                <h1>Criar Conta</h1>
                <p class="subtitle">Cadastre-se para acessar o painel</p>

                <form method="POST" action="login.php" novalidate>
                    <input type="hidden" name="acao" value="cadastro">
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($nomeCadastro, ENT_QUOTES, 'UTF-8') ?>" placeholder="Seu nome completo" required>
                    </div>
                    <div class="form-group">
                        <label for="cadastro-login">Usuário</label>
                        <input type="text" id="cadastro-login" name="login" value="<?= htmlspecialchars($loginCadastro, ENT_QUOTES, 'UTF-8') ?>" placeholder="Escolha um usuário" required>
                    </div>
                    <div class="form-group">
                        <label for="cadastro-senha">Senha</label>
                        <div class="password-wrap">
                            <input type="password" id="cadastro-senha" name="senha" placeholder="Crie uma senha" required>
                            <button type="button" class="btn-show-password" data-password-field="cadastro-senha">Mostrar</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirmar-senha">Confirmar senha</label>
                        <div class="password-wrap">
                            <input type="password" id="confirmar-senha" name="confirmar_senha" placeholder="Repita a senha" required>
                            <button type="button" class="btn-show-password" data-password-field="confirmar-senha">Mostrar</button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Cadastrar</button>
                </form>
            </div>

            <p class="hint">Usuário padrão: <strong>admin</strong> / Senha: <strong>admin123</strong></p>
        </div>
    </div>
    <script src="assets/js/script.js"></script>
</body>
</html>
