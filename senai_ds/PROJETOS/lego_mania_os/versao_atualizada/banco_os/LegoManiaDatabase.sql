-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: localhost    Database: legomania
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
  `nome` varchar(30) DEFAULT NULL,
  `idade` int NOT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `cnpj` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `data_cadastro` date DEFAULT NULL,
  `senha` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
INSERT INTO `cliente` VALUES (1,'João Silva',35,'123456789','12345678000101','joao.silva@email.com','2020-01-15','senha123'),(2,'Maria Oliveira',28,'987654321','98765432000102','maria.oliveira@email.com','2020-02-20','mariA123'),(3,'Carlos Souza',42,'456789123','45678912000103','carlos.souza@email.com','2020-03-10','carlos42'),(4,'Ana Costa',31,'789123456','78912345000104','ana.costa@email.com','2020-04-05','aninha31'),(5,'Pedro Alves',25,'321654987','32165498000105','pedro.alves@email.com','2020-05-12','pedro25'),(6,'Lucia Mendes',38,'654987321','65498732000106','lucia.mendes@email.com','2020-06-18','lucia38'),(7,'Fernando Lima',29,'987321654','98732165000107','fernando.lima@email.com','2020-07-22','fer29lima'),(8,'Patricia Rocha',45,'147258369','14725836000108','patricia.rocha@email.com','2020-08-30','pat45rocha'),(9,'Ricardo Santos',33,'258369147','25836914000109','ricardo.santos@email.com','2020-09-14','ric33santos'),(10,'Juliana Pereira',27,'369147258','36914725000110','juliana.pereira@email.com','2020-10-25','juli27'),(11,'Marcos Ferreira',39,'159357486','15935748000111','marcos.ferreira@email.com','2020-11-03','marcos39'),(12,'Amanda Gonçalves',26,'357486159','35748615000112','amanda.goncalves@email.com','2020-12-08','amanda26'),(13,'Roberto Nunes',44,'486159357','48615935000113','roberto.nunes@email.com','2021-01-17','roberto44'),(14,'Tatiana Martins',30,'753951842','75395184000114','tatiana.martins@email.com','2021-02-19','tati30'),(15,'Eduardo Barbosa',36,'951842753','95184275000115','eduardo.barbosa@email.com','2021-03-21','dudu36'),(16,'Cristina Ramos',41,'842753951','84275395000116','cristina.ramos@email.com','2021-04-05','cris41'),(17,'Lucas Carvalho',24,'654123987','65412398000117','lucas.carvalho@email.com','2021-05-11','lucas24'),(18,'Vanessa Dias',32,'321987654','32198765000118','vanessa.dias@email.com','2021-06-15','van32'),(19,'Gustavo Henrique',28,'987654123','98765412000119','gustavo.henrique@email.com','2021-07-20','gust28'),(20,'Daniela Castro',37,'456321789','45632178000120','daniela.castro@email.com','2021-08-24','dani37'),(21,'Fábio Correia',29,'789456123','78945612000121','fabio.correia@email.com','2021-09-28','fabio29'),(22,'Beatriz Duarte',26,'123789456','12378945000122','beatriz.duarte@email.com','2021-10-30','bia26'),(23,'Rafael Monteiro',40,'456123789','45612378000123','rafael.monteiro@email.com','2021-11-05','rafa40'),(24,'Sandra Vieira',35,'789456321','78945632000124','sandra.vieira@email.com','2021-12-10','san35'),(25,'Paulo César',31,'321456987','32145698000125','paulo.cesar@email.com','2022-01-15','paulo31'),(26,'Mariana Andrade',27,'654789321','65478932000126','mariana.andrade@email.com','2022-02-20','mari27'),(27,'Thiago Pires',33,'987321456','98732145000127','thiago.pires@email.com','2022-03-25','thiago33'),(28,'Isabela Cardoso',29,'159753486','15975348000128','isabela.cardoso@email.com','2022-04-30','isa29'),(29,'Leonardo Moura',38,'357159486','35715948000129','leonardo.moura@email.com','2022-05-05','leo38'),(30,'Camila Freitas',25,'486357159','48635715000130','camila.freitas@email.com','2022-06-10','cami25'),(31,'Hugo Melo',42,'753159486','75315948000131','hugo.melo@email.com','2022-07-15','hugo42'),(32,'Larissa Moreira',30,'951753486','95175348000132','larissa.moreira@email.com','2022-08-20','lari30'),(33,'Diego Araújo',36,'842951753','84295175000133','diego.araujo@email.com','2022-09-25','diego36'),(34,'Natália Tavares',28,'654321987','65432198000134','natalia.tavares@email.com','2022-10-30','nata28'),(35,'Alexandre Campos',45,'321654789','32165478000135','alexandre.campos@email.com','2022-11-05','alex45'),(36,'Renata Fonseca',31,'987123654','98712365000136','renata.fonseca@email.com','2022-12-10','rena31'),(37,'Bruno Teixeira',26,'456987321','45698732000137','bruno.teixeira@email.com','2023-01-15','bruno26'),(38,'Viviane Lopes',39,'789123654','78912365000138','viviane.lopes@email.com','2023-02-20','vivi39'),(39,'André Machado',34,'123456987','12345698000139','andre.machado@email.com','2023-03-25','andre34'),(40,'Cláudia Reis',29,'456789321','45678932000140','claudia.reis@email.com','2023-04-30','clau29'),(41,'Rodrigo Guimarães',43,'789321456','78932145000141','rodrigo.guimaraes@email.com','2023-05-05','rod43'),(42,'Elaine Sousa',27,'321789654','32178965000142','elaine.sousa@email.com','2023-06-10','elaine27'),(43,'Márcio Neves',32,'654987123','65498712000143','marcio.neves@email.com','2023-07-15','marcio32'),(44,'Simone Costa',38,'987321789','98732178000144','simone.costa@email.com','2023-08-20','simone38'),(45,'Felipe Barros',25,'123789654','12378965000145','felipe.barros@email.com','2023-09-25','felipe25'),(46,'Priscila Rios',41,'456123987','45612398000146','priscila.rios@email.com','2023-10-30','priscila41'),(47,'César Brito',30,'789654123','78965412000147','cesar.brito@email.com','2023-11-05','cesar30'),(48,'Luciana Azevedo',36,'321456789','32145678000148','luciana.azevedo@email.com','2023-12-10','luciana36'),(49,'Marcelo Dantas',28,'654321987','65432198000149','marcelo.dantas@email.com','2024-01-15','marcelo28'),(50,'Adriana Peixoto',44,'987654321','98765432000150','adriana.peixoto@email.com','2024-02-20','adri44'),(51,'Gilberto Vasconcelos',29,'123987456','12398745000151','gilberto.vasconcelos@email.com','2024-03-25','gil29'),(52,'Helena Miranda',37,'456789654','45678965000152','helena.miranda@email.com','2024-04-30','helena37'),(53,'Otávio Bezerra',26,'789123987','78912398000153','otavio.bezerra@email.com','2024-05-05','otavio26'),(54,'Regina Medeiros',33,'321987123','32198712000154','regina.medeiros@email.com','2024-06-10','regina33'),(55,'Sérgio Cunha',40,'654123456','65412345000155','sergio.cunha@email.com','2024-07-15','sergio40'),(56,'Teresa Marques',27,'987456123','98745612000156','teresa.marques@email.com','2024-08-20','teresa27'),(57,'Victor Hugo',35,'123654987','12365498000157','victor.hugo@email.com','2024-09-25','victor35'),(58,'Yasmin Santana',31,'456987654','45698765000158','yasmin.santana@email.com','2024-10-30','yasmin31'),(59,'Zélia Albuquerque',42,'789321123','78932112000159','zelia.albuquerque@email.com','2024-11-05','zelia42'),(60,'Wilson Batista',29,'321654123','32165412000160','wilson.batista@email.com','2024-12-10','wilson29');
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fornecedor`
--

DROP TABLE IF EXISTS `fornecedor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fornecedor` (
  `nome` varchar(30) DEFAULT NULL,
  `endereco` varchar(50) DEFAULT NULL,
  `id_fornecedor` int NOT NULL,
  `cpf` varchar(20) NOT NULL,
  `cnpj` varchar(20) NOT NULL,
  `id_usuario` int DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_fornecedor`),
  KEY `FK_fornecedor_id_usuario` (`id_usuario`),
  CONSTRAINT `FK_fornecedor_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`Id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fornecedor`
--

LOCK TABLES `fornecedor` WRITE;
/*!40000 ALTER TABLE `fornecedor` DISABLE KEYS */;
INSERT INTO `fornecedor` VALUES ('Tech Solutions Ltda','Av. Paulista, 1000',1,'111222333','12345678000101',NULL,'1123456789'),('Alimentos S.A.','Rua das Flores, 200',2,'222333444','98765432000102',NULL,'1198765432'),('Construção & Cia','Av. Brasil, 500',3,'333444555','45678912000103',NULL,'2133334444'),('Moda Fashion','Rua Oscar Freire, 300',4,'444555666','78912345000104',NULL,'1144445555'),('Eletrônicos Modernos','Av. Rebouças, 600',5,'555666777','32165498000105',NULL,'3155556666'),('Farmácia Saúde','Rua Augusta, 700',6,'666777888','65498732000106',NULL,'1166667777'),('Móveis Conforto','Alameda Santos, 800',7,'777888999','98732165000107',NULL,'2177778888'),('Auto Peças Rápidas','Av. Faria Lima, 900',8,'888999000','14725836000108',NULL,'3188889999'),('Bebidas Premium','Rua Haddock Lobo, 100',9,'999000111','25836914000109',NULL,'1199990000'),('Livros Cultura','Av. Europa, 200',10,'100011122','36914725000110',NULL,'2100001111'),('Informática Express','Rua Pamplona, 300',11,'111122233','15935748000111',NULL,'3111112222'),('Esportes Radical','Alameda Jaú, 400',12,'122233344','35748615000112',NULL,'1122223333'),('Decoração Home','Rua Estados Unidos, 500',13,'133344455','48615935000113',NULL,'2133334444'),('Pet Shop Amigo','Av. Morumbi, 600',14,'144455566','75395184000114',NULL,'3144445555'),('Joalheria Luxo','Rua Colombia, 700',15,'155566677','95184275000115',NULL,'1155556666'),('Restaurante Sabor','Alameda Franca, 800',16,'166677788','84275395000116',NULL,'2166667777'),('Hotel Conforto','Rua Chile, 900',17,'177788899','65412398000117',NULL,'3177778888'),('Transporte Rápido','Av. Juscelino Kubitschek, 1000',18,'188899900','32198765000118',NULL,'1188889999'),('Segurança Total','Rua México, 1100',19,'199900011','98765412000119',NULL,'2199990000'),('Educação Futuro','Av. das Nações Unidas, 1200',20,'200011122','45632178000120',NULL,'3100001111'),('Beleza Estética','Rua Argentina, 1300',21,'211122233','78945612000121',NULL,'1111112222'),('Limpeza Profissional','Alameda Lorena, 1400',22,'222233344','12378945000122',NULL,'2122223333'),('Consultoria Negócios','Rua Bela Cintra, 1500',23,'233344455','45612378000123',NULL,'3133334444'),('Turismo Aventura','Av. Nove de Julho, 1600',24,'244455566','78945632000124',NULL,'1144445555'),('Telecomunicações','Rua da Consolação, 1700',25,'255566677','32145698000125',NULL,'2155556666'),('Energia Solar','Alameda Campinas, 1800',26,'266677788','65478932000126',NULL,'3166667777'),('Água Mineral','Rua XV de Novembro, 1900',27,'277788899','98732145000127',NULL,'1177778888'),('Gás Natural','Av. Rio Branco, 2000',28,'288899900','15975348000128',NULL,'2188889999'),('Metalurgia Pesada','Rua dos Andradas, 2100',29,'299900011','35715948000129',NULL,'3199990000'),('Têxtil Moderna','Avenida do Contorno, 2200',30,'300011122','48635715000130',NULL,'1100001111'),('Química Avançada','Rua da Bahia, 2300',31,'311122233','75315948000131',NULL,'2111112222'),('Papel e Celulose','Av. Amazonas, 2400',32,'322233344','95175348000132',NULL,'3122223333'),('Mineração Brasil','Rua Espírito Santo, 2500',33,'333344455','84295175000133',NULL,'1133334444'),('Naval Oceânica','Alameda da Serra, 2600',34,'344455566','65432198000134',NULL,'2144445555'),('Aeronáutica Tech','Rua Minas Gerais, 2700',35,'355566677','32165478000135',NULL,'3155556666'),('Agropecuária Forte','Av. Tocantins, 2800',36,'366677788','98712365000136',NULL,'1166667777'),('Pesca Marítima','Rua Goiás, 2900',37,'377788899','45698732000137',NULL,'2177778888'),('Veterinária Animal','Alameda Maranhão, 3000',38,'388899900','78912365000138',NULL,'3188889999'),('Odontologia Dental','Rua Piauí, 3100',39,'399900011','12345698000139',NULL,'1199990000'),('Advocacia Legal','Av. Ceará, 3200',40,'400011122','45678932000140',NULL,'2100001111'),('Contabilidade Exata','Rua Sergipe, 3300',41,'411122233','78932145000141',NULL,'3111112222'),('Psicologia Mente','Alameda Alagoas, 3400',42,'422233344','32178965000142',NULL,'1122223333'),('Arquitetura Projeto','Rua Rondônia, 3500',43,'433344455','65498712000143',NULL,'2133334444'),('Engenharia Civil','Av. Acre, 3600',44,'444455566','98732178000144',NULL,'3144445555'),('Design Gráfico','Rua Roraima, 3700',45,'455566677','12378965000145',NULL,'1155556666'),('Fotografia Imagem','Alameda Amapá, 3800',46,'466677788','45612398000146',NULL,'2166667777'),('Música e Arte','Rua Santa Catarina, 3900',47,'477788899','78965412000147',NULL,'3177778888'),('Cinema Produções','Av. Rio Grande do Sul, 4000',48,'488899900','32145678000148',NULL,'1188889999'),('Teatro Expressão','Rua Mato Grosso, 4100',49,'499900011','65432198000149',NULL,'2199990000'),('Dança Movimento','Alameda Distrito Federal, 4200',50,'500011122','98765432000150',NULL,'3100001111'),('Gastronomia Chef','Rua Mato Grosso do Sul, 4300',51,'511122233','12398745000151',NULL,'1111112222'),('Eventos Festivos','Av. Paraíba, 4400',52,'522233344','45678965000152',NULL,'2122223333'),('Floricultura Natureza','Rua Pernambuco, 4500',53,'533344455','78912398000153',NULL,'3133334444'),('Relojoaria Precisão','Alameda São Paulo, 4600',54,'544455566','32198712000154',NULL,'1144445555'),('Brinquedos Infantis','Rua Paraná, 4700',55,'555566677','65412345000155',NULL,'2155556666'),('Perfumaria Aroma','Av. Santa Catarina, 4800',56,'566677788','98745612000156',NULL,'3166667777'),('Ótica Visão','Rua Rio de Janeiro, 4900',57,'577788899','12365498000157',NULL,'1177778888'),('Cafeteria Aroma','Alameda Minas Gerais, 5000',58,'588899900','45698765000158',NULL,'2188889999'),('Chocolates Doces','Rua Bahia, 5100',59,'599900011','78932112000159',NULL,'3199990000'),('Sorvetes Gelados','Av. Sergipe, 5200',60,'600011122','32165412000160',NULL,'1100001111');
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
  `nome` varchar(50) NOT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `cnpj` varchar(20) DEFAULT NULL,
  `salario` decimal(7,2) NOT NULL,
  `cep` int NOT NULL,
  `idade` int NOT NULL,
  `funcao` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `id_usuario` int NOT NULL,
  PRIMARY KEY (`id_funcionario`),
  KEY `FK_funcionario_id_usuario` (`id_usuario`),
  CONSTRAINT `FK_funcionario_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`Id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `funcionario`
--

LOCK TABLES `funcionario` WRITE;
/*!40000 ALTER TABLE `funcionario` DISABLE KEYS */;
INSERT INTO `funcionario` VALUES (1,'João Silva','12345678901','12345678000101',3500.00,12345678,28,'Analista','joao.silva@empresa.com',1),(2,'Maria Oliveira','98765432109','98765432000102',4200.50,23456789,32,'Gerente','maria.oliveira@empresa.com',2),(3,'Carlos Souza','45678912345','45678912000103',2800.75,34567890,25,'Assistente','carlos.souza@empresa.com',3),(4,'Ana Costa','78912345678','78912345000104',5100.00,45678901,35,'Coordenadora','ana.costa@empresa.com',4),(5,'Pedro Alves','32165498732','32165498000105',3800.25,56789012,29,'Desenvolvedor','pedro.alves@empresa.com',5),(6,'Lucia Mendes','65498732165','65498732000106',2950.00,67890123,27,'Designer','lucia.mendes@empresa.com',6),(7,'Fernando Lima','98732165498','98732165000107',4700.50,78901234,31,'Supervisor','fernando.lima@empresa.com',7),(8,'Patricia Rocha','14725836914','14725836000108',5300.75,89012345,37,'Gerente','patricia.rocha@empresa.com',8),(9,'Ricardo Santos','25836914725','25836914000109',3200.00,90123456,26,'Analista','ricardo.santos@empresa.com',9),(10,'Juliana Pereira','36914725836','36914725000110',4100.25,11223344,30,'Coordenadora','juliana.pereira@empresa.com',10),(11,'Marcos Ferreira','15935748615','15935748000111',3600.50,22334455,28,'Desenvolvedor','marcos.ferreira@empresa.com',11),(12,'Amanda Gonçalves','35748615935','35748615000112',2900.75,33445566,24,'Assistente','amanda.goncalves@empresa.com',12),(13,'Roberto Nunes','48615935748','48615935000113',4900.00,44556677,33,'Supervisor','roberto.nunes@empresa.com',13),(14,'Tatiana Martins','75395184275','75395184000114',4400.25,55667788,31,'Analista','tatiana.martins@empresa.com',14),(15,'Eduardo Barbosa','95184275395','95184275000115',3700.50,66778899,29,'Desenvolvedor','eduardo.barbosa@empresa.com',15),(16,'Cristina Ramos','84275395184','84275395000116',5200.75,77889900,36,'Gerente','cristina.ramos@empresa.com',16),(17,'Lucas Carvalho','65412398765','65412398000117',3300.00,88990011,27,'Designer','lucas.carvalho@empresa.com',17),(18,'Vanessa Dias','32198765432','32198765000118',4000.25,99001122,30,'Analista','vanessa.dias@empresa.com',18),(19,'Gustavo Henrique','98765412398','98765412000119',4500.50,10012233,32,'Supervisor','gustavo.henrique@empresa.com',19),(20,'Daniela Castro','45632178945','45632178000120',3100.75,21123344,26,'Assistente','daniela.castro@empresa.com',20),(21,'Fábio Correia','78945612378','78945612000121',4800.00,32234455,34,'Coordenador','fabio.correia@empresa.com',21),(22,'Beatriz Duarte','12378945612','12378945000122',3400.25,43345566,28,'Desenvolvedor','beatriz.duarte@empresa.com',22),(23,'Rafael Monteiro','45612378945','45612378000123',3900.50,54456677,29,'Analista','rafael.monteiro@empresa.com',23),(24,'Sandra Vieira','78945632178','78945632000124',5400.75,65567788,38,'Gerente','sandra.vieira@empresa.com',24),(25,'Paulo César','32145698732','32145698000125',3500.00,76678899,27,'Designer','paulo.cesar@empresa.com',25),(26,'Mariana Andrade','65478932165','65478932000126',4300.25,87789900,31,'Supervisora','mariana.andrade@empresa.com',26),(27,'Thiago Pires','98732145698','98732145000127',3000.50,98890011,25,'Assistente','thiago.pires@empresa.com',27),(28,'Isabela Cardoso','15975348615','15975348000128',4600.75,19901122,33,'Coordenadora','isabela.cardoso@empresa.com',28),(29,'Leonardo Moura','35715948635','35715948000129',3800.00,20012233,29,'Desenvolvedor','leonardo.moura@empresa.com',29),(30,'Camila Freitas','48635715948','48635715000130',5100.25,31123344,35,'Gerente','camila.freitas@empresa.com',30),(31,'Hugo Melo','75315948675','75315948000131',3300.50,42234455,27,'Analista','hugo.melo@empresa.com',31),(32,'Larissa Moreira','95175348695','95175348000132',4400.75,53345566,31,'Supervisora','larissa.moreira@empresa.com',32),(33,'Diego Araújo','84295175384','84295175000133',2900.00,64456677,24,'Assistente','diego.araujo@empresa.com',33),(34,'Natália Tavares','65432198765','65432198000134',4700.25,75567788,33,'Coordenadora','natalia.tavares@empresa.com',34),(35,'Alexandre Campos','32165478932','32165478000135',3600.50,86678899,28,'Desenvolvedor','alexandre.campos@empresa.com',35),(36,'Renata Fonseca','98712365498','98712365000136',5200.75,97789900,36,'Gerente','renata.fonseca@empresa.com',36),(37,'Bruno Teixeira','45698732145','45698732000137',3400.00,10890011,26,'Designer','bruno.teixeira@empresa.com',37),(38,'Viviane Lopes','78912365478','78912365000138',4100.25,21901122,30,'Analista','viviane.lopes@empresa.com',38),(39,'André Machado','12345698712','12345698000139',4800.50,32012233,34,'Supervisor','andre.machado@empresa.com',39),(40,'Cláudia Reis','45678932145','45678932000140',3700.75,43123344,29,'Desenvolvedora','claudia.reis@empresa.com',40),(41,'Rodrigo Guimarães','78932145678','78932145000141',5300.00,54234455,37,'Gerente','rodrigo.guimaraes@empresa.com',41),(42,'Elaine Sousa','32178965432','32178965000142',3900.25,65345566,31,'Analista','elaine.sousa@empresa.com',42),(43,'Márcio Neves','65498712365','65498712000143',3100.50,76456677,25,'Assistente','marcio.neves@empresa.com',43),(44,'Simone Costa','98732178998','98732178000144',4500.75,87567788,33,'Coordenadora','simone.costa@empresa.com',44),(45,'Felipe Barros','12378965412','12378965000145',3800.00,98678899,28,'Desenvolvedor','felipe.barros@empresa.com',45),(46,'Priscila Rios','45612398745','45612398000146',5000.25,19789900,35,'Gerente','priscila.rios@empresa.com',46),(47,'César Brito','78965412378','78965412000147',3500.50,20890011,27,'Designer','cesar.brito@empresa.com',47),(48,'Luciana Azevedo','32145678932','32145678000148',4200.75,31901122,32,'Supervisora','luciana.azevedo@empresa.com',48),(49,'Marcelo Dantas','65432198765','65432198000149',2900.00,42012233,24,'Assistente','marcelo.dantas@empresa.com',49),(50,'Adriana Peixoto','98765432198','98765432000150',4600.25,53123344,34,'Coordenadora','adriana.peixoto@empresa.com',50),(51,'Gilberto Vasconcelos','12398745612','12398745000151',3700.50,64234455,29,'Desenvolvedor','gilberto.vasconcelos@empresa.com',51),(52,'Helena Miranda','45678965445','45678965000152',5100.75,75345566,37,'Gerente','helena.miranda@empresa.com',52),(53,'Otávio Bezerra','78912398778','78912398000153',3400.00,86456677,26,'Analista','otavio.bezerra@empresa.com',53),(54,'Regina Medeiros','32198712332','32198712000154',4300.25,97567788,32,'Supervisora','regina.medeiros@empresa.com',54),(55,'Sérgio Cunha','65412345665','65412345000155',3000.50,18678899,25,'Assistente','sergio.cunha@empresa.com',55),(56,'Teresa Marques','98745612398','98745612000156',4900.75,29789900,36,'Coordenadora','teresa.marques@empresa.com',56),(57,'Victor Hugo','12365498712','12365498000157',3600.00,30890011,28,'Desenvolvedor','victor.hugo@empresa.com',57),(58,'Yasmin Santana','45698765445','45698765000158',5200.25,41901122,35,'Gerente','yasmin.santana@empresa.com',58),(59,'Zélia Albuquerque','78932112378','78932112000159',3900.50,52012233,31,'Analista','zelia.albuquerque@empresa.com',59),(60,'Wilson Batista','32165412332','32165412000160',4700.75,63123344,34,'Supervisor','wilson.batista@empresa.com',60);
/*!40000 ALTER TABLE `funcionario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ordem_servico`
--

DROP TABLE IF EXISTS `ordem_servico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ordem_servico` (
  `Id_usuario` int NOT NULL,
  `ID_OS` int NOT NULL,
  `Marca_aparelho` varchar(40) DEFAULT NULL,
  `Nome_Cliente` varchar(50) DEFAULT NULL,
  `Prioridade` varchar(30) DEFAULT NULL,
  `Status_` varchar(30) DEFAULT NULL,
  `Data_` date DEFAULT NULL,
  PRIMARY KEY (`ID_OS`),
  KEY `Id_usuario` (`Id_usuario`),
  CONSTRAINT `ordem_servico_ibfk_1` FOREIGN KEY (`Id_usuario`) REFERENCES `usuario` (`Id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ordem_servico`
--

LOCK TABLES `ordem_servico` WRITE;
/*!40000 ALTER TABLE `ordem_servico` DISABLE KEYS */;
INSERT INTO `ordem_servico` VALUES (1,1,'Samsung','Cliente A','Alta','Em andamento','2023-01-10'),(2,2,'Apple','Cliente B','Média','Aguardando peças','2023-01-15'),(3,3,'LG','Cliente C','Baixa','Concluído','2023-01-20'),(4,4,'Motorola','Cliente D','Alta','Em análise','2023-01-25'),(5,5,'Xiaomi','Cliente E','Média','Em andamento','2023-02-01'),(6,6,'Asus','Cliente F','Alta','Aguardando aprovação','2023-02-05'),(7,7,'Dell','Cliente G','Baixa','Concluído','2023-02-10'),(8,8,'HP','Cliente H','Média','Em andamento','2023-02-15'),(9,9,'Lenovo','Cliente I','Alta','Aguardando peças','2023-02-20'),(10,10,'Acer','Cliente J','Baixa','Concluído','2023-02-25'),(11,11,'Sony','Cliente K','Média','Em análise','2023-03-01'),(12,12,'Microsoft','Cliente L','Alta','Em andamento','2023-03-05'),(13,13,'Toshiba','Cliente M','Baixa','Aguardando aprovação','2023-03-10'),(14,14,'Philips','Cliente N','Média','Concluído','2023-03-15'),(15,15,'Positivo','Cliente O','Alta','Em andamento','2023-03-20'),(16,16,'Multilaser','Cliente P','Baixa','Aguardando peças','2023-03-25'),(17,17,'CCE','Cliente Q','Média','Concluído','2023-04-01'),(18,18,'Semp','Cliente R','Alta','Em análise','2023-04-05'),(19,19,'Intelbras','Cliente S','Baixa','Em andamento','2023-04-10'),(20,20,'Nokia','Cliente T','Média','Aguardando aprovação','2023-04-15'),(21,21,'Huawei','Cliente U','Alta','Concluído','2023-04-20'),(22,22,'Alcatel','Cliente V','Baixa','Em andamento','2023-04-25'),(23,23,'ZTE','Cliente W','Média','Aguardando peças','2023-05-01'),(24,24,'OnePlus','Cliente X','Alta','Concluído','2023-05-05'),(25,25,'BlackBerry','Cliente Y','Baixa','Em análise','2023-05-10'),(26,26,'Google','Cliente Z','Média','Em andamento','2023-05-15'),(27,27,'Amazon','Cliente AA','Alta','Aguardando aprovação','2023-05-20'),(28,28,'Razer','Cliente AB','Baixa','Concluído','2023-05-25'),(29,29,'Logitech','Cliente AC','Média','Em andamento','2023-06-01'),(30,30,'Corsair','Cliente AD','Alta','Aguardando peças','2023-06-05'),(31,31,'HyperX','Cliente AE','Baixa','Concluído','2023-06-10'),(32,32,'SteelSeries','Cliente AF','Média','Em análise','2023-06-15'),(33,33,'Gigabyte','Cliente AG','Alta','Em andamento','2023-06-20'),(34,34,'ASRock','Cliente AH','Baixa','Aguardando aprovação','2023-06-25'),(35,35,'MSI','Cliente AI','Média','Concluído','2023-07-01'),(36,36,'EVGA','Cliente AJ','Alta','Em andamento','2023-07-05'),(37,37,'Biostar','Cliente AK','Baixa','Aguardando peças','2023-07-10'),(38,38,'Galax','Cliente AL','Média','Concluído','2023-07-15'),(39,39,'Zotac','Cliente AM','Alta','Em análise','2023-07-20'),(40,40,'Inno3D','Cliente AN','Baixa','Em andamento','2023-07-25'),(41,41,'PNY','Cliente AO','Média','Aguardando aprovação','2023-08-01'),(42,42,'Sapphire','Cliente AP','Alta','Concluído','2023-08-05'),(43,43,'PowerColor','Cliente AQ','Baixa','Em andamento','2023-08-10'),(44,44,'XFX','Cliente AR','Média','Aguardando peças','2023-08-15'),(45,45,'VisionTek','Cliente AS','Alta','Concluído','2023-08-20'),(46,46,'Matrox','Cliente AT','Baixa','Em análise','2023-08-25'),(47,47,'Club3D','Cliente AU','Média','Em andamento','2023-09-01'),(48,48,'Leadtek','Cliente AV','Alta','Aguardando aprovação','2023-09-05'),(49,49,'Palit','Cliente AW','Baixa','Concluído','2023-09-10'),(50,50,'Point of View','Cliente AX','Média','Em andamento','2023-09-15'),(51,51,'Sparkle','Cliente AY','Alta','Aguardando peças','2023-09-20'),(52,52,'Manli','Cliente AZ','Baixa','Concluído','2023-09-25'),(53,53,'Colorful','Cliente BA','Média','Em análise','2023-10-01'),(54,54,'Maxsun','Cliente BB','Alta','Em andamento','2023-10-05'),(55,55,'Yeston','Cliente BC','Baixa','Aguardando aprovação','2023-10-10'),(56,56,'Gainward','Cliente BD','Média','Concluído','2023-10-15'),(57,57,'VTX3D','Cliente BE','Alta','Em andamento','2023-10-20'),(58,58,'HIS','Cliente BF','Baixa','Aguardando peças','2023-10-25'),(59,59,'Diamond','Cliente BG','Média','Concluído','2023-11-01'),(60,60,'Jaton','Cliente BH','Alta','Em análise','2023-11-05');
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
  `Quantidade` int NOT NULL,
  `Nome` varchar(30) NOT NULL,
  PRIMARY KEY (`id_peca`),
  KEY `FK_pecas_id_usuario` (`id_usuario`),
  CONSTRAINT `FK_pecas_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`Id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pecas_estoque`
