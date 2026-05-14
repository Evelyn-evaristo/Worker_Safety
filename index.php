<?php
require_once __DIR__ . "/verificar_login.php";
require_once __DIR__ . "/conexao.php";

$campoData = null;

$verificaCriadoEm = $conexao->query("SHOW COLUMNS FROM leituras LIKE 'criado_em'");
if ($verificaCriadoEm && $verificaCriadoEm->num_rows > 0) {
    $campoData = "criado_em";
}

function montarSerie(mysqli $conexao, ?string $campoData, string $coluna, int $setorId, int $limite = 30): array
{
    $labels = [];
    $dados = [];
    $coluna = $coluna === 'umidade' ? 'umidade' : 'temperatura';

    if ($campoData) {
        $sql = "SELECT {$coluna}, {$campoData} AS momento FROM leituras WHERE setor_id = ? ORDER BY id DESC LIMIT ?";
    } else {
        $sql = "SELECT {$coluna}, NULL AS momento FROM leituras WHERE setor_id = ? ORDER BY id DESC LIMIT ?";
    }

    $stmt = $conexao->prepare($sql);
    if (!$stmt) {
        return ["labels" => $labels, "dados" => $dados];
    }

    $stmt->bind_param("ii", $setorId, $limite);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $linhas = [];
    while ($linha = $resultado->fetch_assoc()) {
        $linhas[] = $linha;
    }

    $stmt->close();

    $linhas = array_reverse($linhas);

    foreach ($linhas as $linha) {
        $labels[] = !empty($linha['momento']) ? date("H:i:s", strtotime($linha['momento'])) : "";
        $dados[] = (float) $linha[$coluna];
    }

    return ["labels" => $labels, "dados" => $dados];
}

$totalAlertas = 0;
$resultTotalAlertas = $conexao->query("SELECT COUNT(*) AS total FROM alarmes WHERE status = 'ativo'");
if ($resultTotalAlertas) {
    $totalAlertas = (int) ($resultTotalAlertas->fetch_assoc()['total'] ?? 0);
}

$totalSetores = 0;
$resultSetores = $conexao->query("SELECT COUNT(*) AS total FROM setores");
if ($resultSetores) {
    $totalSetores = (int) ($resultSetores->fetch_assoc()['total'] ?? 0);
}

$ultimaA = ["temperatura" => "--", "umidade" => "--"];
$ultimaB = ["temperatura" => "--", "umidade" => "--"];

$resultUltimas = $conexao->query("SELECT setor_id, temperatura, umidade FROM leituras WHERE setor_id IN (1,2) ORDER BY id DESC LIMIT 30");

if ($resultUltimas) {
    while ($l = $resultUltimas->fetch_assoc()) {
        if ((int)$l['setor_id'] === 1 && $ultimaA['temperatura'] === "--") {
            $ultimaA['temperatura'] = number_format((float)$l['temperatura'], 1, ',', '.');
            $ultimaA['umidade'] = number_format((float)$l['umidade'], 1, ',', '.');
        }

        if ((int)$l['setor_id'] === 2 && $ultimaB['temperatura'] === "--") {
            $ultimaB['temperatura'] = number_format((float)$l['temperatura'], 1, ',', '.');
            $ultimaB['umidade'] = number_format((float)$l['umidade'], 1, ',', '.');
        }
    }
}

$graficoTempServidores = montarSerie($conexao, $campoData, 'temperatura', 1, 30);
$graficoUmidadeDocumentos = montarSerie($conexao, $campoData, 'umidade', 2, 30);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Geral</title>

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

                        <li class="menu-item active">
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

                        <li class="menu-item">
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
        <h1>Dashboard Geral</h1>

        <div class="dashboard">
            <div class="cards">
                <a class="card card-link" href="temperatura.php">🌡️ Temperatura Servidores: <span><?= $ultimaA['temperatura'] ?></span>°C</a>
                <a class="card card-link" href="temperatura.php">🌡️ Temperatura Documentos: <span><?= $ultimaB['temperatura'] ?></span>°C</a>
                <a class="card card-link" href="umidade.php">💧 Umidade Servidores: <span><?= $ultimaA['umidade'] ?></span>%</a>
                <a class="card card-link" href="umidade.php">💧 Umidade Documentos: <span><?= $ultimaB['umidade'] ?></span>%</a>
                <a class="card card-link" href="alertas.php">🚨 Alertas ativos: <span><?= $totalAlertas ?></span></a>
                <a class="card card-link" href="setores.php">🏢 Setores cadastrados: <span><?= $totalSetores ?></span></a>
            </div>

            <div class="dashboard-grid">
                <div class="chart-box">
                    <h3>Setor Servidores - Temperatura</h3>
                    <div class="chart-area">
                        <canvas id="tempChartA"></canvas>
                    </div>
                </div>

                <div class="chart-box">
                    <h3>Setor Documentos - Umidade</h3>
                    <div class="chart-area">
                        <canvas id="umidadeChartB"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        window.graficoTemperaturaA = {
            labels: <?= json_encode($graficoTempServidores['labels']) ?>,
            dados: <?= json_encode($graficoTempServidores['dados']) ?>,
            label: 'Temperatura Servidores',
            cor: '#a3ff12'
        };

        window.graficoUmidadeB = {
            labels: <?= json_encode($graficoUmidadeDocumentos['labels']) ?>,
            dados: <?= json_encode($graficoUmidadeDocumentos['dados']) ?>,
            label: 'Umidade Documentos',
            cor: '#12b8ff'
        };
    </script>

    <script src="js/script.js"></script>
</body>

</html>
