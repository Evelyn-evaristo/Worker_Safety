<?php
header('Content-Type: application/json; charset=utf-8');
require_once "conexao.php";

$setor_id = $_POST['setor_id'] ?? null;
$temperatura = $_POST['temperatura'] ?? null;
$umidade = $_POST['umidade'] ?? null;

if ($setor_id === null || $temperatura === null || $umidade === null) {
    echo json_encode(["erro" => "dados incompletos"]);
    exit;
}

$setor_id = intval($setor_id);
$temperatura = floatval($temperatura);
$umidade = floatval($umidade);

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

$sql = "INSERT INTO leituras 
(setor_id, temperatura, umidade, alerta_ativo, motivo_alerta, criado_em) 
VALUES (?, ?, ?, ?, ?, NOW())";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("iddis", $setor_id, $temperatura, $umidade, $alerta_ativo, $motivo_alerta);

if (!$stmt->execute()) {
    echo json_encode(["erro" => "erro ao salvar leitura"]);
    exit;
}

$leitura_id = $stmt->insert_id;
$stmt->close();

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

echo json_encode([
    "mensagem" => "ok",
    "alerta" => $alerta_ativo,
    "motivo" => $motivo_alerta
]);

$conexao->close();
?>
