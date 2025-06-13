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
    :root {
        --primary-color: #003366; /* Azul escuro SENAI */
        --secondary-color: #0066cc; /* Azul médio SENAI */
        --accent-color: #e6f2ff; /* Azul claro */
        --text-color: #333333;
        --light-gray: #f5f5f5;
        --white: #ffffff;
        --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
    }
    
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--light-gray);
    }
    
    .logo {
        height: 80px;
        width: auto;
        display: block;
        margin: 0 auto 30px;
    }
    
    .menu {
        background: var(--primary-color);
        color: var(--white);
        padding: 15px 0;
        box-shadow: var(--shadow);
        margin-bottom: 30px;
    }
    
    .menu .titulo {
        font-weight: bold;
        font-size: 1.2rem;
    }
    
    .menu .links a {
        color: var(--white);
        margin-left: 25px;
        text-decoration: none;
        padding: 5px 10px;
        border-radius: 4px;
        transition: var(--transition);
    }
    
    .menu .links a:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }
    
    form {
        background-color: var(--white);
        border-radius: 8px;
        box-shadow: var(--shadow);
        padding: 30px;
        margin-top: 30px;
        border-top: 4px solid var(--primary-color);
    }
    
    h3 {
        color: var(--primary-color);
        margin-bottom: 20px;
        font-size: 1.5rem;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--accent-color);
    }
    
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--primary-color);
    }
    
    input[type="text"],
    select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
        transition: var(--transition);
        margin-bottom: 20px;
    }
    
    input[type="text"]:focus,
    select:focus {
        outline: none;
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 2px rgba(0, 102, 204, 0.2);
    }
    
    input[type="submit"] {
        background-color: var(--primary-color);
        color: var(--white);
        padding: 12px 25px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 600;
        transition: var(--transition);
        width: 100%;
    }
    
    input[type="submit"]:hover {
        background-color: var(--secondary-color);
        transform: translateY(-2px);
    }
    
    @media (max-width: 768px) {
        .menu {
            text-align: center;
        }
        
        .menu .links {
            margin-top: 15px;
        }
        
        .menu .links a {
            margin: 0 10px;
            display: inline-block;
        }
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