--

LOCK TABLES `pecas_estoque` WRITE;
/*!40000 ALTER TABLE `pecas_estoque` DISABLE KEYS */;
INSERT INTO `pecas_estoque` VALUES (1,1,50,'Processador i7'),(2,2,30,'Memória RAM 8GB'),(3,3,45,'SSD 256GB'),(4,4,20,'Placa de Vídeo RTX 3060'),(5,5,60,'HD 1TB'),(6,6,25,'Fonte 500W'),(7,7,40,'Gabinete ATX'),(8,8,15,'Monitor 24\"'),(9,9,35,'Teclado Mecânico'),(10,10,50,'Mouse Óptico'),(11,11,28,'Placa-Mãe B450'),(12,12,42,'Cooler CPU'),(13,13,18,'Notebook i5'),(14,14,22,'Webcam HD'),(15,15,38,'Headphone Gamer'),(16,16,55,'Hub USB'),(17,17,32,'Roteador Wi-Fi'),(18,18,47,'Switch 8 portas'),(19,19,19,'Impressora Laser'),(20,20,27,'Scanner A4'),(21,21,41,'Tablet 10\"'),(22,22,33,'Pen Drive 64GB'),(23,23,24,'HD Externo 1TB'),(24,24,36,'Mousepad Grande'),(25,25,29,'Adaptador HDMI'),(26,26,43,'Cabo de Rede'),(27,27,17,'Fone Bluetooth'),(28,28,52,'Carregador Notebook'),(29,29,31,'Bateria Notebook'),(30,30,48,'Suporte Monitor'),(31,31,21,'Mesa Digitalizadora'),(32,32,39,'Microfone USB'),(33,33,26,'Caixa de Som'),(34,34,44,'Adaptador USB-C'),(35,35,16,'Leitor de Cartão'),(36,36,53,'Cabo USB 3.0'),(37,37,34,'Suporte Notebook'),(38,38,49,'Filtro de Linha'),(39,39,23,'Repetidor Wi-Fi'),(40,40,37,'Dock Station'),(41,41,20,'Monitor 27\"'),(42,42,54,'Teclado Sem Fio'),(43,43,30,'Mouse Sem Fio'),(44,44,46,'SSD 512GB'),(45,45,14,'Placa de Vídeo GTX 1660'),(46,46,51,'Memória RAM 16GB'),(47,47,25,'Processador Ryzen 5'),(48,48,40,'Fonte 600W'),(49,49,18,'Gabinete Mini Tower'),(50,50,55,'Monitor Curvo'),(51,51,32,'Headphone Bluetooth'),(52,52,47,'Webcam 4K'),(53,53,19,'Notebook i7'),(54,54,27,'Impressora Jato de Tinta'),(55,55,41,'Scanner A3'),(56,56,33,'Tablet 12\"'),(57,57,24,'Pen Drive 128GB'),(58,58,36,'HD Externo 2TB'),(59,59,29,'Adaptador VGA'),(60,60,43,'Cabo HDMI');
/*!40000 ALTER TABLE `pecas_estoque` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfil`
--

DROP TABLE IF EXISTS `perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perfil` (
  `Id_perfil` int NOT NULL,
  `Id_usuario` int NOT NULL,
  `nome_cargo` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`Id_perfil`),
  KEY `Id_usuario` (`Id_usuario`),
  CONSTRAINT `perfil_ibfk_1` FOREIGN KEY (`Id_usuario`) REFERENCES `usuario` (`Id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil`
--

LOCK TABLES `perfil` WRITE;
/*!40000 ALTER TABLE `perfil` DISABLE KEYS */;
INSERT INTO `perfil` VALUES (1,1,'Analista de Sistemas'),(2,2,'Gerente de TI'),(3,3,'Assistente Administrativo'),(4,4,'Coordenador de Projetos'),(5,5,'Desenvolvedor Full Stack'),(6,6,'Designer UX/UI'),(7,7,'Supervisor de Equipe'),(8,8,'Gerente de Operações'),(9,9,'Analista Financeiro'),(10,10,'Coordenador de RH'),(11,11,'Desenvolvedor Backend'),(12,12,'Assistente de Marketing'),(13,13,'Supervisor de Vendas'),(14,14,'Analista de Dados'),(15,15,'Desenvolvedor Frontend'),(16,16,'Gerente Comercial'),(17,17,'Designer Gráfico'),(18,18,'Analista de Suporte'),(19,19,'Supervisor de Produção'),(20,20,'Assistente de Logística'),(21,21,'Coordenador de TI'),(22,22,'Desenvolvedor Mobile'),(23,23,'Analista de Negócios'),(24,24,'Gerente de Projetos'),(25,25,'Designer de Produto'),(26,26,'Supervisora Comercial'),(27,27,'Assistente Financeiro'),(28,28,'Coordenadora de Marketing'),(29,29,'Desenvolvedor DevOps'),(30,30,'Gerente de Vendas'),(31,31,'Analista de Qualidade'),(32,32,'Supervisora de RH'),(33,33,'Assistente Administrativo'),(34,34,'Coordenadora de Operações'),(35,35,'Desenvolvedor Python'),(36,36,'Gerente de Contas'),(37,37,'Designer Web'),(38,38,'Analista de Sistemas'),(39,39,'Supervisor de TI'),(40,40,'Desenvolvedora Java'),(41,41,'Gerente de Produto'),(42,42,'Analista Comercial'),(43,43,'Assistente de TI'),(44,44,'Coordenadora Financeira'),(45,45,'Desenvolvedor .NET'),(46,46,'Gerente Regional'),(47,47,'Designer Digital'),(48,48,'Supervisora Administrativa'),(49,49,'Assistente de Vendas'),(50,50,'Coordenador de Logística'),(51,51,'Desenvolvedor PHP'),(52,52,'Gerente de Unidade'),(53,53,'Analista de Suporte Técnico'),(54,54,'Supervisora Operacional'),(55,55,'Assistente de Recursos Humanos'),(56,56,'Coordenadora de Vendas'),(57,57,'Desenvolvedor Ruby'),(58,58,'Gerente de Filial'),(59,59,'Analista de Processos'),(60,60,'Supervisor de Manutenção');
/*!40000 ALTER TABLE `perfil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `Id_usuario` int NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `Senha` varchar(16) DEFAULT NULL,
  `Data_Cadastro` date DEFAULT NULL,
  `cpf` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`Id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'joao.silva@empresa.com','João Silva','Senha@123','2020-01-15','12345678901'),(2,'maria.oliveira@empresa.com','Maria Oliveira','Senha@456','2020-02-20','98765432109'),(3,'carlos.souza@empresa.com','Carlos Souza','Senha@789','2020-03-10','45678912345'),(4,'ana.costa@empresa.com','Ana Costa','Senha@101','2020-04-05','78912345678'),(5,'pedro.alves@empresa.com','Pedro Alves','Senha@202','2020-05-12','32165498732'),(6,'lucia.mendes@empresa.com','Lucia Mendes','Senha@303','2020-06-18','65498732165'),(7,'fernando.lima@empresa.com','Fernando Lima','Senha@404','2020-07-22','98732165498'),(8,'patricia.rocha@empresa.com','Patricia Rocha','Senha@505','2020-08-30','14725836914'),(9,'ricardo.santos@empresa.com','Ricardo Santos','Senha@606','2020-09-14','25836914725'),(10,'juliana.pereira@empresa.com','Juliana Pereira','Senha@707','2020-10-25','36914725836'),(11,'marcos.ferreira@empresa.com','Marcos Ferreira','Senha@808','2020-11-03','15935748615'),(12,'amanda.goncalves@empresa.com','Amanda Gonçalves','Senha@909','2020-12-08','35748615935'),(13,'roberto.nunes@empresa.com','Roberto Nunes','Senha@111','2021-01-17','48615935748'),(14,'tatiana.martins@empresa.com','Tatiana Martins','Senha@222','2021-02-19','75395184275'),(15,'eduardo.barbosa@empresa.com','Eduardo Barbosa','Senha@333','2021-03-21','95184275395'),(16,'cristina.ramos@empresa.com','Cristina Ramos','Senha@444','2021-04-05','84275395184'),(17,'lucas.carvalho@empresa.com','Lucas Carvalho','Senha@555','2021-05-11','65412398765'),(18,'vanessa.dias@empresa.com','Vanessa Dias','Senha@666','2021-06-15','32198765432'),(19,'gustavo.henrique@empresa.com','Gustavo Henrique','Senha@777','2021-07-20','98765412398'),(20,'daniela.castro@empresa.com','Daniela Castro','Senha@888','2021-08-24','45632178945'),(21,'fabio.correia@empresa.com','Fábio Correia','Senha@999','2021-09-28','78945612378'),(22,'beatriz.duarte@empresa.com','Beatriz Duarte','Senha@000','2021-10-30','12378945612'),(23,'rafael.monteiro@empresa.com','Rafael Monteiro','Senha@121','2021-11-05','45612378945'),(24,'sandra.vieira@empresa.com','Sandra Vieira','Senha@232','2021-12-10','78945632178'),(25,'paulo.cesar@empresa.com','Paulo César','Senha@343','2022-01-15','32145698732'),(26,'mariana.andrade@empresa.com','Mariana Andrade','Senha@454','2022-02-20','65478932165'),(27,'thiago.pires@empresa.com','Thiago Pires','Senha@565','2022-03-25','98732145698'),(28,'isabela.cardoso@empresa.com','Isabela Cardoso','Senha@676','2022-04-30','15975348615'),(29,'leonardo.moura@empresa.com','Leonardo Moura','Senha@787','2022-05-05','35715948635'),(30,'camila.freitas@empresa.com','Camila Freitas','Senha@898','2022-06-10','48635715948'),(31,'hugo.melo@empresa.com','Hugo Melo','Senha@909','2022-07-15','75315948675'),(32,'larissa.moreira@empresa.com','Larissa Moreira','Senha@010','2022-08-20','95175348695'),(33,'diego.araujo@empresa.com','Diego Araújo','Senha@111','2022-09-25','84295175384'),(34,'natalia.tavares@empresa.com','Natália Tavares','Senha@212','2022-10-30','65432198765'),(35,'alexandre.campos@empresa.com','Alexandre Campos','Senha@313','2022-11-05','32165478932'),(36,'renata.fonseca@empresa.com','Renata Fonseca','Senha@414','2022-12-10','98712365498'),(37,'bruno.teixeira@empresa.com','Bruno Teixeira','Senha@515','2023-01-15','45698732145'),(38,'viviane.lopes@empresa.com','Viviane Lopes','Senha@616','2023-02-20','78912365478'),(39,'andre.machado@empresa.com','André Machado','Senha@717','2023-03-25','12345698712'),(40,'claudia.reis@empresa.com','Cláudia Reis','Senha@818','2023-04-30','45678932145'),(41,'rodrigo.guimaraes@empresa.com','Rodrigo Guimarães','Senha@919','2023-05-05','78932145678'),(42,'elaine.sousa@empresa.com','Elaine Sousa','Senha@020','2023-06-10','32178965432'),(43,'marcio.neves@empresa.com','Márcio Neves','Senha@121','2023-07-15','65498712365'),(44,'simone.costa@empresa.com','Simone Costa','Senha@222','2023-08-20','98732178998'),(45,'felipe.barros@empresa.com','Felipe Barros','Senha@323','2023-09-25','12378965412'),(46,'priscila.rios@empresa.com','Priscila Rios','Senha@424','2023-10-30','45612398745'),(47,'cesar.brito@empresa.com','César Brito','Senha@525','2023-11-05','78965412378'),(48,'luciana.azevedo@empresa.com','Luciana Azevedo','Senha@626','2023-12-10','32145678932'),(49,'marcelo.dantas@empresa.com','Marcelo Dantas','Senha@727','2024-01-15','65432198765'),(50,'adriana.peixoto@empresa.com','Adriana Peixoto','Senha@828','2024-02-20','98765432198'),(51,'gilberto.vasconcelos@empresa.com','Gilberto Vasconcelos','Senha@929','2024-03-25','12398745612'),(52,'helena.miranda@empresa.com','Helena Miranda','Senha@030','2024-04-30','45678965445'),(53,'otavio.bezerra@empresa.com','Otávio Bezerra','Senha@131','2024-05-05','78912398778'),(54,'regina.medeiros@empresa.com','Regina Medeiros','Senha@232','2024-06-10','32198712332'),(55,'sergio.cunha@empresa.com','Sérgio Cunha','Senha@333','2024-07-15','65412345665'),(56,'teresa.marques@empresa.com','Teresa Marques','Senha@434','2024-08-20','98745612398'),(57,'victor.hugo@empresa.com','Victor Hugo','Senha@535','2024-09-25','12365498712'),(58,'yasmin.santana@empresa.com','Yasmin Santana','Senha@636','2024-10-30','45698765445'),(59,'zelia.albuquerque@empresa.com','Zélia Albuquerque','Senha@737','2024-11-05','78932112378'),(60,'wilson.batista@empresa.com','Wilson Batista','Senha@838','2024-12-10','32165412332');
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

-- Dump completed on 2025-05-08 17:06:54
