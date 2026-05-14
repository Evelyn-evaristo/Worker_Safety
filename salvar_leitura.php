<?php
header('Content-Type: application/json; charset=utf-8');

$caminhoConexao = __DIR__ . "/conexao.php";
if (!file_exists($caminhoConexao)) {
  http_response_code(500);
  echo json_encode(["ok" => false, "erro" => "arquivo_conexao_nao_encontrado"]);
  exit;
}

include $caminhoConexao;

if (!isset($conexao) || $conexao->connect_error) {
  http_response_code(500);
  echo json_encode(["ok" => false, "erro" => "db"]);
  exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
  http_response_code(400);
  echo json_encode(["ok" => false, "erro" => "json invalido"]);
  exit;
}

$setor_id = intval($data["setor_id"] ?? 0);
$temp = floatval($data["temperatura"] ?? 0);
$umid = floatval($data["umidade"] ?? 0);
$alerta = intval($data["alerta_ativo"] ?? 0);
$motivo = $data["motivo_alerta"] ?? null;

if ($setor_id <= 0) {
  http_response_code(400);
  echo json_encode(["ok" => false, "erro" => "setor_id invalido"]);
  exit;
}

$sql = "INSERT INTO leituras (setor_id, temperatura, umidade, alerta_ativo, motivo_alerta)
        VALUES (?, ?, ?, ?, ?)";
$stmt = $conexao->prepare($sql);
if (!$stmt) {
  http_response_code(500);
  echo json_encode(["ok" => false, "erro" => "prepare"]);
  $conexao->close();
  exit;
}

$stmt->bind_param("iddis", $setor_id, $temp, $umid, $alerta, $motivo);

if ($stmt->execute()) {
  echo json_encode(["ok" => true, "id" => $stmt->insert_id]);
} else {
  http_response_code(500);
  echo json_encode(["ok" => false, "erro" => "insert"]);
}

$stmt->close();
$conexao->close();