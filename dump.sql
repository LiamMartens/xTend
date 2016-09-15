-- Adminer 4.2.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `cargo` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `cargo`;

DROP TABLE IF EXISTS `cg_collections`;
CREATE TABLE `cg_collections` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fields` text COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  `mod_only` tinyint(4) DEFAULT '0',
  `admin_only` tinyint(4) DEFAULT '0',
  `creator` int(10) unsigned NOT NULL,
  `_created` datetime DEFAULT CURRENT_TIMESTAMP,
  `_updated` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `cg_collections` (`id`, `name`, `fields`, `data`, `mod_only`, `admin_only`, `creator`, `_created`, `_updated`) VALUES
(2,	'Movies',	'{\"Title\":{\"name\":\"Title\",\"type\":\"text\",\"label\":\"Titel van de film\",\"default\":\"Een film\",\"modifiers\":[]},\"Icon\":{\"name\":\"Icon\",\"type\":\"file\",\"label\":\"Icoon van de film\",\"default\":\"public\\/public\\/img\\/devrant.svg\",\"modifiers\":{\"types\":\"*.svg\"}},\"Release\":{\"name\":\"Release\",\"type\":\"text\",\"label\":\"Realesejaar van de film\",\"default\":\"2016\"},\"Synopsis\":{\"name\":\"Synopsis\",\"type\":\"markdown\",\"label\":\"Korte inhoud\",\"default\":\"Een inhoud\",\"modifiers\":[]},\"Live\":{\"name\":\"Live\",\"type\":\"boolean\",\"label\":\"Vertoont op de site?\",\"default\":\"\"},\"Debug\":{\"name\":\"Debug\",\"type\":\"boolean\",\"label\":\"\",\"default\":\"true\",\"modifiers\":[]}}',	'[{\"Title\":\"Les Mis\\u00e9rables\",\"Release\":\"2012\",\"Synopsis\":\"\\tSynopsis\",\"Icon\":\"public\\/public\\/img\\/devrant.svg\",\"Live\":false,\"Debug\":false,\"_id\":\"6e2f25a2ce09c6e765bab213846d7bb2\"}]',	0,	0,	1,	'2016-09-07 14:43:53',	'2016-09-15 12:01:30'),
(3,	'Colorsets',	'{\"Movie\":{\"name\":\"Movie\",\"type\":\"collection\",\"label\":\"The connected movie\",\"default\":\"9924f49b4ef5a886a56e62dc235af99e\",\"modifiers\":{\"collection\":\"Movies\",\"multiple\":true}},\"Main\":{\"name\":\"Main\",\"type\":\"text\",\"label\":\"Main color\",\"default\":\"\"},\"Secondary color\":{\"name\":\"Secondary color\",\"type\":\"list\",\"label\":\"\",\"default\":\"white\",\"modifiers\":{\"items\":\"black, white\"}}}',	'[{\"Main\":\"green\",\"Movie\":[\"6e2f25a2ce09c6e765bab213846d7bb2\"],\"Secondary color\":\"white\",\"_id\":\"5c56769bd58c3645e553728924a70bf0\"},{\"Main\":\"\",\"Movie\":[\"6e2f25a2ce09c6e765bab213846d7bb2\"],\"Secondary color\":\"white\",\"_id\":\"43217773cdecccaf1982b55903be8e56\"}]',	0,	0,	1,	'2016-09-07 18:35:53',	'2016-09-15 12:09:45');

DROP TABLE IF EXISTS `cg_files`;
CREATE TABLE `cg_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `group` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `creator` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `cg_files` (`id`, `user`, `group`, `name`, `location`, `creator`) VALUES
(7,	NULL,	1,	'public',	'/Volumes/CaseDisk/www/cargo/',	1);

