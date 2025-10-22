-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql100.infinityfree.com
-- Tempo de geração: 22/10/2025 às 16:05
-- Versão do servidor: 10.6.22-MariaDB
-- Versão do PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `if0_40105852_usuarios`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `Empresa`
--

CREATE TABLE `Empresa` (
  `id_empresa` int(11) NOT NULL,
  `nome_empresa` varchar(255) NOT NULL,
  `cnpj` varchar(18) NOT NULL,
  `setor` varchar(100) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `Feedback`
--

CREATE TABLE `Feedback` (
  `id_feedback` int(11) NOT NULL,
  `id_projeto` int(11) NOT NULL,
  `avaliacao` enum('1','2','3','4','5') NOT NULL,
  `comentario` text DEFAULT NULL,
  `data_feedback` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `IA_Analise`
--

CREATE TABLE `IA_Analise` (
  `id_ia` int(11) NOT NULL,
  `problema_identificado` text DEFAULT NULL,
  `solucao_sugerida` text DEFAULT NULL,
  `complexidade` enum('baixa','média','alta') NOT NULL,
  `data_analise` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `Projeto`
--

CREATE TABLE `Projeto` (
  `id_projeto` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `status` enum('Em análise','Em andamento','Concluído') NOT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `id_empresa` int(11) NOT NULL,
  `id_ia` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `Relatorio`
--

CREATE TABLE `Relatorio` (
  `id_relatorio` int(11) NOT NULL,
  `id_projeto` int(11) NOT NULL,
  `resumo` text DEFAULT NULL,
  `data_emissao` date NOT NULL,
  `link_pdf` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `Usuario`
--

CREATE TABLE `Usuario` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('Administrador','Consultor','Cliente') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `Empresa`
--
ALTER TABLE `Empresa`
  ADD PRIMARY KEY (`id_empresa`),
  ADD UNIQUE KEY `cnpj` (`cnpj`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `Feedback`
--
ALTER TABLE `Feedback`
  ADD PRIMARY KEY (`id_feedback`),
  ADD KEY `id_projeto` (`id_projeto`);

--
-- Índices de tabela `IA_Analise`
--
ALTER TABLE `IA_Analise`
  ADD PRIMARY KEY (`id_ia`);

--
-- Índices de tabela `Projeto`
--
ALTER TABLE `Projeto`
  ADD PRIMARY KEY (`id_projeto`),
  ADD KEY `id_empresa` (`id_empresa`),
  ADD KEY `id_ia` (`id_ia`);

--
-- Índices de tabela `Relatorio`
--
ALTER TABLE `Relatorio`
  ADD PRIMARY KEY (`id_relatorio`),
  ADD KEY `id_projeto` (`id_projeto`);

--
-- Índices de tabela `Usuario`
--
ALTER TABLE `Usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `Empresa`
--
ALTER TABLE `Empresa`
  MODIFY `id_empresa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `Feedback`
--
ALTER TABLE `Feedback`
  MODIFY `id_feedback` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `IA_Analise`
--
ALTER TABLE `IA_Analise`
  MODIFY `id_ia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `Projeto`
--
ALTER TABLE `Projeto`
  MODIFY `id_projeto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `Relatorio`
--
ALTER TABLE `Relatorio`
  MODIFY `id_relatorio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `Usuario`
--
ALTER TABLE `Usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
