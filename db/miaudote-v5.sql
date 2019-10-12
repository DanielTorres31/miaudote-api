-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 07-Jun-2018 às 11:30
-- Versão do servidor: 10.1.30-MariaDB
-- PHP Version: 5.6.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `miaudote`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `animal`
--

CREATE TABLE `ANIMAL` (
  `COD_ANIMAL` int(11) NOT NULL,
  `NOM_ANIMAL` varchar(100) DEFAULT NULL,
  `IND_IDADE` char(1) DEFAULT NULL,
  `IND_PORTE_ANIMAL` char(1) DEFAULT NULL,
  `IND_SEXO_ANIMAL` char(1) DEFAULT NULL,
  `IND_CASTRADO` char(1) DEFAULT NULL,
  `IND_ADOTADO` char(1) DEFAULT 'F',
  `IND_EXCLUIDO` char(1) DEFAULT 'F',
  `DAT_CADASTRO` date DEFAULT NULL,
  `DAT_ADOCAO` date DEFAULT NULL,
  `DES_OBSERVACAO` varchar(200) DEFAULT NULL,
  `DES_VACINA` varchar(100) DEFAULT NULL,
  `DES_TEMPERAMENTO` varchar(100) DEFAULT NULL,
  `INSTITUICAO_COD_INSTITUICAO` int(11) NOT NULL,
  `ESPECIE_COD_ESPECIE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `especie`
--

CREATE TABLE `ESPECIE` (
  `COD_ESPECIE` int(11) NOT NULL,
  `DES_ESPECIE` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `especie`
--

INSERT INTO `ESPECIE` (`COD_ESPECIE`, `DES_ESPECIE`) VALUES
(1, 'Cachorro'),
(2, 'Gato');

-- --------------------------------------------------------

--
-- Estrutura da tabela `foto`
--

CREATE TABLE `FOTO` (
  `COD_FOTO_ANIMAL` int(11) NOT NULL,
  `NOM_FOTO` varchar(200) DEFAULT NULL,
  `TIP_FOTO` varchar(10) DEFAULT NULL,
  `BIN_FOTO` mediumblob,
  `IND_FOTO_PRINCIPAL` char(1) DEFAULT NULL,
  `ANIMAL_COD_ANIMAL` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `instituicao`
--

CREATE TABLE `INSTITUICAO` (
  `COD_INSTITUICAO` int(11) NOT NULL,
  `NOM_INSTITUICAO` varchar(100) DEFAULT NULL,
  `NUM_TELEFONE` varchar(15) DEFAULT NULL,
  `IND_TIPO_INSTITUICAO` char(1) DEFAULT NULL,
  `IND_EXCLUIDO` char(1) DEFAULT 'F',
  `DES_EMAIL` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `instituicao`
--

INSERT INTO `INSTITUICAO` (`COD_INSTITUICAO`, `NOM_INSTITUICAO`, `NUM_TELEFONE`, `IND_TIPO_INSTITUICAO`, `IND_EXCLUIDO`, `DES_EMAIL`) VALUES
(1, 'Proteger', '3333', 'O', 'S', 'contato@ongproteger.com.br'),
(2, 'Joao Junior', '9999', 'P', 'N', 'joaojunin@gmail.com');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `USUARIO` (
  `COD_USUARIO` int(11) NOT NULL,
  `DES_SENHA` varchar(80) DEFAULT NULL,
  `NOM_USUARIO` varchar(100) DEFAULT NULL,
  `DES_TIPO_USUARIO` char(1) DEFAULT NULL,
  `DES_EMAIL` varchar(60) DEFAULT NULL,
  `IND_EXCLUIDO` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuario`
--
/*
INSERT INTO `USUARIO` (`COD_USUARIO`, `DES_SENHA`, `NOM_USUARIO`, `DES_TIPO_USUARIO`, `DES_EMAIL`, `IND_EXCLUIDO`) VALUES
(1, '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Henrique', 'A', 'henrique@gmail.com', 'N'),
(2, '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Nixon', 'C', 'nixonsette@gmail.com', 'S'),
(3, '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Teste', 'C', 'teste@gmail.com', 'S'),
(4, '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'teste', 'C', '123@gmail.com', 'S'),
(5, '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'oi', 'C', '456@gmail.com', 'S'),
(6, '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Foda', 'C', '789@gmail.com', 'S'),
(7, '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'teste', 'C', 'asddsd@gmail.com', 'S'),
(8, '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'João Júnior', 'C', 'joaojunior@gmail.com', 'S'),
(9, '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Outro', 'C', '123456@gmail.com', 'S'),
(10, '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'fsdfkdf', 'C', 'ffff@gmail.com', 'S'),
(11, '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'teste@gmail.com', 'C', 'tes3te@gmail.com', 'S'),
(12, '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Nixon', 'C', 'nixonsette@gmail.com', 'S'),
(13, '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '123', 'C', '654@gmail.com', 'S'),
(14, '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Nixon Sette', 'A', 'nixonsette@gmail.com', 'N'),
(15, '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'gfgdfg@gmail.com', 'C', '123@gmail.com', 'S'),
(16, '7b52009b64fd0a2a49e6d8a939753077792b0554', 'ttt', 'C', 'tt@gmail.com', 'S'),
(17, '51eac6b471a284d3341d8c0c63d0f1a286262a18', 'fdsfdsf@gmail.com', 'C', '456@gmail.com', 'S'),
(18, '51eac6b471a284d3341d8c0c63d0f1a286262a18', 'fdsdfdf@gmail.com', 'C', '456@gmail.comf', 'S');
*/
--
-- Indexes for dumped tables
--

--
-- Indexes for table `animal`
--
ALTER TABLE `ANIMAL`
  ADD PRIMARY KEY (`COD_ANIMAL`),
  ADD KEY `fk_ANIMAL_INSTITUICAO1_idx` (`INSTITUICAO_COD_INSTITUICAO`),
  ADD KEY `fk_ANIMAL_ESPECIE1_idx` (`ESPECIE_COD_ESPECIE`);

--
-- Indexes for table `especie`
--
ALTER TABLE `ESPECIE`
  ADD PRIMARY KEY (`COD_ESPECIE`);

--
-- Indexes for table `foto`
--
ALTER TABLE `FOTO`
  ADD PRIMARY KEY (`COD_FOTO_ANIMAL`),
  ADD KEY `fk_FOTO_ANIMAL1_idx` (`ANIMAL_COD_ANIMAL`);

--
-- Indexes for table `instituicao`
--
ALTER TABLE `INSTITUICAO`
  ADD PRIMARY KEY (`COD_INSTITUICAO`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `USUARIO`
  ADD PRIMARY KEY (`COD_USUARIO`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `animal`
--
ALTER TABLE `ANIMAL`
  MODIFY `COD_ANIMAL` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `especie`
--
ALTER TABLE `ESPECIE`
  MODIFY `COD_ESPECIE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `foto`
--
ALTER TABLE `FOTO`
  MODIFY `COD_FOTO_ANIMAL` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instituicao`
--
ALTER TABLE `INSTITUICAO`
  MODIFY `COD_INSTITUICAO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `USUARIO`
  MODIFY `COD_USUARIO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `animal`
--
ALTER TABLE `ANIMAL`
  ADD CONSTRAINT `fk_ANIMAL_ESPECIE1` FOREIGN KEY (`ESPECIE_COD_ESPECIE`) REFERENCES `ESPECIE` (`COD_ESPECIE`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ANIMAL_INSTITUICAO1` FOREIGN KEY (`INSTITUICAO_COD_INSTITUICAO`) REFERENCES `INSTITUICAO` (`COD_INSTITUICAO`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `foto`
--
ALTER TABLE `FOTO`
  ADD CONSTRAINT `fk_FOTO_ANIMAL1` FOREIGN KEY (`ANIMAL_COD_ANIMAL`) REFERENCES `ANIMAL` (`COD_ANIMAL`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
