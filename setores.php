<?php require_once __DIR__ . "/verificar_login.php"; ?>
<?php
include __DIR__ . '/conexao.php';
$sql = "SELECT s.id, s.nome_setor, (SELECT l.temperatura FROM leituras l WHERE l.setor_id=s.id ORDER BY l.id DESC LIMIT 1) AS temperatura, (SELECT l.umidade FROM leituras l WHERE l.setor_id=s.id ORDER BY l.id DESC LIMIT 1) AS umidade FROM setores s ORDER BY s.id ASC";
$res = $conexao->query($sql);
$setores = [];
if ($res) {
    while ($r = $res->fetch_assoc()) $setores[] = $r;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setores</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body data-auto-refresh="5">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-layout">
            <div class="sidebar-header">
                <div class="user-box">
                    <div class="user-avatar"><img src="img/logo.png" alt="Usuário"></div>
                    <div class="user-info">
                        <h1>Worker Safety</h1>
                        <p>Painel de Controle</p>
                    </div>
                </div><button id="btnSidebar" class="btn-sidebar" type="button">☰</button>
            </div>
            <div class="sidebar-content">
                <nav class="menu">
                    <ul>
                        <li class="menu-header"><span>DASHBOARDS</span></li>
                        <li class="menu-item"><a href="index.php"><span class="menu-icon"><img src="img/Dashboard.png" alt="Dashboard"></span><span class="menu-title">Overview</span></a></li>
                        <li class="menu-item"><a href="temperatura.php"><span class="menu-icon"><img src="img/Temperatura.png" alt="Temperatura"></span><span class="menu-title">Temperatura</span></a></li>
                        <li class="menu-item"><a href="umidade.php"><span class="menu-icon"><img src="img/Umidade.png" alt="Umidade"></span><span class="menu-title">Umidade</span></a></li>
                        <li class="menu-item"><a href="alertas.php"><span class="menu-icon"><img src="img/Alertas.png" alt="Alertas"></span><span class="menu-title">Alertas</span></a></li>
                        <li class="menu-item"><a href="setores.php"><span class="menu-icon"><img src="img/Setores.png" alt="Setores"></span><span class="menu-title">Setores</span></a></li>
                        <li class="menu-item"><a href="desenvolvedores.php"><span class="menu-icon"> <img src="img/Desenvolvedores.png" alt="Desenvolvedores"></span> <span class="menu-title"> Desenvolvedores</span></a></li>
                        <li class="menu-item"><a href="logout.php"><span class="menu-icon">↪</span><span class="menu-title">Sair</span></a></li>
                    </ul>
                </nav>
            </div>
            <div class="sidebar-footer">
                <div class="footer-logo"><span class="footer-dot"></span><span class="footer-text">Worker Safety</span></div>
            </div>
        </div>
    </aside>
    <main class="main-content" id="mainContent">
        <h1>Setores</h1>
        <div class="cards"><?php if (count($setores) === 0): ?><div class="card">Nenhum setor cadastrado.</div><?php else: foreach ($setores as $s): ?><div class="card"><strong><?= htmlspecialchars($s['nome_setor']) ?></strong><br>Temperatura: <?= $s['temperatura'] !== null ? number_format((float)$s['temperatura'], 1, ',', '.') . '°C' : '--' ?><br>Umidade: <?= $s['umidade'] !== null ? number_format((float)$s['umidade'], 1, ',', '.') . '%' : '--' ?></div><?php endforeach;
                                                                                                                                                                                                                                                                                                                                                                                                                                                        endif; ?></div>
    </main>
    <script src="js/script.js"></script>
</body>

</html>
