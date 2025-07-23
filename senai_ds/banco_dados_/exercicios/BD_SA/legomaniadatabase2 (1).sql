-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: localhost    Database: legomaniadatabase2
-- ------------------------------------------------------
-- Server version	8.0.40

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cliente`
--

DROP TABLE IF EXISTS `cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente` (
  `id_cliente` int NOT NULL,
  `id_funcionario` int NOT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `cep` int DEFAULT NULL,
  `idade` int DEFAULT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `cnpj` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_cliente`),
  KEY `id_funcionario` (`id_funcionario`),
  CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`id_funcionario`) REFERENCES `funcionario` (`id_funcionario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
INSERT INTO `cliente` VALUES (1,101,'123.456.789-01',12345,58,'Brad Pitt',NULL,'brad.pitt@email.com'),(2,102,'234.567.890-12',23456,47,'Angelina Jolie',NULL,'angelina@email.com'),(3,103,'345.678.901-23',34567,48,'Leonardo DiCaprio',NULL,'leo.dicaprio@email.com'),(4,104,'456.789.012-34',45678,73,'Meryl Streep',NULL,'meryl@email.com'),(5,105,'567.890.123-45',56789,67,'Denzel Washington',NULL,'denzel@email.com'),(6,106,'678.901.234-56',67890,38,'Scarlett Johansson',NULL,'scarlett@email.com'),(7,107,'789.012.345-67',78901,66,'Tom Hanks',NULL,'tom.hanks@email.com'),(8,108,'890.123.456-78',89012,55,'Julia Roberts',NULL,'julia@email.com'),(9,109,'901.234.567-89',90123,57,'Robert Downey Jr',NULL,'robert.downey@email.com'),(10,110,'012.345.678-90',1234,53,'Jennifer Aniston',NULL,'jennifer@email.com'),(11,111,'111.222.333-44',11111,94,'Mickey Mouse',NULL,'mickey@email.com'),(12,112,'222.333.444-55',22222,94,'Minnie Mouse',NULL,'minnie@email.com'),(13,113,'333.444.555-66',33333,88,'Pato Donald',NULL,'pato.donald@email.com'),(14,114,'444.555.666-77',44444,90,'Pateta',NULL,'pateta@email.com'),(15,115,'555.666.777-88',55555,91,'Pluto',NULL,'pluto@email.com'),(16,116,'666.777.888-99',66666,72,'Taz Mania',NULL,'taz@email.com'),(17,117,'777.888.999-00',77777,83,'Bugs Bunny',NULL,'bugs@email.com'),(18,118,'888.999.000-11',88888,87,'Porky Pig',NULL,'porky@email.com'),(19,119,'999.000.111-22',99999,53,'Scooby Doo',NULL,'scooby@email.com'),(20,120,'000.111.222-33',0,53,'Shaggy Rogers',NULL,'shaggy@email.com'),(21,121,'121.212.121-21',12121,51,'Elon Musk',NULL,'elon@email.com'),(22,122,'232.323.232-32',23232,58,'Jeff Bezos',NULL,'jeff@email.com'),(23,123,'343.434.343-43',34343,68,'Oprah Winfrey',NULL,'oprah@email.com'),(24,124,'454.545.454-54',45454,41,'Beyoncé',NULL,'beyonce@email.com'),(25,125,'565.656.565-65',56565,53,'Jay-Z',NULL,'jayz@email.com'),(26,126,'676.767.676-76',67676,33,'Taylor Swift',NULL,'taylor@email.com'),(27,127,'787.878.787-87',78787,36,'Drake',NULL,'drake@email.com'),(28,128,'898.989.898-98',89898,35,'Rihanna',NULL,'rihanna@email.com'),(29,129,'909.090.909-09',90909,29,'Justin Bieber',NULL,'justin@email.com'),(30,130,'010.101.010-10',1010,37,'Lady Gaga',NULL,'lady.gaga@email.com'),(31,131,'313.131.313-13',31313,48,'Rodrigo Santoro',NULL,'rodrigo@email.com'),(32,132,'424.242.424-24',42424,53,'Fernanda Montenegro',NULL,'fernanda@email.com'),(33,133,'535.353.535-35',53535,73,'Wagner Moura',NULL,'wagner@email.com'),(34,134,'646.464.646-46',64646,41,'Alice Braga',NULL,'alice@email.com'),(35,135,'757.575.757-57',75757,53,'Seu Jorge',NULL,'seu.jorge@email.com'),(36,136,'868.686.868-68',86868,48,'Débora Falabella',NULL,'debora@email.com'),(37,137,'979.797.979-79',97979,50,'Lázaro Ramos',NULL,'lazaro@email.com'),(38,138,'080.808.080-80',8080,47,'Taís Araújo',NULL,'tais@email.com'),(39,139,'191.919.191-91',19191,41,'Bruno Gagliasso',NULL,'bruno@email.com'),(40,140,'202.020.202-02',20202,40,'Grazi Massafera',NULL,'grazi@email.com'),(41,141,'414.141.414-14',41414,40,'Son Goku',NULL,'goku@email.com'),(42,142,'525.252.525-25',52525,33,'Naruto Uzumaki',NULL,'naruto@email.com'),(43,143,'636.363.636-36',63636,19,'Monkey D. Luffy',NULL,'luffy@email.com'),(44,144,'747.474.747-47',74747,17,'Ichigo Kurosaki',NULL,'ichigo@email.com'),(45,145,'858.585.858-58',85858,25,'Saitama',NULL,'saitama@email.com'),(46,146,'969.696.969-69',96969,23,'Light Yagami',NULL,'light@email.com'),(47,147,'070.707.070-70',7070,19,'Eren Yeager',NULL,'eren@email.com'),(48,148,'181.818.181-81',18181,19,'Mikasa Ackerman',NULL,'mikasa@email.com'),(49,149,'292.929.292-92',29292,14,'Usagi Tsukino',NULL,'usagi@email.com'),(50,150,'303.030.303-03',30303,14,'Sailor Moon',NULL,'sailor.moon@email.com'),(51,151,'515.151.515-15',51515,76,'Albert Einstein',NULL,'einstein@email.com'),(52,152,'626.262.626-26',62626,86,'Nikola Tesla',NULL,'tesla@email.com'),(53,153,'737.373.737-37',73737,73,'Charles Darwin',NULL,'darwin@email.com'),(54,154,'848.484.848-48',84848,66,'Marie Curie',NULL,'curie@email.com'),(55,155,'959.595.959-59',95959,84,'Isaac Newton',NULL,'newton@email.com'),(56,156,'060.606.060-60',6060,84,'Thomas Edison',NULL,'edison@email.com'),(57,157,'171.717.171-71',17171,77,'Galileo Galilei',NULL,'galileo@email.com'),(58,158,'282.828.282-82',28282,67,'Leonardo da Vinci',NULL,'da.vinci@email.com'),(59,159,'393.939.393-93',39393,52,'William Shakespeare',NULL,'shakespeare@email.com'),(60,160,'404.040.404-04',40404,35,'Wolfgang Mozart',NULL,'mozart@email.com');
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fornecedor`
--

DROP TABLE IF EXISTS `fornecedor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fornecedor` (
  `id_fornecedor` int NOT NULL,
  `id_usuario` int NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `cep` int DEFAULT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `cnpj` varchar(20) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `ramo_atividade` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_fornecedor`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `fornecedor_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fornecedor`
--

LOCK TABLES `fornecedor` WRITE;
/*!40000 ALTER TABLE `fornecedor` DISABLE KEYS */;
INSERT INTO `fornecedor` VALUES (1,1,'Stark Industries',12345678,'123.456.789-01','12.345.678/0001-90','(11)91234-5678','Tecnologia'),(2,2,'Wayne Enterprises',23456789,'234.567.890-12','23.456.789/0001-80','(21)99876-5432','Segurança'),(3,3,'Monstros S.A.',34567890,'345.678.901-23','34.567.890/0001-70','(31)92345-8765','Energia'),(4,4,'Panda Burgers',45678901,'456.789.012-34','45.678.901/0001-60','(41)93456-7890','Alimentação'),(5,5,'Wonka Factory',56789012,'567.890.123-45','56.789.012/0001-50','(51)94567-8901','Doces'),(6,6,'AutoPeças Mario',67890123,'678.901.234-56','67.890.123/0001-40','(61)95678-9012','Automotivo'),(7,7,'Laboratório Dexter',78901234,'789.012.345-67','78.901.234/0001-30','(71)96789-0123','Ciência'),(8,8,'Skynet Systems',89012345,'890.123.456-78','89.012.345/0001-20','(81)97890-1234','Tecnologia'),(9,9,'Pizza Mutante',90123456,'901.234.567-89','90.123.456/0001-10','(91)98901-2345','Alimentação'),(10,10,'Kame House',10234567,'012.345.678-90','01.234.567/0001-00','(85)91234-9876','Treinamento'),(11,11,'Buzz Soluções',11345678,'123.123.123-11','12.321.321/0001-11','(11)93333-4444','Consultoria'),(12,12,'Casa do Scooby',12456789,'234.234.234-22','23.432.432/0001-22','(21)94444-5555','Pet Shop'),(13,13,'Oficina Flintstones',13567890,'345.345.345-33','34.543.543/0001-33','(31)95555-6666','Mecânica'),(14,14,'Restaurante Naruto',14678901,'456.456.456-44','45.654.654/0001-44','(41)96666-7777','Alimentação'),(15,15,'Vila da Música',15789012,'567.567.567-55','56.765.765/0001-55','(51)97777-8888','Eventos'),(16,16,'Frozen Gelo Seco',16890123,'678.678.678-66','67.876.876/0001-66','(61)98888-9999','Bebidas'),(17,17,'Tony Robôs',17901234,'789.789.789-77','78.987.987/0001-77','(71)99999-0000','Tecnologia'),(18,18,'Shrek Agro',18012345,'890.890.890-88','89.098.098/0001-88','(81)90000-1111','Agronegócio'),(19,19,'Swift Moda',19123456,'901.901.901-99','90.109.109/0001-99','(91)91111-2222','Moda'),(20,20,'Bob Esponja Grelhados',20234567,'101.202.303-00','10.101.010/0001-00','(85)92222-3333','Alimentação'),(21,21,'Capitão América',21345678,'111.222.333-44','11.121.121/0001-11','(11)93333-4444','Segurança'),(22,22,'Lisa Soluções Educacionais',22456789,'222.333.444-55','22.232.232/0001-22','(21)94444-5555','Educação'),(23,23,'Katy Eventos',23567890,'333.444.555-66','33.343.343/0001-33','(31)95555-6666','Eventos'),(24,24,'DexTech Labs',24678901,'444.555.666-77','44.454.454/0001-44','(41)96666-7777','Pesquisa'),(25,25,'Bat Soluções',25789012,'555.666.777-88','55.565.565/0001-55','(51)97777-8888','Tecnologia'),(26,26,'Ilha Paradisíaca',26890123,'666.777.888-99','66.676.676/0001-66','(61)98888-9999','Turismo'),(27,27,'Omnitrix Co.',27901234,'777.888.999-00','77.787.787/0001-77','(71)99999-0000','Tecnologia'),(28,28,'Zendaya Cosméticos',28012345,'888.999.000-11','88.898.898/0001-88','(81)90000-1111','Beleza'),(29,29,'Mystery Inc.',29123456,'999.000.111-22','99.909.909/0001-99','(91)91111-2222','Investigação'),(30,30,'Chaves Market',30234567,'000.111.222-33','00.010.010/0001-00','(85)92222-3333','Varejo'),(31,31,'Máskara Shows',31345678,'111.111.111-44','11.111.111/0001-11','(11)93333-4444','Entretenimento'),(32,32,'Feitiçaria Potter',32456789,'222.222.222-55','22.222.222/0001-22','(21)94444-5555','Educação'),(33,33,'One Piece Cargo',33567890,'333.333.333-66','33.333.333/0001-33','(31)95555-6666','Logística'),(34,34,'Selena Produções',34678901,'444.444.444-77','44.444.444/0001-44','(41)96666-7777','Música'),(35,35,'Pika Energia',35789012,'555.555.555-88','55.555.555/0001-55','(51)97777-8888','Energia'),(36,36,'Vin Turbo Peças',36890123,'666.666.666-99','66.666.666/0001-66','(61)98888-9999','Auto Peças'),(37,37,'Pallet Town Delivery',37901234,'777.777.777-00','77.777.777/0001-77','(71)99999-0000','Logística'),(38,38,'Friends Café',38012345,'888.888.888-11','88.888.888/0001-88','(81)90000-1111','Alimentação'),(39,39,'Asgard Forge',39123456,'999.999.999-22','99.999.999/0001-99','(91)91111-2222','Metalurgia'),(40,40,'Bochechas Garden',40234567,'101.010.101-33','10.101.101/0001-00','(85)92222-3333','Floricultura'),(41,41,'Riri Estilosa',41345678,'202.020.202-44','20.202.202/0001-20','(11)91234-5678','Moda'),(42,42,'Ooo Aventura',42456789,'303.030.303-55','30.303.303/0001-30','(21)99876-5432','Turismo'),(43,43,'Jake Elástico',43567890,'404.040.404-66','40.404.404/0001-40','(31)92345-8765','Brinquedos'),(44,44,'Neo Cibernético',44678901,'505.050.505-77','50.505.505/0001-50','(41)93456-7890','Segurança'),(45,45,'Buzz Delivery',45789012,'606.060.606-88','60.606.606/0001-60','(51)94567-8901','Logística'),(46,46,'Cowboy Agro',46890123,'707.070.707-99','70.707.707/0001-70','(61)95678-9012','Agronegócio'),(47,47,'Holland Web',47901234,'808.080.808-00','80.808.808/0001-80','(71)96789-0123','Tecnologia'),(48,48,'Cybertron Tech',48012345,'909.090.909-11','90.909.909/0001-90','(81)97890-1234','Robótica'),(49,49,'Fox Beauty',49123456,'111.222.333-00','11.111.000/0001-00','(91)98901-2345','Beleza'),(50,50,'Megamente Corp',50234567,'222.333.444-11','22.222.111/0001-11','(85)91234-9876','Consultoria'),(51,51,'Alba Decor',51345678,'333.444.555-22','33.333.222/0001-22','(11)93333-4444','Decoração'),(52,52,'Sparrow Viagens',52456789,'444.555.666-33','44.444.333/0001-33','(21)94444-5555','Turismo'),(53,53,'RickTech Dimensões',53567890,'555.666.777-44','55.555.444/0001-44','(31)95555-6666','Pesquisa'),(54,54,'Morty Serviços',54678901,'666.777.888-55','66.666.555/0001-55','(41)96666-7777','Serviços'),(55,55,'Curry Sports',55789012,'777.888.999-66','77.777.666/0001-66','(51)97777-8888','Esportes'),(56,56,'LeBron Fitness',56890123,'888.999.000-77','88.888.777/0001-77','(61)98888-9999','Academia'),(57,57,'Explora Brinquedos',57901234,'999.000.111-88','99.999.888/0001-88','(71)99999-0000','Infantil'),(58,58,'Peppa Suínos',58012345,'000.111.222-99','00.000.999/0001-99','(81)90000-1111','Agropecuária'),(59,59,'Jordan Sneakers',59123456,'111.222.333-10','11.111.111/0001-10','(91)91111-2222','Moda'),(60,60,'Queen B Produções',60234567,'222.333.444-21','22.222.222/0001-21','(85)92222-3333','Entretenimento');
/*!40000 ALTER TABLE `fornecedor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `funcionario`
--

DROP TABLE IF EXISTS `funcionario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `funcionario` (
  `id_funcionario` int NOT NULL,
  `id_usuario` int NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `senha_login` varchar(50) DEFAULT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `cnpj` varchar(20) DEFAULT NULL,
  `idade` int DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `cep` int DEFAULT NULL,
  `salario` decimal(7,2) DEFAULT NULL,
  `funcao` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id_funcionario`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `funcionario_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `funcionario`
--

LOCK TABLES `funcionario` WRITE;
/*!40000 ALTER TABLE `funcionario` DISABLE KEYS */;
INSERT INTO `funcionario` VALUES (1,1,'Bruce Wayne','bat123','123.456.789-01','12.345.678/0001-90',38,'bruce@wayne.com',12345678,25000.00,NULL),(2,2,'Clark Kent','superman','234.567.890-12','23.456.789/0001-80',35,'clark@dailyplanet.com',23456789,22000.00,NULL),(3,3,'Peter Parker','sp1der','345.678.901-23','34.567.890/0001-70',28,'peter@bugle.com',34567890,8000.00,NULL),(4,4,'Tony Stark','ironman','456.789.012-34','45.678.901/0001-60',45,'tony@starkindustries.com',45678901,30000.00,NULL),(5,5,'Diana Prince','wonder','567.890.123-45','56.789.012/0001-50',32,'diana@amazon.com',56789012,21000.00,NULL),(6,6,'Steve Rogers','cap123','678.901.234-56','67.890.123/0001-40',40,'steve@avengers.com',67890123,19500.00,NULL),(7,7,'Natasha Romanoff','blackwidow','789.012.345-67','78.901.234/0001-30',33,'natasha@shield.com',78901234,18000.00,NULL),(8,8,'Bruce Banner','hulk123','890.123.456-78','89.012.345/0001-20',39,'bruce@gamma.com',89012345,17000.00,NULL),(9,9,'Barry Allen','flash99','901.234.567-89','90.123.456/0001-10',27,'barry@ccpd.com',90123456,15000.00,NULL),(10,10,'Selina Kyle','catwoman','012.345.678-90','01.234.567/0001-00',30,'selina@gotham.com',10234567,16500.00,NULL),(11,11,'Logan Howlett','wolverine','111.111.111-11','11.111.111/0001-11',46,'logan@xmen.com',11001100,16000.00,NULL),(12,12,'Charles Xavier','professorx','222.222.222-22','22.222.222/0001-22',60,'charles@xschool.com',12001200,30000.00,NULL),(13,13,'Jean Grey','phoenix','333.333.333-33','33.333.333/0001-33',29,'jean@xmen.com',13001300,14000.00,NULL),(14,14,'Scott Summers','cyclops','444.444.444-44','44.444.444/0001-44',31,'scott@xmen.com',14001400,14500.00,NULL),(15,15,'Ororo Munroe','storm','555.555.555-55','55.555.555/0001-55',33,'ororo@xmen.com',15001500,14800.00,NULL),(16,16,'Deadpool','chimichanga','666.666.666-66','66.666.666/0001-66',35,'wade@merc.com',16001600,15500.00,NULL),(17,17,'Goku Son','kakaroto','777.777.777-77','77.777.777/0001-77',37,'goku@dbz.com',17001700,17000.00,NULL),(18,18,'Vegeta','prince','888.888.888-88','88.888.888/0001-88',38,'vegeta@dbz.com',18001800,16800.00,NULL),(19,19,'Naruto Uzumaki','rasengan','999.999.999-99','99.999.999/0001-99',24,'naruto@konoha.com',19001900,12000.00,NULL),(20,20,'Sasuke Uchiha','sharingan','101.010.101-01','10.101.010/0001-00',24,'sasuke@uchiha.com',20002000,12500.00,NULL),(21,21,'Sakura Haruno','mednin','202.202.202-02','20.202.020/0001-11',24,'sakura@konoha.com',21002100,11800.00,NULL),(22,22,'Kakashi Hatake','sharingan','303.303.303-03','30.303.030/0001-22',35,'kakashi@konoha.com',22002200,19000.00,NULL),(23,23,'Itachi Uchiha','genjutsu','404.404.404-04','40.404.040/0001-33',30,'itachi@akatsuki.com',23002300,20000.00,NULL),(24,24,'Obito Uchiha','maskedman','505.505.505-05','50.505.050/0001-44',29,'obito@akatsuki.com',24002400,19500.00,NULL),(25,25,'Hinata Hyuga','byakugan','606.606.606-06','60.606.060/0001-55',23,'hinata@konoha.com',25002500,11700.00,NULL),(26,26,'Jiraiya','sannin','707.707.707-07','70.707.070/0001-66',54,'jiraiya@konoha.com',26002600,18500.00,NULL),(27,27,'Rick Sanchez','portalgun','808.808.808-08','80.808.080/0001-77',60,'rick@c137.com',27002700,25000.00,NULL),(28,28,'Morty Smith','ohgeez','909.909.909-09','90.909.090/0001-88',16,'morty@c137.com',28002800,9500.00,NULL),(29,29,'Summer Smith','coolgirl','112.112.112-12','11.121.212/0001-99',18,'summer@c137.com',29002900,10200.00,NULL),(30,30,'Beth Smith','vetmed','221.221.221-21','22.232.323/0001-00',40,'beth@c137.com',30003000,17500.00,NULL),(31,31,'Harry Potter','expelliarmus','333.111.222-33','33.333.000/0001-11',25,'harry@hogwarts.com',30100001,15000.00,NULL),(32,32,'Hermione Granger','leviosa','444.222.333-44','44.444.000/0001-22',25,'hermione@hogwarts.com',30200002,15200.00,NULL),(33,33,'Ron Weasley','chessmaster','555.333.444-55','55.555.000/0001-33',25,'ron@hogwarts.com',30300003,14800.00,NULL),(34,34,'Albus Dumbledore','elderwand','666.444.555-66','66.666.000/0001-44',80,'albus@hogwarts.com',30400004,32000.00,NULL),(35,35,'Severus Snape','potions','777.555.666-77','77.777.000/0001-55',50,'snape@hogwarts.com',30500005,21000.00,NULL),(36,36,'Draco Malfoy','serpent','888.666.777-88','88.888.000/0001-66',25,'draco@hogwarts.com',30600006,14000.00,NULL),(37,37,'Luna Lovegood','quibbler','999.777.888-99','99.999.000/0001-77',24,'luna@hogwarts.com',30700007,14500.00,NULL),(38,38,'Gandalf Cinzento','youshallnotpass','101.010.101-10','10.101.000/0001-88',201,'gandalf@middleearth.com',30800008,35000.00,NULL),(39,39,'Frodo Bolseiro','ringbearer','202.020.202-20','20.202.000/0001-99',50,'frodo@shire.com',30900009,13500.00,NULL),(40,40,'Samwise Gamgee','loyal','303.030.303-30','30.303.000/0001-00',52,'sam@shire.com',31000010,13800.00,NULL),(41,41,'Legolas Greenleaf','archer','404.040.404-40','40.404.000/0001-11',2931,'legolas@woodland.com',31100011,18000.00,NULL),(42,42,'Aragorn','strider','505.050.505-50','50.505.000/0001-22',87,'aragorn@gondor.com',31200012,21000.00,NULL),(43,43,'Boromir','gondor','606.060.606-60','60.606.000/0001-33',41,'boromir@gondor.com',31300013,17000.00,NULL),(44,44,'Gimli','dwarfaxe','707.070.707-70','70.707.000/0001-44',139,'gimli@erebor.com',31400014,16000.00,NULL),(45,45,'Thanos','inevitable','808.080.808-80','80.808.000/0001-55',1000,'thanos@titan.com',31500015,99999.99,NULL),(46,46,'Loki Laufeyson','trickster','909.090.909-90','90.909.000/0001-66',1050,'loki@asgard.com',31600016,25000.00,NULL),(47,47,'Thor Odinson','mjolnir','111.111.111-99','11.111.999/0001-77',1500,'thor@asgard.com',31700017,28000.00,NULL),(48,48,'Stephen Strange','sorcerer','222.222.222-88','22.222.888/0001-88',42,'strange@sanctum.com',31800018,23000.00,NULL),(49,49,'Wanda Maximoff','scarletwitch','333.333.333-77','33.333.777/0001-99',30,'wanda@avengers.com',31900019,24500.00,NULL),(50,50,'Vision','mindstone','444.444.444-66','44.444.666/0001-00',6,'vision@avengers.com',32000020,27000.00,NULL),(51,51,'Rocket Raccoon','trashpanda','555.555.555-55','55.555.555/0001-11',12,'rocket@guardians.com',32100021,16000.00,NULL),(52,52,'Groot','iamgroot','666.666.666-44','66.666.444/0001-22',5,'groot@guardians.com',32200022,15500.00,NULL),(53,53,'Gamora','deadliest','777.777.777-33','77.777.333/0001-33',29,'gamora@guardians.com',32300023,18500.00,NULL),(54,54,'Drax','destroyer','888.888.888-22','88.888.222/0001-44',35,'drax@guardians.com',32400024,17000.00,NULL),(55,55,'Star-Lord','legendary','999.999.999-11','99.999.111/0001-55',34,'peter@guardians.com',32500025,19000.00,NULL),(56,56,'Yondu Udonta','arrow','101.010.101-99','10.101.999/0001-66',45,'yondu@ravagers.com',32600026,16800.00,NULL),(57,57,'Nebula','cyborg','202.202.202-88','20.202.888/0001-77',31,'nebula@guardians.com',32700027,16000.00,NULL),(58,58,'T\'Challa','panther','303.303.303-77','30.303.777/0001-88',35,'tchalla@wakanda.com',32800028,27500.00,NULL),(59,59,'Shuri','genius','404.404.404-66','40.404.666/0001-99',21,'shuri@wakanda.com',32900029,22000.00,NULL),(60,60,'Erik Killmonger','vengeance','505.505.505-55','50.505.555/0001-00',33,'erik@wakanda.com',33000030,24000.00,NULL);
/*!40000 ALTER TABLE `funcionario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ordem_servico`
--

DROP TABLE IF EXISTS `ordem_servico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ordem_servico` (
  `id_os` int NOT NULL,
  `id_usuario` int NOT NULL,
  `tecnico` varchar(50) DEFAULT NULL,
  `observacoes` varchar(300) DEFAULT NULL,
  `tempo_uso` varchar(15) DEFAULT NULL,
  `prioridade` varchar(10) DEFAULT NULL,
  `problema` varchar(100) DEFAULT NULL,
  `nome_cliente` varchar(50) DEFAULT NULL,
  `data_recebimento` date DEFAULT NULL,
  `status_` varchar(20) DEFAULT NULL,
  `marca_aparelho` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_os`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `ordem_servico_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ordem_servico`
--

LOCK TABLES `ordem_servico` WRITE;
/*!40000 ALTER TABLE `ordem_servico` DISABLE KEYS */;
INSERT INTO `ordem_servico` VALUES (1,1,'Tony Stark','Falha no reator ARC','2h','Alta','Desligamento inesperado','Nick Fury','2025-01-12','Em andamento','StarkTech'),(2,2,'Shuri','Ajustes no vibranium core','1h30m','Média','Oscilações de energia','T\'Challa','2025-01-15','Pendente','WakandaTech'),(3,3,'Walter White','Contaminação química','3h','Alta','Reações instáveis','Jesse Pinkman','2025-02-01','Resolvido','LabPro'),(4,4,'Peter Parker','Manchas nas lentes','45m','Baixa','Visibilidade comprometida','Mary Jane','2025-02-07','Cancelado','Sony'),(5,5,'Rick Sanchez','Explosões aleatórias','6h','Crítica','Fenda interdimensional','Morty Smith','2025-02-15','Em andamento','PortalGun'),(6,6,'Bruce Banner','Sistema gamma instável','4h','Alta','Fugas de radiação','Natasha Romanoff','2025-02-20','Pendente','GammaCorp'),(7,7,'Luffy','Dano no chapéu de palha','30m','Baixa','Costura rompida','Zoro','2025-02-23','Resolvido','Grand Line'),(8,8,'Hermione Granger','Feitiço de gelo travado','2h','Alta','Congelamento mágico','Harry Potter','2025-03-01','Pendente','Hogwarts'),(9,9,'Batman','Falha no batcomputador','5h','Alta','Não inicializa','Alfred Pennyworth','2025-03-02','Em andamento','WayneTech'),(10,10,'Sonic','Perda de velocidade','1h','Média','Desgaste nos tênis','Tails','2025-03-05','Resolvido','SEGA'),(11,11,'Ash Ketchum','Pokédex sem som','45m','Baixa','Alto-falante queimado','Pikachu','2025-03-07','Resolvido','KantoTech'),(12,12,'Darth Vader','Respiração com ruído','2h','Alta','Filtro entupido','Palpatine','2025-03-10','Em andamento','Empire Systems'),(13,13,'Leia Organa','Comunicação instável','1h','Média','Frequência oscilante','Luke Skywalker','2025-03-12','Pendente','AlderaanCom'),(14,14,'Mario','Joystick travando','50m','Baixa','Botão emperrado','Luigi','2025-03-15','Cancelado','Nintendo'),(15,15,'Homer Simpson','TV sem imagem','3h','Alta','Tela preta','Marge Simpson','2025-03-18','Em andamento','SpringfieldEletrônicos'),(16,16,'Bart Simpson','Skate com vibração','40m','Média','Eixo frouxo','Lisa Simpson','2025-03-20','Resolvido','SpringSkate'),(17,17,'Yoda','Holocron corrompido','2h30m','Alta','Dados inacessíveis','Obi-Wan Kenobi','2025-03-21','Pendente','JediTech'),(18,18,'Optimus Prime','Sensor de rota com erro','5h','Crítica','Erro de navegação','Bumblebee','2025-03-25','Pendente','Cybertron Industries'),(19,19,'Megatron','Canhão com sobrecarga','4h30m','Alta','Superaquecimento','Starscream','2025-03-27','Em andamento','Decepticon'),(20,20,'Jack Sparrow','Bússola desmagnetizada','1h20m','Média','Ponta solta','Will Turner','2025-03-30','Resolvido','Black Pearl Tech'),(21,21,'Katniss Everdeen','Arco desalinhado','1h','Baixa','Corda frouxa','Peeta Mellark','2025-04-01','Resolvido','District 12 Gear'),(22,22,'Gandalf','Bastão sem brilho','3h','Alta','Fio mágico rompido','Frodo Baggins','2025-04-04','Pendente','MiddleEarth Magic'),(23,23,'Elsa','Congelamento excessivo','2h','Alta','Controle de gelo ineficaz','Anna','2025-04-05','Cancelado','Arendelle Labs'),(24,24,'Buzz Lightyear','Botão de asa travado','50m','Média','Mola quebrada','Woody','2025-04-06','Resolvido','Star Command'),(25,25,'Sherlock Holmes','Lupa com distorção','30m','Baixa','Lente rachada','Dr. Watson','2025-04-07','Resolvido','Baker Street Tech'),(26,26,'Lara Croft','GPS impreciso','1h45m','Média','Erro de localização','Sam Nishimura','2025-04-09','Em andamento','TombTech'),(27,27,'Goku','Detector de ki falhando','3h','Alta','Bateria fraca','Vegeta','2025-04-10','Pendente','Capsule Corp'),(28,28,'Vegeta','Gravidade instável','2h','Alta','Pressão flutuante','Bulma','2025-04-11','Em andamento','Capsule Corp'),(29,29,'Sailor Moon','Tiara sem brilho','40m','Média','Falha no LED lunar','Tuxedo Mask','2025-04-13','Resolvido','MoonLabs'),(30,30,'Scooby-Doo','Coleira com chiado','20m','Baixa','Microfone solto','Shaggy','2025-04-14','Resolvido','Mystery Inc.'),(31,31,'Deadpool','Katana sem corte','1h','Baixa','Desgaste na lâmina','Cable','2025-04-15','Cancelado','Merc Inc.'),(32,32,'Harley Quinn','Tacape torto','30m','Baixa','Alça quebrada','Poison Ivy','2025-04-17','Resolvido','Arkham Gear'),(33,33,'Kratos','Machado desbalanceado','2h30m','Alta','Desvio de centro','Atreus','2025-04-18','Pendente','GodTools'),(34,34,'Master Chief','Escudo não carrega','3h','Crítica','Falha no capacitor','Cortana','2025-04-20','Em andamento','UNSC Tech'),(35,35,'Samus Aran','Armadura com travamento','4h','Alta','Erro no visor','Ridley','2025-04-22','Pendente','Galactic Federation'),(36,36,'Neo','Óculos com reflexo','1h','Média','Polarização incorreta','Trinity','2025-04-23','Resolvido','Matrix Co.'),(37,37,'Trinity','Fios do traje soltos','45m','Média','Costura rompida','Morpheus','2025-04-24','Resolvido','Matrix Co.'),(38,38,'John Wick','Arma descarregando sozinha','2h','Alta','Gatilho sensível','Winston','2025-04-25','Em andamento','Continental Arsenal'),(39,39,'Thanos','Manopla sem energia','5h','Crítica','Pedras desincronizadas','Gamora','2025-04-27','Pendente','Titan Industries'),(40,40,'Wolverine','Garras emperradas','3h','Alta','Sistema de liberação com erro','Professor X','2025-04-28','Resolvido','X-Tech'),(41,41,'Iron Giant','Circuito de fala inativo','2h','Alta','Sem resposta ao comando de voz','Hogarth Hughes','2025-04-29','Em andamento','GiantTech'),(42,42,'WALL-E','Sistema de coleta lento','1h30m','Média','Engrenagem enferrujada','EVE','2025-04-30','Pendente','Buy n Large'),(43,43,'Dory','Radar de memória falhando','30m','Baixa','Bug recorrente','Marlin','2025-05-01','Resolvido','PixarSystems'),(44,44,'Mike Wazowski','Monoculador sem foco','1h','Média','Lente deslocada','Sulley','2025-05-02','Resolvido','Monstros S.A.'),(45,45,'Gru','Raio encolhedor com atraso','2h','Alta','Oscilação de frequência','Dr. Nefario','2025-05-03','Pendente','MinionLab'),(46,46,'Moana','Remo quebrado','45m','Baixa','Fissura no centro','Maui','2025-05-04','Resolvido','Polynesia Equip.'),(47,47,'Light Yagami','Caneta falhando','15m','Média','Tinta desaparece','Ryuk','2025-05-05','Cancelado','Death Note Corp.'),(48,48,'Saitama','Treinador de força travado','1h','Alta','Erro de limite superior','Genos','2025-05-06','Em andamento','Hero Assoc.'),(49,49,'Itachi Uchiha','Sharingan sem foco','2h15m','Alta','Ilusão intermitente','Sasuke','2025-05-07','Pendente','Akatsuki Tech'),(50,50,'Naruto Uzumaki','Problema no pergaminho digital','1h','Média','Sumiço dos clones','Kakashi','2025-05-08','Resolvido','KonohaSoft'),(51,51,'Tom','Rabo robótico desconectado','1h10m','Média','Conector danificado','Jerry','2025-05-09','Pendente','CartoonFix'),(52,52,'Jerry','Detector de queijo com delay','40m','Baixa','Falta de sinal','Tom','2025-05-10','Resolvido','CartoonFix'),(53,53,'Finn','Espada vibrando sozinha','1h20m','Alta','Cabo mal conectado','Jake','2025-05-11','Em andamento','Ooo Systems'),(54,54,'Jake','Alongamento travado','2h','Alta','Bug corporal','Finn','2025-05-12','Resolvido','Ooo Systems'),(55,55,'Bugs Bunny','Radar de cenouras descalibrado','35m','Média','Leitura fora do eixo','Daffy Duck','2025-05-13','Pendente','LooneyTech'),(56,56,'Ben 10','Omnitrix com erro 404','1h50m','Alta','Transformações incompletas','Gwen','2025-05-14','Pendente','Galvan Labs'),(57,57,'Coringa','Arma de risada com falha','1h','Alta','Gás não dispara','Batman','2025-05-15','Em andamento','Arkham Devices'),(58,58,'T\'Challa','Pantera elétrica sem resposta','2h','Alta','Falha no pulso vibranium','Shuri','2025-05-16','Resolvido','WakandaTech'),(59,59,'Ash Williams','Motosserra com ruído estranho','1h30m','Média','Corrente desalinhada','Pablo','2025-05-17','Pendente','Necronomicon Inc.'),(60,60,'Eleven','Walkie-talkie invertendo frequências','1h','Média','Vozes distorcidas','Mike Wheeler','2025-05-18','Resolvido','Hawkins Comms');
/*!40000 ALTER TABLE `ordem_servico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pecas_estoque`
--

DROP TABLE IF EXISTS `pecas_estoque`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pecas_estoque` (
  `id_peca` int NOT NULL,
  `id_usuario` int NOT NULL,
  `quantidade` int DEFAULT NULL,
  `data_recebida` date DEFAULT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `tipo` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_peca`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `pecas_estoque_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pecas_estoque`
--

LOCK TABLES `pecas_estoque` WRITE;
/*!40000 ALTER TABLE `pecas_estoque` DISABLE KEYS */;
INSERT INTO `pecas_estoque` VALUES (1,1,10,'2025-01-01','Reator ARC','Energia'),(2,2,5,'2025-01-03','Fragmento de Vibranium','Metal'),(3,3,20,'2025-01-05','Ampola Azul','Químico'),(4,4,12,'2025-01-07','Lente Aranha','Óptico'),(5,5,7,'2025-01-10','Cristal Dimensional','Energia'),(6,6,15,'2025-01-11','Sensor Gamma','Sensor'),(7,7,8,'2025-01-13','Faixa de Borracha','Acessório'),(8,8,11,'2025-01-14','Varinha de Azevinho','Mágico'),(9,9,3,'2025-01-15','Módulo Batcomputador','Computador'),(10,10,18,'2025-01-17','Tênis Supersônico','Vestimenta'),(11,11,22,'2025-01-18','Pokébola Ultra','Equipamento'),(12,12,6,'2025-01-20','Máscara Sith','Proteção'),(13,13,14,'2025-01-21','Transmissor Rebelde','Comunicação'),(14,14,10,'2025-01-22','Botão Turbo','Eletrônico'),(15,15,9,'2025-01-23','Controle Universal','Eletrônico'),(16,16,25,'2025-01-24','Skate Anti-gravidade','Mobilidade'),(17,17,7,'2025-01-25','Holocron Jedi','Mágico'),(18,18,4,'2025-01-26','Chip Cybertron','Circuito'),(19,19,5,'2025-01-27','Energia de Fusão','Energia'),(20,20,13,'2025-01-28','Bússola Mística','Navegação'),(21,21,16,'2025-01-29','Aljava Tática','Armas'),(22,22,19,'2025-01-30','Pedra Élfica','Mágico'),(23,23,9,'2025-02-01','Tiara de Gelo','Vestimenta'),(24,24,10,'2025-02-02','Botão Estelar','Acessório'),(25,25,8,'2025-02-03','Lupa Sherlock','Óptico'),(26,26,12,'2025-02-04','Mapa das Tumbas','Navegação'),(27,27,30,'2025-02-05','Detector de Ki','Sensor'),(28,28,14,'2025-02-06','Módulo de Gravidade','Controle'),(29,29,10,'2025-02-07','Tiara Lunar','Acessório'),(30,30,6,'2025-02-08','Coleira Inteligente','PetTech'),(31,31,5,'2025-02-09','Lâmina Ninja','Arma'),(32,32,9,'2025-02-10','Tacape Reforçado','Arma'),(33,33,4,'2025-02-11','Machado Leviatã','Arma'),(34,34,10,'2025-02-12','Escudo de Plasma','Defesa'),(35,35,11,'2025-02-13','Módulo de Propulsão','Energia'),(36,36,8,'2025-02-14','Óculos Matrix','Vestimenta'),(37,37,6,'2025-02-15','Cinto Tático','Acessório'),(38,38,3,'2025-02-16','Silenciador Letal','Arma'),(39,39,1,'2025-02-17','Pedra da Alma','Energia'),(40,40,2,'2025-02-18','Garra de Adamantium','Arma'),(41,41,13,'2025-02-19','Reforço de Titânio','Metal'),(42,42,9,'2025-02-20','Compensador de Lixo','Mecânico'),(43,43,7,'2025-02-21','Módulo de Navegação','Sensor'),(44,44,4,'2025-02-22','Óculo Monocular','Óptico'),(45,45,6,'2025-02-23','Raio Redutor','Arma'),(46,46,5,'2025-02-24','Remo Espiritual','Acessório'),(47,47,12,'2025-02-25','Caderno Negro','Artefato'),(48,48,14,'2025-02-26','Capa de Herói','Vestimenta'),(49,49,8,'2025-02-27','Sharingan Eletrônico','Óptico'),(50,50,10,'2025-02-28','Pergaminho de Chakra','Documento'),(51,51,7,'2025-03-01','Rabo Robótico','Acessório'),(52,52,8,'2025-03-02','Detector de Queijo','Sensor'),(53,53,4,'2025-03-03','Espada de Cristal','Arma'),(54,54,3,'2025-03-04','Goma de Expansão','Biológico'),(55,55,6,'2025-03-05','Sensor de Cenouras','Sensor'),(56,56,5,'2025-03-06','Cápsula Alienígena','Energia'),(57,57,7,'2025-03-07','Risador de Bolso','Arma'),(58,58,10,'2025-03-08','Pulso de Vibranium','Energia'),(59,59,6,'2025-03-09','Corrente Afiada','Arma'),(60,60,9,'2025-03-10','Walkie-Talkie Quantum','Comunicação');
/*!40000 ALTER TABLE `pecas_estoque` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfil`
--

DROP TABLE IF EXISTS `perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perfil` (
  `id_perfil` int NOT NULL,
  `id_usuario` int NOT NULL,
  `nome_cargo` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_perfil`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `perfil_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil`
--

LOCK TABLES `perfil` WRITE;
/*!40000 ALTER TABLE `perfil` DISABLE KEYS */;
/*!40000 ALTER TABLE `perfil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id_usuario` int NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `senha` varchar(50) DEFAULT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `data_cadastro` date DEFAULT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'Brad Pitt','brad.pitt@email.com','senha123','123.456.789-01','2023-01-10'),(2,'Angelina Jolie','angelina.j@email.com','maleficent','234.567.890-12','2023-01-12'),(3,'Homer Simpson','homer@springfield.com','donuts4life','345.678.901-23','2023-01-15'),(4,'Mickey Mouse','mickey@disney.com','minnie123','456.789.012-34','2023-01-20'),(5,'Emma Watson','emma.w@email.com','hogwarts','567.890.123-45','2023-01-25'),(6,'Robert Downey Jr','robert.dj@stark.com','ironman3000','678.901.234-56','2023-01-30'),(7,'Tom Hanks','t.hanks@email.com','forrest','789.012.345-67','2023-02-01'),(8,'Bart Simpson','bart@springfield.com','eatmyshorts','890.123.456-78','2023-02-05'),(9,'Will Smith','w.smith@email.com','freshprince','901.234.567-89','2023-02-08'),(10,'Goku Son','goku@dbz.com','kamehameha','012.345.678-90','2023-02-10'),(11,'Scarlett Johansson','scarlett@email.com','blackwidow','123.123.123-11','2023-02-12'),(12,'Patrick Estrela','patrick@bikini.com','rosinha','234.234.234-22','2023-02-15'),(13,'Dwayne Johnson','therock@email.com','rocksolid','345.345.345-33','2023-02-18'),(14,'Naruto Uzumaki','naruto@konoha.com','rasengan','456.456.456-44','2023-02-21'),(15,'Lady Gaga','gaga@pop.com','pokerface','567.567.567-55','2023-02-24'),(16,'Elsa Arendelle','elsa@arendelle.com','letitgo','678.678.678-66','2023-02-27'),(17,'Tony Stark','tony@stark.com','iamironman','789.789.789-77','2023-03-01'),(18,'Shrek Ogre','shrek@swamp.com','onions','890.890.890-88','2023-03-04'),(19,'Taylor Swift','taylor@music.com','swiftie','901.901.901-99','2023-03-07'),(20,'SpongeBob SquarePants','spongebob@bikini.com','krabby123','101.202.303-00','2023-03-10'),(21,'Chris Evans','cevans@marvel.com','capamerica','111.222.333-44','2023-03-13'),(22,'Lisa Simpson','lisa@springfield.com','sax123','222.333.444-55','2023-03-16'),(23,'Katy Perry','katy@pop.com','roar!','333.444.555-66','2023-03-19'),(24,'Dexter Genious','dexter@lab.com','deeedeed','444.555.666-77','2023-03-22'),(25,'Bruce Wayne','batman@gotham.com','darkknight','555.666.777-88','2023-03-25'),(26,'Gal Gadot','gal@wonder.com','amazonia','666.777.888-99','2023-03-28'),(27,'Ben 10','ben@omniverse.com','upgrade123','777.888.999-00','2023-04-01'),(28,'Zendaya Maree','zendaya@email.com','mjspidey','888.999.000-11','2023-04-04'),(29,'Scooby Doo','scooby@doobydoo.com','snacktime','999.000.111-22','2023-04-07'),(30,'Chaves Chapolin','chaves@8.com','sandwich','000.111.222-33','2023-04-10'),(31,'Jim Carrey','jim.carrey@email.com','mask123','111.111.111-44','2023-04-13'),(32,'Harry Potter','harry@hogwarts.com','expelliarmus','222.222.222-55','2023-04-16'),(33,'Luffy D Monkey','luffy@onepiece.com','gomugomu','333.333.333-66','2023-04-19'),(34,'Selena Gomez','selena@email.com','starsdance','444.444.444-77','2023-04-22'),(35,'Pikachu Eletric','pikachu@poke.com','pika123','555.555.555-88','2023-04-25'),(36,'Vin Diesel','vin@email.com','fastfam','666.666.666-99','2023-04-28'),(37,'Ash Ketchum','ash@poke.com','catchall','777.777.777-00','2023-05-01'),(38,'Jennifer Aniston','jennifer@email.com','friends','888.888.888-11','2023-05-04'),(39,'Thor Odinson','thor@asgard.com','mjolnir','999.999.999-22','2023-05-07'),(40,'Sandy Bochechas','sandy@texas.com','karate!','101.010.101-33','2023-05-10'),(41,'Rihanna Fenty','rihanna@email.com','shinebright','202.020.202-44','2023-05-13'),(42,'Finn Mertens','finn@ooh.com','sword123','303.030.303-55','2023-05-16'),(43,'Jake the Dog','jake@adventure.com','stretch','404.040.404-66','2023-05-19'),(44,'Keanu Reeves','keanu@email.com','matrixneo','505.050.505-77','2023-05-22'),(45,'Buzz Lightyear','buzz@pixar.com','toinfinity','606.060.606-88','2023-05-25'),(46,'Woody Cowboy','woody@pixar.com','andy!','707.070.707-99','2023-05-28'),(47,'Tom Holland','tomh@email.com','spidey','808.080.808-00','2023-06-01'),(48,'Optimus Prime','optimus@cybertron.com','autobots','909.090.909-11','2023-06-04'),(49,'Megan Fox','megan@email.com','transform','111.222.333-00','2023-06-07'),(50,'Megamente Azul','megamente@blue.com','vilao123','222.333.444-11','2023-06-10'),(51,'Jessica Alba','jessica@email.com','fantastic4','333.444.555-22','2023-06-13'),(52,'Jack Sparrow','jack@pirates.com','rumrum','444.555.666-33','2023-06-16'),(53,'Rick Sanchez','rick@c137.com','getschwifty','555.666.777-44','2023-06-19'),(54,'Morty Smith','morty@c137.com','ohgeez','666.777.888-55','2023-06-22'),(55,'Stephen Curry','curry@nba.com','threes','777.888.999-66','2023-06-25'),(56,'Lebron James','lebron@nba.com','kingjames','888.999.000-77','2023-06-28'),(57,'Dora Exploradora','dora@map.com','swiperno','999.000.111-88','2023-07-01'),(58,'Peppa Pig','peppa@mud.com','snort123','000.111.222-99','2023-07-04'),(59,'Michael Jordan','mj@nba.com','air23','111.222.333-10','2023-07-07'),(60,'Beyoncé Knowles','beyonce@email.com','queenb','222.333.444-21','2023-07-10');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-22 17:12:35
