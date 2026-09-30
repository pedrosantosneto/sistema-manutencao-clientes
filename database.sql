-- Banco de dados do sistema de manutenção (trabalho acadêmico)
CREATE DATABASE IF NOT EXISTS sistema_manutencao CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_manutencao;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    login VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefone VARCHAR(30),
    cidade VARCHAR(100),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Obs: o usuário administrador padrão (login: admin / senha: admin123)
-- é criado automaticamente na primeira execução pelo próprio sistema
-- (ver config/database.php), já com a senha protegida por hash bcrypt.
