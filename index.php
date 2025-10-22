<?php
// =========================================================================
// usuario_crud.php - CRUD para a tabela Usuario (com tipo de acesso)
// =========================================================================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("conexao.php");

// Definições de Tipos para o campo ENUM
$tipos_usuario = ['Administrador', 'Consultor', 'Cliente'];

function array_by_ref(&$array) {
    $refs = [];
    foreach ($array as $key => $value)
        $refs[$key] = &$array[$key];
    return $refs;
}

$acao = $_GET['acao'] ?? 'listar';
$msg = "";
$id = isset($_GET['id_usuario']) ? (int)$_GET['id_usuario'] : 0;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>CRUD de Usuários | Gerenciamento de Acesso</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8f8f8; margin: 0; }
        center { max-width: 900px; margin: 40px auto; padding: 20px; background: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-radius: 8px; }
        h1, h2 { color: #6a0dad; text-align: center; margin-bottom: 25px; }
        p { text-align: center; }
        p a { color: #6a0dad; text-decoration: none; font-weight: bold; padding: 5px 10px; border-radius: 5px; }
        p a:hover { background: #e0b0ff; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; border-radius: 8px; overflow: hidden; }
        th, td { border: 1px solid #eee; padding: 10px 12px; text-align: left; }
        th { background: #6a0dad; color: #fff; text-transform: uppercase; font-size: 14px; }
        tr:nth-child(even) { background: #f9f9f9; }
        tr:hover { background: #f0e6ff; }
        td a { color: #6a0dad; text-decoration: none; margin-right: 10px; }
        td a:hover { text-decoration: underline; }
        .success, .error { padding: 10px; border-radius: 5px; margin: 15px 0; text-align: center; font-weight: bold; }
        .success { color: #155724; background: #d4edda; border: 1px solid #c3e6cb; }
        .error { color: #721c24; background: #f8d7da; border: 1px solid #f5c6cb; }
        form { background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ddd; margin-top: 20px; }
        form label { display: block; margin-bottom: 8px; font-weight: 600; }
        form input, form select { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px; font-size: 15px; }
        form input[type="submit"] { background: #6a0dad; color: white; border: none; cursor: pointer; font-weight: bold; transition: 0.3s; }
        form input[type="submit"]:hover { background: #8a2be2; }
    </style>
</head>
<body>
<center>
<h1>Gerenciamento de Usuários (CRUD)</h1>
<p><a href="?acao=listar">Listar Usuários</a> | <a href="?acao=cadastrar">Novo Cadastro</a></p>

<?php
// ==========================================================================
// CADASTRAR
// ==========================================================================
if ($acao === 'cadastrar') {
    if (isset($_POST['enviar'])) {
        $nome = trim($_POST['nome']);
        $email = trim($_POST['email']);
        $senha = $_POST['senha'];
        $tipo = $_POST['tipo'];

        if (!in_array($tipo, $tipos_usuario)) {
            $msg = "<p class='error'>Tipo de usuário inválido.</p>";
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO Usuario (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nome, $email, $senha_hash, $tipo);

            if ($stmt->execute()) {
                $msg = "<p class='success'>Usuário cadastrado com sucesso!</p>";
                $_POST = [];
            } else {
                $msg = "<p class='error'>Erro ao cadastrar: " . $stmt->error . "</p>";
            }
            $stmt->close();
        }
    }
    ?>

    <h2>Cadastrar Novo Usuário</h2>
    <?= $msg ?>
    <form method="POST" action="?acao=cadastrar">
        <label>Nome:</label>
        <input type="text" name="nome" required placeholder="Digite o nome completo" value="<?= $_POST['nome'] ?? '' ?>">

        <label>E-mail:</label>
        <input type="email" name="email" required placeholder="Digite o e-mail" value="<?= $_POST['email'] ?? '' ?>">

        <label>Senha:</label>
        <input type="password" name="senha" required placeholder="Crie uma senha">

        <label>Tipo de Acesso:</label>
        <select name="tipo" required>
            <option value="">Selecione...</option>
            <?php foreach ($tipos_usuario as $t): ?>
                <option value="<?= $t ?>" <?= (($_POST['tipo'] ?? '') === $t) ? 'selected' : '' ?>><?= $t ?></option>
            <?php endforeach; ?>
        </select>

        <input type="submit" name="enviar" value="Cadastrar">
    </form>

    <?php
}

// ==========================================================================
// EDITAR
// ==========================================================================
if ($acao === 'editar' && $id > 0) {
    $stmt = $conn->prepare("SELECT nome, email, tipo FROM Usuario WHERE id_usuario = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $usuario = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$usuario) {
        echo "<p class='error'>Usuário não encontrado.</p>";
    } else {
        if (isset($_POST['enviar'])) {
            $nome = trim($_POST['nome']);
            $email = trim($_POST['email']);
            $tipo = $_POST['tipo'];
            $nova_senha = $_POST['nova_senha'];

            $sql = "UPDATE Usuario SET nome=?, email=?, tipo=?";
            $params = [$nome, $email, $tipo];
            $types = "sss";

            if (!empty($nova_senha)) {
                $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                $sql .= ", senha=?";
                $params[] = $senha_hash;
                $types .= "s";
            }

            $sql .= " WHERE id_usuario=?";
            $params[] = $id;
            $types .= "i";

            $stmt = $conn->prepare($sql);
            $bind = array_merge([$types], array_by_ref($params));
            call_user_func_array([$stmt, 'bind_param'], $bind);

            if ($stmt->execute()) {
                echo "<p class='success'>Usuário atualizado com sucesso!</p>";
            } else {
                echo "<p class='error'>Erro ao atualizar: " . $stmt->error . "</p>";
            }
            $stmt->close();
        }
        ?>

        <h2>Editar Usuário</h2>
        <form method="POST" action="?acao=editar&id_usuario=<?= $id ?>">
            <label>Nome:</label>
            <input type="text" name="nome" required value="<?= htmlspecialchars($usuario['nome']) ?>">

            <label>E-mail:</label>
            <input type="email" name="email" required value="<?= htmlspecialchars($usuario['email']) ?>">

            <label>Tipo de Acesso:</label>
            <select name="tipo" required>
                <?php foreach ($tipos_usuario as $t): ?>
                    <option value="<?= $t ?>" <?= ($usuario['tipo'] === $t) ? 'selected' : '' ?>><?= $t ?></option>
                <?php endforeach; ?>
            </select>

            <label>Nova Senha (opcional):</label>
            <input type="password" name="nova_senha" placeholder="Deixe em branco para não alterar">

            <input type="submit" name="enviar" value="Salvar Alterações">
        </form>
        <?php
    }
}

// ==========================================================================
// EXCLUIR
// ==========================================================================
if ($acao === 'excluir' && $id > 0) {
    $stmt = $conn->prepare("DELETE FROM Usuario WHERE id_usuario = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<p class='success'>Usuário excluído com sucesso!</p>";
    } else {
        echo "<p class='error'>Erro ao excluir: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// ==========================================================================
// LISTAR
// ==========================================================================
if ($acao === 'listar') {
    echo "<h2>Lista de Usuários</h2>";
    $result = $conn->query("SELECT id_usuario, nome, email, tipo FROM Usuario ORDER BY nome ASC");
    if ($result->num_rows > 0) {
        echo "<table><tr><th>ID</th><th>Nome</th><th>E-mail</th><th>Tipo</th><th>Ações</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id_usuario']}</td>
                <td>" . htmlspecialchars($row['nome']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>{$row['tipo']}</td>
                <td>
                    <a href='?acao=editar&id_usuario={$row['id_usuario']}'>Editar</a> |
                    <a href='?acao=excluir&id_usuario={$row['id_usuario']}' onclick='return confirm(\"Tem certeza?\")'>Excluir</a>
                </td>
            </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Nenhum usuário cadastrado.</p>";
    }
}
$conn->close();
?>
</center>
</body>
</html>
