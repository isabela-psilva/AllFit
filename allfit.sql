-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 07-Nov-2026 às 20:37
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
-- Estrutura da tabela `cardapio_dieta`
--

CREATE TABLE IF NOT EXISTS `cardapio_dieta` (
  `id_cardapio` int(11) NOT NULL AUTO_INCREMENT,
  `id_dieta` int(11) NOT NULL,
  `dia_semana` varchar(20) NOT NULL,
  `tipo_refeicao` varchar(30) NOT NULL,
  `id_receita` int(11) NOT NULL,
  PRIMARY KEY (`id_cardapio`),
  UNIQUE KEY `uk_dieta_dia_refeicao` (`id_dieta`,`dia_semana`,`tipo_refeicao`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=57 ;

--
-- Extraindo dados da tabela `cardapio_dieta`
--

INSERT INTO `cardapio_dieta` (`id_cardapio`, `id_dieta`, `dia_semana`, `tipo_refeicao`, `id_receita`) VALUES
(1, 2, 'Segunda-feira', 'Café da manhã', 2),
(2, 2, 'Segunda-feira', 'Almoço', 4),
(3, 2, 'Segunda-feira', 'Lanche', 38),
(4, 2, 'Segunda-feira', 'Jantar', 12),
(5, 2, 'Terça-feira', 'Café da manhã', 1),
(6, 2, 'Terça-feira', 'Almoço', 4),
(7, 2, 'Terça-feira', 'Lanche', 8),
(8, 2, 'Terça-feira', 'Jantar', 10),
(9, 2, 'Quarta-feira', 'Café da manhã', 2),
(10, 2, 'Quarta-feira', 'Almoço', 4),
(11, 2, 'Quarta-feira', 'Lanche', 8),
(12, 2, 'Quarta-feira', 'Jantar', 12),
(13, 2, 'Quinta-feira', 'Café da manhã', 1),
(14, 2, 'Quinta-feira', 'Almoço', 6),
(15, 2, 'Quinta-feira', 'Lanche', 8),
(16, 2, 'Quinta-feira', 'Jantar', 10),
(17, 2, 'Sexta-feira', 'Café da manhã', 2),
(18, 2, 'Sexta-feira', 'Almoço', 4),
(19, 2, 'Sexta-feira', 'Lanche', 8),
(20, 2, 'Sexta-feira', 'Jantar', 12),
(21, 2, 'Sábado', 'Café da manhã', 1),
(22, 2, 'Sábado', 'Almoço', 6),
(23, 2, 'Sábado', 'Lanche', 8),
(24, 2, 'Sábado', 'Jantar', 10),
(25, 2, 'Domingo', 'Café da manhã', 2),
(26, 2, 'Domingo', 'Almoço', 4),
(27, 2, 'Domingo', 'Lanche', 8),
(28, 2, 'Domingo', 'Jantar', 12),
(29, 3, 'Segunda-feira', 'Café da manhã', 17),
(30, 3, 'Segunda-feira', 'Almoço', 6),
(31, 3, 'Segunda-feira', 'Lanche', 36),
(32, 3, 'Segunda-feira', 'Jantar', 12),
(33, 3, 'Terça-feira', 'Café da manhã', 16),
(34, 3, 'Terça-feira', 'Almoço', 22),
(35, 3, 'Terça-feira', 'Lanche', 33),
(36, 3, 'Terça-feira', 'Jantar', 42),
(37, 3, 'Quarta-feira', 'Café da manhã', 14),
(38, 3, 'Quarta-feira', 'Almoço', 26),
(39, 3, 'Quarta-feira', 'Lanche', 38),
(40, 3, 'Quarta-feira', 'Jantar', 44),
(41, 3, 'Quinta-feira', 'Café da manhã', 20),
(42, 3, 'Quinta-feira', 'Almoço', 29),
(43, 3, 'Quinta-feira', 'Lanche', 39),
(44, 3, 'Quinta-feira', 'Jantar', 10),
(45, 3, 'Sexta-feira', 'Café da manhã', 15),
(46, 3, 'Sexta-feira', 'Almoço', 24),
(47, 3, 'Sexta-feira', 'Lanche', 37),
(48, 3, 'Sexta-feira', 'Jantar', 47),
(49, 3, 'Sábado', 'Café da manhã', 2),
(50, 3, 'Sábado', 'Almoço', 23),
(51, 3, 'Sábado', 'Lanche', 34),
(52, 3, 'Sábado', 'Jantar', 43),
(53, 3, 'Domingo', 'Café da manhã', 13),
(54, 3, 'Domingo', 'Almoço', 4),
(55, 3, 'Domingo', 'Lanche', 32),
(56, 3, 'Domingo', 'Jantar', 46);

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
  `alimentos_nao_consumidos` mediumtext,
  PRIMARY KEY (`id_dieta`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `dietas`
--

INSERT INTO `dietas` (`id_dieta`, `id_usuario`, `valor_maximo`, `grupo_alimentar`, `meta_calorias`, `hora_cafe`, `hora_almoco`, `hora_janta`, `ciclo`, `usa_suplemento`, `suplemento`, `alimentos_nao_consumidos`) VALUES
(1, 2, '300.00', 'Todos os grupos', 2000, '09:00:00', '12:30:00', '22:00:00', 'Um mês', 0, '', ''),
(2, 2, '500.00', 'Todos os grupos', 2000, '09:00:00', '12:30:00', '21:40:00', 'Um mês', 0, '', ''),
(3, 2, '500.00', 'Todos os grupos', 2000, '08:00:00', '13:30:00', '21:40:00', 'Quatro meses', 0, '', '');

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
(2, 1),
(3, 1);

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
-- Estrutura da tabela `exercicios`
--

CREATE TABLE IF NOT EXISTS `exercicios` (
  `id_exercicio` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text,
  `categoria` varchar(50) NOT NULL,
  `tipo_exercicio` varchar(30) NOT NULL,
  `local` varchar(30) NOT NULL,
  `duracao_minutos` int(11) DEFAULT NULL,
  `series` int(11) DEFAULT NULL,
  `repeticoes` int(11) DEFAULT NULL,
  `etapa_treino` varchar(30) NOT NULL DEFAULT 'principal',
  PRIMARY KEY (`id_exercicio`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Extraindo dados da tabela `exercicios`
--

INSERT INTO `exercicios` (`id_exercicio`, `nome`, `descricao`, `categoria`, `tipo_exercicio`, `local`, `duracao_minutos`, `series`, `repeticoes`, `etapa_treino`) VALUES
(1, 'Postura da Montanha', 'Exercício de postura e equilíbrio.', 'Alongamento', 'Yoga', 'todos', 5, 2, 1, 'aquecimento'),
(2, 'Postura da Criança', 'Movimento de relaxamento e alongamento.', 'Alongamento', 'Yoga', 'todos', 5, 2, 1, 'finalizacao'),
(3, 'Respiração Consciente', 'Exercício de respiração e relaxamento.', 'Relaxamento', 'Yoga', 'todos', 5, 1, 1, 'aquecimento'),
(4, 'Caminhada Leve', 'Caminhada em ritmo confortável.', 'Aeróbico', 'Caminhada/Corrida', 'ar_livre', 15, 1, 1, 'aquecimento'),
(5, 'Caminhada Moderada', 'Caminhada em ritmo moderado.', 'Aeróbico', 'Caminhada/Corrida', 'ar_livre', 20, 1, 1, 'principal'),
(6, 'Corrida Leve', 'Corrida em ritmo leve.', 'Aeróbico', 'Caminhada/Corrida', 'ar_livre', 15, 1, 1, 'principal'),
(7, 'Marcha Estacionária', 'Marchar sem sair do lugar.', 'Aquecimento', 'Aeróbico', 'casa', 5, 2, 1, 'aquecimento'),
(8, 'Passos Laterais', 'Deslocamento lateral em ritmo confortável.', 'Aeróbico', 'Aeróbico', 'todos', 5, 2, 1, 'aquecimento'),
(9, 'Polichinelo Adaptado', 'Movimento aeróbico com intensidade adaptável.', 'Aeróbico', 'Aeróbico', 'casa', 5, 2, 10, 'principal'),
(10, 'Supino na Máquina', 'Exercício de fortalecimento dos músculos do peitoral.', 'Força', 'Anaeróbico', 'academia', 10, 3, 12, 'principal'),
(11, 'Rosca Direta', 'Exercício de fortalecimento dos braços.', 'Força', 'Anaeróbico', 'academia', 10, 3, 12, 'principal'),
(12, 'Elevação de Panturrilha', 'Exercício de fortalecimento da panturrilha.', 'Força', 'Anaeróbico', 'todos', 5, 3, 12, 'finalizacao'),
(13, 'Flexão na Parede', 'Flexão utilizando a parede como apoio.', 'Força', 'Calistenia', 'casa', 5, 2, 10, 'principal'),
(14, 'Agachamento Livre', 'Exercício de fortalecimento das pernas.', 'Força', 'Calistenia', 'todos', 5, 3, 12, 'principal'),
(15, 'Prancha', 'Exercício de fortalecimento do abdômen.', 'Força', 'Calistenia', 'todos', 5, 2, 1, 'principal'),
(16, 'Gato e Vaca', 'Movimento suave de mobilidade da coluna.', 'Mobilidade', 'Yoga', 'todos', 5, 2, 10, 'aquecimento'),
(17, 'Respiração Diafragmática', 'Exercício de respiração e relaxamento.', 'Relaxamento', 'Yoga', 'todos', 5, 1, 1, 'aquecimento'),
(18, 'Torção Sentada Suave', 'Movimento de alongamento sentado.', 'Alongamento', 'Yoga', 'todos', 5, 2, 1, 'finalizacao'),
(19, 'Caminhada Intervalada Leve', 'Alternância entre caminhada lenta e moderada.', 'Aeróbico', 'Caminhada/Corrida', 'ar_livre', 20, 1, 1, 'aquecimento'),
(20, 'Caminhada na Esteira', 'Caminhada em ritmo confortável na esteira.', 'Aeróbico', 'Caminhada/Corrida', 'academia', 15, 1, 1, 'principal'),
(21, 'Corrida Intervalada Leve', 'Alternância entre caminhada e corrida leve.', 'Aeróbico', 'Caminhada/Corrida', 'ar_livre', 20, 1, 1, 'principal'),
(22, 'Bicicleta Ergométrica', 'Atividade aeróbica na bicicleta estacionária.', 'Aeróbico', 'Aeróbico', 'academia', 15, 1, 1, 'principal'),
(23, 'Dança Leve', 'Movimentos de dança em ritmo confortável.', 'Aeróbico', 'Aeróbico', 'casa', 10, 1, 1, 'principal'),
(24, 'Step Touch', 'Movimento lateral alternado com passos simples.', 'Aeróbico', 'Aeróbico', 'todos', 5, 2, 10, 'aquecimento'),
(25, 'Remada Baixa', 'Exercício de fortalecimento das costas.', 'Força', 'Anaeróbico', 'academia', 10, 3, 12, 'principal'),
(26, 'Cadeira Extensora', 'Exercício de fortalecimento das pernas.', 'Força', 'Anaeróbico', 'academia', 10, 3, 12, 'principal'),
(27, 'Puxada Frontal', 'Exercício de fortalecimento das costas.', 'Força', 'Anaeróbico', 'academia', 10, 3, 12, 'principal'),
(28, 'Elevação Pélvica', 'Movimento de fortalecimento dos glúteos.', 'Força', 'Calistenia', 'casa', 5, 3, 12, 'principal'),
(29, 'Dead Bug', 'Exercício de controle e fortalecimento abdominal.', 'Força', 'Calistenia', 'casa', 5, 2, 10, 'principal'),
(30, 'Flexão Inclinada', 'Flexão realizada com apoio em uma superfície elevada.', 'Força', 'Calistenia', 'todos', 5, 2, 10, 'principal');

-- --------------------------------------------------------

--
-- Estrutura da tabela `exercicio_dificuldade`
--

CREATE TABLE IF NOT EXISTS `exercicio_dificuldade` (
  `id_exercicio_dificuldade` int(11) NOT NULL AUTO_INCREMENT,
  `id_exercicio` int(11) NOT NULL,
  `id_dificuldade` int(11) NOT NULL,
  PRIMARY KEY (`id_exercicio_dificuldade`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

--
-- Extraindo dados da tabela `exercicio_dificuldade`
--

INSERT INTO `exercicio_dificuldade` (`id_exercicio_dificuldade`, `id_exercicio`, `id_dificuldade`) VALUES
(1, 1, 4),
(2, 4, 1),
(3, 4, 4),
(4, 5, 1),
(5, 5, 4),
(6, 6, 1),
(7, 6, 2),
(8, 6, 4),
(9, 7, 4),
(10, 8, 4),
(11, 9, 1),
(12, 9, 2),
(13, 10, 3),
(14, 11, 3),
(15, 12, 1),
(16, 12, 4),
(17, 13, 3),
(18, 14, 1),
(19, 14, 2),
(20, 14, 4),
(21, 15, 2),
(22, 15, 3),
(32, 19, 1),
(33, 19, 4),
(34, 20, 1),
(35, 20, 4),
(36, 21, 1),
(37, 21, 2),
(38, 21, 4),
(39, 24, 1),
(40, 24, 4),
(41, 25, 3),
(42, 26, 1),
(43, 27, 3),
(44, 30, 3);

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
-- Estrutura da tabela `receitas`
--

CREATE TABLE IF NOT EXISTS `receitas` (
  `id_receita` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `ingredientes` text NOT NULL,
  `modo_preparo` text NOT NULL,
  `calorias` int(11) NOT NULL,
  `tempo_preparo` int(11) NOT NULL,
  `tipo_refeicao` varchar(50) NOT NULL,
  `imagem` varchar(300) NOT NULL,
  PRIMARY KEY (`id_receita`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

--
-- Extraindo dados da tabela `receitas`
--

INSERT INTO `receitas` (`id_receita`, `nome`, `descricao`, `ingredientes`, `modo_preparo`, `calorias`, `tempo_preparo`, `tipo_refeicao`, `imagem`) VALUES
(1, 'Panqueca de Banana', 'Panqueca nutritiva e prática para começar o dia.', 'Banana, ovo, aveia e canela.', 'Amasse a banana, misture com o ovo e a aveia. Coloque em uma frigideira antiaderente e doure dos dois lados.', 280, 15, 'Café da manhã', ''),
(2, 'Omelete com Legumes', 'Omelete rico em proteínas e acompanhado de vegetais.', 'Ovos, tomate, cenoura, espinafre e azeite.', 'Bata os ovos, acrescente os legumes picados e prepare em uma frigideira até cozinhar.', 320, 15, 'Café da manhã', ''),
(3, 'Tapioca com Frango', 'Tapioca recheada com frango desfiado.', 'Goma de tapioca, frango desfiado e tomate.', 'Espalhe a goma em uma frigideira, espere formar a tapioca e acrescente o recheio.', 350, 20, 'Café da manhã', ''),
(4, 'Frango com Arroz Integral', 'Refeição equilibrada com proteínas e carboidratos.', 'Frango, arroz integral, brócolis e azeite.', 'Grelhe o frango temperado. Cozinhe o arroz e os brócolis separadamente e sirva juntos.', 480, 30, 'Almoço', ''),
(5, 'Carne com Batata Doce', 'Combinação nutritiva de proteínas e carboidratos.', 'Carne bovina magra, batata doce e cenoura.', 'Grelhe a carne e cozinhe a batata doce e a cenoura até ficarem macias.', 520, 35, 'Almoço', ''),
(6, 'Peixe com Legumes', 'Prato leve com peixe assado e vegetais.', 'Filé de peixe, abobrinha, cenoura, limão e azeite.', 'Tempere o peixe, coloque em uma assadeira com os legumes e leve ao forno até cozinhar.', 390, 30, 'Almoço', ''),
(7, 'Vitamina de Banana', 'Bebida nutritiva para complementar a alimentação.', 'Banana, leite e aveia.', 'Bata todos os ingredientes no liquidificador até obter uma mistura homogênea.', 300, 5, 'Lanche', ''),
(8, 'Salada de Frutas com Aveia', 'Opção prática com frutas variadas e fibras.', 'Banana, maçã, mamão e aveia.', 'Pique as frutas em um recipiente e acrescente a aveia.', 220, 10, 'Lanche', ''),
(9, 'Sanduíche Natural de Frango', 'Sanduíche com frango desfiado e vegetais.', 'Pão integral, frango desfiado, alface, tomate e cenoura.', 'Misture o frango com os vegetais e monte o sanduíche com o pão integral.', 330, 15, 'Lanche', ''),
(10, 'Sopa de Legumes', 'Preparação leve com diferentes vegetais.', 'Abóbora, cenoura, batata, chuchu e temperos naturais.', 'Cozinhe os legumes com água e temperos até ficarem macios. Sirva em seguida.', 280, 30, 'Jantar', ''),
(11, 'Frango com Purê de Abóbora', 'Prato com proteína e acompanhamento de vegetais.', 'Frango, abóbora, alho e azeite.', 'Cozinhe a abóbora e amasse até formar um purê. Grelhe o frango e sirva junto.', 400, 30, 'Jantar', ''),
(12, 'Omelete de Espinafre', 'Omelete com vegetais para uma refeição prática.', 'Ovos, espinafre, tomate e temperos naturais.', 'Bata os ovos, acrescente o espinafre e o tomate picados e cozinhe em uma frigideira.', 290, 15, 'Jantar', ''),
(13, 'Mingau de Aveia com Banana', 'Mingau cremoso e nutritivo para começar o dia.', '1 banana; 3 colheres de sopa de aveia; 200 ml de leite; canela a gosto.', 'Amasse a banana. Coloque em uma panela com a aveia e o leite. Mexa em fogo baixo até engrossar. Finalize com canela.', 280, 10, 'Café da manhã', ''),
(14, 'Pão de Queijo de Frigideira', 'Uma opção prática de pão de queijo preparado na frigideira.', '1 ovo; 2 colheres de sopa de tapioca; 2 colheres de sopa de queijo ralado; 1 pitada de sal.', 'Misture todos os ingredientes. Despeje em uma frigideira antiaderente e cozinhe dos dois lados até dourar.', 250, 10, 'Café da manhã', ''),
(15, 'Crepioca de Frango', 'Crepioca recheada com frango desfiado.', '1 ovo; 2 colheres de sopa de tapioca; 3 colheres de sopa de frango desfiado; temperos a gosto.', 'Misture o ovo com a tapioca. Coloque em uma frigideira e doure dos dois lados. Acrescente o frango desfiado como recheio.', 290, 15, 'Café da manhã', ''),
(16, 'Overnight Oats com Morango', 'Preparação gelada de aveia com frutas.', '3 colheres de sopa de aveia; 150 ml de leite; 5 morangos picados; 1 colher de chá de mel.', 'Misture a aveia com o leite em um recipiente. Acrescente os morangos e o mel. Deixe na geladeira durante a noite.', 260, 10, 'Café da manhã', ''),
(17, 'Cuscuz com Ovo Mexido', 'Combinação simples de cuscuz e ovos.', '1 xícara de flocão de milho; água para hidratar; 2 ovos; sal a gosto.', 'Hidrate o flocão com água e sal. Deixe descansar por alguns minutos e cozinhe na cuscuzeira. Prepare os ovos mexidos e sirva junto.', 320, 20, 'Café da manhã', ''),
(18, 'Muffin de Legumes', 'Muffins assados com ovos e vegetais.', '2 ovos; 1/2 cenoura ralada; 1/2 abobrinha ralada; 2 colheres de sopa de farinha de aveia; sal a gosto.', 'Misture todos os ingredientes. Distribua em forminhas untadas e asse em forno preaquecido a 180 graus por aproximadamente 20 minutos.', 180, 25, 'Café da manhã', ''),
(19, 'Torrada Integral com Ricota', 'Torradas com recheio leve e cremoso.', '2 fatias de pão integral; 3 colheres de sopa de ricota; tomate picado; orégano a gosto.', 'Toste as fatias de pão. Amasse a ricota e misture com o tomate e o orégano. Distribua sobre as torradas.', 230, 10, 'Café da manhã', ''),
(20, 'Waffle de Banana e Aveia', 'Waffle caseiro preparado com banana e aveia.', '1 banana madura; 1 ovo; 3 colheres de sopa de aveia; 1 colher de chá de fermento.', 'Amasse a banana e misture com o ovo, a aveia e o fermento. Coloque na máquina de waffle e asse até dourar.', 270, 15, 'Café da manhã', ''),
(21, 'Tapioca de Batata-Doce', 'Tapioca recheada com purê de batata-doce.', '3 colheres de sopa de goma de tapioca; 1/2 batata-doce cozida; sal a gosto; orégano.', 'Amasse a batata-doce até formar um purê. Prepare a tapioca em uma frigideira e recheie com o purê temperado.', 240, 20, 'Café da manhã', ''),
(22, 'Frango Grelhado com Quinoa', 'Frango grelhado acompanhado de quinoa e vegetais.', '120 g de peito de frango; 1/2 xícara de quinoa cozida; cenoura; brócolis; azeite e temperos.', 'Tempere o frango e grelhe até ficar completamente cozido. Cozinhe a quinoa e os vegetais. Monte o prato e sirva.', 390, 30, 'Almoço', ''),
(23, 'Escondidinho de Batata-Doce com Carne', 'Escondidinho com purê de batata-doce e carne moída.', '150 g de batata-doce; 100 g de carne moída; cebola; alho; sal e temperos.', 'Cozinhe a batata-doce e amasse até formar um purê. Refogue a carne com cebola e alho. Em um recipiente, faça uma camada de purê, uma de carne e finalize com purê. Leve ao forno para aquecer.', 420, 35, 'Almoço', ''),
(24, 'Almôndegas Caseiras com Legumes', 'Almôndegas assadas acompanhadas de vegetais.', '120 g de carne moída; 1 colher de sopa de aveia; alho; cebola; cenoura; abobrinha.', 'Misture a carne com a aveia e os temperos. Modele as almôndegas e asse a 180 graus até cozinhar completamente. Sirva com legumes cozidos.', 400, 35, 'Almoço', ''),
(25, 'Tilápia Assada com Arroz Integral', 'Peixe assado acompanhado de arroz integral.', '120 g de filé de tilápia; 1/2 xícara de arroz integral cozido; limão; alho; azeite e temperos.', 'Tempere o peixe com limão, alho e temperos. Asse até ficar completamente cozido. Sirva com arroz integral.', 370, 30, 'Almoço', ''),
(26, 'Carne Moída com Abóbora', 'Carne moída refogada com abóbora cozida.', '120 g de carne moída; 150 g de abóbora; cebola; alho; cheiro-verde.', 'Refogue a carne com alho e cebola. Acrescente a abóbora em cubos e um pouco de água. Cozinhe até a abóbora ficar macia.', 380, 25, 'Almoço', ''),
(27, 'Bowl de Grão-de-Bico com Legumes', 'Prato vegetariano com grão-de-bico e vegetais.', '1/2 xícara de grão-de-bico cozido; cenoura; pepino; tomate; folhas verdes; azeite e limão.', 'Cozinhe o grão-de-bico até ficar macio. Pique os vegetais e misture tudo em uma tigela. Tempere com azeite e limão.', 350, 20, 'Almoço', ''),
(28, 'Pimentão Recheado com Frango', 'Pimentões assados recheados com frango desfiado.', '1 pimentão; 100 g de frango desfiado; 2 colheres de sopa de milho; tomate picado; temperos.', 'Corte o pimentão ao meio e retire as sementes. Misture o frango com o milho, o tomate e os temperos. Recheie e asse até o pimentão ficar macio.', 330, 30, 'Almoço', ''),
(29, 'Carne em Tiras com Brócolis', 'Carne salteada com brócolis e temperos.', '120 g de carne bovina em tiras; 1 xícara de brócolis; alho; cebola; azeite e sal.', 'Refogue a carne com alho e cebola. Acrescente o brócolis previamente cozido e misture até ficar macio.', 360, 25, 'Almoço', ''),
(30, 'Frango Assado com Batatas', 'Frango assado acompanhado de batatas temperadas.', '120 g de peito de frango; 1 batata média; alho; limão; azeite e ervas.', 'Tempere o frango e corte a batata em pedaços. Coloque em uma assadeira e leve ao forno a 200 graus até o frango cozinhar completamente e as batatas dourarem.', 430, 40, 'Almoço', ''),
(31, 'Iogurte Natural com Frutas', 'Lanche refrescante com iogurte e frutas frescas.', '1 pote de iogurte natural; 1/2 banana; 4 morangos; 1 colher de sopa de aveia.', 'Pique as frutas. Coloque o iogurte em uma tigela e acrescente as frutas e a aveia.', 220, 5, 'Lanche', ''),
(32, 'Panqueca de Aveia', 'Panqueca simples preparada com aveia e ovo.', '1 ovo; 2 colheres de sopa de aveia; 1 banana pequena; canela a gosto.', 'Amasse a banana e misture com o ovo e a aveia. Despeje em uma frigideira antiaderente e doure dos dois lados.', 240, 10, 'Lanche', ''),
(33, 'Bolinho de Banana', 'Bolinho caseiro de banana e aveia.', '2 bananas maduras; 1 ovo; 4 colheres de sopa de aveia; 1 colher de chá de fermento; canela.', 'Amasse as bananas e misture com os demais ingredientes. Distribua em forminhas e asse a 180 graus por aproximadamente 20 minutos.', 190, 25, 'Lanche', ''),
(34, 'Vitamina de Mamão com Aveia', 'Bebida cremosa de mamão e aveia.', '1 fatia de mamão; 200 ml de leite; 1 colher de sopa de aveia.', 'Coloque todos os ingredientes no liquidificador e bata até obter uma mistura homogênea.', 210, 5, 'Lanche', ''),
(35, 'Torrada Integral com Abacate', 'Torrada com pasta de abacate temperada.', '2 fatias de pão integral; 1/2 abacate pequeno; limão; sal e pimenta.', 'Amasse o abacate e tempere com limão, sal e pimenta. Toste o pão e espalhe a pasta por cima.', 250, 10, 'Lanche', ''),
(36, 'Homus com Palitos de Cenoura', 'Pasta de grão-de-bico acompanhada de cenoura.', '1/2 xícara de grão-de-bico cozido; 1 colher de sopa de azeite; limão; alho; cenoura em palitos.', 'Bata o grão-de-bico com azeite, limão e alho até formar uma pasta. Sirva com a cenoura cortada em palitos.', 200, 10, 'Lanche', ''),
(37, 'Maçã Assada com Canela', 'Sobremesa ou lanche leve de maçã assada.', '1 maçã; canela em pó; 1 colher de chá de mel.', 'Corte a maçã ao meio e retire as sementes. Polvilhe canela e mel. Leve ao forno até ficar macia.', 130, 20, 'Lanche', ''),
(38, 'Vitamina de Frutas Vermelhas', 'Vitamina refrescante com frutas vermelhas.', '1/2 xícara de morangos; 1/4 xícara de amoras; 200 ml de leite; 1 colher de chá de mel.', 'Coloque todos os ingredientes no liquidificador e bata até ficar homogêneo.', 190, 5, 'Lanche', ''),
(39, 'Muffin Salgado de Grão-de-Bico', 'Muffin assado com farinha de grão-de-bico e vegetais.', '1/2 xícara de farinha de grão-de-bico; 1 ovo; tomate picado; cenoura ralada; água e temperos.', 'Misture a farinha com o ovo e um pouco de água até formar uma massa. Acrescente os vegetais e os temperos. Distribua em forminhas e asse a 180 graus por aproximadamente 20 minutos.', 210, 25, 'Lanche', ''),
(40, 'Peixe Assado com Batatas', 'Filé de peixe assado acompanhado de batatas.', '120 g de filé de peixe; 1 batata pequena; limão; alho; azeite e ervas.', 'Tempere o peixe com limão e alho. Corte a batata em rodelas e coloque em uma assadeira. Acrescente o peixe e asse até ficar completamente cozido.', 390, 35, 'Jantar', ''),
(41, 'Abobrinha Recheada com Frango', 'Abobrinha assada recheada com frango desfiado.', '1 abobrinha média; 100 g de frango desfiado; tomate; cebola; alho e temperos.', 'Corte a abobrinha ao meio e retire parte do miolo. Refogue o frango com tomate e cebola. Recheie a abobrinha e leve ao forno até ficar macia.', 280, 30, 'Jantar', ''),
(42, 'Caldo de Mandioca com Carne', 'Caldo cremoso de mandioca com carne desfiada.', '150 g de mandioca; 80 g de carne bovina; alho; cebola; cheiro-verde e sal.', 'Cozinhe a mandioca até ficar macia. Bata parte da mandioca com a água do cozimento. Cozinhe a carne separadamente, desfie e misture ao caldo. Finalize com cheiro-verde.', 390, 40, 'Jantar', ''),
(43, 'Omelete de Forno com Legumes', 'Omelete assada com vegetais variados.', '2 ovos; cenoura ralada; tomate picado; espinafre; cebola e temperos.', 'Bata os ovos e misture os vegetais picados. Coloque em um recipiente untado e asse a 180 graus por aproximadamente 20 minutos.', 250, 25, 'Jantar', ''),
(44, 'Salada Morna de Lentilha', 'Salada nutritiva de lentilha com vegetais.', '1/2 xícara de lentilha cozida; cenoura; tomate; cebola; azeite; limão e cheiro-verde.', 'Cozinhe a lentilha até ficar macia. Refogue levemente a cenoura e a cebola. Misture com a lentilha e o tomate. Tempere com limão e azeite.', 300, 25, 'Jantar', ''),
(45, 'Wrap Integral de Frango', 'Wrap recheado com frango e vegetais.', '1 pão folha integral; 100 g de frango desfiado; alface; tomate; cenoura ralada.', 'Aqueça levemente o pão. Distribua o frango e os vegetais no centro. Enrole e sirva.', 320, 15, 'Jantar', ''),
(46, 'Berinjela Recheada com Legumes', 'Berinjela assada com recheio de vegetais.', '1 berinjela média; tomate; cenoura; cebola; milho; azeite e temperos.', 'Corte a berinjela ao meio e retire parte do miolo. Pique e refogue o miolo com os demais vegetais. Recheie as metades e asse até ficarem macias.', 260, 30, 'Jantar', ''),
(47, 'Creme de Abóbora com Gengibre', 'Creme quente de abóbora com toque de gengibre.', '200 g de abóbora; 1 pedaço pequeno de gengibre; cebola; alho; água e sal.', 'Cozinhe a abóbora com a cebola, o alho e o gengibre. Bata tudo no liquidificador com parte da água do cozimento até obter um creme.', 180, 25, 'Jantar', ''),
(48, 'Frango Acebolado com Legumes', 'Frango refogado com cebola e vegetais.', '120 g de peito de frango em tiras; 1/2 cebola; cenoura; abobrinha; alho e azeite.', 'Refogue o frango com alho e cebola até cozinhar completamente. Acrescente a cenoura e a abobrinha e cozinhe até ficarem macias.', 340, 25, 'Jantar', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `receita_objetivo`
--

CREATE TABLE IF NOT EXISTS `receita_objetivo` (
  `id_receita_objetivo` int(11) NOT NULL AUTO_INCREMENT,
  `id_receita` int(11) NOT NULL,
  `id_objetivo` int(11) NOT NULL,
  PRIMARY KEY (`id_receita_objetivo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=148 ;

--
-- Extraindo dados da tabela `receita_objetivo`
--

INSERT INTO `receita_objetivo` (`id_receita_objetivo`, `id_receita`, `id_objetivo`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 4, 1),
(4, 6, 1),
(5, 8, 1),
(6, 10, 1),
(7, 12, 1),
(8, 3, 2),
(9, 4, 2),
(10, 5, 2),
(11, 7, 2),
(12, 9, 2),
(13, 11, 2),
(14, 1, 3),
(15, 2, 3),
(16, 6, 3),
(17, 8, 3),
(18, 10, 3),
(19, 11, 3),
(20, 12, 3),
(21, 13, 1),
(22, 13, 3),
(23, 14, 1),
(24, 15, 1),
(25, 15, 2),
(26, 16, 1),
(27, 16, 3),
(28, 17, 1),
(29, 17, 2),
(30, 18, 1),
(31, 18, 3),
(32, 19, 1),
(33, 19, 3),
(34, 20, 1),
(35, 20, 2),
(36, 21, 1),
(37, 21, 3),
(38, 22, 1),
(39, 22, 2),
(40, 22, 3),
(41, 23, 1),
(42, 23, 2),
(43, 24, 1),
(44, 24, 2),
(45, 24, 3),
(46, 25, 1),
(47, 25, 2),
(48, 25, 3),
(49, 26, 1),
(50, 26, 2),
(51, 26, 3),
(52, 27, 1),
(53, 27, 3),
(54, 28, 1),
(55, 28, 2),
(56, 29, 1),
(57, 29, 2),
(58, 29, 3),
(59, 30, 1),
(60, 30, 2),
(61, 31, 1),
(62, 31, 2),
(63, 32, 1),
(64, 32, 2),
(65, 32, 3),
(66, 33, 1),
(67, 33, 2),
(68, 33, 3),
(69, 34, 1),
(70, 34, 3),
(71, 35, 1),
(72, 35, 3),
(73, 36, 1),
(74, 36, 3),
(75, 37, 1),
(76, 37, 3),
(77, 38, 1),
(78, 38, 3),
(79, 39, 1),
(80, 39, 2),
(81, 40, 1),
(82, 40, 2),
(83, 40, 3),
(84, 41, 1),
(85, 41, 2),
(86, 41, 3),
(87, 42, 1),
(88, 42, 2),
(89, 43, 1),
(90, 43, 2),
(91, 43, 3),
(92, 44, 1),
(93, 44, 3),
(94, 45, 1),
(95, 45, 2),
(96, 46, 1),
(97, 46, 3),
(98, 47, 1),
(99, 47, 3),
(100, 48, 1),
(101, 48, 2),
(102, 48, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `receita_restricao`
--

CREATE TABLE IF NOT EXISTS `receita_restricao` (
  `id_receita_restricao` int(11) NOT NULL AUTO_INCREMENT,
  `id_receita` int(11) NOT NULL,
  `id_restricao` int(11) NOT NULL,
  PRIMARY KEY (`id_receita_restricao`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Extraindo dados da tabela `receita_restricao`
--

INSERT INTO `receita_restricao` (`id_receita_restricao`, `id_receita`, `id_restricao`) VALUES
(1, 1, 6),
(2, 2, 6),
(3, 12, 6),
(4, 7, 1),
(5, 9, 2),
(6, 1, 8),
(7, 2, 8),
(8, 3, 8),
(9, 4, 8),
(10, 5, 8),
(11, 6, 8),
(12, 7, 8),
(13, 9, 8),
(14, 11, 8),
(15, 12, 8),
(16, 3, 9),
(17, 4, 9),
(18, 5, 9),
(19, 6, 9),
(20, 9, 9),
(21, 11, 9);

-- --------------------------------------------------------

--
-- Estrutura da tabela `restricoes_alimentares`
--

CREATE TABLE IF NOT EXISTS `restricoes_alimentares` (
  `id_restricao` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY (`id_restricao`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Extraindo dados da tabela `restricoes_alimentares`
--

INSERT INTO `restricoes_alimentares` (`id_restricao`, `nome`) VALUES
(1, 'Laticínios'),
(2, 'Glúten'),
(3, 'Oleaginosas'),
(4, 'Leguminosas'),
(5, 'Frutos do mar'),
(6, 'Ovos'),
(7, 'Outros'),
(8, 'Vegano'),
(9, 'Vegetariano'),
(10, 'Diabético'),
(11, 'Hipertenso'),
(12, 'Por motivos religiosos'),
(13, 'Outra restrição');

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
(1, 2, 'Anaeróbico', '12:04:00', '13:06:00', 60, 'academia', 'segunda, terca, quarta, quinta, sexta', 7);

-- --------------------------------------------------------

--
-- Estrutura da tabela `treino_dificuldade`
--

CREATE TABLE IF NOT EXISTS `treino_dificuldade` (
  `id_treino` int(11) NOT NULL,
  `id_dificuldade` int(11) NOT NULL,
  PRIMARY KEY (`id_treino`,`id_dificuldade`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

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
