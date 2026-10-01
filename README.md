# Sistema de Manutenção - Trabalho Acadêmico

Sistema web simples em **PHP + MySQL (PDO)** com autenticação de usuários e CRUD de Clientes.

## Estrutura

```
├── index.php              # redireciona para login ou dashboard
├── login.php               # tela de login
├── logout.php              # encerra a sessão
├── dashboard.php            # área protegida (painel inicial)
├── database.sql             # script de criação das tabelas
├── config/
│   └── database.php         # conexão PDO + seed do usuário admin
├── includes/
│   └── auth.php              # exigirLogin() - controle de acesso
├── clientes/
│   ├── cadastrar.php          # formulário de cadastro
│   ├── listar.php               # listagem de clientes
│   ├── editar.php                # edição de cliente
│   └── excluir.php                # exclusão de cliente
└── assets/
    ├── css/style.css            # estilo responsivo
    └── js/script.js               # validações client-side
```

## Como executar

1. Suba um servidor MySQL local (XAMPP, WAMP, Laragon, etc.) e importe o `database.sql`.
2. Ajuste as credenciais em `config/database.php` se necessário (host/usuário/senha).
3. Coloque a pasta do projeto no diretório servido pelo Apache/PHP (ex: `htdocs`).
4. Acesse `index.php` no navegador.
5. Login padrão criado automaticamente na primeira execução:
   - **Usuário:** `admin`
   - **Senha:** `admin123`



