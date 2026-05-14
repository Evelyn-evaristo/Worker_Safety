<?php
/** ALTERAR */
$host = "sql10.freesqldatabase.com";
$usuario = "sql10826718";
$senha = "cVlBuFfFwc";
$banco = "sql10826718";

$conexao = new mysqli($host, $usuario, $senha, $banco);

if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}

$conexao->set_charset("utf8mb4");
?>
