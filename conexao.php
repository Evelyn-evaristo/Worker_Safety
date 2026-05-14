<?php
/** ALTERAR */
$host = "sql12.freesqldatabase.com";
$usuario = "sql12799999";
$senha = "abc123";
$banco = "sql12799999";

$conexao = new mysqli($host, $usuario, $senha, $banco);

if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}

$conexao->set_charset("utf8mb4");
?>