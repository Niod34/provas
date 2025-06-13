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
    :root {
        --primary: #003366; /* Azul SENAI */
        --primary-dark: #002244;
        --primary-light: #e6f2ff;
        --accent: #0066cc;
        --text: #333333;
        --light: #f8f9fa;
        --white: #ffffff;
        --gray: #e9ecef;
        --success: #4CAF50;
        --warning: #FFC107;
        --danger: #F44336;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.12);
        --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
        --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
        --transition: all 0.2s ease-in-out;
    }

    body {
        font-family: 'Roboto', 'Segoe UI', sans-serif;
        background-color: var(--light);
        color: var(--text);
        line-height: 1.6;
        padding: 0;
        margin: 0;
    }

    .logo {
        display: block;
        margin: 30px auto;
        width: 180px;
        height: auto;
        filter: drop-shadow(var(--shadow-sm));
    }

    .menu {
        background: var(--primary);
        color: var(--white);
        padding: 0 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: var(--shadow-md);
        height: 70px;
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .menu .titulo {
        font-weight: 600;
        font-size: 1.3rem;
        letter-spacing: 0.5px;
    }

    .menu .links {
        display: flex;
        gap: 1rem;
    }

    .menu .links a {
        color: var(--white);
        text-decoration: none;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        font-weight: 500;
        transition: var(--transition);
    }

    .menu .links a:hover {
        background-color: rgba(255, 255, 255, 0.15);
    }

    .menu .links a.ativo {
        background-color: var(--primary-dark);
        font-weight: 600;
    }

    .container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    h2 {
        color: var(--primary);
        font-size: 1.8rem;
        margin: 2rem 0 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--primary-light);
    }

    .kanban-board {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .coluna {
        background: var(--white);
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
    }

    .coluna h3 {
        color: var(--primary);
        font-size: 1.2rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--gray);
    }

    .tarefa {
        background: var(--white);
        border-radius: 6px;
        padding: 1.2rem;
        margin-bottom: 1rem;
        box-shadow: var(--shadow-sm);
        border-left: 4px solid var(--accent);
        transition: var(--transition);
    }

    .tarefa:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }

    .tarefa strong {
        color: var(--primary);
        font-weight: 500;
    }

    .botoes-linha {
        display: flex;
        gap: 0.8rem;
        margin: 1rem 0 0.8rem;
    }

    .btn {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 4px;
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    .editar {
        background-color: var(--accent);
        color: var(--white);
        text-decoration: none;
    }

    .excluir {
        background-color: var(--danger);
        color: var(--white);
    }

    .alterar-status {
        background-color: var(--success);
        color: var(--white);
    }

    .form-status {
        display: flex;
        gap: 0.8rem;
        margin-top: 1rem;
    }

    select {
        padding: 0.5rem;
        border: 1px solid var(--gray);
        border-radius: 4px;
        flex-grow: 1;
        background-color: var(--light);
    }

    /* Cores por status */
    .coluna:nth-child(1) .tarefa { border-left-color: var(--danger); }
    .coluna:nth-child(2) .tarefa { border-left-color: var(--warning); }
    .coluna:nth-child(3) .tarefa { border-left-color: var(--success); }

    @media (max-width: 768px) {
        .kanban-board {
            grid-template-columns: 1fr;
        }
        
        .menu {
            flex-direction: column;
            height: auto;
            padding: 1rem;
        }
        
        .menu .titulo {
            margin-bottom: 1rem;
        }
        
        .menu .links {
            width: 100%;
            justify-content: space-around;
        }
        
        .botoes-linha {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .form-status {
            flex-direction: column;
        }
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
                        <a href='editarT.php?id_tarefa={$tarefa['id_tarefa']}' class='editar'>Editar</a>

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
