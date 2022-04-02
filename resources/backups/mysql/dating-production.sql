CREATE DATABASE  IF NOT EXISTS `dating` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `dating`;
-- MySQL dump 10.13  Distrib 5.7.25, for Linux (x86_64)
--
-- Host: 188.166.18.52    Database: dating
-- ------------------------------------------------------
-- Server version	5.7.22-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity`
--

DROP TABLE IF EXISTS `activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity`
--

LOCK TABLES `activity` WRITE;
/*!40000 ALTER TABLE `activity` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES (1,'4 Tips om een snelle online seksdate te regelen als man','### Online seksdate regelen\r\n\r\nHet begint steeds normaler te worden, mensen vinden elkaar via het internet. Internet is het snelst groeiende medium voor online dates, flirts en relaties. Je hebt besloten om ook online op zoek te gaan naar een seksdates, maar vraagt je toch af of het niet makkelijker kan. Je krijgt genoeg berichten, praat met wat leuke vrouwen, maar aan het einde van de dag lig je nog alleen in bed. Hoe zorg je er nou voor dat je je slagingskans vergroot en de termijn aanzienlijk inkort waarin je een date regelt?\r\n\r\nLaten we bij het probleem beginnen, want er zijn immers mannen en vrouwen genoeg op datevrij.nl. Waar gaat het dan mis?\r\n\r\nMannen willen vaak snel scoren. Niet teveel chatten, maar gewoon straight to the point en heerlijk genieten tussen de dekens. Het heeft ook wel wat spanning als je niet direct alles weet van iemand en diegene in de slaapkamer leert kennen toch? Dat is de oorzaak van het probleem, want vrouwen zijn (uitzonderingen daargelaten) niet zo. Wat doen ze hier dan zal je je afvragen.. Ze willen wel een seksdate, maar ze willen niet na het eerste berichtje al gelijk met je bed.\r\n\r\n**Hoe los je dit op?**\r\n\r\nProbeer de balans te vinden tussen een praatje maken en elkaar leren kennen, uiteindelijk is dit voor beide partijen wel fijn. Je hebt geen zin om eeuwig berichten uit te wisselen dus op een gegeven moment wordt het toch tijd om aanstalten te maken. \r\n\r\nNa het lezen van onderstaande tips weet je precies hoe jij zelf kan inspelen op de factoren die je kan beïnvloeden om een seksdate te regelen.\r\n\r\n### Tip 1: Zorg dat je profiel vrouwen aantrekt\r\n\r\nDe eerste indruk die iemand van jouw profiel krijgt zijn foto\'s en je profieltekst en natuurlijk wat voorkeuren en uiterlijke kenmerken. Het is misschien oppervlakkig, maar ook vrouwen willen een bepaald gevoel bij een man krijgen. En dan bedoelen we natuurlijk niet je geslachtsdeel. Een leuke, spontane foto waarop je lacht doet het veel beter als profielfoto. Als ze interesse heeft en benieuwd is wat zich daar onder allemaal afspeelt zal ze daar snel genoeg over beginnen en kan je haar helemaal gek maken door steeds iets meer bloot te geven. Een cadeautje pak je ook niet voor niets in toch?\r\n\r\nIn de perfecte profieltekst gebruik je het liefst steekwoorden of ietwat cryptische omschrijvingen, zorg er in het laatste geval voor dat het niet een te vaag verhaal wordt. Gebleken is dat met steekwoorden het meeste resultaat wordt geboekt en de meeste reacties verwacht mogen worden. Dit komt omdat je niet gelijk alles prijs geeft en er nog veel open blijft om over te praten. Praten heb je waarschijnlijk geen zin in, maar een date scoren wel toch? Vrouwen geilen op mannen die enig mysterie om zich heen hebben wat ze kunnen gaan ontdekken.\r\n\r\nZorg dus voor het meest aantrekkelijke visitekaartje met wat leuke foto\'s en een goede profieltekst.\r\n\r\n### Tip 2: Laat een goede indruk achter van jezelf\r\n\r\nNet als in elk ander gesprek met iemand die je niet kent is het verstandig om je eerst voor te stellen. Gooi niet gelijk een heel levensverhaal op tafel, maar wat basis dingen over jezelf. Wie ben je, wat doe je in het dagelijks leven? Wat voor baan/studie doe je? Vrouwen willen een beeld krijgen van wie jij bent als persoon. Belangrijker is nog dat je dezelfde interesse in haar toont, anders zal het al snel onpersoonlijk opvatten en dan zijn de kansen op een geile seksdate vrij klein. Stel haar vragen, leer haar kennen en belangrijkste hier in is dat je haar het gevoel geeft dat je haar door en door kent en alles van haar weet en wilt weten. Nu zal ze je ook sneller vertrouwen en zal de seksdate eerder plaatsvinden.\r\n\r\nMaak grapjes, laat haar lachen en geef complimenten. De meeste vrouwen weten wel dat je ze helemaal plat wilt neuken. Je bereikt er meer mee als je haar fantasie weet te prikkelen. Probeer op een subtiele manier duidelijk te maken dat je haar langs alle zeven hemelen wilt laten zweven. Een vrouw wilt weten dat het om haar gaat, niet alleen dat je een stijve pik krijgt wanneer je een foto van haar borsten te zien krijgt. Vrouwen zijn gevoeliger voor sensuele bewoordingen en verhalen.\r\n\r\n### Tip 3: Blijf uit de (digitale) friendzone!\r\n\r\nWanneer vrouwen echt af willen spreken zullen ze er vaker omheen draaien en subtiele hints geven, misschien geeft ze het wel heel direct aan, maar het laatste wat je wilt is in een situatie komen waarin je dit gaat afdwingen. Je zit toch op een online seksdating website? Jazeker, maar als een vrouw alleen op gelijk en zo snel mogelijk seks uit is dan kan ze ook op stap gaan of de buurman vragen. Ze wil bemind worden. Vertel haar dat je aan haar denkt en wat ze met je doet, wat je zo sexy aan haar vindt en wat je allemaal voor fantasieën met en over haar hebt.\r\n\r\nWanneer je dit doet zal ze helemaal gek van je worden, in positieve zin uiteraard. Wanneer je haar genoeg gevleid hebt, zal ze waarschijnlijk niet lang meer willen wachten om je de avond van je leven te bezorgen.\r\n\r\n### Tip 4: Ga voor een goede eerste indruk voor een snelle date\r\n\r\nJe wilt natuurlijk zo snel mogelijk duidelijk maken waar je op uit bent. Het gevaar is dat de eerste paar berichten cruciaal zijn voor de eerste indruk. Laat hier dus zeker niet in doorschemeren dat je wanhopig op zoek bent naar seks, maar ga wederom op zoek naar de spanning. Tast af wat de gevoelige onderwerpen zijn en hoe je het gesprek op een leuke manier kan opbouwen. \"Jij ziet er lekker uit, zullen we eens een keer een lekkere seks afspraak hebben\" zal het waarschijnlijk niet heel goed doen als openingsbericht.\r\n\r\nAls je echt snel succes wilt boeken, dan is het juist belangrijk om in het begin wat meer de kat uit de boom te kijken, waarna je de fantasie kan aanwakkeren en dan gaat de rest vanzelf. Zie het als een stukje omgekeerde psychologie, zij moet intens verlangen naar jou en niet het gevoel hebben dat je haar zo snel mogelijk in je bed wilt hebben. Het grootste voordeel hieraan voor beide partijen is dat als het bevalt na de eerste keer, dat de afspraken daarna alleen maar geiler worden!\r\n','4 gouden tips om online zo snel mogelijk een seksdate te regelen. Deze zorgen direct voor meer succes op weg naar je online seksdate.',1,'2017-08-25 09:14:58','2017-08-25 09:14:58');
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bot_categories`
--

DROP TABLE IF EXISTS `bot_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bot_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bot_categories`
--

