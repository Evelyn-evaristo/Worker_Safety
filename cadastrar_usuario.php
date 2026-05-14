<?php
require_once __DIR__ . "/conexao.php";

$nome = trim($_POST["nome"] ?? "");
$email = trim($_POST["email"] ?? "");
$senha = $_POST["senha"] ?? "";
$confirmarSenha = $_POST["confirmar_senha"] ?? "";

if ($nome === "" || $email === "" || $senha === "" || $confirmarSenha === "") {
    header("Location: cadastro.php?erro=" . urlencode("Preencha todos os campos."));
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: cadastro.php?erro=" . urlencode("Informe um e-mail válido."));
    exit;
}

if (strlen($senha) < 6) {
    header("Location: cadastro.php?erro=" . urlencode("A senha deve ter no mínimo 6 caracteres."));
    exit;
}

if ($senha !== $confirmarSenha) {
    header("Location: cadastro.php?erro=" . urlencode("As senhas não conferem."));
    exit;
}

$verifica = $conexao->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
$verifica->bind_param("s", $email);
$verifica->execute();
$existe = $verifica->get_result()->fetch_assoc();
$verifica->close();

if ($existe) {
    header("Location: cadastro.php?erro=" . urlencode("Este e-mail já está cadastrado."));
    exit;
}

$senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);
$sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
$stmt = $conexao->prepare($sql);

if (!$stmt) {
    header("Location: cadastro.php?erro=" . urlencode("Erro ao preparar o cadastro."));
    exit;
}

$stmt->bind_param("sss", $nome, $email, $senhaCriptografada);

if ($stmt->execute()) {
    header("Location: login.php?sucesso=" . urlencode("Cadastro realizado. Agora faça login."));
    exit;
}

header("Location: cadastro.php?erro=" . urlencode("Erro ao cadastrar usuário."));
exit;
?>
