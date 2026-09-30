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
