<?php
header('Content-Type: application/json; charset=utf-8');
require_once "conexao.php";

// =========================
// Receber JSON do ESP32
// =========================
$dados = json_decode(file_get_contents("php://input"), true);

$setor_id = $dados['setor_id'] ?? null;
$temperatura = $dados['temperatura'] ?? null;
$umidade = $dados['umidade'] ?? null;

// =========================
// Validar dados
// =========================
if ($setor_id === null || $temperatura === null || $umidade === null) {
    echo json_encode(["erro" => "dados incompletos"]);
    exit;
}

// =========================
// Converter tipos
// =========================
$setor_id = intval($setor_id);
$temperatura = floatval($temperatura);
$umidade = floatval($umidade);

// =========================
// Alertas
// =========================
$alerta_ativo = 0;
$motivo_alerta = "";

if ($temperatura > 30) {
    $alerta_ativo = 1;
    $motivo_alerta = "Temperatura acima do limite";
}

if ($umidade > 85) {
    $alerta_ativo = 1;

    $motivo_alerta = $motivo_alerta
        ? $motivo_alerta . " e umidade acima do limite"
        : "Umidade acima do limite";
}

// =========================
// Salvar leitura
// =========================
$sql = "INSERT INTO leituras
(setor_id, temperatura, umidade, alerta_ativo, motivo_alerta, criado_em)
VALUES (?, ?, ?, ?, ?, NOW())";

$stmt = $conexao->prepare($sql);

$stmt->bind_param(
    "iddis",
    $setor_id,
    $temperatura,
    $umidade,
    $alerta_ativo,
    $motivo_alerta
);

if (!$stmt->execute()) {
    echo json_encode([
        "erro" => "erro ao salvar leitura",
        "mysql" => $stmt->error
    ]);

    exit;
}

$leitura_id = $stmt->insert_id;

$stmt->close();

// =========================
// Salvar alarme
// =========================
if ($alerta_ativo == 1) {

    $tipo = "ALERTA";
    $status = "ativo";

    $sqlAlarme = "INSERT INTO alarmes
    (leitura_id, setor_id, tipo, mensagem, temperatura, umidade, status, criado_em)
    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmtAlarme = $conexao->prepare($sqlAlarme);

    $stmtAlarme->bind_param(
        "iissdds",
        $leitura_id,
        $setor_id,
        $tipo,
        $motivo_alerta,
        $temperatura,
        $umidade,
        $status
    );

    $stmtAlarme->execute();

    $stmtAlarme->close();
}

// =========================
// Resposta
// =========================
echo json_encode([
    "mensagem" => "ok",
    "alerta" => $alerta_ativo,
    "motivo" => $motivo_alerta
]);

$conexao->close();
?>
