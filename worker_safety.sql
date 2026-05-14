-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 13/05/2026 às 10:41
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `worker_safety`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `configuracoes`
--

CREATE TABLE `configuracoes` (
  `id` int(11) NOT NULL,
  `setor_id` int(11) NOT NULL,
  `limite_temp_max` decimal(5,2) NOT NULL,
  `limite_temp_min` decimal(5,2) NOT NULL,
  `limite_umidade_max` decimal(5,2) NOT NULL,
  `limite_umidade_min` decimal(5,2) NOT NULL,
  `buzzer_ativo` tinyint(1) DEFAULT 1,
  `led_ativo` tinyint(1) DEFAULT 1,
  `atualizado_em` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `configuracoes`
--

INSERT INTO `configuracoes` (`id`, `setor_id`, `limite_temp_max`, `limite_temp_min`, `limite_umidade_max`, `limite_umidade_min`, `buzzer_ativo`, `led_ativo`, `atualizado_em`) VALUES
(1, 1, 25.00, 18.00, 70.00, 40.00, 1, 1, '2026-04-13 12:09:20'),
(2, 2, 22.00, 18.00, 70.00, 45.00, 1, 1, '2026-04-09 14:50:11');

-- --------------------------------------------------------

--
-- Estrutura para tabela `leituras`
--

CREATE TABLE `leituras` (
  `id` int(11) NOT NULL,
  `setor_id` int(11) NOT NULL,
  `temperatura` decimal(5,2) NOT NULL,
  `umidade` decimal(5,2) NOT NULL,
  `alerta_ativo` tinyint(1) NOT NULL DEFAULT 0,
  `motivo_alerta` varchar(120) DEFAULT NULL,
  `criado_em` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `leituras`
--

INSERT INTO `leituras` (`id`, `setor_id`, `temperatura`, `umidade`, `alerta_ativo`, `motivo_alerta`, `criado_em`) VALUES
(514, 1, 25.30, 0.00, 0, '', '2026-05-06 13:43:23'),
(515, 2, 0.00, 59.00, 0, '', '2026-05-06 13:43:23'),
(516, 1, 25.30, 0.00, 0, '', '2026-05-06 13:43:53'),
(517, 2, 0.00, 59.00, 0, '', '2026-05-06 13:43:53'),
(518, 1, 25.30, 0.00, 0, '', '2026-05-06 13:44:23'),
(519, 2, 0.00, 59.00, 0, '', '2026-05-06 13:44:24'),
(520, 1, 25.30, 0.00, 0, '', '2026-05-06 13:44:53'),
(521, 2, 0.00, 59.00, 0, '', '2026-05-06 13:44:53'),
(522, 1, 25.30, 0.00, 0, '', '2026-05-06 13:45:23'),
(523, 2, 0.00, 59.00, 0, '', '2026-05-06 13:45:23'),
(524, 1, 26.20, 0.00, 0, '', '2026-05-06 13:45:52'),
(525, 2, 0.00, 59.00, 0, '', '2026-05-06 13:45:53'),
(526, 1, 26.20, 0.00, 0, '', '2026-05-06 13:46:23'),
(527, 2, 0.00, 59.00, 0, '', '2026-05-06 13:46:23'),
(528, 1, 26.20, 0.00, 0, '', '2026-05-06 13:46:53'),
(529, 2, 0.00, 59.00, 0, '', '2026-05-06 13:46:53'),
(530, 1, 25.80, 0.00, 0, '', '2026-05-06 13:47:22'),
(531, 2, 0.00, 59.00, 0, '', '2026-05-06 13:47:23'),
(532, 1, 25.80, 0.00, 0, '', '2026-05-06 13:47:53'),
(533, 2, 0.00, 59.00, 0, '', '2026-05-06 13:47:53'),
(534, 1, 25.80, 0.00, 0, '', '2026-05-06 13:48:23'),
(535, 2, 0.00, 59.00, 0, '', '2026-05-06 13:48:23'),
(536, 1, 25.30, 0.00, 0, '', '2026-05-06 13:48:53'),
(537, 2, 0.00, 59.00, 0, '', '2026-05-06 13:48:53'),
(538, 1, 25.30, 0.00, 0, '', '2026-05-06 13:49:23'),
(539, 2, 0.00, 59.00, 0, '', '2026-05-06 13:49:23'),
(540, 1, 25.30, 0.00, 0, '', '2026-05-06 13:49:53'),
(541, 2, 0.00, 59.00, 0, '', '2026-05-06 13:49:53'),
(542, 1, 25.30, 0.00, 0, '', '2026-05-06 13:50:23'),
(543, 2, 0.00, 59.00, 0, '', '2026-05-06 13:50:24'),
(544, 1, 24.80, 0.00, 0, '', '2026-05-06 13:50:53'),
(545, 2, 0.00, 59.00, 0, '', '2026-05-06 13:50:53');

-- --------------------------------------------------------

--
-- Estrutura para tabela `setores`
--

CREATE TABLE `setores` (
  `id` int(11) NOT NULL,
  `nome_setor` varchar(100) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `setores`
--

INSERT INTO `setores` (`id`, `nome_setor`, `ativo`) VALUES
(1, 'Servidores', 1),
(2, 'Documentos', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `criado_em` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `criado_em`) VALUES
(1, 'Administrador', 'admin@workersafety.com', '$2y$12$15dcsVfytPW2GZlBsj7odu1dJAbu6O1ldDINJOtObm0BZbF7aEN9S', '2026-05-11 00:00:00');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `configuracoes`
--
ALTER TABLE `configuracoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_config_setor` (`setor_id`);

--
-- Índices de tabela `leituras`
--
ALTER TABLE `leituras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_setor_data` (`setor_id`,`criado_em`);

--
-- Índices de tabela `setores`
--
ALTER TABLE `setores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_setor_nome` (`nome_setor`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_usuario_email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `configuracoes`
--
ALTER TABLE `configuracoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `leituras`
--
ALTER TABLE `leituras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=546;

--
-- AUTO_INCREMENT de tabela `setores`
--
ALTER TABLE `setores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `configuracoes`
--
ALTER TABLE `configuracoes`
  ADD CONSTRAINT `fk_config_setor` FOREIGN KEY (`setor_id`) REFERENCES `setores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `leituras`
--
ALTER TABLE `leituras`
  ADD CONSTRAINT `fk_leituras_setor` FOREIGN KEY (`setor_id`) REFERENCES `setores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`root`@`localhost` EVENT `ev_limpar_leituras_1h` ON SCHEDULE EVERY 5 MINUTE STARTS '2026-04-13 12:04:52' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM leituras
  WHERE created_at < (NOW() - INTERVAL 1 HOUR)$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
