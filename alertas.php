<?php
require_once "verificar_login.php";
require_once "conexao.php";

$sql = "SELECT 
            a.id,
            a.tipo,
            a.mensagem,
            a.temperatura,
            a.umidade,
            a.status,
            a.criado_em,
            a.setor_id,
            s.nome_setor
        FROM alarmes a
        LEFT JOIN setores s ON s.id = a.setor_id
        ORDER BY a.id DESC
        LIMIT 20";

$res = $conexao->query($sql);
$alertas = [];

if ($res) {
    while ($row = $res->fetch_assoc()) {
        $alertas[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alertas</title>

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
                    <div class="user-avatar">
                        <img src="img/logo.png" alt="Usuário">
                    </div>

                    <div class="user-info">
                        <h1>Worker Safety</h1>
                        <p>Painel de Controle</p>
                    </div>
                </div>

                <button id="btnSidebar" class="btn-sidebar" type="button">☰</button>
            </div>

            <div class="sidebar-content">
                <nav class="menu">
                    <ul>
                        <li class="menu-header"><span>DASHBOARDS</span></li>

                        <li class="menu-item">
                            <a href="index.php">
                                <span class="menu-icon"><img src="img/Dashboard.png" alt="Dashboard"></span>
                                <span class="menu-title">Overview</span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="temperatura.php">
                                <span class="menu-icon"><img src="img/Temperatura.png" alt="Temperatura"></span>
                                <span class="menu-title">Temperatura</span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="umidade.php">
                                <span class="menu-icon"><img src="img/Umidade.png" alt="Umidade"></span>
                                <span class="menu-title">Umidade</span>
                            </a>
                        </li>

                        <li class="menu-item active">
                            <a href="alertas.php">
                                <span class="menu-icon"><img src="img/Alertas.png" alt="Alertas"></span>
                                <span class="menu-title">Alertas</span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="setores.php">
                                <span class="menu-icon"><img src="img/Setores.png" alt="Setores"></span>
                                <span class="menu-title">Setores</span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="desenvolvedores.php">
                                <span class="menu-icon"><img src="img/Desenvolvedores.png" alt="Desenvolvedores"></span>
                                <span class="menu-title">Desenvolvedores</span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="logout.php">
                                <span class="menu-icon">↪</span>
                                <span class="menu-title">Sair</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="sidebar-footer">
                <div class="footer-logo">
                    <span class="footer-dot"></span>
                    <span class="footer-text">Worker Safety</span>
                </div>
            </div>

        </div>
    </aside>

    <main class="main-content" id="mainContent">
        <h1>Alertas</h1>

        <?php if (count($alertas) > 0): ?>
            <?php $ultimo = $alertas[0]; ?>

            <div class="alerta-critico">
                <h2>⚠ ALERTA CRÍTICO</h2>

                <p>
                    Origem:
                    <strong>
                        <?= htmlspecialchars($ultimo['nome_setor'] ?: 'Setor ' . $ultimo['setor_id']) ?>
                    </strong>
                </p>

                <p>
                    Quando:
                    <strong>
                        <?= $ultimo['criado_em'] ? date('d/m/Y H:i:s', strtotime($ultimo['criado_em'])) : 'Sem horário' ?>
                    </strong>
                </p>

                <p>
                    Motivo:
                    <strong>
                        <?= htmlspecialchars($ultimo['mensagem'] ?: 'Fora do limite') ?>
                    </strong>
                </p>
            </div>
        <?php endif; ?>

        <div class="alerts-box">
            <h3>Histórico recente</h3>

            <ul id="alertList">
                <?php if (count($alertas) === 0): ?>
                    <li>Sem alertas ativos no momento.</li>
                <?php else: ?>
                    <?php foreach ($alertas as $a): ?>
                        <li>
                            <strong><?= htmlspecialchars($a['nome_setor'] ?: 'Setor ' . $a['setor_id']) ?></strong>
                            |
                            <?= $a['criado_em'] ? date('d/m/Y H:i:s', strtotime($a['criado_em'])) : 'Sem horário' ?>
                            |
                            <?= htmlspecialchars($a['mensagem'] ?: 'Fora do limite') ?>
                            |
                            T: <?= number_format((float)$a['temperatura'], 1, ',', '.') ?>°C
                            /
                            U: <?= number_format((float)$a['umidade'], 1, ',', '.') ?>%
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </main>

    <script src="js/script.js"></script>
</body>

</html>
