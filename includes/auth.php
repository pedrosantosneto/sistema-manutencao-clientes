<?php
/**
 * Controle de acesso básico: bloqueia páginas protegidas para quem não
 * estiver autenticado, redirecionando para a tela de login.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function exigirLogin(): void
{
    if (empty($_SESSION['usuario_id'])) {
        header('Location: ' . (strpos($_SERVER['SCRIPT_NAME'], '/clientes/') !== false ? '../login.php' : 'login.php'));
        exit;
    }
}

// Gera (ou reaproveita) o token CSRF da sessão, usado para proteger ações destrutivas.
function gerarTokenCSRF(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validarTokenCSRF(?string $token): bool
{
    return !empty($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
