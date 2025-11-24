SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `nome` varchar(120) NOT NULL,
  `email` varchar(120) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `administradores` (`id`, `nome`, `email`, `senha`, `criado_em`) VALUES
(1, 'Administrador Master', 'admin@barbearia.com', '$2y$10$HemfoP7d44jSRG7PHTkNbOxyHFhLG31Sd/3M1Wh49X6aAlnN0BkFK', '2025-11-15 17:17:44');

CREATE TABLE `agendamentos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `barbeiro_id` int(11) DEFAULT NULL,
  `administrador_id` int(11) DEFAULT NULL,
  `servico_id` int(11) DEFAULT NULL,
  `data_agendamento` date NOT NULL,
  `hora_agendamento` time NOT NULL,
  `status` enum('pendente','confirmado','cancelado','concluido') DEFAULT 'pendente',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `agendamentos` (`id`, `usuario_id`, `barbeiro_id`, `administrador_id`, `servico_id`, `data_agendamento`, `hora_agendamento`, `status`, `criado_em`) VALUES
(9, 5, 4, NULL, NULL, '2025-11-18', '13:00:00', 'cancelado', '2025-11-18 02:39:35'),
(10, 5, 4, NULL, NULL, '2025-10-25', '16:00:00', 'cancelado', '2025-11-18 02:45:18'),
(11, 5, 2, NULL, NULL, '2025-10-25', '16:00:00', 'cancelado', '2025-11-18 02:47:03'),
(12, 5, 4, NULL, NULL, '2025-11-19', '13:00:00', 'confirmado', '2025-11-18 03:10:31'),
(13, 5, 4, NULL, 4, '2025-11-14', '14:00:00', 'cancelado', '2025-11-18 04:06:36'),
(14, 5, 4, NULL, 9, '2025-11-19', '15:30:00', 'cancelado', '2025-11-18 04:34:58'),
(15, 5, 4, NULL, NULL, '2025-11-19', '13:30:00', 'cancelado', '2025-11-18 14:58:21'),
(16, 5, 4, NULL, 11, '2025-11-18', '11:00:00', 'cancelado', '2025-11-18 17:06:00'),
(17, 5, 2, NULL, 11, '2025-11-18', '11:00:00', 'cancelado', '2025-11-18 17:10:16'),
(18, 5, 6, NULL, NULL, '2025-11-19', '12:30:00', 'cancelado', '2025-11-18 19:12:51'),
(19, 5, 6, NULL, NULL, '2025-11-19', '10:00:00', 'cancelado', '2025-11-18 19:19:46'),
(20, 5, 6, NULL, NULL, '2025-11-20', '15:30:00', 'cancelado', '2025-11-18 21:42:25'),
(21, 5, 6, NULL, NULL, '2025-11-19', '16:30:00', 'cancelado', '2025-11-18 21:44:43'),
(22, 5, 11, NULL, 11, '2025-11-20', '15:30:00', 'cancelado', '2025-11-19 16:25:23'),
(23, 5, 11, NULL, 5, '2025-11-20', '13:00:00', 'cancelado', '2025-11-19 20:08:46'),
(24, 5, 11, NULL, 4, '2025-11-20', '12:00:00', 'concluido', '2025-11-19 22:07:20'),
(25, 5, 10, NULL, 11, '2025-11-20', '17:00:00', 'concluido', '2025-11-19 22:07:37'),
(26, 5, 11, NULL, 11, '2025-11-19', '14:00:00', 'concluido', '2025-11-20 01:49:04'),
(27, 5, 10, NULL, 11, '2025-11-19', '14:00:00', 'confirmado', '2025-11-20 01:50:48'),
(28, 5, 10, NULL, 11, '2025-11-24', '09:30:00', 'concluido', '2025-11-23 16:11:04'),
(29, 5, 10, NULL, 4, '2025-11-24', '15:00:00', 'confirmado', '2025-11-23 16:42:36'),
(30, 5, 11, NULL, 10, '2025-11-23', '17:00:00', 'confirmado', '2025-11-23 16:45:09'),
(31, 5, 11, NULL, 4, '2025-11-06', '16:30:00', 'confirmado', '2025-11-23 18:33:10');

CREATE TABLE `barbeiros` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `especialidades` text DEFAULT NULL,
  `sobre` text DEFAULT NULL,
  `horario_atendimento` varchar(100) DEFAULT NULL,
  `status` enum('disponivel','ocupado','ausente') DEFAULT 'disponivel',
  `avaliacao` decimal(3,2) DEFAULT 5.00,
  `total_avaliacoes` int(11) DEFAULT 0,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `barbeiros` (`id`, `nome`, `foto`, `cargo`, `especialidades`, `sobre`, `horario_atendimento`, `status`, `avaliacao`, `total_avaliacoes`, `data_cadastro`, `ativo`) VALUES
(9, 'Ramon Silva', 'barbeiro_691dedbcc8689.jpg', 'Barbeiro Profissional', 'Corte Social e Barba Responsiva', '', 'Seg à Sab - 08 ás 20hrs', 'disponivel', 5.00, 0, '2025-11-19 16:18:04', 1),
(10, 'Pedro Renato', 'barbeiro_691dee6b22568.jpg', 'Barbeiro Profissional', 'Especialista em Degradê', '', 'Seg à Sab - 08 ás 20hrs', 'disponivel', 5.00, 0, '2025-11-19 16:20:59', 1),
(11, 'Mateus Nobre', 'barbeiro_691def3fdd619.jpg', 'Barbeiro Profissional', 'Degradê, Barba e Sombrancelha', '', 'Seg à Sab - 08 ás 20hrs', 'disponivel', 5.00, 0, '2025-11-19 16:24:31', 1);


CREATE TABLE `bloqueios` (
  `id` int(11) NOT NULL,
  `data` date NOT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fim` time DEFAULT NULL,
  `descricao` varchar(200) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `configuracao` (
  `id` int(11) NOT NULL,
  `nome_barbeira` varchar(120) DEFAULT 'Barbearia LV2',
  `cor_primaria` varchar(20) DEFAULT '#ffc400',
  `cor_secundaria` varchar(20) DEFAULT '#1f1f1f',
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `configuracao` (`id`, `nome_barbeira`, `cor_primaria`, `cor_secundaria`, `logo`) VALUES
(1, 'Barbearia LV2', '#ffc400', '#1f1f1f', NULL);

CREATE TABLE `horarios_barbeiro` (
  `id` int(11) NOT NULL,
  `barbeiro_id` int(11) NOT NULL,
  `dia_semana` enum('segunda','terca','quarta','quinta','sexta','sabado') NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `servicos` (
  `id` int(11) NOT NULL,
  `nome` varchar(120) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `duracao` int(11) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `duracao_min` int(11) NOT NULL DEFAULT 30,
  `img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `servicos` (`id`, `nome`, `preco`, `duracao`, `ativo`, `duracao_min`, `img`) VALUES
(4, 'Corte Degradê', 40.00, 0, 1, 40, 'degrade.avif'),
(5, 'Corte na Tesoura', 45.00, 0, 1, 45, 'tesoura.jfif'),
(7, 'Sobrancelha', 15.00, 0, 1, 15, 'sombrancelha.jfif'),
(8, 'Pigmentação', 35.00, 0, 1, 30, 'pigmentacao.jfif'),
(9, 'Hidratação Capilar', 30.00, 0, 1, 20, 'hidratacao.jpg'),
(10, 'Luzes / Platinado', 120.00, 0, 1, 90, 'platinado.jpg'),
(11, 'Combo Premium (Corte + Barba + Hidratação)', 70.00, 0, 1, 70, 'combo.png');

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(120) NOT NULL,
  `email` varchar(120) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('cliente','admin') DEFAULT 'cliente',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `criado_em`) VALUES
(1, 'Cliente Teste', 'cliente@teste.com', '$2y$10$HemfoP7d44jSRG7PHTkNbOxyHFhLG31Sd/3M1Wh49X6aAlnN0BkFK', 'cliente', '2025-11-15 17:17:44'),
(2, 'Vini', 'sdjvhd@gmail.com', '$2y$10$sXGRytxjNruBi8..QR6.ye4lDSBxeIrOIz2XDO9mXsrwaBsOVbrie', 'cliente', '2025-11-15 18:55:59'),
(3, 'Luis', 'luis9861231@gmail.com', '$2y$10$tbOJjC3AM5uqeFQpUXl9UO3yo7G7uGHn5C/cTyejerLC3yGoGcajq', 'cliente', '2025-11-15 18:58:50'),
(5, 'Luis Nobre', 'devluis.nobre@gmail.com', '$2y$10$vT2Prv8TXwTXQdrMvTg45e1JJo6ehCxw5ek/crLwOlQrx3Ra9.HHq', 'admin', '2025-11-16 03:12:12'),
(6, 'manoel', '123m@gmail.com', '$2y$10$u0ORNrEvr33gKMIP/hU0oerseI8Tn6OG8PuEWJqKvF3QAmn.Jk66e', 'admin', '2025-11-17 21:32:42'),
(7, 'Isaac  Nobre', 'foto.nilton@hotmail.com', '$2y$10$XRxxxiQBGTw6f1YZz0Kb7OpT0uw9CklB6vI5O9nXeAqq9vuNai6KC', 'admin', '2025-11-24 00:05:33');

ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `agendamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_agend_usuario` (`usuario_id`),
  ADD KEY `fk_agend_admin` (`administrador_id`),
  ADD KEY `fk_agend_servico` (`servico_id`);

ALTER TABLE `barbeiros`
  ADD PRIMARY KEY (`id`);
-
ALTER TABLE `bloqueios`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `configuracao`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `horarios_barbeiro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barbeiro_id` (`barbeiro_id`);

ALTER TABLE `servicos`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `agendamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

ALTER TABLE `barbeiros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

ALTER TABLE `bloqueios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `configuracao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `horarios_barbeiro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `servicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

ALTER TABLE `agendamentos`
  ADD CONSTRAINT `fk_agend_admin` FOREIGN KEY (`administrador_id`) REFERENCES `administradores` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_agend_servico` FOREIGN KEY (`servico_id`) REFERENCES `servicos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_agend_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

ALTER TABLE `horarios_barbeiro`
  ADD CONSTRAINT `horarios_barbeiro_ibfk_1` FOREIGN KEY (`barbeiro_id`) REFERENCES `usuarios` (`id`);
COMMIT;

