-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 24-Set-2026 às 18:31
-- Versão do servidor: 5.6.13
-- versão do PHP: 5.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `allfit`
--
CREATE DATABASE IF NOT EXISTS `allfit` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `allfit`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `dietas`
--

CREATE TABLE IF NOT EXISTS `dietas` (
  `id_dieta` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `valor_maximo` decimal(10,2) DEFAULT NULL,
  `grupo_alimentar` varchar(100) DEFAULT NULL,
  `meta_calorias` int(11) DEFAULT NULL,
  `hora_cafe` time DEFAULT NULL,
  `hora_almoco` time DEFAULT NULL,
  `hora_janta` time DEFAULT NULL,
  `ciclo` varchar(50) DEFAULT NULL,
  `usa_suplemento` tinyint(1) NOT NULL,
  `suplemento` varchar(100) DEFAULT NULL,
  `alimentos_nao_consumidos` text,
  PRIMARY KEY (`id_dieta`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `dietas`
--

INSERT INTO `dietas` (`id_dieta`, `id_usuario`, `valor_maximo`, `grupo_alimentar`, `meta_calorias`, `hora_cafe`, `hora_almoco`, `hora_janta`, `ciclo`, `usa_suplemento`, `suplemento`, `alimentos_nao_consumidos`) VALUES
(1, 2, '300.00', 'Todos os grupos', 2000, '09:00:00', '12:30:00', '22:00:00', 'Um mÃªs', 0, '', ''),
(2, 2, '500.00', 'Todos os grupos', 2000, '09:00:00', '12:30:00', '21:40:00', 'Um mÃªs', 0, '', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `dieta_restricao`
--

CREATE TABLE IF NOT EXISTS `dieta_restricao` (
  `id_dieta` int(11) NOT NULL,
  `id_restricao` int(11) NOT NULL,
  PRIMARY KEY (`id_dieta`,`id_restricao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `dieta_restricao`
--

INSERT INTO `dieta_restricao` (`id_dieta`, `id_restricao`) VALUES
(1, 1),
(2, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `dificuldades_mobilidade`
--

CREATE TABLE IF NOT EXISTS `dificuldades_mobilidade` (
  `id_dificuldade` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) NOT NULL,
  PRIMARY KEY (`id_dificuldade`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Extraindo dados da tabela `dificuldades_mobilidade`
--

INSERT INTO `dificuldades_mobilidade` (`id_dificuldade`, `nome`) VALUES
(1, 'Dor no joelho'),
(2, 'Problemas na coluna'),
(3, 'Lesão no ombro'),
(4, 'Dificuldade de equilíbrio');

-- --------------------------------------------------------

--
-- Estrutura da tabela `objetivos`
--

CREATE TABLE IF NOT EXISTS `objetivos` (
  `id_objetivo` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY (`id_objetivo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `objetivos`
--

INSERT INTO `objetivos` (`id_objetivo`, `nome`) VALUES
(1, 'Melhorar alimentação'),
(2, 'Ganhar Massa'),
(3, 'Emagrecer');

-- --------------------------------------------------------

--
-- Estrutura da tabela `perfis`
--

CREATE TABLE IF NOT EXISTS `perfis` (
  `id_perfil` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `idade` int(11) NOT NULL,
  `altura` decimal(4,2) NOT NULL,
  `peso` decimal(5,2) NOT NULL,
  PRIMARY KEY (`id_perfil`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `perfis`
--

INSERT INTO `perfis` (`id_perfil`, `id_usuario`, `idade`, `altura`, `peso`) VALUES
(1, 2, 29, '1.67', '60.00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `treinos`
--

CREATE TABLE IF NOT EXISTS `treinos` (
  `id_treino` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `tipo_exercicio` varchar(30) NOT NULL,
  `horario_inicio` time NOT NULL,
  `horario_fim` time NOT NULL,
  `duracao` int(11) NOT NULL,
  `local` varchar(30) NOT NULL,
  `dias_treino` varchar(100) NOT NULL,
  `dias_total` int(11) NOT NULL,
  PRIMARY KEY (`id_treino`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `treinos`
--

INSERT INTO `treinos` (`id_treino`, `id_usuario`, `tipo_exercicio`, `horario_inicio`, `horario_fim`, `duracao`, `local`, `dias_treino`, `dias_total`) VALUES
(1, 2, 'Calistenia', '09:00:00', '11:00:00', 60, 'casa', 'segunda, quarta, sexta', 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `treino_dificuldade`
--

CREATE TABLE IF NOT EXISTS `treino_dificuldade` (
  `id_treino` int(11) NOT NULL,
  `id_dificuldade` int(11) NOT NULL,
  PRIMARY KEY (`id_treino`,`id_dificuldade`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `treino_dificuldade`
--

INSERT INTO `treino_dificuldade` (`id_treino`, `id_dificuldade`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(20) NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nome`, `email`, `senha`) VALUES
(1, 'Teste', 'teste@gmail.com', '1234'),
(2, 'Isabela Pereira', 'isa@gmail.com', '28manga97');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario_objetivo`
--

CREATE TABLE IF NOT EXISTS `usuario_objetivo` (
  `id_usuario_objetivo` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_objetivo` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario_objetivo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
