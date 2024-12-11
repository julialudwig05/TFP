-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 11-Dez-2024 às 12:01
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `ranking_pizzas`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `classificacoes`
--

CREATE TABLE `classificacoes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `pizza_id` int(11) DEFAULT NULL,
  `classificacao` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pizzas`
--

CREATE TABLE `pizzas` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `classificacoes` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `pizzas`
--

INSERT INTO `pizzas` (`id`, `nome`, `imagem`, `classificacoes`) VALUES
(2, 'Quatro Queijos', 'uploads/pizzaQueijo.avif', 0),
(3, 'Coração', 'uploads/pizzaCoracao.jpg', 0),
(4, 'Filé', 'uploads/pizzaFile.jpg', 0),
(5, 'Portuguesa', 'uploads/pizzaPortuguesa.jpg', 0),
(6, 'Frango', 'uploads/pizzaFrango.jpg', 0),
(7, 'Bacon', 'uploads/pizzaBacon.jpg', 0),
(8, 'Milho', 'uploads/pizzaMilho.jpg', 0),
(9, 'MMs', 'uploads/pizzaMM.jpg', 0),
(10, 'Morango Moreno', 'uploads/pizzaMorangoMoreno.jpg', 0),
(11, 'Dois Amores', 'uploads/pizzaDoisAmores.jpg', 0),
(12, 'Sorvete', 'uploads/pizzaSorvete.jpg', 0),
(13, 'Strogonoff', 'uploads/pizzaStrogonoff.jpg', 0),
(14, 'Strogonoff de Frango', 'uploads/pizzaStrogonoffFrango.jpg', 0),
(15, 'Banana Nevada', 'uploads/pizzaBananaNevada.jpg', 0),
(16, 'Calabresa', 'uploads/pizzaCalabresa.jpg', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `papel` enum('usuário','gerente') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `papel`) VALUES
(6, 'admin', 'admin@aluno.feliz.ifrs.edu.br', 'senha123', 'gerente'),
(7, 'Julia Ludwig', 'julialudwigifrs@aluno.feliz.ifrs.edu.br', '$2y$10$ZdzS9V5B8n2uF10t0mPzleAJ8l7H9BNjrmREnHyCY0osMDvuMTB0q', 'usuário'),
(8, 'Matheus', 'matheusschuckfrohlich@aluno.feliz.ifrs.edu.br', '$2y$10$19Y6P7Yb0PPCy2jXcfg.iurzgNpQqvDF0JJlrnT54LRlZ1iBf47sm', 'usuário');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `classificacoes`
--
ALTER TABLE `classificacoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`,`pizza_id`),
  ADD KEY `pizza_id` (`pizza_id`);

--
-- Índices para tabela `pizzas`
--
ALTER TABLE `pizzas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `classificacoes`
--
ALTER TABLE `classificacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pizzas`
--
ALTER TABLE `pizzas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `classificacoes`
--
ALTER TABLE `classificacoes`
  ADD CONSTRAINT `classificacoes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `classificacoes_ibfk_2` FOREIGN KEY (`pizza_id`) REFERENCES `pizzas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
