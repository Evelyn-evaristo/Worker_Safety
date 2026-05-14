<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

 $erro = $_GET["erro"] ?? "";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro | Worker Safety</title>
    <link rel="stylesheet" href="style/cadastro.css">
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
                    <h1>Criar conta</h1>
                    <p>Cadastre um usuário do sistema</p>
                </div>
            </div>

            <?php if ($erro): ?>
                <div class="message error"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <form action="cadastrar_usuario.php" method="POST">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" placeholder="Digite o nome" required>

                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="Digite o e-mail" required>

                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Mínimo de 6 caracteres" minlength="6" required>

                <label for="confirmar_senha">Confirmar senha</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Repita a senha" minlength="6" required>

                <button type="submit">Cadastrar</button>
            </form>

            <p class="auth-link">Já tem conta? <a href="login.php">Entrar</a></p>
        </section>
    </main>
</body>
</html>
