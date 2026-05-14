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

if ($setor_id <= 0) {
  http_response_code(400);
  echo json_encode(["ok" => false, "erro" => "setor_id invalido"]);
  exit;
}

$alerta_ativo = 0;
$motivo_alerta = "";

if ($temp > 30) {
  $alerta_ativo = 1;
  $motivo_alerta = "Temperatura acima do limite";
}

if ($umid > 85) {
  $alerta_ativo = 1;
  $motivo_alerta = $motivo_alerta 
    ? $motivo_alerta . " e umidade acima do limite"
    : "Umidade acima do limite";
}

$sql = "INSERT INTO leituras (setor_id, temperatura, umidade, alerta_ativo, motivo_alerta, criado_em)
        VALUES (?, ?, ?, ?, ?, NOW())";
$stmt = $conexao->prepare($sql);
if (!$stmt) {
  http_response_code(500);
  echo json_encode(["ok" => false, "erro" => "prepare"]);
  $conexao->close();
  exit;
}

$stmt->bind_param("iddis", $setor_id, $temp, $umid, $alerta_ativo, $motivo_alerta);

if ($stmt->execute()) {
  $leitura_id = $stmt->insert_id;
  
  if ($alerta_ativo == 1) {
    $tipo = "ALERTA";
    $status = "ativo";
    
    $sqlAlarme = "INSERT INTO alarmes (leitura_id, setor_id, tipo, mensagem, temperatura, umidade, status, criado_em)
                  VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmtAlarme = $conexao->prepare($sqlAlarme);
    if ($stmtAlarme) {
      $stmtAlarme->bind_param(
        "iissdds",
        $leitura_id,
        $setor_id,
        $tipo,
        $motivo_alerta,
        $temp,
        $umid,
        $status
      );
      $stmtAlarme->execute();
      $stmtAlarme->close();
    }
  }
  
  echo json_encode([
    "ok" => true, 
    "id" => $leitura_id,
    "alerta" => $alerta_ativo,
    "motivo" => $motivo_alerta
  ]);
} else {
  http_response_code(500);
  echo json_encode(["ok" => false, "erro" => "insert"]);
}

$stmt->close();
$conexao->close();