LOCK TABLES `bot_categories` WRITE;
/*!40000 ALTER TABLE `bot_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `bot_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bot_category_user`
--

DROP TABLE IF EXISTS `bot_category_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bot_category_user` (
  `bot_category_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  KEY `bot_category_user_user_id_foreign` (`user_id`),
  CONSTRAINT `bot_category_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bot_category_user`
--

LOCK TABLES `bot_category_user` WRITE;
/*!40000 ALTER TABLE `bot_category_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `bot_category_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conversation_messages`
--

DROP TABLE IF EXISTS `conversation_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conversation_messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` int(10) unsigned NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `sender_id` int(10) unsigned NOT NULL,
  `recipient_id` int(10) unsigned NOT NULL,
  `body` varchar(3000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `has_attachment` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conversation_messages`
--

LOCK TABLES `conversation_messages` WRITE;
/*!40000 ALTER TABLE `conversation_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `conversation_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conversation_notes`
--

DROP TABLE IF EXISTS `conversation_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conversation_notes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `conversation_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `body` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conversation_notes`
--

LOCK TABLES `conversation_notes` WRITE;
/*!40000 ALTER TABLE `conversation_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `conversation_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conversations`
--

DROP TABLE IF EXISTS `conversations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conversations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_a_id` int(10) unsigned NOT NULL,
  `user_b_id` int(10) unsigned NOT NULL,
  `locked_by_user_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conversations`
--

LOCK TABLES `conversations` WRITE;
/*!40000 ALTER TABLE `conversations` DISABLE KEYS */;
/*!40000 ALTER TABLE `conversations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `creditpacks`
--

DROP TABLE IF EXISTS `creditpacks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `creditpacks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `credits` mediumint(8) unsigned NOT NULL,
  `price` mediumint(8) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `creditpacks`
--

LOCK TABLES `creditpacks` WRITE;
/*!40000 ALTER TABLE `creditpacks` DISABLE KEYS */;
INSERT INTO `creditpacks` VALUES (1,'small','Dummy','Dummy',20,25000,NULL,NULL),(2,'medium','Dummy','Dummy',30,60000,NULL,NULL),(3,'large','Dummy','Dummy',40,100000,NULL,NULL);
/*!40000 ALTER TABLE `creditpacks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8_unicode_ci NOT NULL,
  `queue` text COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faqs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `section` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqs`
--

LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_reserved_at_index` (`queue`,`reserved_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `layout_part_view`
--

DROP TABLE IF EXISTS `layout_part_view`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `layout_part_view` (
  `view_id` int(10) unsigned NOT NULL,
  `layout_part_id` int(10) unsigned NOT NULL,
  KEY `layout_part_view_view_id_foreign` (`view_id`),
  KEY `layout_part_view_layout_part_id_foreign` (`layout_part_id`),
  CONSTRAINT `layout_part_view_layout_part_id_foreign` FOREIGN KEY (`layout_part_id`) REFERENCES `layout_parts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `layout_part_view_view_id_foreign` FOREIGN KEY (`view_id`) REFERENCES `views` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `layout_part_view`
--

LOCK TABLES `layout_part_view` WRITE;
/*!40000 ALTER TABLE `layout_part_view` DISABLE KEYS */;
INSERT INTO `layout_part_view` VALUES (1,1),(1,2),(2,2);
/*!40000 ALTER TABLE `layout_part_view` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `layout_parts`
--

DROP TABLE IF EXISTS `layout_parts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `layout_parts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `layout_parts`
--

LOCK TABLES `layout_parts` WRITE;
/*!40000 ALTER TABLE `layout_parts` DISABLE KEYS */;
INSERT INTO `layout_parts` VALUES (1,'left-sidebar'),(2,'right-sidebar');
/*!40000 ALTER TABLE `layout_parts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message_attachments`
--

DROP TABLE IF EXISTS `message_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_attachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` int(10) unsigned NOT NULL,
  `message_id` int(10) unsigned NOT NULL,
  `filename` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message_attachments`
--

LOCK TABLES `message_attachments` WRITE;
/*!40000 ALTER TABLE `message_attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `message_attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `module_instances`
--

DROP TABLE IF EXISTS `module_instances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `module_instances` (
  `module_id` int(10) unsigned NOT NULL,
  `layout_part_id` int(10) unsigned NOT NULL,
  `view_id` int(10) unsigned NOT NULL,
  `priority` smallint(5) unsigned NOT NULL DEFAULT '100',
  KEY `module_instances_module_id_foreign` (`module_id`),
  KEY `module_instances_layout_part_id_foreign` (`layout_part_id`),
  KEY `module_instances_view_id_foreign` (`view_id`),
  CONSTRAINT `module_instances_layout_part_id_foreign` FOREIGN KEY (`layout_part_id`) REFERENCES `layout_parts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `module_instances_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `module_instances_view_id_foreign` FOREIGN KEY (`view_id`) REFERENCES `views` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `module_instances`
--

LOCK TABLES `module_instances` WRITE;
/*!40000 ALTER TABLE `module_instances` DISABLE KEYS */;
INSERT INTO `module_instances` VALUES (1,1,1,2),(2,1,1,1),(3,2,1,1);
/*!40000 ALTER TABLE `module_instances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modules`
--

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` VALUES (1,'search',NULL,NULL,NULL),(2,'user-account',NULL,NULL,NULL),(3,'online-users',NULL,NULL,NULL),(4,'shoutbox',NULL,NULL,NULL);
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `note_categories`
--

DROP TABLE IF EXISTS `note_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `note_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `note_categories`
--

LOCK TABLES `note_categories` WRITE;
/*!40000 ALTER TABLE `note_categories` DISABLE KEYS */;
INSERT INTO `note_categories` VALUES (1,'Naam'),(2,'Woonplaats'),(3,'Beroep'),(4,'Familie/Relatie'),(5,'Hobby’s'),(6,'Belangrijke data'),(7,'Seksuele voorkeuren'),(8,'Overige');
/*!40000 ALTER TABLE `note_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `method` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(5) unsigned NOT NULL,
  `transactionId` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_user` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `role_user_user_id_foreign` (`user_id`),
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_user`
--

LOCK TABLES `role_user` WRITE;
/*!40000 ALTER TABLE `role_user` DISABLE KEYS */;
INSERT INTO `role_user` VALUES (1,1,'2017-07-09 16:36:56','2017-07-09 16:36:56'),(3,3,'2017-07-13 10:06:53','2017-07-13 10:06:53'),(4,3,'2017-07-13 10:28:43','2017-07-13 10:28:43'),(5,3,'2017-08-23 09:33:42','2017-08-23 09:33:42'),(6,3,'2017-08-23 13:55:24','2017-08-23 13:55:24'),(8,3,'2017-09-04 09:11:45','2017-09-04 09:11:45'),(9,3,'2017-09-04 09:24:34','2017-09-04 09:24:34'),(10,3,'2017-09-11 09:30:54','2017-09-11 09:30:54'),(11,3,'2017-09-11 09:34:57','2017-09-11 09:34:57'),(12,3,'2017-09-27 09:10:09','2017-09-27 09:10:09'),(13,3,'2018-10-09 20:25:03','2018-10-09 20:25:03'),(14,3,'2018-10-11 22:52:25','2018-10-11 22:52:25'),(15,3,'2018-11-21 21:56:05','2018-11-21 21:56:05'),(16,3,'2018-11-21 21:59:50','2018-11-21 21:59:50'),(17,3,'2018-11-21 22:04:04','2018-11-21 22:04:04'),(18,3,'2018-11-21 22:08:17','2018-11-21 22:08:17'),(19,3,'2018-11-21 22:13:21','2018-11-21 22:13:21'),(20,3,'2018-11-21 22:16:51','2018-11-21 22:16:51'),(21,3,'2018-11-21 22:20:17','2018-11-21 22:20:17'),(22,3,'2018-11-21 22:26:25','2018-11-21 22:26:25'),(23,3,'2018-11-21 22:31:14','2018-11-21 22:31:14'),(24,3,'2018-11-21 22:34:41','2018-11-21 22:34:41'),(25,3,'2018-11-21 22:37:16','2018-11-21 22:37:16'),(26,3,'2018-11-21 22:43:56','2018-11-21 22:43:56'),(27,3,'2018-11-21 22:56:40','2018-11-21 22:56:40'),(28,3,'2018-11-21 22:59:19','2018-11-21 22:59:19'),(29,3,'2018-11-21 23:02:10','2018-11-21 23:02:10'),(30,3,'2018-11-21 23:04:30','2018-11-21 23:04:30'),(31,3,'2018-11-21 23:06:34','2018-11-21 23:06:34'),(32,3,'2018-11-21 23:10:56','2018-11-21 23:10:56'),(33,3,'2018-11-22 13:00:51','2018-11-22 13:00:51'),(34,3,'2018-11-22 13:03:49','2018-11-22 13:03:49'),(35,3,'2018-11-22 13:07:27','2018-11-22 13:07:27'),(36,3,'2018-11-22 13:16:23','2018-11-22 13:16:23'),(37,3,'2018-11-22 13:20:54','2018-11-22 13:20:54'),(38,3,'2018-11-22 13:23:27','2018-11-22 13:23:27'),(39,3,'2018-11-22 13:26:40','2018-11-22 13:26:40'),(40,3,'2018-11-22 13:34:23','2018-11-22 13:34:23'),(41,3,'2018-11-22 13:57:35','2018-11-22 13:57:35'),(42,3,'2018-11-22 14:05:05','2018-11-22 14:05:05'),(43,3,'2019-01-13 12:34:32','2019-01-13 12:34:32'),(44,3,'2019-01-13 12:42:45','2019-01-13 12:42:45'),(45,3,'2019-01-13 12:48:39','2019-01-13 12:48:39'),(46,3,'2019-01-13 12:54:13','2019-01-13 12:54:13'),(47,3,'2019-01-13 12:57:47','2019-01-13 12:57:47'),(48,3,'2019-01-13 13:01:52','2019-01-13 13:01:52'),(49,3,'2019-01-13 13:06:02','2019-01-13 13:06:02'),(50,3,'2019-01-13 13:11:42','2019-01-13 13:11:42'),(51,3,'2019-01-13 13:14:36','2019-01-13 13:14:36'),(52,3,'2019-01-13 13:17:10','2019-01-13 13:17:10'),(53,3,'2019-01-20 20:30:30','2019-01-20 20:30:30'),(54,3,'2019-01-20 20:35:27','2019-01-20 20:35:27'),(55,3,'2019-01-20 20:38:22','2019-01-20 20:38:22'),(56,3,'2019-01-20 20:49:52','2019-01-20 20:49:52'),(57,3,'2019-01-21 08:14:52','2019-01-21 08:14:52'),(58,3,'2019-01-21 08:17:39','2019-01-21 08:17:39'),(59,3,'2019-01-21 08:21:35','2019-01-21 08:21:35'),(60,3,'2019-01-21 08:31:24','2019-01-21 08:31:24'),(61,3,'2019-01-21 08:34:16','2019-01-21 08:34:16'),(62,3,'2019-01-21 08:38:53','2019-01-21 08:38:53'),(63,3,'2019-01-21 08:42:28','2019-01-21 08:42:28'),(64,3,'2019-01-21 09:58:14','2019-01-21 09:58:14'),(65,3,'2019-01-21 10:00:32','2019-01-21 10:00:32'),(66,3,'2019-01-21 10:17:00','2019-01-21 10:17:00'),(67,3,'2019-01-21 10:29:39','2019-01-21 10:29:39');
/*!40000 ALTER TABLE `role_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin'),(2,'peasant'),(3,'bot'),(4,'operator');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8_unicode_ci,
  `payload` text COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  UNIQUE KEY `sessions_id_unique` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tacs`
--

DROP TABLE IF EXISTS `tacs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tacs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tacs`
--

LOCK TABLES `tacs` WRITE;
/*!40000 ALTER TABLE `tacs` DISABLE KEYS */;
/*!40000 ALTER TABLE `tacs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonial_users`
--

DROP TABLE IF EXISTS `testimonial_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `testimonial_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `testimonial_id` int(10) unsigned NOT NULL,
  `image_filename` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dob` datetime NOT NULL,
  `username` text COLLATE utf8_unicode_ci NOT NULL,
  `gender` smallint(6) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `testimonial_users_testimonial_id_foreign` (`testimonial_id`),
  CONSTRAINT `testimonial_users_testimonial_id_foreign` FOREIGN KEY (`testimonial_id`) REFERENCES `testimonials` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonial_users`
--

LOCK TABLES `testimonial_users` WRITE;
/*!40000 ALTER TABLE `testimonial_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `testimonial_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `testimonials` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL,
  `pretend_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonials`
--

LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_accounts`
--

DROP TABLE IF EXISTS `user_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `credits` mediumint(8) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_accounts`
--

LOCK TABLES `user_accounts` WRITE;
/*!40000 ALTER TABLE `user_accounts` DISABLE KEYS */;
INSERT INTO `user_accounts` VALUES (1,1,2,'2017-07-09 16:36:56','2017-07-09 16:36:56');
/*!40000 ALTER TABLE `user_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_images`
--

DROP TABLE IF EXISTS `user_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `filename` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `profile` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_images_user_id_foreign` (`user_id`),
  CONSTRAINT `user_images_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=263 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_images`
--

LOCK TABLES `user_images` WRITE;
/*!40000 ALTER TABLE `user_images` DISABLE KEYS */;
INSERT INTO `user_images` VALUES (4,3,'706944b18d02059bbc31359d3c41b161.jpeg',1,1,'2017-07-13 10:06:54','2017-07-13 10:09:00'),(5,3,'7c6fd87ad22983b678cf482baba8a2ea.jpeg',1,0,'2017-07-13 10:06:55','2017-07-13 10:09:00'),(6,3,'16e3884fcb76f97dcb197162d3cd389f.jpeg',0,0,'2017-07-13 10:06:55','2017-07-13 10:07:38'),(7,3,'48b8c38ebb61a425f22842c6aa18ae6c.jpeg',0,0,'2017-07-13 10:06:55','2017-07-13 10:07:45'),(8,3,'86fb4ac20850cf269e74aa42edd79dde.jpeg',0,0,'2017-07-13 10:06:55','2017-07-13 10:07:50'),(9,3,'a194c14aa82a7e8dc84f376e24c8b3d9.jpeg',0,0,'2017-07-13 10:06:55','2017-07-13 10:07:54'),(10,3,'c3087529d1cf642efb0f4d6f65867bab.jpeg',0,0,'2017-07-13 10:06:55','2017-07-13 10:07:59'),(11,3,'92b6b8e8e1bdf94e3e68f3d2f105407e.jpeg',0,0,'2017-07-13 10:06:55','2017-07-13 10:07:19'),(12,3,'e202b0310a1e5760a566cb8e11be5e37.jpeg',0,0,'2017-07-13 10:06:55','2017-07-13 10:07:16'),(13,4,'4d8d181a01e5c538425cd8dfd30deb92.jpeg',1,1,'2017-07-13 10:28:43','2017-07-13 10:28:43'),(14,4,'5f939af66e6adbd5db2556fb73dea2be.jpeg',0,0,'2017-07-13 10:28:44','2017-07-13 10:29:18'),(15,4,'374c3e7fd966997cb042faf37d415b2f.jpeg',0,0,'2017-07-13 10:28:44','2017-07-13 10:29:11'),(16,4,'f3ace7bfdb4f904c25c87cd1b4b65431.jpeg',1,0,'2017-07-13 10:28:44','2017-07-13 10:28:44'),(17,4,'51205d24c01eeca2a13a0b7e7e2de4ac.jpeg',0,0,'2017-07-13 10:28:44','2017-07-13 10:29:07'),(18,4,'56a30ad928d191d9b9ac37755c3b3945.jpeg',0,0,'2017-07-13 10:28:44','2017-07-13 10:29:02'),(19,4,'71b37f7b936a6e0a67dfca2e352db021.jpeg',0,0,'2017-07-13 10:28:44','2017-07-13 10:28:58'),(20,5,'49dc1caedd05646ea6cb8a3d62e51999.jpeg',1,1,'2017-08-23 09:33:42','2017-08-23 09:33:42'),(21,5,'c5a30d679ca1582fdbf1c211c6bef5e9.jpeg',0,0,'2017-08-23 09:33:43','2017-08-23 09:33:43'),(22,5,'851da8753605edf603abcc782ac4928f.jpeg',0,0,'2017-08-23 09:33:43','2017-08-23 09:33:43'),(23,6,'485ac9ce87a1653db18c6c5ec5fc9745.jpeg',1,1,'2017-08-23 13:55:24','2017-08-23 13:55:24'),(24,6,'2310d4903f44894f3ef5225b6d41b51d.jpeg',0,0,'2017-08-23 13:55:26','2017-08-23 13:55:26'),(25,6,'d62ee72262277a6c7966af2d33d6054f.jpeg',1,0,'2017-08-23 13:55:26','2017-08-23 13:57:09'),(26,6,'e9ef567de6d73375df80fb27f16cba96.jpeg',0,0,'2017-08-23 13:55:26','2017-08-23 13:55:26'),(27,6,'5b87d3094067ded2067604c320e0d84c.jpeg',0,0,'2017-08-23 13:55:26','2017-08-23 13:55:26'),(28,6,'527e0967643f71a21068fe3bab9ba48d.jpeg',1,0,'2017-08-23 13:55:26','2017-08-23 13:56:49'),(29,6,'2dcfd7e813fa04191d7bf908fd3652f7.jpeg',0,0,'2017-08-23 13:55:26','2017-08-23 13:55:26'),(30,6,'f87733bf8fddafa698040029fa49a9e9.jpeg',1,0,'2017-08-23 13:55:26','2017-08-23 13:57:06'),(31,6,'f1ed68a55ee9136efc290db71677db42.jpeg',0,0,'2017-08-23 13:55:26','2017-08-23 13:55:26'),(32,6,'b237ddd57f0e6114547f1f52a95ac261.jpeg',1,0,'2017-08-23 13:55:26','2017-08-23 13:56:56'),(35,8,'6417c00bc2e0eedbb306679632fde225.jpeg',1,1,'2017-09-04 09:11:45','2017-09-04 09:11:45'),(36,8,'17a5c4ac5f4ae2ee6f4181b63a72c345.jpeg',0,0,'2017-09-04 09:11:45','2017-09-04 09:11:45'),(37,8,'21eb7ff48799eabe433dd60db6f4677e.jpeg',1,0,'2017-09-04 09:11:45','2017-09-04 09:13:02'),(38,9,'8e355aa7e9eabc4fb9f8a7235572c005.jpeg',1,1,'2017-09-04 09:24:35','2017-09-04 09:24:35'),(39,9,'be88dbf49580ffaf519e97dc8b7f552a.jpeg',0,0,'2017-09-04 09:24:36','2017-09-04 09:24:36'),(40,9,'e8714ff6aa761d8867e012e89da3f56b.jpeg',0,0,'2017-09-04 09:24:36','2017-09-04 09:24:36'),(41,9,'9cf3c221d0fe6294941581bb96e43f14.jpeg',0,0,'2017-09-04 09:24:36','2017-09-04 09:24:36'),(42,9,'4ffeccca8814799a337fc8df695ad316.jpeg',0,0,'2017-09-04 09:24:36','2017-09-04 09:24:36'),(43,10,'9e4f7dd4b446c8565bf7f9930202944f.jpeg',1,1,'2017-09-11 09:30:54','2017-09-11 09:30:54'),(44,10,'8e1e0a81e13e30ba9fc1ed778e5740bb.jpeg',0,0,'2017-09-11 09:30:55','2017-09-11 09:30:55'),(45,10,'f50cecb0f57df0549b9521488dc8039e.jpeg',1,0,'2017-09-11 09:30:55','2017-09-11 09:36:12'),(46,10,'a8acfb68fdc566059a73dab2dd970592.jpeg',0,0,'2017-09-11 09:30:55','2017-09-11 09:30:55'),(47,10,'187d24b78d6692413f869da0d9fa2e55.jpeg',1,0,'2017-09-11 09:30:55','2017-09-11 09:36:15'),(48,10,'ffa8ac37ad8816083211875d31d34c3b.jpeg',1,0,'2017-09-11 09:30:55','2017-09-11 09:36:19'),(49,11,'e43e67f89f630752fa5cca0ed7992bd2.jpeg',1,1,'2017-09-11 09:34:57','2017-09-11 09:34:57'),(50,11,'01dddf6e2efa9d5f5ae6ad24d5c565c1.jpeg',1,0,'2017-09-11 09:34:57','2018-10-12 00:11:37'),(51,12,'766f9baeb83eeb94821f3cd09d720f97.jpeg',1,1,'2017-09-27 09:10:09','2017-09-27 09:10:09'),(52,12,'2a465ae4901e4b1dd99398fd08a88162.jpeg',0,0,'2017-09-27 09:10:09','2017-09-27 09:13:44'),(53,12,'b8f3582af4040bbabb85da08ccb1f166.jpeg',1,0,'2017-09-27 09:10:09','2018-10-12 00:11:26'),(54,13,'025d5d0f04d1e15207541b94f283edcb.jpeg',1,1,'2018-10-09 20:25:04','2018-10-09 20:25:04'),(55,13,'b1ba3fdcdc9f0c37722aebdf161455b7.jpeg',1,0,'2018-10-09 20:25:04','2018-10-12 00:11:05'),(56,13,'1d1240f0bbf142492c021c8a3c951100.jpeg',1,0,'2018-10-09 20:25:04','2018-10-12 00:11:11'),(57,13,'6192a9f1e956a770396d56ccfe3ba4bf.jpeg',0,0,'2018-10-09 20:25:04','2018-10-09 20:25:04'),(58,13,'79beff72376a73244a21feb54d6d08b0.jpeg',0,0,'2018-10-09 20:25:04','2018-10-09 20:25:04'),(59,14,'27f57b6806047f5fa488f65608f7c69a.jpeg',1,1,'2018-10-11 22:52:25','2018-10-11 22:52:25'),(60,14,'53362060464f843d13a16bf1ec85a91f.jpeg',0,0,'2018-10-11 22:52:26','2018-10-11 22:52:26'),(61,14,'e5f9dc6a6392ba51a472fe1178bd88ed.jpeg',0,0,'2018-10-11 22:52:26','2018-10-11 22:52:26'),(62,14,'d2a95f63cbf33e1a639a18d265e1bd29.jpeg',0,0,'2018-10-11 22:52:26','2018-10-11 22:52:26'),(63,14,'67ed4411202c062814ec63f9eeec5c04.jpeg',0,0,'2018-10-11 22:52:26','2018-10-11 22:52:26'),(64,14,'3d60fadf17b67918778e46bb0fbf9062.jpeg',0,0,'2018-10-11 22:52:26','2018-10-11 22:52:26'),(65,14,'3961d2d5acd233c32214963aec9c1b3d.jpeg',0,0,'2018-10-11 22:52:26','2018-10-11 22:52:26'),(66,15,'4490e307a04d5deaff6747cf2627d157.jpeg',1,1,'2018-11-21 21:56:05','2018-11-21 21:56:05'),(67,15,'ddc382dc57ac37b579c41e293c842fad.jpeg',0,0,'2018-11-21 21:56:07','2018-11-21 21:56:07'),(68,15,'7c60a6eeee7c86ded9513833adc2ed6e.jpeg',0,0,'2018-11-21 21:56:07','2018-11-21 21:56:07'),(69,15,'b334d95478c68ab749e3fd69366f426f.jpeg',0,0,'2018-11-21 21:56:07','2018-11-21 21:56:07'),(70,15,'6112687fbc3f1cf715eb736b5cc13dc4.jpeg',0,0,'2018-11-21 21:56:07','2018-11-21 21:56:07'),(71,15,'aa6d0bdb96dbfdade5ed6078209c27cf.jpeg',0,0,'2018-11-21 21:56:07','2018-11-21 21:56:07'),(72,15,'11bdf8ca9cfb68f01589a380e6f98273.jpeg',0,0,'2018-11-21 21:56:07','2018-11-21 21:56:07'),(73,15,'ab853a3af549841894568fbf4bfcfbe3.jpeg',0,0,'2018-11-21 21:56:07','2018-11-21 21:56:07'),(74,15,'4324f7bcd96c5877cefa4fc8d2962f6f.jpeg',0,0,'2018-11-21 21:56:07','2018-11-21 21:56:07'),(75,15,'91fd3f35bd17014ca99323417d29e27c.jpeg',0,0,'2018-11-21 21:56:07','2018-11-21 21:56:07'),(76,15,'2cf3c5afc2522f208fe48cb7b178b6d3.jpeg',0,0,'2018-11-21 21:56:07','2018-11-21 21:56:07'),(77,16,'1da3cb17f96f57af3e3011e491bb3e2f.jpeg',1,1,'2018-11-21 21:59:50','2018-11-21 21:59:50'),(78,17,'688fa4b84f5c06d570aaf12b4cfbfb79.jpeg',1,1,'2018-11-21 22:04:05','2018-11-21 22:04:05'),(79,17,'9eed6a486e9f89d9db4004cb5f7e2ca1.jpeg',0,0,'2018-11-21 22:04:05','2018-11-21 22:04:05'),(80,17,'a0b3130551af4e200638d86579bdd242.jpeg',0,0,'2018-11-21 22:04:05','2018-11-21 22:04:05'),(81,17,'2cdc6f859c26fa096e033efcd83abc52.jpeg',0,0,'2018-11-21 22:04:05','2018-11-21 22:04:05'),(82,17,'1ccb84666da91b903b6e6f2f56478b81.jpeg',0,0,'2018-11-21 22:04:05','2018-11-21 22:04:05'),(83,17,'2199c155edc69079eb100856e11637cb.jpeg',0,0,'2018-11-21 22:04:05','2018-11-21 22:04:05'),(84,18,'09fe00af163876ce3abf15eb5e98c67d.jpeg',1,1,'2018-11-21 22:08:18','2018-11-21 22:08:18'),(85,18,'b9c30ce7f9c1ef2a278aeae632654681.jpeg',0,0,'2018-11-21 22:08:18','2018-11-21 22:08:18'),(86,18,'34fdfea5d8c351394643a7d66664c809.jpeg',0,0,'2018-11-21 22:08:18','2018-11-21 22:08:18'),(87,18,'e03325fa2efbe69e728718696f27a62b.jpeg',0,0,'2018-11-21 22:08:18','2018-11-21 22:08:18'),(88,19,'1bca91d75311cba0ee586d44ea75f887.jpeg',1,1,'2018-11-21 22:13:21','2018-11-21 22:13:21'),(89,19,'19b27217c36db47a9129f5a675ddd450.jpeg',0,0,'2018-11-21 22:13:21','2018-11-21 22:13:21'),(90,19,'2a2b1c821ca8bde0def553d15b2a5e1b.jpeg',0,0,'2018-11-21 22:13:21','2018-11-21 22:13:21'),(91,20,'baaebe0a40f3e6d8b08f81d553070f6b.jpeg',1,1,'2018-11-21 22:16:51','2018-11-21 22:16:51'),(92,20,'6debb296e22d80eb8c9ec57f2947bf01.jpeg',0,0,'2018-11-21 22:16:52','2018-11-21 22:16:52'),(93,20,'ae8953d03889d31498fb223f0d8dd9f7.jpeg',0,0,'2018-11-21 22:16:52','2018-11-21 22:16:52'),(94,20,'d5ed6754c0f995b4555c012c4569b62e.jpeg',0,0,'2018-11-21 22:16:52','2018-11-21 22:16:52'),(95,21,'9b34ad25bdf356440490570b0b358af6.jpeg',1,1,'2018-11-21 22:20:17','2018-11-21 22:20:17'),(96,21,'66a7ec9f6ad77ae1dc3f69da8a52c0ad.jpeg',0,0,'2018-11-21 22:20:18','2018-11-21 22:20:18'),(97,21,'5f46c7ebdad541130a8d2ee73894b554.jpeg',0,0,'2018-11-21 22:20:18','2018-11-21 22:20:18'),(98,21,'e2181934b663fcdbba0fbdbfe03417e5.jpeg',0,0,'2018-11-21 22:20:18','2018-11-21 22:20:18'),(99,21,'d828092e0df0b18b11e5e6641a5ba59b.jpeg',0,0,'2018-11-21 22:20:18','2018-11-21 22:20:18'),(100,22,'45f89fd42b62d39454581a929710da5b.jpeg',1,1,'2018-11-21 22:26:25','2018-11-21 22:26:25'),(101,22,'3913c59eae704021ba1eddfa953a89f3.jpeg',0,0,'2018-11-21 22:26:26','2018-11-21 22:26:26'),(102,22,'8566d9dd2c95f4609c6c917711d4e761.jpeg',0,0,'2018-11-21 22:26:26','2018-11-21 22:26:26'),(103,22,'22650f335efb27f3733ca8ae2865c713.jpeg',0,0,'2018-11-21 22:26:26','2018-11-21 22:26:26'),(104,22,'1b2a58b50d18e67a66f04b5c6588a7b0.jpeg',0,0,'2018-11-21 22:26:26','2018-11-21 22:26:26'),(105,22,'8275a3c277537b14fce248cac41aa83a.jpeg',0,0,'2018-11-21 22:26:26','2018-11-21 22:26:26'),(106,22,'bba4e702ee6326bb589da35d819544f3.jpeg',0,0,'2018-11-21 22:26:26','2018-11-21 22:26:26'),(107,23,'fd8b71a524b9fffbe01cc2fab24c039d.jpeg',1,1,'2018-11-21 22:31:14','2018-11-21 22:31:14'),(108,23,'4785af762d538a2254fd1208772e02c1.jpeg',0,0,'2018-11-21 22:31:14','2018-11-21 22:31:14'),(109,23,'a90b5cf0a2ebab3d33b4c72303c63375.jpeg',0,0,'2018-11-21 22:31:14','2018-11-21 22:31:14'),(110,24,'06d84061cf4cde62fd2e726e805169ec.jpeg',1,1,'2018-11-21 22:34:41','2018-11-21 22:34:41'),(111,24,'32da02e68e448884ab07609fbea370f0.jpeg',0,0,'2018-11-21 22:34:42','2018-11-21 22:34:42'),(112,24,'9b88574a742565cf5758bdae6da4916f.jpeg',0,0,'2018-11-21 22:34:42','2018-11-21 22:34:42'),(113,24,'6b6ac0bfb0b252d86ee6dea650d7ac9c.jpeg',0,0,'2018-11-21 22:34:42','2018-11-21 22:34:42'),(114,24,'ea0745b8ebbb3e34c30ae36c5a9dfa1c.jpeg',0,0,'2018-11-21 22:34:42','2018-11-21 22:34:42'),(115,24,'0507892aa845e9b764ef450e18a6703b.jpeg',0,0,'2018-11-21 22:34:42','2018-11-21 22:34:42'),(116,25,'a660ca8a055f18a3337a13efb9ac2b19.jpeg',1,1,'2018-11-21 22:37:16','2018-11-21 22:37:16'),(117,25,'87f1c958223de44b738fb9f6ae27e4ae.jpeg',0,0,'2018-11-21 22:37:16','2018-11-21 22:37:16'),(118,25,'2d8ea06a544344f81535dafb3418a07b.jpeg',0,0,'2018-11-21 22:37:16','2018-11-21 22:37:16'),(119,26,'144215c9fd2054cc301490bea230c7c9.jpeg',1,1,'2018-11-21 22:43:56','2018-11-21 22:43:56'),(120,26,'aeea5868d5d14c4250ec5411514f582a.jpeg',0,0,'2018-11-21 22:43:57','2018-11-21 22:43:57'),(121,26,'877944943dbcfa26f6cd69941f4ee7e1.jpeg',0,0,'2018-11-21 22:43:57','2018-11-21 22:43:57'),(122,27,'ac15d2d5fe4744e90e1cdf8651b003f7.jpeg',1,1,'2018-11-21 22:56:40','2018-11-21 22:56:40'),(123,27,'21b1daf569152dd0de5fb4e1d65dd51d.jpeg',0,0,'2018-11-21 22:56:40','2018-11-21 22:56:40'),(124,27,'1c7c08901b19d2453061d9240e0ae606.jpeg',0,0,'2018-11-21 22:56:40','2018-11-21 22:56:40'),(125,27,'2279df9c87ac8c14a5ba7c34b3e54448.jpeg',0,0,'2018-11-21 22:56:40','2018-11-21 22:56:40'),(126,27,'5dd5bf546748baf844135d0360763d8d.jpeg',0,0,'2018-11-21 22:56:40','2018-11-21 22:56:40'),(127,28,'d768bd4f636bb5b015a1d2d45f5be342.jpeg',1,1,'2018-11-21 22:59:20','2018-11-21 22:59:20'),(128,28,'ef1e9d3eb4f833487219d83a10b0e3b9.jpeg',0,0,'2018-11-21 22:59:20','2018-11-21 22:59:20'),(129,29,'def86811181b6afec8e2c84aa46deda8.jpeg',1,1,'2018-11-21 23:02:10','2018-11-21 23:02:10'),(130,29,'8ba6203f764ca7639b265f79bb7dac7c.jpeg',0,0,'2018-11-21 23:02:11','2018-11-21 23:02:11'),(131,29,'24c6b8b6c759fd01e2e216bed030de82.jpeg',0,0,'2018-11-21 23:02:11','2018-11-21 23:02:11'),(132,29,'11f2bb6f6f37f614d82ea2b047c4946b.jpeg',0,0,'2018-11-21 23:02:11','2018-11-21 23:02:11'),(133,30,'9af38028a3382b4ea8956a4a4680d80b.jpeg',1,1,'2018-11-21 23:04:31','2018-11-21 23:04:31'),(134,30,'74f177e4ee2b8688fbe80147c6b59a20.jpeg',0,0,'2018-11-21 23:04:31','2018-11-21 23:04:31'),(135,31,'e1ac4d79263793cc2216011826085137.jpeg',1,1,'2018-11-21 23:06:34','2018-11-21 23:06:34'),(136,31,'4f2b6ed2e2637e988b7a66c234cf703f.jpeg',0,0,'2018-11-21 23:06:35','2018-11-21 23:06:35'),(137,31,'fdae1145ec078c27cd8c72304e4620c1.jpeg',0,0,'2018-11-21 23:06:35','2018-11-21 23:06:35'),(138,31,'1027be33bc8d7aac2b7a2547df08823a.jpeg',0,0,'2018-11-21 23:06:35','2018-11-21 23:06:35'),(139,31,'45206ad2d7e5fd94736529a88a3d3ac5.jpeg',0,0,'2018-11-21 23:06:35','2018-11-21 23:06:35'),(140,31,'8960f75e56e1844b5d6992994472fbf1.jpeg',0,0,'2018-11-21 23:06:35','2018-11-21 23:06:35'),(141,32,'d27b382cec75d5340a38fa933c305ff4.jpeg',1,1,'2018-11-21 23:10:57','2018-11-21 23:10:57'),(142,32,'b199887759ed13aa78f92f7cfae01f69.jpeg',0,0,'2018-11-21 23:10:57','2018-11-21 23:10:57'),(143,33,'d057ff95a17bbc547d1595fe4f2bba50.jpeg',1,1,'2018-11-22 13:00:51','2018-11-22 13:00:51'),(144,33,'e9566337051754017d89ea88370927b6.jpeg',0,0,'2018-11-22 13:00:51','2018-11-22 13:00:51'),(145,34,'6100286822af13273e47bc7dd8b9c7ef.jpeg',1,1,'2018-11-22 13:03:50','2018-11-22 13:03:50'),(146,34,'36dc96440375db7e7b533159161fe6a4.jpeg',0,0,'2018-11-22 13:03:50','2018-11-22 13:03:50'),(147,34,'aa645ee521852d9a1e828e75258b69d9.jpeg',0,0,'2018-11-22 13:03:50','2018-11-22 13:03:50'),(148,35,'baa855f38ec66025823b77464be4defe.jpeg',1,1,'2018-11-22 13:07:27','2018-11-22 13:07:27'),(149,35,'32239709d10b930ff9661eac4775646e.jpeg',0,0,'2018-11-22 13:07:28','2018-11-22 13:07:28'),(150,35,'5ef1f2f3ad101fc20bb112fd03d365f2.jpeg',0,0,'2018-11-22 13:07:28','2018-11-22 13:07:28'),(151,35,'b4ee517e336e31d1484f2eb22d1ccaba.jpeg',0,0,'2018-11-22 13:07:28','2018-11-22 13:07:28'),(152,35,'3404713e1b06f16aa64f1925499d2d73.jpeg',0,0,'2018-11-22 13:07:28','2018-11-22 13:07:28'),(153,35,'2a1c3e74cb182abf651a7266b83e12e8.jpeg',0,0,'2018-11-22 13:07:28','2018-11-22 13:07:28'),(154,36,'21b8f67fbb9fa97fdb539f8d85fa97f5.jpeg',1,1,'2018-11-22 13:16:23','2018-11-22 13:16:23'),(155,36,'378aa77a6f85e5b462e7c97c7a02ad2c.jpeg',0,0,'2018-11-22 13:16:23','2018-11-22 13:16:23'),(156,37,'3bb46913fc738a0eadea17218bf79ea4.jpeg',1,1,'2018-11-22 13:20:55','2018-11-22 13:20:55'),(157,37,'51974a569d2671c228708c8e26c13cbb.jpeg',0,0,'2018-11-22 13:20:55','2018-11-22 13:20:55'),(158,37,'ff6f0886d4c0245177865d78b1818d3d.jpeg',0,0,'2018-11-22 13:20:55','2018-11-22 13:20:55'),(159,37,'40310f76435cabc609eecb6d2c9f3bdf.jpeg',0,0,'2018-11-22 13:20:55','2018-11-22 13:20:55'),(160,38,'2fa7ead31b5cb7bee6b17e4900d61bba.jpeg',1,1,'2018-11-22 13:23:27','2018-11-22 13:23:27'),(161,38,'fcc45584044f550fbd1f9e4a88cb73de.jpeg',0,0,'2018-11-22 13:23:28','2018-11-22 13:23:28'),(162,38,'1b59a6d32514fd090744d611fdba9ae3.jpeg',0,0,'2018-11-22 13:23:28','2018-11-22 13:23:28'),(163,38,'48d4dd35812be858f5922ae6bc4411ae.jpeg',0,0,'2018-11-22 13:23:28','2018-11-22 13:23:28'),(164,38,'c7697795cb2624480c43da6f19734b63.jpeg',0,0,'2018-11-22 13:23:28','2018-11-22 13:23:28'),(165,38,'8ee3674ae8ecc34be0266c3b2273e77b.jpeg',0,0,'2018-11-22 13:23:28','2018-11-22 13:23:28'),(166,38,'433c044fc534335302acaf0f36a1ad6f.jpeg',0,0,'2018-11-22 13:23:28','2018-11-22 13:23:28'),(167,38,'d473a80f08a5b4eb555b1d02f5025507.jpeg',0,0,'2018-11-22 13:23:28','2018-11-22 13:23:28'),(168,39,'0f0192fb5e5e068d838e9e8dcdc6850d.jpeg',1,1,'2018-11-22 13:26:41','2018-11-22 13:26:41'),(169,39,'45964ce9bdc85b550ae31f5c38b4d423.jpeg',0,0,'2018-11-22 13:26:41','2018-11-22 13:26:41'),(170,39,'9d5cb5b13154717418b030e9694b03bf.jpeg',0,0,'2018-11-22 13:26:41','2018-11-22 13:26:41'),(171,39,'a35d1a91e48819756f435aaf7e2ab853.jpeg',0,0,'2018-11-22 13:26:41','2018-11-22 13:26:41'),(172,40,'bf6304e0c29aaa014304e384be788099.jpeg',1,1,'2018-11-22 13:34:23','2018-11-22 13:34:23'),(173,40,'440805e811db674d240f35fb7c99e2a0.jpeg',0,0,'2018-11-22 13:34:24','2018-11-22 13:34:24'),(174,40,'32b36b1bd1f05e1906389e7a5a046a3f.jpeg',0,0,'2018-11-22 13:34:24','2018-11-22 13:34:24'),(175,40,'34de4378f2decc0d626d3fd6d93b559d.jpeg',0,0,'2018-11-22 13:34:24','2018-11-22 13:34:24'),(176,40,'13c9fe566185c43367ae8d777425b42f.jpeg',0,0,'2018-11-22 13:34:24','2018-11-22 13:34:24'),(177,40,'c1ca65c0c49c4896c1318c7a8e1a5a98.jpeg',0,0,'2018-11-22 13:34:24','2018-11-22 13:34:24'),(178,40,'3dd74c3d49cb3e4d74df50a03755ae89.jpeg',0,0,'2018-11-22 13:34:24','2018-11-22 13:34:24'),(179,41,'4ce4777c68ec0471c76a7ab8a4d01187.jpeg',1,1,'2018-11-22 13:57:36','2018-11-22 13:57:36'),(180,41,'4a79c43287134ed629242e119d8cf454.jpeg',0,0,'2018-11-22 13:57:36','2018-11-22 13:57:36'),(181,42,'1fc1819270517124a49f342ba7f4c58b.jpeg',1,1,'2018-11-22 14:05:06','2018-11-22 14:05:06'),(182,42,'31c8e5277a346ba6216832e1cf91ae98.jpeg',0,0,'2018-11-22 14:05:07','2018-11-22 14:05:07'),(183,42,'8804bc654377c52c11ac8cfe6d614025.jpeg',0,0,'2018-11-22 14:05:07','2018-11-22 14:05:07'),(184,42,'744fb391a16dfe9dc327765a5e0e00b0.jpeg',0,0,'2018-11-22 14:05:07','2018-11-22 14:05:07'),(185,42,'856dcbd0d7690a2432ccc275aedaa83b.jpeg',0,0,'2018-11-22 14:05:07','2018-11-22 14:05:07'),(186,42,'7098f45f1b670cf2d28c680f0b158758.jpeg',0,0,'2018-11-22 14:05:07','2018-11-22 14:05:07'),(187,42,'45aaaf017a5eed697207906980e0775b.jpeg',0,0,'2018-11-22 14:05:07','2018-11-22 14:05:07'),(188,43,'206ffcc9bb2ff48eecc4f9ee826a3966.jpeg',1,1,'2019-01-13 12:34:32','2019-01-13 12:34:32'),(189,43,'e8e428ecfb23d7e1a0a284aa3da07b20.jpeg',0,0,'2019-01-13 12:34:32','2019-01-13 12:34:32'),(190,44,'20333af891d5f872dd4e049fe5a52734.jpeg',1,1,'2019-01-13 12:42:45','2019-01-13 12:42:45'),(191,44,'8e8325ec3314d719b49fc51e6bc66166.jpeg',0,0,'2019-01-13 12:42:46','2019-01-13 12:42:46'),(192,44,'788145513f290c9a798d3f7f82e5abf2.jpeg',0,0,'2019-01-13 12:42:46','2019-01-13 12:42:46'),(193,44,'15e546e4238b5debb68c996912b46d6a.jpeg',0,0,'2019-01-13 12:42:46','2019-01-13 12:42:46'),(194,45,'e486d76e6419b157fc2ea21346ebcb04.jpeg',1,1,'2019-01-13 12:48:39','2019-01-13 12:48:39'),(195,45,'c07240268390aa57b0a50e71f5352fb9.jpeg',0,0,'2019-01-13 12:48:40','2019-01-13 12:48:40'),(196,45,'b44597c91c5852dbac1fcb75f882fdca.jpeg',0,0,'2019-01-13 12:48:40','2019-01-13 12:48:40'),(197,45,'6b226178d93a73ad6354a3788d9e992c.jpeg',0,0,'2019-01-13 12:48:40','2019-01-13 12:48:40'),(198,46,'62902e425001b68c077e5d5d2d306441.jpeg',1,1,'2019-01-13 12:54:13','2019-01-13 12:54:13'),(199,46,'679c633be115b2af110655a764f11ad9.jpeg',0,0,'2019-01-13 12:54:14','2019-01-13 12:54:14'),(200,46,'2309a4ce59da74537e031e027ee0a14d.jpeg',0,0,'2019-01-13 12:54:14','2019-01-13 12:54:14'),(201,46,'5d9f072ba9aa42bf85c1c3e7612f15ff.jpeg',0,0,'2019-01-13 12:54:14','2019-01-13 12:54:14'),(202,47,'eef5d50553a7af772282ce4a7c52bf81.jpeg',1,1,'2019-01-13 12:57:48','2019-01-13 12:57:48'),(203,47,'3a527d22daeca0852f299d6a7382c268.jpeg',0,0,'2019-01-13 12:57:48','2019-01-13 12:57:48'),(204,48,'23b58f1d8fcfb1667286581b0d95a64d.jpeg',1,1,'2019-01-13 13:01:52','2019-01-13 13:01:52'),(205,48,'c56619cb05483a12e1fd8ebf867edcb6.jpeg',0,0,'2019-01-13 13:01:52','2019-01-13 13:01:52'),(206,48,'48a7c59b43001ac85210457b9b30f1b8.jpeg',0,0,'2019-01-13 13:01:52','2019-01-13 13:01:52'),(207,49,'0d8e533d548fb8a5d6e73f9db31832de.jpeg',1,1,'2019-01-13 13:06:03','2019-01-13 13:06:03'),(208,49,'38227bde3c7a2703cee018adf2748c21.jpeg',0,0,'2019-01-13 13:06:03','2019-01-13 13:06:03'),(209,49,'e50cb976015c9992d375c28fbc0de9b1.jpeg',0,0,'2019-01-13 13:06:03','2019-01-13 13:06:03'),(210,50,'5c978a7f0d141fa7659f3e3c71c49a2e.jpeg',1,1,'2019-01-13 13:11:43','2019-01-13 13:11:43'),(211,50,'6464e6215f2f98ed0302a46a30837744.jpeg',0,0,'2019-01-13 13:11:43','2019-01-13 13:11:43'),(212,50,'fd72ce1b01c21b4739171fe361099be5.jpeg',0,0,'2019-01-13 13:11:43','2019-01-13 13:11:43'),(213,50,'a297964f8ac0294451ae7a6085007d9d.jpeg',0,0,'2019-01-13 13:11:43','2019-01-13 13:11:43'),(214,50,'ce795824dfd0b6704be02e93471c0084.jpeg',0,0,'2019-01-13 13:11:43','2019-01-13 13:11:43'),(215,51,'05c91262b163d41b18a503accbb002d7.jpeg',1,1,'2019-01-13 13:14:36','2019-01-13 13:14:36'),(216,52,'fb33299b12c80871006c1801469a6f53.jpeg',1,1,'2019-01-13 13:17:10','2019-01-13 13:17:10'),(217,52,'c37ea14659bb91ef49ebf5901e1df526.jpeg',0,0,'2019-01-13 13:17:10','2019-01-13 13:17:10'),(218,52,'4dfdc83c2e8ab65c95a36421d75903e2.jpeg',0,0,'2019-01-13 13:17:10','2019-01-13 13:17:10'),(219,53,'1ae18fb29acf4acd3523c9364b47f144.jpeg',1,1,'2019-01-20 20:30:31','2019-01-20 20:30:31'),(220,53,'735dd64a6bbec82c600dbaf2aa4bce2e.jpeg',0,0,'2019-01-20 20:30:31','2019-01-20 20:30:31'),(221,53,'d3116d4ad5cca6c4cacfd8975637f5bc.jpeg',0,0,'2019-01-20 20:30:31','2019-01-20 20:30:31'),(222,54,'01fce40a61c156c3bac3d1ee552189d3.jpeg',1,1,'2019-01-20 20:35:27','2019-01-20 20:35:27'),(223,54,'64b1be6716b5f3d48a1e67571c0e35f1.jpeg',0,0,'2019-01-20 20:35:29','2019-01-20 20:35:29'),(224,54,'356104b5dfa6f963d2f73b9f0c396ec4.jpeg',0,0,'2019-01-20 20:35:29','2019-01-20 20:35:29'),(225,54,'668506f5860276dd2375a875ce277faa.jpeg',0,0,'2019-01-20 20:35:29','2019-01-20 20:35:29'),(226,54,'6fe977387395f6200c08112b76aa7baa.jpeg',0,0,'2019-01-20 20:35:29','2019-01-20 20:35:29'),(227,54,'db1dd89314e2a4fe6f62f1a15c8cebf9.jpeg',0,0,'2019-01-20 20:35:29','2019-01-20 20:35:29'),(228,54,'45eacee04ee3c9238d2cba62103f275a.jpeg',0,0,'2019-01-20 20:35:29','2019-01-20 20:35:29'),(229,54,'0761c5a3514b8ee3ec8dd4692839326d.jpeg',0,0,'2019-01-20 20:35:29','2019-01-20 20:35:29'),(230,55,'b2478b081eb4680de18d0415226a40a6.jpeg',1,1,'2019-01-20 20:38:23','2019-01-20 20:38:23'),(231,55,'52f53b877b26ca4fa63d5d03a1ed0d54.jpeg',0,0,'2019-01-20 20:38:23','2019-01-20 20:38:23'),(232,56,'3536029e49d054618af083801b1fe4dd.jpeg',1,1,'2019-01-20 20:49:53','2019-01-20 20:49:53'),(233,56,'9ef3b2e6ccf57a5e946ec63ce5c5d79c.jpeg',0,0,'2019-01-20 20:49:53','2019-01-20 20:49:53'),(234,56,'acfabb65a72ec4dc55015e705ec64845.jpeg',0,0,'2019-01-20 20:49:53','2019-01-20 20:49:53'),(235,57,'6da9025c1cec9dd8219d106e56a3ce5f.jpeg',1,1,'2019-01-21 08:14:52','2019-01-21 08:14:52'),(236,57,'1c0242d7bda8bf75fe74d5b14dbdce91.jpeg',0,0,'2019-01-21 08:14:53','2019-01-21 08:14:53'),(237,58,'79d32fb5557381d2f89c68667d4857e0.jpeg',1,1,'2019-01-21 08:17:39','2019-01-21 08:17:39'),(238,58,'4177d1fcdc4f277aaef7a44b24d54c1c.jpeg',0,0,'2019-01-21 08:17:40','2019-01-21 08:17:40'),(239,59,'d92d25989f81eeceac2ec5fff6e70668.jpeg',1,1,'2019-01-21 08:21:36','2019-01-21 08:21:36'),(240,59,'c2d2b9eabfd2a0e355531f66fe7ed9e0.jpeg',0,0,'2019-01-21 08:21:36','2019-01-21 08:21:36'),(241,60,'e02297e93d227f8991d60356e45a4ff9.jpeg',1,1,'2019-01-21 08:31:24','2019-01-21 08:31:24'),(242,60,'0e4e09c85b6580b5e3203c394d611129.jpeg',0,0,'2019-01-21 08:31:24','2019-01-21 08:31:24'),(243,61,'539d553a2a2954fe606b8f94352424c4.jpeg',1,1,'2019-01-21 08:34:16','2019-01-21 08:34:16'),(244,61,'ef7d5eaa6a703e95461e6af65668350e.jpeg',0,0,'2019-01-21 08:34:17','2019-01-21 08:34:17'),(245,62,'0bce259dea0a863ffab6c0671f8a6e81.jpeg',1,1,'2019-01-21 08:38:54','2019-01-21 08:38:54'),(246,62,'fa29ccf85b8f1a74da92500ecadd4aeb.jpeg',0,0,'2019-01-21 08:38:54','2019-01-21 08:38:54'),(247,63,'63a375cdbfc3bbeae1483b68cbdc556a.jpeg',1,1,'2019-01-21 08:42:28','2019-01-21 08:42:28'),(248,63,'0f3a019d357d100654b2bcf759f1304d.jpeg',0,0,'2019-01-21 08:42:29','2019-01-21 08:42:29'),(249,63,'724015682eb623382451bf926c23f0e6.jpeg',0,0,'2019-01-21 08:42:29','2019-01-21 08:42:29'),(250,64,'686b2cd1f2cf8dbf4bd463dcc8ad817b.jpeg',1,1,'2019-01-21 09:58:14','2019-01-21 09:58:14'),(251,64,'2dd69709103007403a97c4894fc4f38c.jpeg',0,0,'2019-01-21 09:58:14','2019-01-21 09:58:14'),(252,65,'f888b1019bde89ae3adb0ebd6f369602.jpeg',1,1,'2019-01-21 10:00:32','2019-01-21 10:00:32'),(253,65,'a5b281429c8871d0c6b4398a873c882d.jpeg',0,0,'2019-01-21 10:00:33','2019-01-21 10:00:33'),(254,65,'596e319b536dd8e26da64e82431404cd.jpeg',0,0,'2019-01-21 10:00:33','2019-01-21 10:00:33'),(255,65,'2d6e3129d000468820cccefdbc04bca2.jpeg',0,0,'2019-01-21 10:00:33','2019-01-21 10:00:33'),(256,66,'6302e70619c6f26998803e33508e1c6e.jpeg',1,1,'2019-01-21 10:17:00','2019-01-21 10:17:00'),(257,66,'3bc43ff65a1adaa382bf115a5143099c.jpeg',0,0,'2019-01-21 10:17:00','2019-01-21 10:17:00'),(258,67,'ff7338307c4592ad9cbe3cc28d5295aa.jpeg',1,1,'2019-01-21 10:29:39','2019-01-21 10:29:39'),(259,67,'a210008c7787ac6e7b16957a88498340.jpeg',0,0,'2019-01-21 10:29:40','2019-01-21 10:29:40'),(260,67,'2fa5b875d6d6a90855dd0b3f083c84f8.jpeg',0,0,'2019-01-21 10:29:40','2019-01-21 10:29:40'),(261,67,'09a55feaa108d7e4b5a1a769004fa268.jpeg',0,0,'2019-01-21 10:29:40','2019-01-21 10:29:40'),(262,67,'15163a4b5fbcb7e6a0ac6d8505ea6ece.jpeg',0,0,'2019-01-21 10:29:40','2019-01-21 10:29:40');
/*!40000 ALTER TABLE `user_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_meta`
--

DROP TABLE IF EXISTS `user_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_meta` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `about_me` text COLLATE utf8_unicode_ci,
  `dob` date DEFAULT NULL,
  `gender` tinyint(4) DEFAULT NULL,
  `relationship_status` tinyint(4) DEFAULT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `height` tinyint(4) DEFAULT NULL,
  `body_type` tinyint(4) DEFAULT NULL,
  `eye_color` tinyint(4) DEFAULT NULL,
  `hair_color` tinyint(4) DEFAULT NULL,
  `smoking_habits` tinyint(4) DEFAULT NULL,
  `drinking_habits` tinyint(4) DEFAULT NULL,
  `country` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `looking_for_gender` tinyint(4) DEFAULT NULL,
  `lat` double(20,15) DEFAULT NULL,
  `lng` double(20,15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_meta_user_id_foreign` (`user_id`),
  CONSTRAINT `user_meta_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_meta`
--

LOCK TABLES `user_meta` WRITE;
/*!40000 ALTER TABLE `user_meta` DISABLE KEYS */;
INSERT INTO `user_meta` VALUES (1,1,NULL,NULL,1,NULL,'Amsterdam',NULL,NULL,NULL,NULL,NULL,NULL,'nl','2017-07-09 16:36:56','2017-07-09 16:36:56',NULL,52.367984300000000,4.903561399999944),(3,3,'Ik werk in de Entertainment en toerisme (leisure) branche en geniet hier met volle teugen van.. Over 2 jaar ga ik voor docente studeren en is mijn doel om in de toerisme branche les te gaan geven. Met stages ben ik al zo goed als over heel de wereld geweest, maar over een paar jaar wil ik aan de andere kant staan dus en andere mensen opleiden in de toerisme wereld. Het is zo anders vergeleken met andere beroepen. Zoveel vrijheid en blijheid, alleen wel regelmatig van huis, waardoor ik dus helaas nog alleen ben.. Heb trouwens gewoon mijn eigen stekkie in Amsterdam-Noord.','1993-10-06',2,1,'Amsterdam',4,2,2,5,2,2,'nl','2017-07-13 10:06:53','2017-07-13 10:12:36',NULL,52.367984300000000,4.903561399999944),(4,4,'Een leuke, levendige, spontane en vooral geile jongedame.. Studeren doe ik al niet meer, na mijn HBO diploma kon ik terecht bij een bedrijf waar ik mijn afstudeerstage bij heb gedaan. Mag mezelf officieel Fysiotherapeute noemen. Heb ook nog wel wat lekkere massagekneepjes van het vak geleerd, mag ik je die eens laten ervaren? ','1997-06-10',2,2,'Amsterdam',5,1,3,4,2,1,'nl','2017-07-13 10:28:43','2017-07-13 10:28:43',NULL,52.367984300000000,4.903561399999944),(5,5,'Had nooit gedacht dat ik me op zo\'n site zou inschrijven, maar ja je bent jong en je wilt wat hè haha. Er staan nog wat dingen op mijn verlanglijstje, waaronder een heerlijke date met een vrouw en een geile avond met een (wat) oudere man. Wacht even, ik wil niets missen hoor, dus denk je dat je wat voor mij bent en je bent geen mooie dame of oudere man, tja, nooit geschoten altijd mis! Natuurlijk ben ik niet vies van mannen van mijn leeftijd. Volgens mij gaat het allemaal om de magische klik hebben samen, dan komt de rest vanzelf goed toch? ','1993-03-18',2,1,'Amsterdam',5,3,3,6,3,1,'nl','2017-08-23 09:33:42','2017-08-23 09:33:42',NULL,52.367984300000000,4.903561399999944),(6,6,'Ja ook ik als model ben op zoek naar een boyfriend, heb me net ingeschreven en ik hoop hier wel een beetje succes te hebben. Je bent knap en dat jij nog geen vriendje hebt dat versta ik niet, dat is wat ik te horen krijg van mijn familie en vrienden. Helemaal niet leuk, ben ik wel te kieskeurig dan? Nee ik vind gewoon dat het moet klikken en dat er wederzijds respect is, wat vind jij daar van? Hopelijk word het met jou wel wat ;) Ik heb er zin om mijn zoektocht te starten','1992-06-19',2,1,'Amsterdam',4,1,2,4,3,3,'nl','2017-08-23 13:55:24','2017-08-23 13:56:40',NULL,52.367984300000000,4.903561399999944),(8,8,'Ben het met mezelf spelen wel een beetje zat aan het raken, kan zulke rare geile gedachtes hebben over normale situaties dat ik ook wel denk dat ik aan geile rollenspellen toe ben. Als jij daar niet vies van bent of gewoon net zoals ik er eens mee wil experimenteren, laat dan gerust een berichtje achter. Ik sta voor een hoop dingen open dus ik denk dat we er wel uit moeten komen. ','1986-10-08',2,1,'Amsterdam',5,2,2,5,1,1,'nl','2017-09-04 09:11:45','2017-09-04 09:11:45',NULL,52.367984300000000,4.903561399999944),(9,9,'Ik ben altijd al een beetje van het ouderwetse geweest, van huis uit meegekregen.. Seks na het trouwen, een langdurige relatie aangaan noem het maar op.. Heb me hier niet aan kunnen houden haha. Aangezien ik hierdoor vroeger voor m\'n gevoel veel gemist heb wil ik dat goed maken door het in te halen. Na wat positieve geluiden in de omgeving ben ik hier beland. Wil jij vooral lekker genieten samen, of heb je spannende uitdagingen voor me? Liefs, Sandra','1992-03-16',2,1,'Amsterdam',4,2,2,6,3,2,'nl','2017-09-04 09:24:34','2017-09-04 09:24:34',NULL,52.367984300000000,4.903561399999944),(10,10,'Ik wil bewijzen dat een pondje meer bij een vrouw helemaal niet erg hoeft te zijn.. Daarnaast weet ik alles van lekker koken en bourgondisch genieten, maar we zijn hier om elkaar eerst tussen de lakens uit te testen, ook daar weet ik je wel te overtuigen denk ik! Wanneer mag ik je komen overtuigen?','1984-12-23',2,1,'Amsterdam',5,5,1,5,2,1,'nl','2017-09-11 09:30:54','2017-09-11 09:36:05',NULL,52.367984300000000,4.903561399999944),(11,11,'Een beetje gek, een beetje prettig gestoord. Maar ik ben oh zo lief eerlijk en oprecht.  Ben jij degene waar ik naar zoek?','1991-04-14',2,NULL,'Amsterdam',4,5,2,NULL,3,3,'nl','2017-09-11 09:34:57','2018-06-28 18:42:34',1,52.367984300000000,4.903561399999944),(12,12,'Heey, ik ben best een gezellige vrouw, ik hou van mensen om mij heen en zo nu en dan ben ik ook graag alleen of samen met een man lekker op de bank. Mijn vriendinnen weten niet dat ik mij hier heb aangemeld, omdat die niet zo’n voorstanders zijn van “online daten”. Ik heb echt geen moeite met online daten en ik denk ook dat dat een beetje de tijd is waar we nu in leven toch, denk jij van niet?? Hoe dan ook lijkt het mij gewoon heel leuk om hier snel een leuke man te leren kennen.\r\n\r\nZie jij een lekker sex afspraakje zonder verplichtingen binnenkort wel zitten, laat het me dan weten. Ik denk dat dat de ideale kennismaking is. Hoe zou die avond voor jou beginnen..?','1987-07-31',2,1,'Amsterdam',5,3,2,5,1,2,'nl','2017-09-27 09:10:09','2018-08-27 09:25:31',1,52.367984300000000,4.903561399999944),(13,13,'Jeannette is mijn naam. Heb me altijd anders voor moeten doen. Mensen hadden bepaalde verwachtingen van mij. Maar nu niet meer. Los van die oneerlijke man, los van mijn verplichtingen. Het is nu Jeannette tijd! En wat rijmt er op Jeannette? Heel goed, jouw bed!','1978-11-23',2,1,'Amsterdam',4,3,1,1,2,2,'nl','2018-10-09 20:25:03','2018-10-09 20:25:03',1,52.367984300000000,4.903561399999944),(14,14,'Vaak kom ik van die rijke playboys tegen, maar vaak zijn dat allemaal praatjes. En praatjes vullen geen... \r\nIk ben de mannen zat die ik ontmoet in de bars van hotels, daar ging ik meestal op stap.\r\nSerieus maar ook weer niet. Betrouwbaar, eerlijk en verzorgd. Een gezellige man die zijn mannetje staat en het minimaal toch wel drie keer per nacht volhoud. Ben jij dat?','1994-03-02',2,1,'Amsterdam',4,1,3,4,3,1,'nl','2018-10-11 22:52:25','2018-10-11 22:52:25',1,52.367984300000000,4.903561399999944),(15,15,'Ik ben een hardwerkende huisvrouw terwijl mijn man vaak weg is van huis.. Dan hebben we het niet over uren maar over lange dagen! Voor deze eenzame dagen ben ik op zoek naar leuke man die de stilte in mij wilt verdrijven. Ik zal willen beginnen met een leuke date.. Wat denk jij ervan? ','1982-01-19',2,2,'Amsterdam',5,5,2,5,1,2,'nl','2018-11-21 21:56:05','2018-11-21 21:56:05',1,52.367984300000000,4.903561399999944),(16,16,'Als secretaresse is het af en toe een eenzaam leven zonder een man. Ik heb dan wel een lieve kat als huisdier maar dat geeft geen voldoening. Ik hoop hier een serieuze man te vinden voor een date of misschien wel meer! Stuur me gewoon een berichtje want zelf zal ik dit niet heel snel doen.\r\n','1998-01-01',2,1,'Amsterdam',4,1,1,5,3,2,'nl','2018-11-21 21:59:50','2018-11-21 21:59:50',1,52.367984300000000,4.903561399999944),(17,17,'Wat je niet veel zal zien of horen is dat een vrouw in de bouw werkt als timmervrouw.. Echter doe ik dit wel en met een hele hoop plezier! Ik ben een dominante vrouw die weet wat ze wilt. Een klein beetje perfectionistisch kan je mij ook wel noemen en dus zoek ik een vent die zijn mannetje staat! Maar waar zou ik die nou kunnen vinden deze tijd? Xx Ab\r\n','1978-08-24',2,1,'Amsterdam',6,4,3,1,3,3,'nl','2018-11-21 22:04:04','2018-11-21 22:41:18',1,52.367984300000000,4.903561399999944),(18,18,'Heb jij ook zo zin in een lekker potje plezier , spanning en genot? Ben je al een langere tijd op zoek naar de juiste dame ervoor? Dan meld ik mij heel graag aan om goedgekeurd te worden.. Kan jij ontvangen of durf je lekker stout buiten te genieten? Wat weerhoudt je dan nog mij een bericht te sturen? ;) xx\r\n','1990-11-14',2,2,'Amsterdam',4,4,2,5,2,1,'nl','2018-11-21 22:08:17','2018-11-21 22:08:17',1,52.367984300000000,4.903561399999944),(19,19,'Ik ben een echt najaars kind en hou van het heerlijke knusse thuis.. Nu zoek ik alleen nog een vrijwilliger die het aandurft in mijn warme huisje met een knusse bank en misschien wel bed? Ligt helemaal aan jou en hoe snel je kan afspreken ik hou namelijk niet van wachten.. xxx\r\n','1989-04-08',2,1,'Amsterdam',5,2,3,4,2,2,'nl','2018-11-21 22:13:21','2018-11-21 22:13:21',2,52.367984300000000,4.903561399999944),(20,20,'Ik ben een halfbloedje die graag dingen doet die mijn ouders niet goedkeuren.. Ik weet niet wat het is ik geil er gewoon op wanneer er dingen gebeuren die niet mogen. Nu nog iemand die dit samen met mij wilt doen.. Lekker geil met speeltjes en hele lange heftige seks.. Iemand? \r\n','1979-06-11',2,1,'Amsterdam',5,4,3,1,3,2,'nl','2018-11-21 22:16:51','2018-11-21 22:16:51',1,52.367984300000000,4.903561399999944),(21,21,'Studente met een eigen studentenwoning ;) Ik hoef geen jonge gasten onder de 30.. Ik vind het namelijk heerlijk om goed aangepakt te worden door een sugardaddy. Je hoeft niet singel te zijn want buiten stoute seks zoek ik nergens anders naar! Durf jij t aan? Dad? ;)\r\n','1996-12-30',2,1,'Amsterdam',4,1,1,4,3,1,'nl','2018-11-21 22:20:17','2018-11-21 22:20:17',1,52.367984300000000,4.903561399999944),(22,22,'Ik heb een bepaalde Fetisj waar mijn man niet mee kan omgaan.. We hebben dan ook de afspraak gemaakt dat ik wat buitenshuis mag zoeken om die tintelingen te stillen. Dus mocht je een man zijn op zoek naar spannende seks op een spannende plaats? Waar wacht je dan nog op? Psst vraag er 1 en krijg er  … ;)\r\n','1988-02-14',2,2,'Amsterdam',5,4,3,4,2,1,'nl','2018-11-21 22:26:25','2018-11-21 22:26:25',1,52.367984300000000,4.903561399999944),(23,23,'Stille wateren hebben .. Maak de zin maar af en wie weet krijgen we nog een leuk gesprek samen? Ik heb een hoop te vertellen en ben zeker weten geen saai date maatje.. Dus wat dacht je ervan? Een leuke date samen met een leuke happy ending? \r\n','1986-03-21',2,1,'Venlo',3,2,4,4,1,3,'nl','2018-11-21 22:31:14','2018-11-21 22:31:14',2,51.370374800000000,6.172403099999997),(24,24,'Kan jij mij laten zien wat echte liefde is en hoe echte liefde voelt? Hoe de seks voelt wanneer er liefde bij is en hoe een man echt kan zijn wanneer die van je houdt? Ik weet het namelijk niet meer en mijn vertrouwen is weg. xxx\r\n','1985-04-12',2,1,'Venlo',4,3,4,4,3,2,'nl','2018-11-21 22:34:41','2018-11-21 22:34:41',1,51.370374800000000,6.172403099999997),(25,25,'Mijn naam is Aevy , dit spreek je ook wel uit als ´Ivy´. Ik kom uit een wat rijkere familie en werk zelf ook heel hard als advocate! Ik zoek dan ook een man met klasse een man die weet wat die doet en die door de keuring van mijn ouders heen komt hihi! Xxx\r\n','1991-09-01',2,1,'Venlo',4,1,3,4,3,3,'nl','2018-11-21 22:37:16','2018-11-21 22:40:04',2,51.370374800000000,6.172403099999997),(26,26,'Het is wat vreemd ik heb altijd gezegd dat ik dit noooit zou doen.. En hier ben ik dan met een mond vol tanden en een klein beetje schaamte. Zou jij die schaamte bij mij weg kunnen halen? Wacht dan vooral niet want ik vind het allemaal nog een beetje spannend hihi.\r\nAileen:\r\n','1992-10-19',2,1,'Venlo',4,5,2,5,2,2,'nl','2018-11-21 22:43:56','2018-11-21 22:43:56',1,51.370374800000000,6.172403099999997),(27,27,'Momenteel ben doe ik HBO-V en na dit te hebben afgerond twijfel ik nog verder te studeren! Door studie tijden heb ik weinig tijd voor stappen en ik realiseer mij dat een vent niet naar mij toe komt gelopen uit het niets. Ik hoop hier dan ook meer geluk te hebben in de liefde! Liefs Aileen\r\n','1993-07-23',2,1,'Venlo',4,1,2,5,3,2,'nl','2018-11-21 22:56:40','2018-11-21 22:56:40',1,51.370374800000000,6.172403099999997),(28,28,'Ik ben een mysterieus type die hier en daar raar uit de hoek kan komen. Voor sommige zal dit alleen maar leuk en spannend zijn en voor de anderen zal ik te dominant zijn. Ik ben dan ook op zoek naar de onderdanige mannen die open staan voor een spannend potje seks en rollenspellen.. Bij mij thuis! Interesse? Xx Barbara\r\n','1971-08-06',2,1,'Venlo',5,5,4,4,1,2,'nl','2018-11-21 22:59:19','2018-11-21 22:59:19',2,51.370374800000000,6.172403099999997),(29,29,'Ik werk als danser en stripper voor een bureau die mensen uitzend voor op verjaardagen. Echter haal ik hier niet mijn pleziertjes en genot uit en zou ik een fuckbuddy willen vinden voor een aantal keer in de week.. Ik ben dominant en geniet van een beetje pijn x\r\n','1988-09-11',2,2,'Venlo',5,2,4,6,2,1,'nl','2018-11-21 23:02:10','2018-11-21 23:02:10',1,51.370374800000000,6.172403099999997),(30,30,'Ik geil echt heel erg op onderdanige oudere mannen die doen wat ik wil wanneer ik het wil.. Je krijgt er een onvergetelijke dag voor terug met een heel nat poesje en hoop orgasmes de dag door. Durf jij je voor een keer over te geven aan een dame?\r\n','1992-11-16',2,1,'Rotterdam',5,1,2,5,1,2,'nl','2018-11-21 23:04:30','2018-11-21 23:04:30',1,51.928824000000000,4.478083000000000),(31,31,'Sommige kijken raar op wanneer ik vertel dat ik daadwerkelijk een huisje boompje beestje mens ben.. Er zullen er niet veel zijn maar daarom zoek ik hier naar de eerlijke man met een aantal wensen xx\r\n','1998-01-01',2,1,'Rotterdam',4,2,1,6,3,2,'nl','2018-11-21 23:06:34','2018-11-21 23:06:34',1,51.928824000000000,4.478083000000000),(32,32,'Waar zijn de tijden dat er nog echte mannen zijn die de touwtjes in handen heeft en houdt? Dat is iets wat ik namelijk heel erg mis in deze tijd waardoor ik nog steeds singel ben. Als jij een vent ben neem dan vooral contact op en dan zal ik jouw onderdanige vrouwtje zijn X\r\n','1970-03-08',2,1,'Rotterdam',5,6,1,5,3,1,'nl','2018-11-21 23:10:56','2018-11-21 23:10:56',1,51.928824000000000,4.478083000000000),(33,33,'Ik werk als steward voor een groot bedrijf met een aantal verschillende toestellen.. De ene keer ben ik maanden thuis naast jou op de bank.. De andere keer ben je weken alleen maar af en toe 1 a 2 avonden samen. Het is een levensstyle wat je moet aandurven maar je krijgt er veel voor terug inclusief eventuele vrijheid! Xx\r\n','1990-08-01',2,1,'Rotterdam',5,5,3,4,1,2,'nl','2018-11-22 13:00:51','2018-11-22 13:00:51',2,51.928824000000000,4.478083000000000),(34,34,'In mijn lange bonte jas zie je mij buiten lopen.. Je hebt met een dame afgesproken voor een spannend hotel dagje. Hoe die eruit gaat zien? Dat mag jij helemaal zelf invullen.. Ik zal alles voor je doen en je dag onvergeetbaar maken. Het enige wat jij nu hoeft te doen is mij te vertellen wat ik onder die jas aan zal trekken en een hotelkamer boeken.. X Corina\r\n\r\n','1989-11-15',2,2,'Rotterdam',4,5,4,1,2,1,'nl','2018-11-22 13:03:49','2018-11-22 13:03:49',1,51.928824000000000,4.478083000000000),(35,35,'Ik ben een echte rockfan al zal je dit niet heel snel zeggen.. Ik werk op festivals achter de knoppen als podiumtechnicus en maak geweldige dingen mee. Wist je dat ik je mag meenemen en dat we ook spannende dingen achter de stage kunnen doen? Proberen? ;)\r\n','1994-05-13',2,1,'Rotterdam',4,3,3,4,2,2,'nl','2018-11-22 13:07:27','2018-11-22 13:07:27',2,51.928824000000000,4.478083000000000),(36,36,'De meeste vrouwen geilen op een harde lange stijve pik.. Ik daar in tegen houdt van kleine harde pikken en mocht je nou ook eens geluk hebben dat ik een strak kutje heb.. Zo voel je mij gegarandeerd goed wanneer je mij lekker hard neemt.. Interesse ? ;) \r\n','1988-06-06',2,2,'Rotterdam',3,3,5,4,3,2,'nl','2018-11-22 13:16:23','2018-11-22 13:16:23',2,51.928824000000000,4.478083000000000),(37,37,'De hormonen gieren door mijn lijf wanneer ik wordt vastgebonden aan het bed en een zweepje langs mijn poesje voelt gaan. Met de gedachten alleen al wordt ik nat en geil. Zou jij deze fantasie voor mij waar willen maken? \r\n','1991-07-09',2,1,'Rotterdam',4,2,4,4,1,2,'nl','2018-11-22 13:20:54','2018-11-22 13:20:54',1,51.928824000000000,4.478083000000000),(38,38,'Mijn klitje tintelt en mijn hormonen zijn op jacht naar een vent die mij eens heerlijk wilt komen verwennen in mijn stulpje.. Ik zal je op de beste manier begroeten en je een onvergetelijke dag geven. Kan jij mijn honger stillen voor een periode? xx\r\n','1997-08-11',2,1,'Rotterdam',4,4,1,4,3,1,'nl','2018-11-22 13:23:27','2018-11-22 13:23:27',2,51.928824000000000,4.478083000000000),(39,39,'Ik ben net verhuisd van land naar stad toe en het nog een te gekke drukte voor mij! Hierdoor zoek ik af en toe thuis heel graag even de liefde en rust op en dan het liefst bij mijn aankomende vent.. Ben jij de rust zichzelf? \r\n','1985-12-16',2,1,'Rotterdam',3,5,3,5,3,2,'nl','2018-11-22 13:26:40','2018-11-22 13:26:40',1,51.928824000000000,4.478083000000000),(40,40,'Mijn naam is Cathrina en ik kom uit een gelovig gezin.. Zo ben ik dan ook opgevoed maar toch heerst er een stille hete voeten droom in mij die ik maar niet kan vergeten.. Ik mag geen relatie en heel eigenlijk geen seks.. Maar toch zoek ik een man die wat stouts met mij wilt doen hihi. Laat me niet te lang wachten ik wil niet dat mijn profiel gevonden wordt xxx\r\n','1994-01-12',2,1,'Rotterdam',4,2,4,5,3,3,'nl','2018-11-22 13:34:23','2018-11-22 13:34:23',1,51.928824000000000,4.478083000000000),(41,41,'Ik ben een sportieve dame die liever een zwakker persoon als vent heeft dan een dominante.. velen kijken er raar van op en zeggen dat ik beter zou kunnen krijgen. Wat nou als dit is waar ik mij bij thuis voel? Kan jij je ook thuis voelen bij mij? Laten we dan maar eens daten?! \r\n','1996-02-18',2,1,'Rotterdam',5,4,1,1,1,2,'nl','2018-11-22 13:57:35','2018-11-22 13:57:35',1,51.928824000000000,4.478083000000000),(42,42,'Mijn ouders komen uit Engeland en ik ben hier dan ook opgegroeid. Als je dan ook van een Engels accent houdt zal je bij mij aan het goede adres zijn haha! Ik geef nu Engels les aan MBO leerlingen en dit vind ik geweldig werk om te doen. Ik ben dan op zoek naar een kinderliefhebber die samen met mij een gezin zou willen starten. \r\n','1993-09-21',2,1,'Rotterdam',5,3,1,4,3,2,'nl','2018-11-22 14:05:05','2018-11-22 14:05:05',1,51.928824000000000,4.478083000000000),(43,43,'Niet veel mensen hoor je hier specifiek over maar ik ga vaak naar een parenclub samen met mijn vriendin.. Nu zoeken we een vent die met ons mee zou willen voor een spannende orgie.. Mocht je iemand mee willen nemen is die ook zeker welkom! Laat me weten of je interesse hebt Xx\r\n','1998-01-01',2,2,'Rotterdam',5,2,4,5,3,2,'nl','2019-01-13 12:34:32','2019-01-13 12:34:32',1,51.928824000000000,4.478083000000000),(44,44,'Ik werk als vrijwilliger bij een aantal dierenasielen en besteed hier best veel tijd aan.. Het is dan vaak ook een beestenbende in huis wanneer er weer een beestje opgevangen moet worden en dit doe ik met alle liefde.. Ik zoek dan ook een man met begrip hiervoor en dezelfde liefde voor beesten hebt zoals mij! Ik vraag niet veel maar wat ik vraag is wel heel belangrijk schat ! xx\r\n','1991-05-01',2,1,'Rotterdam',4,4,2,5,3,2,'nl','2019-01-13 12:42:45','2019-01-13 12:42:45',1,51.928824000000000,4.478083000000000),(45,45,'\r\nAl die ouders en kinderen op het kinderdagverblijf laten mij naar een eigen gezin hunkeren.. Lekker werken daarna samen knus thuis en uiteindelijk een gezinnetje opbouwen. Ben jij hier ook echt naar op zoek en ben je single? Laten we dan eens een drankje doen samen en wie weet XXx \r\n','1992-06-22',2,1,'Rotterdam',4,1,3,4,3,3,'nl','2019-01-13 12:48:39','2019-01-13 12:48:39',1,51.928824000000000,4.478083000000000),(46,46,' werk in een vliegveld toren en dit vergt een hoop energie van mij! Ik wil dan ook af en toe even heerlijk kunnen ontspannen met iemand om alle hormonen en stress van mij af te slaan.. Dus voel jij je aangetrokken tot een part time fuckbuddy? Dan hebben we elkaar gevonden x\r\n','1980-11-21',2,1,'Rotterdam',5,2,2,5,2,2,'nl','2019-01-13 12:54:13','2019-01-13 12:54:13',2,51.928824000000000,4.478083000000000),(47,47,'\r\nIk ben een echte Jazz queen en geniet hier iedere dag van .. Met mijn muzieksmaak ,  gekke buien en liefde kan je een geweldige tijd mee maken als dit van wederzijdse kant komt. Durf jij het aan met mij eens een concertje te bezoeken en lekker gek mee te doen? \r\n','1994-09-11',2,1,'Rotterdam',5,4,1,4,2,1,'nl','2019-01-13 12:57:47','2019-01-13 12:57:47',1,51.928824000000000,4.478083000000000),(48,48,'\r\nMet mijn grote borsten en strakke kutje weet ik jouw de beste avond te geven die je in een lange tijd niet hebt meegemaakt ;) Maar de vraag is wat heb jij voor mij allemaal in je macht? Of zou je mij in je macht kunnen krijgen? \r\n','1982-04-11',2,2,'Rotterdam',5,5,5,1,2,1,'nl','2019-01-13 13:01:52','2019-01-13 13:01:52',1,51.928824000000000,4.478083000000000),(49,49,'Ik wordt hier ook wel de blowjob king genoemd door mijn zachte tong en grote mond.. Ooit een slechte ervaring ermee gehad? Laat mij dit maar eens oplossen voor jou dan als je durft.. Ik weet wel een paar kunstjes om je helemaal gek te maken maar ken jij deze ook voor mij?\r\n','1976-08-12',2,1,'Rotterdam',3,5,3,4,3,2,'nl','2019-01-13 13:06:02','2019-01-13 13:06:02',1,51.928824000000000,4.478083000000000),(50,50,'\r\nIk ben nog thuiswonende en studerende bij mijn ouders thuis op een boerderij! Ik studeer voor dierenarts en heb dan ook al mijn eigen paard en hond. Ik ben op zoek naar een serieuze man van mijn leeftijd voor liefdevolle relatie met een mooie toekomst in het zicht. \r\n','1996-06-23',2,1,'Rotterdam',4,4,5,4,3,2,'nl','2019-01-13 13:11:42','2019-01-13 13:11:42',1,51.928824000000000,4.478083000000000),(51,51,'\r\nIk werk ik een restaurant als chefkok en besteed hier vrijwel 6 dagen in de week aan! De ene keer zal je me een hele dag kwijt zijn en de andere dag maar 1 dagdeel. Ik kan 5 sterren koken en hou van een net huis. Je zal dus een hele goede handen komen bij mij hihi xxx\r\n','1988-03-08',2,1,'Rotterdam',3,2,3,1,1,2,'nl','2019-01-13 13:14:36','2019-01-13 13:14:36',1,51.928824000000000,4.478083000000000),(52,52,'\r\nMijn vent is voor een half jaar weg naar Amerika voor zaken.. Nu weet ik dat hij ook niet helemaal heilig is en waarom zou ik dat dan wel zijn? De hormonen gieren door mijn lijf en ik kan me niet meer inhouden. Alleen is nog de vraag hier met wie zal ik dit eens doen? Durf jij van bil te gaan met een getrouwde vrouw?\r\n','1985-01-28',2,2,'Rotterdam',5,3,4,4,1,2,'nl','2019-01-13 13:17:10','2019-01-13 13:17:10',1,51.928824000000000,4.478083000000000),(53,53,'Mijn ouders komen uit Engeland en ik ben hier dan ook opgegroeid. Als je dan ook van een Engels accent houdt zal je bij mij aan het goede adres zijn haha! Ik geef nu Engels les aan MBO leerlingen en dit vind ik geweldig werk om te doen. Ik ben dan op zoek naar een kinderliefhebber die samen met mij een gezin zou willen starten. ','1988-11-04',2,1,'Rotterdam',4,2,3,1,3,2,'nl','2019-01-20 20:30:30','2019-01-20 20:30:30',1,51.928824000000000,4.478083000000000),(54,54,'Samen met mijn vriendin zijn wij op zoek naar een nieuw spannend avontuur.. Weet jij van aanpakken en weet jij hoe je een avond onvergetelijk kan maken? Weet jij hoe je samen met mijn vriendin onsj gillend een orgasme kan geven? Laat dan maar snel van je horen hmmm xx\r\n','1989-06-02',2,2,'Rotterdam',5,4,4,4,2,1,'nl','2019-01-20 20:35:27','2019-01-20 20:35:27',1,51.928824000000000,4.478083000000000),(55,55,'Je hoort het niet meer veel maar ik zit in de kampioenschappen van het Twirlen en ik zoek een supporter ;) Niet zomaar een supporter.. Een supporter die voor altijd naast mij zal staan , voor mij zal zorgen en mij speciaal voelen. Je zal alles wederzijds ontvangen dat is een belofte Xx\r\n','1992-01-12',2,1,'Den Haag',4,4,2,5,1,2,'nl','2019-01-20 20:38:22','2019-01-20 20:38:22',1,52.070538000000000,4.319340000000000),(56,56,'\r\nIk zit in een open relatie waarin ik niet meer zo gelukkig ben.. Zou jij mij kunnen laten zien wat geluk is en hoe geluk en genot in bed voelt? Kan jij me laten voelen waarom vrouwen schreeuwen en trillen door een orgasme? \r\n','1998-01-01',2,2,'Den Haag',5,4,2,5,2,1,'nl','2019-01-20 20:49:52','2019-01-20 20:49:52',1,52.070538000000000,4.319340000000000),(57,57,'\r\nSeks is makkelijk te vinden maar liefde.. Liefde blijft een lastig onderwerp voor mij. Na 5 jaar vrijgezel te zijn ben ik er klaar mee en snak ik naar de liefde.. Zou jij mij die liefde willen geven en laten zien hoe dat weer voelt na 5 jaar? Ons weer voelen als kinderen van 14 giechelend en verliefd? \r\n','1990-02-14',2,1,'Den Haag',5,1,1,6,3,1,'nl','2019-01-21 08:14:52','2019-01-21 08:14:52',1,52.070538000000000,4.319340000000000),(58,58,'\r\nLaat de kachel uit die hebben we niet nodig.. Het enige wat wij nodig hebben is een bed een deken en elkaars lichaamswarmte. Doe jij mee aan dit milieu plan? X\r\n','1994-03-18',2,1,'Den Haag',4,4,2,5,2,2,'nl','2019-01-21 08:17:39','2019-01-21 08:17:39',1,52.070538000000000,4.319340000000000),(59,59,'\r\nIk doe aan prive pornofilms mee en ben op zoek naar een geile vent die het leuk lijkt mee te doen aan de film.. Het enige wat je mee hoeft te brengen is een goed humeur en jouw geile bui.. Hoe de film loopt ligt helemaal aan jou want ik zal alles doen wat je hartje maar begeert .. Durf jij? Misschien wel samen met mij en mijn vriendin?\r\n','1998-01-01',2,1,'Den Haag',5,4,4,4,2,3,'nl','2019-01-21 08:21:35','2019-01-21 08:21:35',1,52.070538000000000,4.319340000000000),(60,60,'\r\n\r\nAls administratie medewerker heb ik altijd al stoute dromen gehad over seks hebben met de baas.. Stiekem seks met een klant in de wc hebben. Ik wil dit tot werkelijkheid brengen maar de vraag is zou iemand dit aandurven? ;) \r\n','1998-01-01',2,1,'Den Haag',4,5,5,1,3,1,'nl','2019-01-21 08:31:24','2019-01-21 08:31:24',1,52.070538000000000,4.319340000000000),(61,61,':\r\nWat is het aller stoutste wat je hebt gedaan en zou je dit durven overtreffen? Ik heb namelijk hele geile ideeen die eigenlijk niet door de beugel kunnen.. Maar is dat niet juist wat het allemaal zo leuk maakt om te doen? \r\n\r\n','1992-09-23',2,1,'Den Haag',5,3,3,6,1,2,'nl','2019-01-21 08:34:16','2019-01-21 08:34:16',1,52.070538000000000,4.319340000000000),(62,62,'\r\nMijn leven loopt best geleidelijk en vlekkeloos als verpleegkundige zijnde en toch mis ik er iets aan.. Ik woon nu alweer 3 jaar op mijzelf en ik moet erbij zeggen dat dit gaat vervelen.. Ik weet niet of dat ik toe ben aan iets vast, maar ik ben zeker wel toe aan iets nieuws! \r\n','1996-10-24',2,1,'Den Haag',5,4,1,6,3,2,'nl','2019-01-21 08:38:53','2019-01-21 08:38:53',1,52.070538000000000,4.319340000000000),(63,63,'\r\n\r\nDe dagen beginnen weer donkerder te worden en de nachten weer langer.. Ik heb een warm en knus bed die ik nu nog deel in mijn eentje. Hier hoop ik binnenkort met iemand extra erin te leggen voor lange speciale warme nachten. Dus mocht je nog op zoek zijn wees dan vooral degene die mij wel durft aan te spreken !\r\n','1974-04-11',2,1,'Den Haag',4,5,1,4,3,2,'nl','2019-01-21 08:42:28','2019-01-21 08:42:28',1,52.070538000000000,4.319340000000000),(64,64,'\r\nIk vind het heerlijk wanneer een man sperma in mijn mondje spuit terwijl ik ermee speel.. Over mijn borsten terwijl ik het erin masseert.. In mijn kontje zodat je het eruit ziet lopen.. Over mijn klitje terwijl ik mijzelf verwen.. Mag ik vragen om een afspraak aan je?\r\n','1975-11-14',2,1,'Den Haag',4,6,3,6,3,1,'nl','2019-01-21 09:58:14','2019-01-21 09:58:14',1,52.070538000000000,4.319340000000000),(65,65,'\r\nIk ben nog maagd en constant wanneer ik mijn vibrator tegen mijn klitje aan houdt verlang ik naar een echte pik die mij komt verwennen.. Niet alleen vaginaal maar het liefst ook nog eens lekker diep anaal.. Zijn er gedadigen?\r\n','1990-08-19',2,1,'Den Haag',5,4,4,5,1,2,'nl','2019-01-21 10:00:32','2019-01-21 10:00:32',1,52.070538000000000,4.319340000000000),(66,66,'Zin in een spannend uitje samen in het mooie bos? Of maak je liever de kleedkamer in de H&M onveilig samen met mij? ;)','1989-12-29',2,1,'Den Haag',6,3,1,7,2,3,'nl','2019-01-21 10:17:00','2019-01-21 10:17:00',1,52.070538000000000,4.319340000000000),(67,67,'\r\nEigenlijk mag dit helemaal niet maar ik vind het zo een geil idee om af te spreken met een vreemde in een hotel.. Gekke dingen met elkaar te doen en daarna weer weg te lopen alsof er niks is gebeurd. Zou jij mij een fantastische ervaring willen geven in een hotel kamer?\r\n','1987-02-20',2,1,'Den Haag',5,4,1,4,3,1,'nl','2019-01-21 10:29:39','2019-01-21 10:29:39',1,52.070538000000000,4.319340000000000);
/*!40000 ALTER TABLE `user_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `account_type` smallint(5) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deactivated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin@gmail.com','$2y$10$VGHEtHrqC1gGjEppMJzOgO4FK.Uhb0eTV6/hmoPpcUs/x7NBGjmIy',0,1,'mlT0o6yL72SGM5gVzYGQ4I5WtKhK8ARnZRacITOgmOBkkMDhFFzbbby7VXCj','2017-07-09 16:36:56','2017-07-09 16:36:56',NULL),(3,'Britt93',NULL,'$2y$10$XYAr5qvhjfwMFA9Lgz2dc.ii.PV4ed7zYxuPbA5N3F2rDV6yQtScm',0,1,NULL,'2017-07-13 10:06:53','2017-07-13 10:12:36',NULL),(4,'StouteKim',NULL,'$2y$10$a2F0ww85pXvFFY95fGtUXOKvgnRig3okXPHfMvLdld9KkUydlwpMO',0,1,NULL,'2017-07-13 10:28:43','2017-07-13 10:28:43',NULL),(5,'Lisa-zoekt',NULL,'$2y$10$P./wTeNsvJb2Qj4eIJqw7uqLV0DyV.YBr/FFhJPtSonPtW5sAjU92',0,1,NULL,'2017-08-23 09:33:42','2017-08-23 09:33:42',NULL),(6,'Saraa',NULL,'$2y$10$IpUaqhyIj5vzP0KL/t3Ime/LvVUQUfsE.0yURzOM9xEgW/D0Nc9UO',0,1,NULL,'2017-08-23 13:55:24','2017-08-23 13:56:40',NULL),(8,'Roosje86',NULL,'$2y$10$rILqBRWnDZLnE7BDzHG6GenmH6e7C/JFd1YbTryL4c9jp1H.4bMzC',0,1,NULL,'2017-09-04 09:11:45','2017-09-04 09:11:45',NULL),(9,'SexOnly',NULL,'$2y$10$f9WY6hHXramHGvUUN0xJjuPIBc/fmOFzlJ34VtCXOlVUtsJ1YJQbW',0,1,NULL,'2017-09-04 09:24:34','2017-09-04 09:24:34',NULL),(10,'RondMaarFijn',NULL,'$2y$10$HloJOTDzNqQqj2GkQ78Efe6mmpGEt/3hpIa/gQ1oHN9pAWMYtjv8S',0,1,NULL,'2017-09-11 09:30:54','2017-09-11 09:36:05',NULL),(11,'AlisA',NULL,'$2y$10$F.HtirCf6Fiya166FcNKG.acl8lockUtUTCDYuqdv6XrDk2VDuYny',0,1,NULL,'2017-09-11 09:34:57','2018-06-28 18:42:34',NULL),(12,'Monteroxie',NULL,'$2y$10$na8uMx.WgcAXhFh.n3TxwOqagRDrQLphE.IR5gYVmDz3hyVtglJKK',0,1,NULL,'2017-09-27 09:10:09','2018-08-27 09:25:31',NULL),(13,'VerpleegstertjeJ',NULL,'$2y$10$DHwzVG.cahhlMViKQPaA6ujOYEIVn7QgZTnjKtuQX7gh.p7b2hQvy',0,1,NULL,'2018-10-09 20:25:03','2018-10-09 20:25:03',NULL),(14,'GeenPlayersPlease',NULL,'$2y$10$29fcFFR3k9HK9NBOPUztoOImtE0LROPVFP3iQ2kpYotdcC/XRRU/G',0,1,NULL,'2018-10-11 22:52:25','2018-10-11 22:52:25',NULL),(15,'Anouk',NULL,'$2y$10$ePjCDgmicNeZ2zP58Xvyau/s3mjLpDmmRI12L5Bn6Nw33uBro9/k.',0,1,NULL,'2018-11-21 21:56:05','2018-11-21 21:56:05',NULL),(16,'Ambertjex',NULL,'$2y$10$tWorQsGB1qzHwMN3SJ8x7unjZ9jle6qrCaJCsHvNVtPKrJnxz8vAO',0,1,NULL,'2018-11-21 21:59:50','2018-11-21 21:59:50',NULL),(17,'AbbieDox',NULL,'$2y$10$zjEtxub/tsGf1IaYn5vQ4uP4n.YMNiBHysF9OYLRWbW6./b2cr4cS',0,1,NULL,'2018-11-21 22:04:04','2018-11-21 22:41:18',NULL),(18,'LadyAbby',NULL,'$2y$10$4RHAZzRPUYKYEudq8GLnQeNQwwIevvrfux54tc9s80QnyJ/i19GYW',0,1,NULL,'2018-11-21 22:08:17','2018-11-21 22:08:17',NULL),(19,'Adalynrose',NULL,'$2y$10$x7uHxJNrCRaY5LFgd4hCDOqrOSxHHQuzYgm4sLIBSbopNvk.PRcUG',0,1,NULL,'2018-11-21 22:13:21','2018-11-21 22:13:21',NULL),(20,'AdeliahNoor',NULL,'$2y$10$/YQJkeFECt1vg8dy5zopP...NjibUHZfKRsyFsSiFnIu0ub/Sv1JK',0,1,NULL,'2018-11-21 22:16:51','2018-11-21 22:16:51',NULL),(21,'Adeliesje',NULL,'$2y$10$9LUEfslq6qbjnerjlsFCOO8j5Xiz5Cp2DmaQ8l0B4WQP1CpDH0lvW',0,1,NULL,'2018-11-21 22:20:17','2018-11-21 22:20:17',NULL),(22,'HotBelinda',NULL,'$2y$10$WVK/0Vmczl0ygFwGAawaCeKtuK4urITjw5ZzqujwDHpBtCDoD5jAm',0,1,NULL,'2018-11-21 22:26:25','2018-11-21 22:26:25',NULL),(23,'Adilababe',NULL,'$2y$10$nVOt2Ocu.wDh8m8wkJmf7uiiphan.Yh./q8BQ1d3ZYhchTW8HoUDm',0,1,NULL,'2018-11-21 22:31:14','2018-11-21 22:31:14',NULL),(24,'Adriana4',NULL,'$2y$10$rSWr9mkU45eKVGokTwLKI.H4ODYz2xpSPzIFFBd8jcpROsjtz18x2',0,1,NULL,'2018-11-21 22:34:41','2018-11-21 22:34:41',NULL),(25,'Aevyxx',NULL,'$2y$10$GHwqlmdErEEJZyc4oJfxgOWHF8M/0LRwDCvJ0kIShVIRPaGud7n3i',0,1,NULL,'2018-11-21 22:37:16','2018-11-21 22:40:04',NULL),(26,'Aidaxox',NULL,'$2y$10$ptAGb0eOp6XfyXPmJWSm/e7VIdBAlKnjmPb7HZkSzyF66my/H.U5O',0,1,NULL,'2018-11-21 22:43:56','2018-11-21 22:43:56',NULL),(27,'KissAileen',NULL,'$2y$10$6NdOI9Kctc75W1iTM//RM.xW9wjvTBR1p5WhG9geP2Ygv8ZhIKlky',0,1,NULL,'2018-11-21 22:56:40','2018-11-21 22:56:40',NULL),(28,'Barbara',NULL,'$2y$10$YWiv4fXYMQhuhFRvLNDxKuRCB2SxqoUbS6Ce/ynzO9Sj4ph78yryC',0,1,NULL,'2018-11-21 22:59:19','2018-11-21 22:59:19',NULL),(29,'HeyBabeth',NULL,'$2y$10$g53nfOdACLkI5l7zl3GPi.qiK5cRfpk145w4faC83yPHr7DcycD/6',0,1,NULL,'2018-11-21 23:02:10','2018-11-21 23:02:10',NULL),(30,'Beautje',NULL,'$2y$10$k5AYm9Rro9l1Z1rX1VAuAerTgjqUkl3vm1KjzR/zqVTLH0iyElSg2',0,1,NULL,'2018-11-21 23:04:30','2018-11-21 23:04:30',NULL),(31,'Banou',NULL,'$2y$10$wSUqE1WTUrMXqzY5GXHpPekcfLqjbczyzjGwo0KpEl1Dx0h7utumm',0,1,NULL,'2018-11-21 23:06:34','2018-11-21 23:06:34',NULL),(32,'Beccaenjij',NULL,'$2y$10$a5cCcRihmXNzrjIQL.9k6eYrdzqfHsNFw0obcJKxK/i4XlzK479zi',0,1,NULL,'2018-11-21 23:10:56','2018-11-21 23:10:56',NULL),(33,'Belinda',NULL,'$2y$10$6cvQgEKokNn/443srNq9f.3sXRfrYMPwZpoIHW72k/jpIx7P7xhtG',0,1,NULL,'2018-11-22 13:00:51','2018-11-22 13:00:51',NULL),(34,'Corinax',NULL,'$2y$10$MT5xCIRu.mZdpece8Ib5LOiZfoqn0PIy7/1rEXdp8pPE1FGMbz112',0,1,NULL,'2018-11-22 13:03:49','2018-11-22 13:03:49',NULL),(35,'Bella',NULL,'$2y$10$NWOB1cg34ZgDFb7DOVA.O.AzFu70jkGSBKeonV7TGq.2h.zFGKqgS',0,1,NULL,'2018-11-22 13:07:27','2018-11-22 13:07:27',NULL),(36,'Caatje',NULL,'$2y$10$B3Gx5yNrTjiU87y9YQDQ2e3hZ4vjkQ7q6ylzmTZCwKhgqB.NxgUru',0,1,NULL,'2018-11-22 13:16:23','2018-11-22 13:16:23',NULL),(37,'Caelyn',NULL,'$2y$10$BF5l2U07OUB12lamWBGxP.iZBs0FWWwyBrZx8oqAmcoOiBEraJlD2',0,1,NULL,'2018-11-22 13:20:54','2018-11-22 13:20:54',NULL),(38,'Caitlyn',NULL,'$2y$10$N/n6quZzyrnt4XD.Fnr16ugE.64DQF8UhsM0pK8R5lah7xkvL.y1W',0,1,NULL,'2018-11-22 13:23:27','2018-11-22 13:23:27',NULL),(39,'XCloexx',NULL,'$2y$10$s.tQPzx3dtEoDuHTofjUzuf3iqbryboh4zFbTaFwgZmoGtgs8k7Tu',0,1,NULL,'2018-11-22 13:26:40','2018-11-22 13:26:40',NULL),(40,'Cathrina',NULL,'$2y$10$atHY.zB.BLAl5NeoBdB5lOpPtLKVE1gECNSCNVCtds1wIWAkCrdp2',0,1,NULL,'2018-11-22 13:34:23','2018-11-22 13:34:23',NULL),(41,'Benthe',NULL,'$2y$10$V/toOxRaQzMzcYkKkcGJO.CN0bvCLXFZQ2MCmg5V7ic2VLv9Flegu',0,1,NULL,'2018-11-22 13:57:35','2018-11-22 13:57:35',NULL),(42,'Babet',NULL,'$2y$10$e6zmJzzHDGRohipaLU0GTu0O1uDTny42gF4.xkl4DNfzlJhKsKcmm',0,1,NULL,'2018-11-22 14:05:05','2018-11-22 14:05:05',NULL),(43,'Carolijntje',NULL,'$2y$10$VTYv8nQToD.rySgf4KTokOuf18KyyCdPavWckiAkASAuo2Rf.UVoq',0,1,NULL,'2019-01-13 12:34:32','2019-01-13 12:34:32',NULL),(44,'Caitt',NULL,'$2y$10$317MyCFQMH3O8FcGLCdKkuL9uPJOOX0j5k3M2kQT6JwGqKvjQRTpa',0,1,NULL,'2019-01-13 12:42:45','2019-01-13 12:42:45',NULL),(45,'Caithlynn',NULL,'$2y$10$dEHEMUDUbMf31e70X5Vd1.gRrAn1kIobpRAQaIs8VgeABwS.RT0jm',0,1,NULL,'2019-01-13 12:48:39','2019-01-13 12:48:39',NULL),(46,'Carola',NULL,'$2y$10$qHcmBFAzf6h0WsMKubkMieq.iy0afStfeoZdk364TMMT0IDUeXtOK',0,1,NULL,'2019-01-13 12:54:13','2019-01-13 12:54:13',NULL),(47,'Celine',NULL,'$2y$10$mjdY23c73HKRkBuL7JqpHebtwg.sSo4jpV5UdqaUdBohFFmqJFcPm',0,1,NULL,'2019-01-13 12:57:47','2019-01-13 12:57:47',NULL),(48,'Chantal',NULL,'$2y$10$jzMgZdO3usqSf/NIVUD0Xue0T3juffi06YeFrlYr4GlmdJnwqHlc6',0,1,NULL,'2019-01-13 13:01:52','2019-01-13 13:01:52',NULL),(49,'Charissa',NULL,'$2y$10$iivgMB95145CVRfrCe8EEev1D4B04nhMcjB7euEa9gIKQqBC11udO',0,1,NULL,'2019-01-13 13:06:02','2019-01-13 13:06:02',NULL),(50,'Cassandra',NULL,'$2y$10$d8w12ERUWh8qshPIHLbNfeulPp/jnQMn4riKrsAUv3yH3EKl6uode',0,1,NULL,'2019-01-13 13:11:42','2019-01-13 13:11:42',NULL),(51,'Celeste',NULL,'$2y$10$bPYgLq3e44arww9ifm2HQuHPGXs3h6vjkC/C2SfV4fanTwYXbwFEm',0,1,NULL,'2019-01-13 13:14:36','2019-01-13 13:14:36',NULL),(52,'Cherelle',NULL,'$2y$10$5A8z.qTfdsU9LrBb2HDcIuaYW8d6TjcA38rqZgRh9QVfCFr0V.gdq',0,1,NULL,'2019-01-13 13:17:10','2019-01-13 13:17:10',NULL),(53,'Babetjex',NULL,'$2y$10$pjW6Na4poEiVPN4TdlkF3emJ0qTK/PB6E/bpGG4cL9Ty7VPMestUy',0,1,NULL,'2019-01-20 20:30:30','2019-01-20 20:30:30',NULL),(54,'Dana2',NULL,'$2y$10$BqQ27D46FzrYzncPK.GNYO19j8OHBrPSKaOgU0q5R/ZCsxumEZ.Mi',0,1,NULL,'2019-01-20 20:35:27','2019-01-20 20:35:27',NULL),(55,'Danielle',NULL,'$2y$10$8m1jStmlIehKeEuYy0ebX.kLzoNN8EumO3r9nncCf2Hv1u/L0MTCC',0,1,NULL,'2019-01-20 20:38:22','2019-01-20 20:38:22',NULL),(56,'Danoukxox',NULL,'$2y$10$FAJWyzYIL7Yp8WUBCr.c/ekxv3aJlrchC3qR9XSuAYWTdny23Kdkq',0,1,NULL,'2019-01-20 20:49:52','2019-01-20 20:49:52',NULL),(57,'Darcy',NULL,'$2y$10$PGyRNRqR7.MwDqkGY.UpXuxJPqx/EA0WQIYV6TQt7zy/R8MzUY3cW',0,1,NULL,'2019-01-21 08:14:52','2019-01-21 08:14:52',NULL),(58,'Darlene',NULL,'$2y$10$rA9jwIqorkICHXOIHkxifesjzw4Gc98bBRv3GxZ21F9XbvK2FJjmi',0,1,NULL,'2019-01-21 08:17:39','2019-01-21 08:17:39',NULL),(59,'Dayenne',NULL,'$2y$10$KOqdiaSaS.Rl4rrkSFxlneCcotTPrlG87oEp1OKGVSGxmdK.fLnB6',0,1,NULL,'2019-01-21 08:21:35','2019-01-21 08:21:35',NULL),(60,'Daimy',NULL,'$2y$10$q12UGxnwmC9PkAt2mbMXneQNo0WMJmyMJFYmvacAAVw7OYrZW3MOG',0,1,NULL,'2019-01-21 08:31:24','2019-01-21 08:31:24',NULL),(61,'Charity',NULL,'$2y$10$qQkn/grT2uT7deC1BqUF4.NAXFjy2flIe2MStV7l50y631lvLTvTe',0,1,NULL,'2019-01-21 08:34:16','2019-01-21 08:34:16',NULL),(62,'Denise',NULL,'$2y$10$Ad1JtIWWYydK5z7Omq1IpOocRdZjrR3v6kDpEAudyPRWHfw75xW7K',0,1,NULL,'2019-01-21 08:38:53','2019-01-21 08:38:53',NULL),(63,'Delara',NULL,'$2y$10$YzWOCgny4fmVjqpj99u/zuRWRjxAe1bzDGBx5Gom.wlrgPVlk.25a',0,1,NULL,'2019-01-21 08:42:28','2019-01-21 08:42:28',NULL),(64,'Desiree',NULL,'$2y$10$o8exIH7tJUqLoLlrU2SDHeXPBwFlEQNGax9omhUK5HHbjUIXfHKyG',0,1,NULL,'2019-01-21 09:58:14','2019-01-21 09:58:14',NULL),(65,'Debora',NULL,'$2y$10$KJUcRnK/KlauwitI46MQfeghoQgrO1HjRoq4M5SjK3iXAkKtUaN7W',0,1,NULL,'2019-01-21 10:00:32','2019-01-21 10:00:32',NULL),(66,'Keetjexx',NULL,'$2y$10$W8OJJxlMhSlR5TbuT12HIuRha6VBwhAbd6RoWmtHTJS8sU4076PUu',0,1,NULL,'2019-01-21 10:17:00','2019-01-21 10:17:00',NULL),(67,'Eisha',NULL,'$2y$10$favFAMzkVBAYXKOJRxx48unEIUNv2e/KYQy/AvqpWXPez6DBmoxcu',0,1,NULL,'2019-01-21 10:29:39','2019-01-21 10:29:39',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `views`
--

DROP TABLE IF EXISTS `views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `views` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `route_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `views`
--

LOCK TABLES `views` WRITE;
/*!40000 ALTER TABLE `views` DISABLE KEYS */;
INSERT INTO `views` VALUES (1,'Home','home',NULL,NULL),(2,'Contact','contact.get',NULL,NULL),(3,'Users Search','contact.get',NULL,NULL),(4,'Users Overview','contact.get',NULL,NULL),(5,'User profile','contact.get',NULL,NULL);
/*!40000 ALTER TABLE `views` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-03 12:58:16
