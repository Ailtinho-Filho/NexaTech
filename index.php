<?php
// =========================================================================
// usuario_crud.php - CRUD para a tabela Usuario (com tipo de acesso)
// =========================================================================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui o arquivo de conexão (conexao.php)
include("conexao.php");

// Definições de Tipos para o campo ENUM no HTML/PHP
$tipos_usuario = ['Administrador', 'Consultor', 'Cliente'];

// Função auxiliar para bind_param dinâmico
function array_by_ref(&$array) {
    $refs = array();
    foreach($array as $key => $value)
        $refs[$key] = &$array[$key];
    return $refs;
}

// Inicializa variáveis de estado
$acao = isset($_GET['acao']) ? $_GET['acao'] : 'listar';
$msg = "";
$id = isset($_GET['id_usuario']) ? (int)$_GET['id_usuario'] : 0;
// =========================================================================
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>CRUD de Usuários | Gerenciamento de Acesso</title>
    <style>
        /* Cores: Roxo (#6a0dad), Branco (#fff), Quase Branco (#f8f8f8) */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #f8f8f8; 
            color: #333;
            margin: 0;
            padding: 0;
        }
        center { 
            max-width: 900px; 
            margin: 40px auto; 
            padding: 20px;
            background-color: #fff; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.1); 
            border-radius: 8px; 
        }
        h1, h2 { 
            color: #6a0dad; 
            text-align: center;
            margin-bottom: 25px;
        }
        p { 
            text-align: center;
            margin-bottom: 20px;
        }
        p a {
            color: #6a0dad; 
            text-decoration: none;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        p a:hover {
            background-color: #e0b0ff; 
        }
        table { 
            border-collapse: collapse; 
            width: 100%; 
            margin: 20px 0; 
            background-color: #000000;
            border-radius: 8px;
            overflow: hidden; 
        }
        th, td { 
            border: 1px solid #eee; 
            padding: 12px 15px; 
            text-align: left; 
        }
        th { 
            background-color: #6a0dad; 
            color: white; 
            font-weight: 600; /* Um pouco mais de peso */
            text-transform: uppercase;
            font-size: 14px;
        }
        tr:nth-child(even) { 
            background-color: #f9f9f9; /* Linhas pares bem suaves */
        }
        tr:hover { 
            background-color: #f0e6ff; /* Roxo suave no hover */
        }
        td a {
            color: #6a0dad; 
            text-decoration: none;
            margin-right: 10px;
            transition: color 0.3s ease;
        }
        td a:hover {
            color: #8a2be2;
            text-decoration: underline;
        }
        .success { 
            color: #28a745; 
            font-weight: bold; 
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
        .error { 
            color: #dc3545; 
            font-weight: bold; 
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
        form {
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-top: 20px;
        }
        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }
        form input[type="text"],
        form input[type="email"],
        form input[type="password"],
        form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box; 
        }
        form input[type="submit"] {
            background-color: #6a0dad; 
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 17px;
            transition: background-color 0.3s ease;
            width: auto; 
            display: block; 
            margin: 20px auto 0;
            text-transform: uppercase;
        }
        form input[type="submit"]:hover {
            background-color: #8a2be2; 
        }
    </style>
</head>
<body>
<center>
    <h1>Gerenciamento de Usuários (CRUD)</h1>
    <p>
        <a href="?acao=listar">Listar Usuários</a> | 
        <a href="?acao=cadastrar">Novo Cadastro</a>
    </p>
    
    <?php echo $msg; ?>

<?php
// =========================================================================
// 3. FUNÇÃO CADASTRAR (CREATE)
// =========================================================================
if ($acao == 'cadastrar') {
    if (isset($_POST['enviar'])) {
        $nome       = htmlspecialchars(trim($_POST['nome']));
        $email      = htmlspecialchars(trim($_POST['email']));
        $senha_bruta = $_POST['senha'];
        $tipo       = $_POST['tipo'];

        // VALIDAÇÃO BÁSICA DO TIPO
        if (!in_array($tipo, $tipos_usuario)) {
             $msg = "<p class='error'>Tipo de usuário inválido.</p>";
        } else {
            // CRIPTOGRAFIA
            $senha_hashed = password_hash($senha_bruta, PASSWORD_DEFAULT);

            // PREPARED STATEMENT para Inserção
            $sql = "INSERT INTO Usuario (nome, email, senha, tipo) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                $msg = "<p class='error'>Erro na preparação da query: " . $conn->error . "</p>";
            } else {
                $stmt->bind_param("ssss", $nome, $email, $senha_hashed, $tipo);

                if ($stmt->execute()) {
                    $msg = "<p class='success'>Usuário **cadastrado** com sucesso!</p>";
                    // Limpa as variáveis POST para não preencher o formulário após o sucesso
                    $_POST = array(); 
                } else {
                    // Erro 1062 (Duplicate entry) se o e-mail for repetido (UNIQUE)
                    if ($conn->errno == 1062) {
                        $msg = "<p class='error'>Erro ao cadastrar: O e-mail informado já está em uso.</p>";
                    } else {
                        $msg = "<p class='error'>Erro ao cadastrar: " . $stmt->error . "</p>";
                    }
                }
                $stmt->close();
            }
        }
    }
    
    // Formulário de Cadastro
    ?>
        <h2>Cadastrar Novo Usuário</h2>
        <?php echo $msg; // Exibe a mensagem de erro/sucesso do cadastro ?>
        <form method="POST" action="?acao=cadastrar">
            <label>Nome:</label><br>
            <input type="text" name="nome" required value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>"><br><br>
            
            <label>E-mail:</label><br>
            <input type="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"><br><br>
            
            <label>Senha:</label><br>
            <input type="password" name="senha" required><br><br>
            
            <label>Tipo de Acesso:</label><br>
            <select name="tipo" required>
                <option value="">Selecione...</option>
                <?php foreach ($tipos_usuario as $t): ?>
                    <option value="<?php echo $t; ?>" <?php echo (isset($_POST['tipo']) && $_POST['tipo'] == $t) ? 'selected' : ''; ?>>
                        <?php echo $t; ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <input type="submit" name="enviar" value="Cadastrar">
        </form>
    <?php
}

