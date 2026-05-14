<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/conexao.php";

$email = trim($_POST["email"] ?? "");
$senha = $_POST["senha"] ?? "";

if ($email === "" || $senha === "") {
    header("Location: login.php?erro=" . urlencode("Preencha e-mail e senha."));
    exit;
}

$sql = "SELECT id, nome, email, senha FROM usuarios WHERE email = ? LIMIT 1";
$stmt = $conexao->prepare($sql);

if (!$stmt) {
    header("Location: login.php?erro=" . urlencode("Erro ao preparar o login."));
    exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
$stmt->close();

if ($usuario && password_verify($senha, $usuario["senha"])) {
    session_regenerate_id(true);
    $_SESSION["usuario_id"] = $usuario["id"];
    $_SESSION["usuario_nome"] = $usuario["nome"];
    $_SESSION["usuario_email"] = $usuario["email"];
    header("Location: index.php");
    exit;
}

header("Location: login.php?erro=" . urlencode("E-mail ou senha incorretos."));
exit;
?>
