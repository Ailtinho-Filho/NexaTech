<?php
// =========================================================================
// conexao.php - Arquivo dedicado à conexão com o banco de dados
// =========================================================================

// Informações do Banco de Dados
$servidor = "sql100.infinityfree.com";
$usuario  = "if0_40105852";
$senha    = "ailton30092005";
$banco    = "if0_40105852_usuarios";

// Cria a conexão
$conn = new mysqli($servidor, $usuario, $senha, $banco);

// Verifica se deu certo
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Define o charset para UTF-8
$conn->set_charset("utf8");
// A variável $conn está pronta para uso
?>
