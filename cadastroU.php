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
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .logo {
            display: block;
            margin: 20px auto;
            width: 300px;
            height: auto;
        }

        .menu {
            background: #007bff;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .menu .titulo {
            font-weight: bold;
        }

        .menu .links a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 5px;
        }

        .menu .links a.ativo {
            background-color: #0056b3;
        }

        form {
            margin-top: 30px;
        }

        label, input {
            display: block;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 5px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
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
