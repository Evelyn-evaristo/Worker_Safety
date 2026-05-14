<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION["usuario_id"])) {
    header("Location: index.php");
    exit;
}

 $erro = $_GET["erro"] ?? "";
$sucesso = $_GET["sucesso"] ?? "";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Worker Safety</title>
    <link rel="stylesheet" href="style/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <main class="auth-page">
        <section class="auth-card">
            <div class="brand">
                <img src="img/logo.png" alt="Worker Safety">
                <div>
                    <h1>Worker Safety</h1>
                    <p>Acesse o painel de controle</p>
                </div>
            </div>

            <?php if ($erro): ?>
                <div class="message error"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <?php if ($sucesso): ?>
                <div class="message success"><?= htmlspecialchars($sucesso) ?></div>
            <?php endif; ?>

            <form action="autenticar.php" method="POST">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="Digite seu e-mail" required>

                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>

                <button type="submit">Entrar</button>
            </form>

            <p class="auth-link">Ainda não tem conta? <a href="cadastro.php">Cadastre-se</a></p>
        </section>
    </main>
</body>
</html>
