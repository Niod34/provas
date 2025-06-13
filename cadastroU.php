<?php
require_once 'conexao.php';

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);

    if (!empty($nome) && !empty($email)) {
        $sql = "INSERT INTO Usuario (nome, email) VALUES (:nome, :email)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":email", $email);

        if ($stmt->execute()) {
            echo "<script>alert('Usuário cadastrado com sucesso!');</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar.');</script>";
        }
    } else {
        echo "<script>alert('Preencha todos os campos.');</script>";
    }
}

// Página atual
$paginaAtual = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuários</title>
    <style>
    :root {
        --primary: #003366; /* Azul SENAI */
        --primary-dark: #002244;
        --primary-light: #e6f2ff;
        --accent: #0066cc;
        --text: #333333;
        --light: #f8f9fa;
        --white: #ffffff;
        --gray: #e9ecef;
        --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    body {
        font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        background-color: var(--light);
        color: var(--text);
        line-height: 1.6;
        margin: 0;
        padding: 0;
    }

    .logo {
        display: block;
        margin: 30px auto;
        width: 200px;
        height: auto;
        filter: drop-shadow(var(--shadow));
    }

    .menu {
        background: var(--primary);
        color: var(--white);
        padding: 0 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: var(--shadow);
        height: 70px;
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .menu .titulo {
        font-weight: 700;
        font-size: 1.4rem;
        letter-spacing: 0.5px;
    }

    .menu .links {
        display: flex;
        gap: 15px;
    }

    .menu .links a {
        color: var(--white);
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 500;
        transition: var(--transition);
        display: flex;
        align-items: center;
    }

    .menu .links a:hover {
        background-color: rgba(255, 255, 255, 0.15);
        transform: translateY(-2px);
    }

    .menu .links a.ativo {
        background-color: var(--primary-dark);
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .container {
        max-width: 800px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .card {
        background: var(--white);
        border-radius: 10px;
        box-shadow: var(--shadow);
        padding: 30px;
        margin-top: 30px;
    }

    h3 {
        color: var(--primary);
        margin: 0 0 25px 0;
        font-size: 1.5rem;
        font-weight: 600;
        position: relative;
        padding-bottom: 10px;
    }

    h3::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background: var(--accent);
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--primary);
    }

    input[type="text"],
    input[type="email"] {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--gray);
        border-radius: 6px;
        font-size: 1rem;
        transition: var(--transition);
        background-color: var(--light);
    }

    input[type="text"]:focus,
    input[type="email"]:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.2);
        background-color: var(--white);
    }

    input[type="submit"] {
        background-color: var(--primary);
        color: var(--white);
        padding: 12px 25px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 600;
        transition: var(--transition);
        width: 100%;
        margin-top: 10px;
        letter-spacing: 0.5px;
    }

    input[type="submit"]:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .menu {
            flex-direction: column;
            height: auto;
            padding: 15px;
        }
        
        .menu .titulo {
            margin-bottom: 15px;
        }
        
        .menu .links {
            flex-direction: column;
            width: 100%;
            gap: 5px;
        }
        
        .menu .links a {
            justify-content: center;
            padding: 8px;
        }
    }
</style>
</head>
<body>

<img src="logo_senai.png" alt="Logo SENAI" class="logo"><br>

<div class="menu">
    <div class="titulo">Gerenciamento de Tarefas</div>
    <div class="links">
        <a href="cadastroU.php" class="<?= $paginaAtual == 'cadastroU.php' ? 'ativo' : '' ?>">Cadastro de Usuários</a>
        <a href="cadastrot.php" class="<?= $paginaAtual == 'cadastrot.php' ? 'ativo' : '' ?>">Cadastro de Tarefas</a>
        <a href="gerenciarT.php" class="<?= $paginaAtual == 'gerenciarT.php' ? 'ativo' : '' ?>">Gerenciar Tarefas</a>
    </div>
</div>

<h3>Cadastro de Usuários</h3>
<form method="post" action="">
    <label for="nome">Nome:</label>
    <input type="text" name="nome" required>

    <label for="email">Email:</label>
    <input type="email" name="email" required>

    <input type="submit" value="Cadastrar">
</form>

</body>
</html>