DROP TABLE IF EXISTS `cg_groups`;
CREATE TABLE `cg_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci NOT NULL,
  `admin` tinyint(4) NOT NULL DEFAULT '0',
  `mod` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `cg_groups` (`id`, `name`, `permissions`, `admin`, `mod`) VALUES
(1,	'administrator',	'[]',	1,	0),
(2,	'moderator',	'[]',	0,	1),
(3,	'gebruiker',	'[\"collections\",\"users\",\"users\\/change\"]',	0,	0);

DROP TABLE IF EXISTS `cg_sessions`;
CREATE TABLE `cg_sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned NOT NULL,
  `identifier` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifier` (`identifier`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `cg_sessions` (`id`, `user`, `identifier`, `key`, `_created`, `_updated`) VALUES
(39,	1,	'3c67e3c6478282bb2da1e2b0e2cc93a7747f1a3ca6f4397a21dcef4574ac10e6',	'4e07408562bedb8b60ce05c1decfe3ad16b72230967de01f640b7e4729b49fce',	'2016-08-24 22:20:25',	'2016-08-24 22:20:25'),
(40,	1,	'2aa7a1d93ddafd48ae3ce267be1ec6b9ec329479b504a4a8d929ba403fb2b05e',	'd4735e3a265e16eee03f59718b9b5d03019c07d8b6c51f90da3a666eec13ab35',	'2016-08-25 10:23:25',	'2016-08-25 10:23:25'),
(51,	9,	'8ccfc9ab7bcca167f3404f01050ac78d106995494d47965a4a232102aceb0d5c',	'7eda69057865f6d0d85205fdd72a72c051f2be85251446ac24f43f62be45cce3',	'2016-08-25 15:50:34',	'2016-08-25 15:50:34'),
(54,	1,	'8350f8980e39cb9595a0f692613c0b65a78ab2527e85ba0951bdc9dce201a644',	'2c624232cdd221771294dfbb310aca000a0df6ac8b66b696d90ef06fdefb64a3',	'2016-08-25 19:46:09',	'2016-08-25 19:46:09'),
(55,	1,	'5c67e2a5e24092ec2a5baecf01c250d352be255453bfc8ffd23a9eca250c2180',	'ef2d127de37b942baad06145e54b0c619a1f22327b2ebbcfbec78f5564afe39d',	'2016-08-26 17:20:59',	'2016-08-26 17:20:59'),
(56,	1,	'deb8dd58e1d8afc8cc2cb0358f4ac25365c331dcd946604a2738ceb201927c20',	'18ac3e7343f016890c510e93f935261169d9e3f565436429830faf0934f4f8e4',	'2016-08-30 16:30:46',	'2016-08-30 16:30:46'),
(57,	1,	'e17b60e498a9e9b6277e82acb3870e2c4281c1fece1087c6fe540e90e1e7346f',	'3f79bb7b435b05321651daefd374cdc681dc06faa65e374e38337b88ca046dea',	'2016-08-30 17:21:08',	'2016-08-30 17:21:08'),
(59,	1,	'9ff0acd3437af5f56c7073ad96e06863291935a7a2ffbfa4927bcb86cda8f637',	'19581e27de7ced00ff1ce50b2047e7a567c76b1cbaebabe5ef03f7c3017bb5b7',	'2016-08-31 10:32:35',	'2016-08-31 10:32:35'),
(65,	1,	'0b03f0fc52aa7bc89a90e99a3afe0d615f93e8834bd207ccc4ae9b4058a14ed3',	'5feceb66ffc86f38d952786c6d696c79c2dbc239dd4e91b46729d73a27fb57e9',	'2016-09-01 08:31:41',	'2016-09-01 08:31:41'),
(72,	1,	'baf26fecd0df7e32616ced0c0d41472a2a4c7cf8da66821ac6686d266368eaa8',	'3e23e8160039594a33894f6564e1b1348bbd7a0088d42c4acb73eeaed59c009d',	'2016-09-02 16:51:04',	'2016-09-02 16:51:04'),
(83,	1,	'16fba82087b92630bc0a594dbabd91acd46c06afa94693836a344c48cb61d2ab',	'6b86b273ff34fce19d6b804eff5a3f5747ada4eaa22f1d49c01e52ddb7875b4b',	'2016-09-03 09:14:54',	'2016-09-03 09:14:54'),
(87,	1,	'c5e7cdb3260962059f866386e738cd15c32ed1b753143f6c7b1402c5e87d23f0',	'2e7d2c03a9507ae265ecf5b5356885a53393a2029d241394997265a1a25aefc6',	'2016-09-04 16:52:03',	'2016-09-04 16:52:03'),
(89,	1,	'f1a5554f2cd8d93a463c2be2f3be2272b8f81a3688dc4891b7b513470001df8a',	'252f10c83610ebca1a059c0bae8255eba2f95be4d1d7bcfa89d7248a82d9f111',	'2016-09-05 16:14:19',	'2016-09-05 16:14:19'),
(104,	1,	'2200f304f08e21ab240bbbbdb68481d8c1829a80387bde9e3e39b053da939c52',	'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855',	'2016-09-06 21:06:38',	'2016-09-06 21:06:38'),
(108,	1,	'18673aa5ddc6dd60e737bc34f1f1573a71c7664cdff5dc865e040f88f8f537ac',	'6059c26efa32e33006f32bee6139f1b1c13c08fac508ecc2946cab619aeca04b',	'2016-09-07 13:17:44',	'2016-09-07 13:17:44'),
(109,	1,	'6b746ba5ef8e2b017e94a932890482e79340afbb54e26fd78a36c79402e59426',	'a22d45c58fedeb14032cad1aec32e0218ac39303cfda3c479a5f2a4a96ea5aea',	'2016-09-07 13:18:26',	'2016-09-07 13:18:26'),
(110,	1,	'4469598a4062a85cd172f8d1ce7add5fc10b89f712a7f4511a8d6874cc21e6e2',	'e4e2701a229635a3dc5787d36b49b0b0bfe56b0cc99d3f3f8dad4826e6cdcb1a',	'2016-09-07 13:18:40',	'2016-09-07 13:18:40'),
(111,	1,	'bc77eeafa75b31fb20613bd4e0d4f502ed432859f27e964ace4b6a45ce66c14a',	'882aa7c86702bd59a4fff200a2c3b3dc4c877bbf7e3f3f8533c4d9854c8e6e65',	'2016-09-07 13:19:10',	'2016-09-07 13:19:10'),
(112,	1,	'5905f2d9c93694906f5c95b3b3af31d2bb9fd4cd6995faaa128b41a1534c7a32',	'fa5d8d5a8743e0dba62a631baca37927bac677e4352003868cb60b7ef7f6fe6a',	'2016-09-07 13:20:11',	'2016-09-07 13:20:11'),
(113,	1,	'31f3fb9039899698108c22113faf2ca2396576e7b4de7f8a65072f04f2fd2777',	'1feaef78b3c2e9b6f694585c1248ccd3ee26d3a9b094748cfd1da9706de440f9',	'2016-09-08 22:43:01',	'2016-09-08 22:43:01'),
(114,	1,	'8a6ee2e37b370a83ee3d4bcaa0317ca08d1a4388db0f3da9eae45f1f140030b3',	'15dbb573aa3328161e6218f2a1dafcd090f6e548ef06ff9767267b996b86724c',	'2016-09-09 07:11:58',	'2016-09-09 07:11:58'),
(115,	1,	'5b15e44640570a0df98b4d4041971cbeceb1972d2219fbcfd1f1848278a964d6',	'f01a364e26f219a5ceecfbd5db706cfa8281de46e49521987d28d6924bccee79',	'2016-09-10 08:12:16',	'2016-09-10 08:12:16'),
(116,	1,	'079df4e637008305ab0de067a51197c8effdb7426d23ea3bea00b0946aef3873',	'efc90e33ef271df4ac80c2e0a3e22e2d4c4e299f373567efb2c08c7c223e88a7',	'2016-09-11 16:29:02',	'2016-09-11 16:29:02'),
(117,	1,	'8fbd50a3b26480b0f820ed50159de114785def17aa762e1a25de299659386ab3',	'aaad0f792197354e5fb2c52a1aeacb7e5be28beba1b5bbea790a01231603bb11',	'2016-09-12 08:52:38',	'2016-09-12 08:52:38'),
(118,	1,	'5e1816d99109753be22014728bd0e06343951d0b65eb334fcf4b470f528d8724',	'0e56d9f46cc5e560f8c8ee26dccf66102339f3c2e3e908a8d7491394241dc5f4',	'2016-09-13 22:35:11',	'2016-09-13 22:35:11'),
(119,	1,	'afc7dd4455bc4157550b1cafde755a8aa7c827edbdc96682422a2bcd8a67c7fe',	'01752ebf5a744a4394c168e01d048d3e102478b18a09922bd89e35878819e0ef',	'2016-09-14 14:13:42',	'2016-09-14 14:13:42'),
(120,	1,	'1d4d967d643ca59a4f924b5e836cc1b9d215d8c777628e79cba9d8eb07eb050a',	'2d933d59e50eb5dd727bc2f8fedc1623ad745b1ab84a3db220244fb68cf75c2f',	'2016-09-14 22:08:39',	'2016-09-14 22:08:39');

DROP TABLE IF EXISTS `cg_users`;
CREATE TABLE `cg_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `group` int(10) unsigned NOT NULL DEFAULT '1',
  `_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `cg_users` (`id`, `name`, `username`, `email`, `password`, `salt`, `language`, `group`, `_created`, `_updated`) VALUES
(1,	'Admin',	'admin',	'hi@liammartens.com',	'$2y$10$GLUQfXM5TBbJppDM0wyvDOWMKlS1sW6twPdLGN.HWHMau55inG/vG',	'19d7cad1dc0a70eb8988c65667c5e0abd6bb6319',	'en',	1,	'2016-08-01 17:05:06',	'2016-08-12 17:07:39'),
(9,	'Liam Martens',	'liammartens',	'hi@liammartens.com',	'$2y$10$An0QdC8Eqj7Wf9YNdAOXreZdw7Hr55nzqLCTXVhhIJou/M8CZkgQS',	'f667bfe4c98cb8e889608678a36fe7a660b8f41f',	'en',	3,	'2016-08-19 19:02:22',	'2016-08-19 19:02:22');

-- 2016-09-15 12:18:33