// =========================================================================
// 4. FUNÇÃO EXCLUIR (DELETE)
// =========================================================================
if ($acao == 'excluir' && $id > 0) {
    // PREPARED STATEMENT
    $sql = "DELETE FROM Usuario WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $msg = "<p class='success'>Usuário excluído com sucesso!</p>";
    } else {
        // Erro 1451 (Foreign Key constraint) se o usuário estiver ligado a uma empresa
        if ($conn->errno == 1451) {
             $msg = "<p class='error'>Erro ao excluir: Este usuário possui Empresas associadas e não pode ser removido.</p>";
        } else {
             $msg = "<p class='error'>Erro ao excluir: " . $stmt->error . "</p>";
        }
    }
    $stmt->close();
    $acao = 'listar'; // Redireciona para a lista
}

// =========================================================================
// 5. FUNÇÃO EDITAR (UPDATE)
// =========================================================================
if ($acao == 'editar' && $id > 0) {
    
    // Processa a submissão da Edição
    if (isset($_POST['enviar'])) {
        $nome       = htmlspecialchars(trim($_POST['nome']));
        $email      = htmlspecialchars(trim($_POST['email']));
        $tipo       = $_POST['tipo'];
        $nova_senha = $_POST['nova_senha']; 

        // Monta a query dinamicamente
        $sql = "UPDATE Usuario SET nome = ?, email = ?, tipo = ?";
        $params = "sss";
        $bind_array = [$nome, $email, $tipo];
        
        if (!empty($nova_senha)) {
            $senha_hashed = password_hash($nova_senha, PASSWORD_DEFAULT);
            $sql .= ", senha = ?";
            $params .= "s";
            $bind_array[] = $senha_hashed;
        }

        $sql .= " WHERE id_usuario = ?";
        $params .= "i"; 
        $bind_array[] = $id;

        $stmt = $conn->prepare($sql);
        array_unshift($bind_array, $params);
        call_user_func_array([$stmt, 'bind_param'], array_by_ref($bind_array));

        if ($stmt->execute()) {
            $msg = "<p class='success'>Usuário **atualizado** com sucesso!</p>";
        } else {
             if ($conn->errno == 1062) {
                $msg = "<p class='error'>Erro ao atualizar: O e-mail informado já está em uso por outro usuário.</p>";
            } else {
                $msg = "<p class='error'>Erro ao atualizar: " . $stmt->error . "</p>";
            }
        }
        $stmt->close();
    }
    
    // Carrega os dados para exibição no formulário
    $sql_select = "SELECT nome, email, tipo FROM Usuario WHERE id_usuario = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $resultado = $stmt_select->get_result();
    $usuario = $resultado->fetch_assoc();
    $stmt_select->close();

    if (!$usuario) {
        $msg = "<p class='error'>Usuário não encontrado.</p>";
        $acao = 'listar';
    }
    
    // Formulário de Edição
    if ($acao == 'editar') {
    ?>
        <h2>Editar Usuário: <?php echo htmlspecialchars($usuario['nome']); ?></h2>
        <?php echo $msg; // Exibe a mensagem de sucesso/erro da edição ?>
        <form method="POST" action="?acao=editar&id_usuario=<?php echo $id; ?>">
            <label>Nome:</label><br>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required><br><br>
            
            <label>E-mail:</label><br>
            <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required><br><br>
            
            <label>Tipo de Acesso:</label><br>
            <select name="tipo" required>
                <?php foreach ($tipos_usuario as $t): ?>
                    <option value="<?php echo $t; ?>" <?php echo ($usuario['tipo'] == $t) ? 'selected' : ''; ?>>
                        <?php echo $t; ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>
            
            <label>Nova Senha (deixe em branco para não alterar):</label><br>
            <input type="password" name="nova_senha"><br><br>
            
            <input type="submit" name="enviar" value="Salvar Alterações">
        </form>
    <?php
    }
}


// =========================================================================
// 6. FUNÇÃO LISTAR (READ) - Padrão
// =========================================================================
if ($acao == 'listar') {
    // Se a ação de exclusão ou atualização for bem-sucedida, a mensagem é exibida aqui
    echo $msg; 
    
    echo "<h2>Lista de Usuários Cadastrados (READ)</h2>";
    
    $sql = "SELECT id_usuario, nome, email, tipo FROM Usuario ORDER BY nome ASC"; 
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Nome</th><th>E-mail</th><th>Tipo</th><th>Ações</th></tr>";
        
        while ($row = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id_usuario'] . "</td>";
            echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['tipo']) . "</td>";
            echo "<td>";
            echo "<a href='?acao=editar&id_usuario=" . $row['id_usuario'] . "'>Editar</a> | ";
            echo "<a href='?acao=excluir&id_usuario=" . $row['id_usuario'] . "' onclick=\"return confirm('Tem certeza que deseja excluir este usuário?');\">Excluir</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Nenhum usuário cadastrado.</p>";
    }
    
    // Fechar conexão no final da execução
    $conn->close();
}
?>

</center>
</body>
</html>
