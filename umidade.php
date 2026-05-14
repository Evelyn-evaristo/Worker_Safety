<?php require_once __DIR__ . "/verificar_login.php"; ?>
<?php
include __DIR__ . '/conexao.php';
$campoData = null;
if (($r = $conexao->query("SHOW COLUMNS FROM leituras LIKE 'created_at'")) && $r->num_rows > 0) $campoData = 'created_at';
if (!$campoData && ($r = $conexao->query("SHOW COLUMNS FROM leituras LIKE 'criado_em'")) && $r->num_rows > 0) $campoData = 'criado_em';

function serieUmidade(mysqli $c, ?string $campoData, int $setor): array
{
    $labels = [];
    $dados = [];
    $sql = $campoData
        ? "SELECT umidade, {$campoData} AS momento FROM leituras WHERE setor_id = ? ORDER BY id DESC LIMIT 30"
        : "SELECT umidade, NULL AS momento FROM leituras WHERE setor_id = ? ORDER BY id DESC LIMIT 30";
    $stmt = $c->prepare($sql);
    if (!$stmt) return ["labels" => $labels, "dados" => $dados];
    $stmt->bind_param("i", $setor);
    $stmt->execute();
    $res = $stmt->get_result();
    $lin = [];
    while ($row = $res->fetch_assoc()) $lin[] = $row;
    $stmt->close();
    foreach (array_reverse($lin) as $row) {
        $labels[] = $row['momento'] ? date('H:i:s', strtotime($row['momento'])) : '';
        $dados[] = (float)$row['umidade'];
    }
    return ["labels" => $labels, "dados" => $dados];
}
$ga = serieUmidade($conexao, $campoData, 1);
$gb = serieUmidade($conexao, $campoData, 2);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Umidade</title>
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
        <h1>Umidade por Setor</h1>
        <div class="dashboard-grid">
            <div class="chart-box">
                <h3>Setor A - Servidores</h3>
                <div class="chart-area"><canvas id="umidadeChartA"></canvas></div>
            </div>
            <div class="chart-box">
                <h3>Setor B - Documentos</h3>
                <div class="chart-area"><canvas id="umidadeChartB"></canvas></div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.graficoUmidadeA = {
            labels: <?= json_encode($ga['labels']) ?>,
            dados: <?= json_encode($ga['dados']) ?>,
            label: 'Umidade A',
            cor: '#4cc9ff'
        };
        window.graficoUmidadeB = {
            labels: <?= json_encode($gb['labels']) ?>,
            dados: <?= json_encode($gb['dados']) ?>,
            label: 'Umidade B',
            cor: '#12b8ff'
        };
    </script>
    <script src="js/script.js"></script>
</body>

</html>
