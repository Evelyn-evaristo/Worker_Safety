-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 14/05/2026 às 18:57
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
-- Estrutura para tabela `alarmes`
--

CREATE TABLE `alarmes` (
  `id` int(11) NOT NULL,
  `leitura_id` int(11) DEFAULT NULL,
  `setor_id` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `mensagem` varchar(180) NOT NULL,
  `temperatura` decimal(5,2) DEFAULT NULL,
  `umidade` decimal(5,2) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'ativo',
  `criado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Índices de tabela `alarmes`
--
ALTER TABLE `alarmes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_alarm_setor` (`setor_id`),
  ADD KEY `idx_alarm_status` (`status`);

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
-- AUTO_INCREMENT de tabela `alarmes`
--
ALTER TABLE `alarmes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
