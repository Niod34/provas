 <?php
require_once 'conexao.php';

// Verifica se o ID da tarefa foi passado
if (!isset($_GET['id_tarefa'])) {
    echo "Tarefa não especificada.";
    exit;
}

$id_tarefa = $_GET['id_tarefa'];

// Consulta os dados da tarefa pelo ID
$stmt = $pdo->prepare("SELECT * FROM Tarefa WHERE id_tarefa = :id");
$stmt->execute([':id' => $id_tarefa]);
$tarefa = $stmt->fetch(PDO::FETCH_ASSOC);

// Se não encontrou a tarefa
if (!$tarefa) {
    echo "Tarefa não encontrada.";
    exit;
}

// Atualização do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = trim($_POST['descricao']);
    $setor = trim($_POST['setor']);
    $prioridade = $_POST['prioridade'];

    if (!empty($descricao) && !empty($setor)) {
        $update = $pdo->prepare("UPDATE Tarefa SET descricao = :descricao, setor = :setor, prioridade = :prioridade WHERE id_tarefa = :id");
        $update->execute([
            ':descricao' => $descricao,
            ':setor' => $setor,
            ':prioridade' => $prioridade,
            ':id' => $id_tarefa
        ]);

        echo "<script>alert('Tarefa atualizada com sucesso!'); window.location.href = 'gerenciarT.php';</script>";
        exit;
    } else {
        echo "<script>alert('Preencha todos os campos.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Tarefa</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        form { max-width: 500px; margin: auto; }
        label, input, select, textarea {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            padding: 8px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>

<h2>Editar Tarefa</h2>

<form method="post">
    <label for="descricao">Descrição:</label>
    <textarea name="descricao" required><?= htmlspecialchars($tarefa['descricao']) ?></textarea>

    <label for="setor">Setor:</label>
    <input type="text" name="setor" value="<?= htmlspecialchars($tarefa['setor']) ?>" required>

    <label for="prioridade">Prioridade:</label>
    <select name="prioridade" required>
        <option value="baixa" <?= $tarefa['prioridade'] == 'baixa' ? 'selected' : '' ?>>Baixa</option>
        <option value="media" <?= $tarefa['prioridade'] == 'media' ? 'selected' : '' ?>>Média</option>
        <option value="alta" <?= $tarefa['prioridade'] == 'alta' ? 'selected' : '' ?>>Alta</option>
    </select>

    <input type="submit" value="Atualizar Tarefa">
</form>

</body>
</html>
