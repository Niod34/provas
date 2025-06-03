<?php
require_once 'conexao.php';

// Atualização de status
if (isset($_POST['alterar_status'])) {
    $id_tarefa = $_POST['id_tarefa'];
    $novo_status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE Tarefa SET status = :status WHERE id_tarefa = :id");
    $stmt->execute([':status' => $novo_status, ':id' => $id_tarefa]);
}

// Exclusão
if (isset($_POST['excluir'])) {
    $id_tarefa = $_POST['id_tarefa'];
    $stmt = $pdo->prepare("DELETE FROM Tarefa WHERE id_tarefa = :id");
    $stmt->execute([':id' => $id_tarefa]);
}

// Consulta geral
$tarefas = $pdo->query("
    SELECT T.*, U.nome AS nome_usuario 
    FROM Tarefa T 
    JOIN Usuario U ON T.id_usuario = U.id_usuario
")->fetchAll(PDO::FETCH_ASSOC);

// Página atual
$paginaAtual = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Tarefas</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }

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
            text-decoration: none;
            margin-left: 20px;
            padding: 6px 10px;
            border-radius: 5px;
        }

        .menu .links a.ativo {
            background-color: #0056b3;
        }

        .coluna { width: 30%; float: left; margin-right: 3%; }
        .tarefa {
            background: #f0f0f0;  /* fundo cinza claro */
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 8px;
        }

  

        .clearfix::after { content: ""; display: table; clear: both; }

        form { display: inline; }
        button {
            margin: 3px 0;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .editar, .excluir, .alterar-status {
            background-color: #0056b3;
            color: white;
        }

        /* Alinhar botão editar e excluir lado a lado */
        .botoes-linha {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .botoes-linha form {
            margin: 0;
        }

        /* Status com botão lado a lado */
        .form-status {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<img src="logo_senai.png" alt="Logo SENAI" class="logo"><br>

<!-- Menu -->
<div class="menu">
    <div class="titulo">Gerenciamento de Tarefas</div>
    <div class="links">
        <a href="cadastroU.php" class="<?= $paginaAtual == 'cadastro_usuario.php' ? 'ativo' : '' ?>">Cadastro de Usuários</a>
        <a href="cadastrot.php" class="<?= $paginaAtual == 'cadastro_tarefa.php' ? 'ativo' : '' ?>">Cadastro de Tarefas</a>
        <a href="gerenciarT.php" class="<?= $paginaAtual == 'gerenciar_tarefas.php' ? 'ativo' : '' ?>">Gerenciar Tarefas</a>
    </div>
</div>

<h2>Tarefas</h2>
<div class="clearfix">
    <?php
    $statusList = ['a fazer' => 'A Fazer', 'fazendo' => 'Fazendo', 'pronto' => 'Pronto'];

    foreach ($statusList as $status => $titulo) {
        echo "<div class='coluna'><h3>$titulo</h3>";

        foreach ($tarefas as $tarefa) {
            if ($tarefa['status'] == $status) {
                echo "<div class='tarefa'>
                    <strong>Descrição:</strong> {$tarefa['descricao']}<br>
                    <strong>Setor:</strong> {$tarefa['setor']}<br>
                    <strong>Prioridade:</strong> {$tarefa['prioridade']}<br>
                    <strong>Vinculada a:</strong> {$tarefa['nome_usuario']}<br><br>

                    <div class='botoes-linha'>
                        <form method='post' action='editar_tarefa.php'>
                            <input type='hidden' name='id_tarefa' value='{$tarefa['id_tarefa']}'>
                            <button class='editar'>Editar</button>
                        </form>

                        <form method='post'>
                            <input type='hidden' name='id_tarefa' value='{$tarefa['id_tarefa']}'>
                            <button class='excluir' name='excluir'>Excluir</button>
                        </form>
                    </div>

                    <form method='post' class='form-status'>
                        <input type='hidden' name='id_tarefa' value='{$tarefa['id_tarefa']}'>
                        <select name='status' required>
                            <option value='a fazer'" . ($tarefa['status'] == 'a fazer' ? ' selected' : '') . ">A Fazer</option>
                            <option value='fazendo'" . ($tarefa['status'] == 'fazendo' ? ' selected' : '') . ">Fazendo</option>
                            <option value='pronto'" . ($tarefa['status'] == 'pronto' ? ' selected' : '') . ">Pronto</option>
                        </select>
                        <button class='alterar-status' name='alterar_status'>Alterar Status</button>
                    </form>
                </div>";
            }
        }

        echo "</div>";
    }
    ?>
</div>

</body>
</html>
