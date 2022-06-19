-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 17/03/2015 às 07:41
-- Versão do servidor: 5.5.42-cll
-- Versão do PHP: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de dados: `magnist1_magnistr_im15`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria`
--

CREATE TABLE IF NOT EXISTS `categoria` (
  `categoria_id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria_title` varchar(200) DEFAULT NULL,
  `categoria_url` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`categoria_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Fazendo dump de dados para tabela `categoria`
--

INSERT INTO `categoria` (`categoria_id`, `categoria_title`, `categoria_url`) VALUES
(12, 'São Paulo', 'sao-paulo'),
(13, 'Barueri', 'barueri'),
(14, 'Osasco', 'osasco');

-- --------------------------------------------------------

--
-- Estrutura para tabela `chatban`
--

CREATE TABLE IF NOT EXISTS `chatban` (
  `banid` int(11) NOT NULL AUTO_INCREMENT,
  `dtmcreated` datetime DEFAULT '0000-00-00 00:00:00',
  `dtmtill` datetime DEFAULT '0000-00-00 00:00:00',
  `address` varchar(255) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `blockedCount` int(11) DEFAULT '0',
  PRIMARY KEY (`banid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `chatconfig`
--

CREATE TABLE IF NOT EXISTS `chatconfig` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vckey` varchar(255) DEFAULT NULL,
  `vcvalue` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

--
-- Fazendo dump de dados para tabela `chatconfig`
--

INSERT INTO `chatconfig` (`id`, `vckey`, `vcvalue`) VALUES
(1, 'dbversion', '1.6.3'),
(2, 'featuresversion', '1.6.4'),
(3, 'title', 'Imobiliária Modelo'),
(4, 'hosturl', 'http://magnistrade.com/imobnew/'),
(5, 'logo', ''),
(6, 'usernamepattern', '{name}'),
(7, 'chatstyle', 'simplicity'),
(8, 'chattitle', 'Atendimento Online'),
(9, 'geolink', 'http://api.hostip.info/get_html.php?ip={ip}'),
(10, 'geolinkparams', 'width=440,height=100,toolbar=0,scrollbars=0,location=0,status=1,menubar=0,resizable=1'),
(11, 'max_uploaded_file_size', '100000'),
(12, 'max_connections_from_one_host', '10'),
(13, 'email', 'email@seusite.com'),
(14, 'left_messages_locale', 'en'),
(15, 'sendmessagekey', 'enter'),
(16, 'enableban', '0'),
(17, 'enablessl', '0'),
(18, 'forcessl', '0'),
(19, 'usercanchangename', '1'),
(20, 'enablegroups', '0'),
(21, 'enablestatistics', '1'),
(22, 'enablepresurvey', '1'),
(23, 'surveyaskmail', '0'),
(24, 'surveyaskgroup', '1'),
(25, 'surveyaskmessage', '0'),
(26, 'enablepopupnotification', '0'),
(27, 'showonlineoperators', '0'),
(28, 'enablecaptcha', '0'),
(29, 'online_timeout', '30'),
(30, 'updatefrequency_operator', '2'),
(31, 'updatefrequency_chat', '2'),
(32, 'updatefrequency_oldchat', '7');

-- --------------------------------------------------------

--
-- Estrutura para tabela `chatgroup`
--

CREATE TABLE IF NOT EXISTS `chatgroup` (
  `groupid` int(11) NOT NULL AUTO_INCREMENT,
  `vcemail` varchar(64) DEFAULT NULL,
  `vclocalname` varchar(64) NOT NULL,
  `vccommonname` varchar(64) NOT NULL,
  `vclocaldescription` varchar(1024) NOT NULL,
  `vccommondescription` varchar(1024) NOT NULL,
  PRIMARY KEY (`groupid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `chatgroupoperator`
--

CREATE TABLE IF NOT EXISTS `chatgroupoperator` (
  `groupid` int(11) NOT NULL,
  `operatorid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `chatmessage`
--

CREATE TABLE IF NOT EXISTS `chatmessage` (
  `messageid` int(11) NOT NULL AUTO_INCREMENT,
  `threadid` int(11) NOT NULL,
  `ikind` int(11) NOT NULL,
  `agentId` int(11) NOT NULL DEFAULT '0',
  `tmessage` text NOT NULL,
  `dtmcreated` datetime DEFAULT '0000-00-00 00:00:00',
  `tname` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`messageid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1249 ;

--
-- Fazendo dump de dados para tabela `chatmessage`
--

INSERT INTO `chatmessage` (`messageid`, `threadid`, `ikind`, `agentId`, `tmessage`, `dtmcreated`, `tname`) VALUES
(1233, 7, 3, 0, 'O visitante veio da página http://magnistrade.com/imobnew/', '2015-03-12 14:10:57', NULL),
(1234, 7, 4, 0, 'Obrigado por nos contatar. Aguarde...', '2015-03-12 14:10:57', NULL),
(1235, 7, 6, 0, 'O operador Admin entrou no chat', '2015-03-12 14:11:13', NULL),
(1236, 7, 7, 0, '', '2015-03-12 14:11:13', NULL),
(1237, 7, 2, 1, 'Olá! Bem vindo ao nosso suporte. Como posso ajudá-lo?', '2015-03-12 14:11:19', 'Admin'),
(1238, 7, 1, 0, 'Gostaria de comprar uma casa.', '2015-03-12 14:11:48', 'Visitante'),
(1239, 7, 2, 1, 'Show me the money!', '2015-03-12 14:12:11', 'Admin'),
(1240, 7, 1, 0, 'haha', '2015-03-12 14:12:26', 'Visitante'),
(1241, 7, 3, 0, 'Visitor navigated to http://magnistrade.com/imobnew/', '2015-03-12 14:12:48', NULL),
(1242, 7, 3, 0, 'Visitor navigated to http://magnistrade.com/imobnew/', '2015-03-12 14:12:54', NULL),
(1243, 7, 2, 1, 'hey', '2015-03-12 14:13:04', 'Admin'),
(1244, 7, 3, 0, 'O visitante fechou a janela do chat', '2015-03-12 14:13:04', NULL),
(1245, 7, 6, 0, 'O visitante entrou no chat', '2015-03-12 14:13:08', NULL),
(1246, 7, 6, 0, 'O visitante entrou no chat', '2015-03-12 14:13:33', NULL),
(1247, 7, 3, 0, 'O visitante fechou a janela do chat', '2015-03-12 14:14:04', NULL),
(1248, 7, 6, 0, 'O Admin deixou o chat', '2015-03-12 14:15:50', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `chatoperator`
--

CREATE TABLE IF NOT EXISTS `chatoperator` (
  `operatorid` int(11) NOT NULL AUTO_INCREMENT,
  `vclogin` varchar(64) NOT NULL,
  `vcpassword` varchar(64) NOT NULL,
  `vclocalename` varchar(64) NOT NULL,
  `vccommonname` varchar(64) NOT NULL,
  `vcemail` varchar(64) DEFAULT NULL,
  `dtmlastvisited` datetime DEFAULT '0000-00-00 00:00:00',
  `istatus` int(11) DEFAULT '0',
  `vcavatar` varchar(255) DEFAULT NULL,
  `vcjabbername` varchar(255) DEFAULT NULL,
  `iperm` int(11) DEFAULT '65535',
  `dtmrestore` datetime DEFAULT '0000-00-00 00:00:00',
  `vcrestoretoken` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`operatorid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Fazendo dump de dados para tabela `chatoperator`
--

INSERT INTO `chatoperator` (`operatorid`, `vclogin`, `vcpassword`, `vclocalename`, `vccommonname`, `vcemail`, `dtmlastvisited`, `istatus`, `vcavatar`, `vcjabbername`, `iperm`, `dtmrestore`, `vcrestoretoken`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Admin', 'Admin', 'info@magnistrade.com', '2015-03-16 18:37:02', 0, '', '', 65535, '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `chatresponses`
--

CREATE TABLE IF NOT EXISTS `chatresponses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `locale` varchar(8) DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  `vcvalue` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Fazendo dump de dados para tabela `chatresponses`
--

INSERT INTO `chatresponses` (`id`, `locale`, `groupid`, `vcvalue`) VALUES
(1, 'pt-br', NULL, 'Olá, como posso ajudá-lo?'),
(2, 'pt-br', NULL, 'Olá! Bem vindo ao nosso suporte. Como posso ajudá-lo?');

-- --------------------------------------------------------

--
-- Estrutura para tabela `chatrevision`
--

CREATE TABLE IF NOT EXISTS `chatrevision` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `chatrevision`
--

INSERT INTO `chatrevision` (`id`) VALUES
(31);

-- --------------------------------------------------------

--
-- Estrutura para tabela `chatthread`
--

CREATE TABLE IF NOT EXISTS `chatthread` (
  `threadid` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(64) NOT NULL,
  `userid` varchar(255) DEFAULT NULL,
  `agentName` varchar(64) DEFAULT NULL,
  `agentId` int(11) NOT NULL DEFAULT '0',
  `dtmcreated` datetime DEFAULT '0000-00-00 00:00:00',
  `dtmmodified` datetime DEFAULT '0000-00-00 00:00:00',
  `lrevision` int(11) NOT NULL DEFAULT '0',
  `istate` int(11) NOT NULL DEFAULT '0',
  `ltoken` int(11) NOT NULL,
  `remote` varchar(255) DEFAULT NULL,
  `referer` text,
  `nextagent` int(11) NOT NULL DEFAULT '0',
  `locale` varchar(8) DEFAULT NULL,
  `lastpinguser` datetime DEFAULT '0000-00-00 00:00:00',
  `lastpingagent` datetime DEFAULT '0000-00-00 00:00:00',
  `userTyping` int(11) DEFAULT '0',
  `agentTyping` int(11) DEFAULT '0',
  `shownmessageid` int(11) NOT NULL DEFAULT '0',
  `userAgent` varchar(255) DEFAULT NULL,
  `messageCount` varchar(16) DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  PRIMARY KEY (`threadid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Fazendo dump de dados para tabela `chatthread`
--

INSERT INTO `chatthread` (`threadid`, `userName`, `userid`, `agentName`, `agentId`, `dtmcreated`, `dtmmodified`, `lrevision`, `istate`, `ltoken`, `remote`, `referer`, `nextagent`, `locale`, `lastpinguser`, `lastpingagent`, `userTyping`, `agentTyping`, `shownmessageid`, `userAgent`, `messageCount`, `groupid`) VALUES
(7, 'Visitante', '1426183853.725265819917', 'Admin', 1, '2015-03-12 14:10:57', '2015-03-12 14:15:50', 31, 3, 32350552, '189.55.99.139', 'http://magnistrade.com/imobnew/', 0, 'pt-br', '0000-00-00 00:00:00', '2015-03-12 14:15:51', 0, 0, 1238, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36', '2', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE IF NOT EXISTS `cliente` (
  `cliente_id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_nome` varchar(200) DEFAULT NULL,
  `cliente_creci` varchar(20) DEFAULT NULL,
  `cliente_telefone3` varchar(20) DEFAULT NULL,
  `cliente_rua` varchar(300) DEFAULT NULL,
  `cliente_uf` varchar(2) DEFAULT NULL,
  `cliente_num` varchar(20) DEFAULT NULL,
  `cliente_complemento` varchar(2000) DEFAULT NULL,
  `cliente_cidade` varchar(200) DEFAULT NULL,
  `cliente_bairro` varchar(200) DEFAULT NULL,
  `cliente_cep` varchar(20) DEFAULT NULL,
  `cliente_telefone1` varchar(20) DEFAULT NULL,
  `cliente_telefone2` varchar(20) DEFAULT NULL,
  `cliente_lat` varchar(40) NOT NULL,
  `cliente_lon` varchar(40) NOT NULL,
  PRIMARY KEY (`cliente_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Fazendo dump de dados para tabela `cliente`
--

INSERT INTO `cliente` (`cliente_id`, `cliente_nome`, `cliente_creci`, `cliente_telefone3`, `cliente_rua`, `cliente_uf`, `cliente_num`, `cliente_complemento`, `cliente_cidade`, `cliente_bairro`, `cliente_cep`, `cliente_telefone1`, `cliente_telefone2`, `cliente_lat`, `cliente_lon`) VALUES
(1, 'Rafael Clares Diniz', '11.111', '(11) 99222-3344', 'Rua Heitor Penteado', 'SP', '1800', '3 Andar', 'São Paulo', 'Sumaré', '05438-300', '(11) 4004-0000', '(11) 4004-0000', '-23.5445118', '-46.6847155');

-- --------------------------------------------------------

--
-- Estrutura para tabela `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `config_id` int(11) NOT NULL AUTO_INCREMENT,
  `config_site_title` varchar(500) DEFAULT NULL,
  `config_site_description` text,
  `config_site_keywords` text,
  `config_site_about` text,
  PRIMARY KEY (`config_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Fazendo dump de dados para tabela `config`
--

INSERT INTO `config` (`config_id`, `config_site_title`, `config_site_description`, `config_site_keywords`, `config_site_about`) VALUES
(1, 'Imobiliária Modelo', 'Encontre seu imóvel aqui.', 'imóveis, imobiliária, apartamentos, casas, sobrados, terrenos, fazendas', '<p style="text-align: justify;"></p>\r\n<p style="text-align: justify;">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>\r\n<span style=" text-align: start;">\r\n</span>');

-- --------------------------------------------------------

--
-- Estrutura para tabela `dono`
--

CREATE TABLE IF NOT EXISTS `dono` (
  `dono_id` int(11) NOT NULL AUTO_INCREMENT,
  `dono_nome` varchar(200) DEFAULT NULL,
  `dono_creci` varchar(20) DEFAULT NULL,
  `dono_telefone3` varchar(20) DEFAULT NULL,
  `dono_rua` varchar(300) DEFAULT NULL,
  `dono_uf` varchar(2) DEFAULT NULL,
  `dono_num` varchar(20) DEFAULT NULL,
  `dono_complemento` varchar(2000) DEFAULT NULL,
  `dono_cidade` varchar(200) DEFAULT NULL,
  `dono_bairro` varchar(200) DEFAULT NULL,
  `dono_cep` varchar(20) DEFAULT NULL,
  `dono_telefone1` varchar(20) DEFAULT NULL,
  `dono_telefone2` varchar(20) DEFAULT NULL,
  `dono_email` varchar(200) DEFAULT NULL,
  `dono_user` int(11) NOT NULL,
  PRIMARY KEY (`dono_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Fazendo dump de dados para tabela `dono`
--

INSERT INTO `dono` (`dono_id`, `dono_nome`, `dono_creci`, `dono_telefone3`, `dono_rua`, `dono_uf`, `dono_num`, `dono_complemento`, `dono_cidade`, `dono_bairro`, `dono_cep`, `dono_telefone1`, `dono_telefone2`, `dono_email`, `dono_user`) VALUES
(1, 'Imobiliária Modelo', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', 0),
(4, 'Alberto Pereira', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '(12) 99222-3311', '', 'albertopereira@albertopereira.com', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `foto`
--

CREATE TABLE IF NOT EXISTS `foto` (
  `foto_id` int(11) NOT NULL AUTO_INCREMENT,
  `foto_title` varchar(200) DEFAULT NULL,
  `foto_url` varchar(200) DEFAULT NULL,
  `foto_pos` int(11) DEFAULT '0',
  `foto_item` int(11) DEFAULT NULL,
  PRIMARY KEY (`foto_id`),
  KEY `fk_foto_item` (`foto_item`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=254 ;

--
-- Fazendo dump de dados para tabela `foto`
--

INSERT INTO `foto` (`foto_id`, `foto_title`, `foto_url`, `foto_pos`, `foto_item`) VALUES
(232, NULL, '6e09f6d793dd153fae388073abb362ba.jpg', 1, 10),
(233, NULL, '23b9494a7a09b95e0bc8e23e38675dd9.jpg', 2, 10),
(234, NULL, 'dfa336bac84a15546994513261a17449.jpg', 0, 10),
(235, NULL, '6ea1741c0a3a85dbc611b8f7476c0945.jpg', 3, 10),
(236, NULL, '03d199396ab165c92a5d59c605ce497c.jpg', 0, 11),
(237, NULL, 'a503dcde1d5595859c61d695b1a00cf2.jpg', 0, 11),
(238, NULL, '268fc78f5c57fc5de16c949675179826.jpg', 0, 11),
(239, NULL, '8b32dab35881f2a5736c3a361e5d99f8.jpg', 0, 11),
(240, NULL, 'e5d2dcc146d99a3e6bb171ed6605b1dd.jpg', 0, 12),
(241, NULL, '96f770591cb6df90b2ec03540e8548ed.jpg', 0, 12),
(242, NULL, 'ff5e45cfefff967d57f8713ade9947c9.jpg', 0, 12),
(243, NULL, '15189498c920460ea08f3c43d999f9d8.jpg', 0, 13),
(244, NULL, 'eaec9c5a25ed1bb5b6369c32d291786d.jpg', 0, 13),
(245, NULL, '16e1ccb8cbacf107553dffc627fb4b7e.jpg', 0, 13),
(246, NULL, '957352453560c9a11e8951d016fe5df8.jpg', 0, 13),
(247, NULL, '9a77e7011f800fcfa5fa8d716c7fd69c.jpg', 0, 14),
(248, NULL, '412dafaf0d085e0ae1fde24fff16a1a5.jpg', 0, 15),
(249, NULL, '9ff82b7c65ea74750be3890fcd2c168f.jpg', 0, 15),
(250, NULL, 'fbbf2f4eaeeadfcbe91bc5ff402711ae.jpg', 0, 15),
(251, NULL, 'e5b89a7772051dfaccb9f0d1fa9248f1.jpg', 0, 16),
(252, NULL, 'c709678785c4622a61ce718ebfdbc7de.jpg', 0, 17),
(253, NULL, '17b246ea6f0551aa152563b942d5eead.jpg', 0, 18);

-- --------------------------------------------------------

--
-- Estrutura para tabela `item`
--

CREATE TABLE IF NOT EXISTS `item` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_ref` varchar(200) DEFAULT NULL,
  `item_desc` text,
  `item_sub` int(11) DEFAULT NULL,
  `item_preco` double DEFAULT NULL,
  `item_url` varchar(300) DEFAULT NULL,
  `item_show` int(11) DEFAULT '0' COMMENT '1 = sim',
  `item_vendido` varchar(100) DEFAULT '0' COMMENT '1 = vendido\r\n2 = alugado',
  `item_views` int(11) DEFAULT '0',
  `item_categoria` int(11) DEFAULT NULL,
  `item_area` int(11) DEFAULT '0',
  `item_dorm` int(11) DEFAULT '0',
  `item_wc` int(11) DEFAULT '1',
  `item_suite` int(11) DEFAULT '0',
  `item_vaga` int(11) DEFAULT '0',
  `item_finalidade` int(11) DEFAULT '1' COMMENT '1 = venda\r\n2 = locacao\r\n3 = ambos',
  `item_tipo` int(11) DEFAULT '1' COMMENT '1 = casas',
  `item_destaque` int(11) DEFAULT '0' COMMENT '1 = sim',
  `item_destaque_pos` int(11) DEFAULT '0',
  `item_slide` int(11) DEFAULT '0' COMMENT '1 = sim',
  `item_preco_locacao` double DEFAULT NULL,
  `item_busca` varchar(500) DEFAULT NULL,
  `item_preco_condominio` double DEFAULT '0',
  `item_preco_iptu` double DEFAULT '0',
  `item_endereco` varchar(300) DEFAULT NULL,
  `item_mapa` int(11) DEFAULT '1' COMMENT '2 sim',
  `item_dono` int(11) DEFAULT '0',
  `item_user` int(11) DEFAULT '1',
  `item_pos` int(11) NOT NULL DEFAULT '0',
  `item_preco_temp` double(10,2) NOT NULL,
  `item_lat` varchar(40) NOT NULL,
  `item_lon` varchar(40) NOT NULL,
  PRIMARY KEY (`item_id`),
  KEY `fk_item_sub` (`item_sub`),
  KEY `fk_item_tipo` (`item_tipo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Fazendo dump de dados para tabela `item`
--

INSERT INTO `item` (`item_id`, `item_ref`, `item_desc`, `item_sub`, `item_preco`, `item_url`, `item_show`, `item_vendido`, `item_views`, `item_categoria`, `item_area`, `item_dorm`, `item_wc`, `item_suite`, `item_vaga`, `item_finalidade`, `item_tipo`, `item_destaque`, `item_destaque_pos`, `item_slide`, `item_preco_locacao`, `item_busca`, `item_preco_condominio`, `item_preco_iptu`, `item_endereco`, `item_mapa`, `item_dono`, `item_user`, `item_pos`, `item_preco_temp`, `item_lat`, `item_lon`) VALUES
(10, 'CS4411', '', 52, 600000, NULL, 1, '11', 7, 12, 600, 4, 4, 2, 5, 1, 16, 1, 0, 1, 0, 'Casa em Condomínio São Paulo Vila Madalena', 700, 5000, 'Rua Heitor Penteado, 800', 2, 1, 1, 0, 0.00, '-23.5476509', '-46.686529'),
(11, 'CS4412', '', 55, 0, NULL, 1, '8', 4, 12, 800, 5, 3, 3, 4, 2, 9, 1, 0, 1, 19000, 'Casa São Paulo Higienópolis', 0, 6000, 'Rua Bela Cintra, 1223', 1, 0, 1, 8, 0.00, '-23.5573512', '-46.6628306'),
(12, 'AP0001', '', 54, 0, NULL, 1, '10', 0, 12, 900, 2, 1, 1, 1, 2, 8, 1, 0, 1, 25000, 'Apartamento São Paulo Jardins', 3500, 18000, 'Rua Estados Unidos, 900', 2, 1, 1, 3, 0.00, '-23.5704451', '-46.6643089'),
(13, 'AP8000', '', 53, 1400000, NULL, 1, '4', 10, 12, 450, 4, 3, 2, 2, 1, 8, 1, 0, 1, 0, 'Apartamento São Paulo Sumaré', 670, 11000, 'Rua Heitor Penteado, 1000', 2, 1, 1, 1, 0.00, '-23.5475399', '-46.6881589'),
(14, 'AP0010', '', 52, 0, NULL, 1, '6', 0, 12, 180, 3, 2, 1, 1, 2, 8, 1, 0, 1, 4900, 'Apartamento São Paulo Vila Madalena', 800, 7000, 'Rua Purpurina, 100', 2, 1, 1, 4, 0.00, '-23.5528739', '-46.6894764'),
(15, 'AP0011', '', 54, 2000000, NULL, 1, '0', 3, 12, 370, 4, 4, 3, 4, 3, 8, 1, 0, 0, 25000, 'Apartamento São Paulo Jardins', 3700, 33000, 'Alameda Santos, 400', 1, 1, 1, 2, 0.00, '-23.569917', '-46.6483382'),
(16, '', '', 55, 7000000, NULL, 1, '1', 2, 12, 900, 6, 8, 6, 12, 1, 9, 1, 0, 1, 0, 'Casa São Paulo Higienópolis', 0, 50000, 'Rua Teodoro Sampaio', 2, 1, 1, 7, 0.00, '-23.5652163', '-46.690234'),
(17, 'CS4412', '', 52, 1600000, NULL, 1, '0', 0, 12, 400, 3, 4, 3, 4, 1, 9, 1, 0, 0, 0, 'Casa São Paulo Vila Madalena', 0, 5000, 'Rua Harmonia, 400', 1, 1, 1, 6, 0.00, '-23.5546086', '-46.6884413'),
(18, 'CS4415', '', 53, 2000000, NULL, 1, '8', 1, 12, 500, 4, 5, 3, 6, 1, 9, 1, 0, 0, 0, 'Casa São Paulo Sumaré', 0, 0, 'Rua Heitor Penteado, 200', 1, 4, 1, 5, 0.00, '-23.5495226', '-46.6801676');

-- --------------------------------------------------------

--
-- Estrutura para tabela `smtp`
--

CREATE TABLE IF NOT EXISTS `smtp` (
  `smtp_id` int(11) NOT NULL AUTO_INCREMENT,
  `smtp_host` varchar(200) DEFAULT NULL,
  `smtp_username` varchar(100) DEFAULT NULL,
  `smtp_password` varchar(100) DEFAULT NULL,
  `smtp_fromname` varchar(200) DEFAULT NULL,
  `smtp_bcc` varchar(100) DEFAULT NULL,
  `smtp_replyto` varchar(100) DEFAULT NULL,
  `smtp_port` int(11) DEFAULT NULL,
  PRIMARY KEY (`smtp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Fazendo dump de dados para tabela `smtp`
--

INSERT INTO `smtp` (`smtp_id`, `smtp_host`, `smtp_username`, `smtp_password`, `smtp_fromname`, `smtp_bcc`, `smtp_replyto`, `smtp_port`) VALUES
(1, 'mail.seusite.com', 'email@seusite.com', 'senha', 'Imobiliária Modelo', 'dono@seusite.com', 'rafadinix@gmail.com', 587);

-- --------------------------------------------------------

--
-- Estrutura para tabela `sub`
--

CREATE TABLE IF NOT EXISTS `sub` (
  `sub_id` int(11) NOT NULL AUTO_INCREMENT,
  `sub_title` varchar(200) DEFAULT NULL,
  `sub_url` varchar(200) DEFAULT NULL,
  `sub_categoria` int(11) DEFAULT NULL,
  PRIMARY KEY (`sub_id`),
  KEY `fk_sub_categoria` (`sub_categoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=56 ;

--
-- Fazendo dump de dados para tabela `sub`
--

INSERT INTO `sub` (`sub_id`, `sub_title`, `sub_url`, `sub_categoria`) VALUES
(52, 'Vila Madalena', 'vila-madalena', 12),
(53, 'Sumaré', 'sumare', 12),
(54, 'Jardins', 'jardins', 12),
(55, 'Higienópolis', 'higienopolis', 12);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipo`
--

CREATE TABLE IF NOT EXISTS `tipo` (
  `tipo_id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_title` varchar(100) DEFAULT NULL,
  `tipo_url` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`tipo_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Fazendo dump de dados para tabela `tipo`
--

INSERT INTO `tipo` (`tipo_id`, `tipo_title`, `tipo_url`) VALUES
(1, 'Galpão', 'galpao'),
(2, 'Terreno', 'terreno'),
(3, 'Sobrado', 'sobrado'),
(4, 'Sala Comercial', 'sala-comercial'),
(5, 'Chácara', 'chacara'),
(6, 'Sítio', 'sitio'),
(7, 'Fazenda', 'fazenda'),
(8, 'Apartamento', 'apartamento'),
(9, 'Casa', 'casa'),
(10, 'Comercial', 'comercial'),
(11, 'Lote', 'lote'),
(13, 'Área', 'area'),
(14, 'Lançamento', 'lancamento'),
(15, 'Casa e Comercio', 'casa-salao-comercial'),
(16, 'Casa Condomínio', 'casa-em-condominio'),
(17, 'Flat', 'flat'),
(18, 'Loja', 'loja'),
(19, 'Indústria', 'industria'),
(20, 'Hotel', 'hotel'),
(21, 'Prédio', 'predio'),
(22, 'Ilha', 'ilha'),
(23, 'Prontos para Morar', 'prontos-para-morar'),
(24, 'Breves Lançamentos', 'breves-lancamentos');

-- --------------------------------------------------------

--
-- Estrutura para tabela `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login` varchar(20) DEFAULT NULL,
  `user_password` varchar(100) DEFAULT NULL,
  `user_email` varchar(100) DEFAULT NULL,
  `user_name` varchar(200) DEFAULT NULL,
  `user_level` int(11) DEFAULT '2' COMMENT '2 = corretor',
  `user_fone1` varchar(20) DEFAULT NULL,
  `user_fone2` varchar(20) DEFAULT NULL,
  `user_creci` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Fazendo dump de dados para tabela `user`
--

INSERT INTO `user` (`user_id`, `user_login`, `user_password`, `user_email`, `user_name`, `user_level`, `user_fone1`, `user_fone2`, `user_creci`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'info@magnistrade.com', 'Admin', 1, NULL, NULL, NULL);

--
-- Restrições para dumps de tabelas
--

--
-- Restrições para tabelas `foto`
--
ALTER TABLE `foto`
  ADD CONSTRAINT `fk_foto_item` FOREIGN KEY (`foto_item`) REFERENCES `item` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `fk_item_sub` FOREIGN KEY (`item_sub`) REFERENCES `sub` (`sub_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_item_tipo` FOREIGN KEY (`item_tipo`) REFERENCES `tipo` (`tipo_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `sub`
--
ALTER TABLE `sub`
  ADD CONSTRAINT `fk_sub_categoria` FOREIGN KEY (`sub_categoria`) REFERENCES `categoria` (`categoria_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
