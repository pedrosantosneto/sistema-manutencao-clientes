<?php
/**
 * Conexão com o banco de dados (PDO) + seed inicial do usuário admin.
 * Ajuste as credenciais abaixo conforme o seu ambiente (XAMPP/WAMP/etc.).
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'sistema_manutencao');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    // Sem servidor MySQL disponível: cai para um SQLite local, apenas para
    // permitir rodar/visualizar o sistema sem precisar instalar um SGBD.
    // Para a entrega oficial, use o MySQL com o database.sql fornecido.
    $sqlitePath = __DIR__ . '/../storage/preview.sqlite';
    if (!is_dir(dirname($sqlitePath))) {
        mkdir(dirname($sqlitePath), 0777, true);
    }
    $novo = !file_exists($sqlitePath);

    $pdo = new PDO('sqlite:' . $sqlitePath, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    if ($novo) {
        $pdo->exec('CREATE TABLE usuarios (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nome VARCHAR(100) NOT NULL,
            login VARCHAR(50) NOT NULL UNIQUE,
            senha VARCHAR(255) NOT NULL,
            criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )');
        $pdo->exec('CREATE TABLE clientes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nome VARCHAR(150) NOT NULL,
            email VARCHAR(150) NOT NULL,
            telefone VARCHAR(30),
            cidade VARCHAR(100),
            criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )');
    }
}

// Cria o usuário administrador padrão na primeira execução, caso ainda não exista.
$total = $pdo->query('SELECT COUNT(*) AS total FROM usuarios')->fetch()['total'];
if ($total == 0) {
    $senhaHash = password_hash('admin123', PASSWORD_BCRYPT);
    $stmt = $pdo->prepare('INSERT INTO usuarios (nome, login, senha) VALUES (?, ?, ?)');
    $stmt->execute(['Administrador', 'admin', $senhaHash]);
}
