<?php
header('Content-Type: application/json; charset=utf-8');
include __DIR__ . '/conexao.php';

$campoData = null;
if (($r = $conexao->query("SHOW COLUMNS FROM leituras LIKE 'created_at'")) && $r->num_rows > 0) {
    $campoData = 'created_at';
} elseif (($r = $conexao->query("SHOW COLUMNS FROM leituras LIKE 'criado_em'")) && $r->num_rows > 0) {
    $campoData = 'criado_em';
}

function serie(mysqli $conexao, ?string $campoData, string $coluna, int $setorId): array {
    $labels = [];
    $dados = [];
    $coluna = $coluna === 'umidade' ? 'umidade' : 'temperatura';
    $sql = $campoData
        ? "SELECT {$coluna}, {$campoData} AS momento FROM leituras WHERE setor_id = ? ORDER BY id DESC LIMIT 30"
        : "SELECT {$coluna}, NULL AS momento FROM leituras WHERE setor_id = ? ORDER BY id DESC LIMIT 30";
    $stmt = $conexao->prepare($sql);
    if (!$stmt) return ["labels" => $labels, "dados" => $dados];
    $stmt->bind_param('i', $setorId);
    $stmt->execute();
    $res = $stmt->get_result();
    $linhas = [];
    while ($row = $res->fetch_assoc()) $linhas[] = $row;
    $stmt->close();

    foreach (array_reverse($linhas) as $row) {
        $labels[] = !empty($row['momento']) ? date('H:i:s', strtotime($row['momento'])) : '';
        $dados[] = (float) $row[$coluna];
    }
    return ["labels" => $labels, "dados" => $dados];
}

echo json_encode([
    "ok" => true,
    "atualizado_em" => date('c'),
    "graficos" => [
        "temperatura_setor_a" => serie($conexao, $campoData, 'temperatura', 1),
        "umidade_setor_b" => serie($conexao, $campoData, 'umidade', 2)
    ]
], JSON_UNESCAPED_UNICODE);
