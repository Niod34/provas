<?php
require_once 'conexao.php';

// Buscar usuários para preencher o select
$usuarios = $pdo->query("SELECT id_usuario, nome FROM Usuario")->fetchAll(PDO::FETCH_ASSOC);

// Processar formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descricao = trim($_POST["descricao"]);
    $setor = trim($_POST["setor"]);
    $id_usuario = $_POST["id_usuario"];
    $prioridade = $_POST["prioridade"];
    $data_cadastro = date("Y-m-d");
    $status = "a fazer";

    if (!empty($descricao) && !empty($setor) && !empty($id_usuario) && !empty($prioridade)) {
        $sql = "INSERT INTO Tarefa (id_usuario, descricao, setor, prioridade, data_cadastro, status) 
                VALUES (:id_usuario, :descricao, :setor, :prioridade, :data_cadastro, :status)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':setor', $setor);
        $stmt->bindParam(':prioridade', $prioridade);
        $stmt->bindParam(':data_cadastro', $data_cadastro);
        $stmt->bindParam(':status', $status);

        if ($stmt->execute()) {
            echo "<script>alert('Tarefa cadastrada com sucesso!');</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar tarefa.');</script>";
        }
    } else {
        echo "<script>alert('Preencha todos os campos.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Tarefas</title>
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
        }
        form {
            margin-top: 30px;
        }
        label, input, select {
            display: block;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 5px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<img src="logo_senai.png" alt="Logo SENAI" class="logo"><br><br>

<div class="menu">
    <div class="titulo">Gerenciamento de Tarefas</div>
    <div class="links">
        <a href="cadastroU.php">Cadastro de Usuários</a>
        <a href="cadastrot.php">Cadastro de Tarefas</a>
        <a href="gerenciarT.php">Gerenciar Tarefas</a>
    </div>
</div>

<h3>Cadastro de Tarefas</h3>

<form method="post" action="">
    <label for="descricao">Descrição:</label>
    <input type="text" name="descricao" required>

    <label for="setor">Setor:</label>
    <input type="text" name="setor" required>

    <label for="id_usuario">Usuário:</label>
    <select name="id_usuario" required>
        <option value="">Selecione</option>
        <?php foreach ($usuarios as $usuario): ?>
            <option value="<?= $usuario['id_usuario'] ?>"><?= htmlspecialchars($usuario['nome']) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="prioridade">Prioridade:</label>
    <select name="prioridade" required>
        <option value="baixa">Baixa</option>
        <option value="média">Média</option>
        <option value="alta">Alta</option>
    </select>

    <input type="submit" value="Cadastrar">
</form>

</body>
</html>
