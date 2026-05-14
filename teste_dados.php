<?php
require_once __DIR__ . "/conexao.php";

$dados_teste = [
    ['setor_id' => 1, 'temperatura' => 22.5, 'umidade' => 65.0],
    ['setor_id' => 1, 'temperatura' => 23.1, 'umidade' => 66.5],
    ['setor_id' => 1, 'temperatura' => 21.8, 'umidade' => 64.2],
    ['setor_id' => 2, 'temperatura' => 20.5, 'umidade' => 72.0],
    ['setor_id' => 2, 'temperatura' => 21.2, 'umidade' => 71.5],
    ['setor_id' => 2, 'temperatura' => 20.8, 'umidade' => 73.1],
];

foreach ($dados_teste as $dado) {
    $sql = "INSERT INTO leituras (setor_id, temperatura, umidade, alerta_ativo, motivo_alerta, criado_em) 
            VALUES (?, ?, ?, 0, '', NOW())";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("idd", $dado['setor_id'], $dado['temperatura'], $dado['umidade']);
    $stmt->execute();
    $stmt->close();
}

echo "Dados de teste inseridos com sucesso!";

$conexao->close();
?>