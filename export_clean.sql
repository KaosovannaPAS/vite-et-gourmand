/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commandes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `date_prestation` date NOT NULL,
  `heure_prestation` time NOT NULL,
  `adresse_prestation` text NOT NULL,
  `ville` varchar(100) NOT NULL,
  `nb_personnes` int(11) NOT NULL,
  `prix_total` decimal(10,2) NOT NULL,
  `statut` varchar(50) DEFAULT 'en attente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dishes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(191) NOT NULL,
  `type` enum('entree','plat','dessert') NOT NULL,
  `allergenes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_vegan` tinyint(1) DEFAULT 0,
  `is_gluten_free` tinyint(1) DEFAULT 0,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `dishes` VALUES (1,'Foie Gras Maison au Sauternes','entree','Sulfites, Lait','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/foie_gras_maison.png'),(2,'Saumon Gravlax et Blinis','entree','Poisson, Gluten, Oeuf','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/saumon_gravlax_blinis.png'),(3,'Velout├® de Ch├ótaignes aux Truffes','entree','Fruits ├á coque','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/veloute_chataignes.png'),(4,'Tartare de Saint-Jacques aux Agrumes','entree','Mollusques','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/tartare_st_jacques.png'),(5,'Dinde R├┤tie aux Marrons','plat','Fruits ├á coque','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(6,'Filet de B┼ôuf en Cro├╗te (Wellington)','plat','Gluten, Oeuf, Moutarde','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/fondant_chocolat.png'),(7,'R├┤ti de Butternut Farci aux Champignons','plat','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(8,'Risotto aux Truffes et Parmesan','plat','Lait','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(9,'B├╗che Glac├®e Marron-Poire','dessert','Lait, Oeuf, Fruits ├á coque','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(10,'Pavlova aux Fruits Rouges','dessert','Oeuf','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(11,'Mousse au Chocolat Noir Intense','dessert','Soja','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/fondant_chocolat.png'),(12,'Salade d\'Oranges ├ëpic├®e','dessert','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(13,'Hu├«tres du Bassin n┬░3','entree','Mollusques','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/huitres_bassin.png'),(14,'Lobster Roll Deluxe','entree','Crustac├®s, Gluten, Lait','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/lobster_roll.png'),(15,'Toast de Faux-Gras V├®g├®tal','entree','Gluten, Soja','2026-02-18 02:54:37',1,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/faux_gras.png'),(16,'Carpaccio de Betteraves Fum├®','entree','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(17,'Chapon Fin aux Morilles','plat','Lait','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(18,'Dos de Cabillaud Sauce Champagne','plat','Poisson, Sulfites, Lait','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(19,'Seitan Laqu├® aux ├ëpices','plat','Gluten, Soja','2026-02-18 02:54:37',1,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(20,'Risotto de Sarrasin aux L├®gumes Oubli├®s','plat','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(21,'Omelette Norv├®gienne','dessert','Lait, Oeuf, Gluten','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(22,'Macarons Foie Gras & Figue','dessert','Oeuf, Fruits ├á coque','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(23,'Fondant Chocolat-Avocat','dessert','Soja','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/fondant_chocolat.png'),(24,'Carpaccio d\'Ananas R├┤ti','dessert','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(25,'Duo de Saumon C┼ôur','entree','Poisson','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(26,'Cappuccino de Champignons','entree','Lait','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(27,'Tartare de Tomates C┼ôur de B┼ôuf','entree','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(28,'Aum├┤ni├¿re de Ch├¿vre Miel','entree','Lait, Gluten','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(29,'Magret de Canard Sauce Griottes','plat','','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/magret_canard.png'),(30,'Risotto aux Asperges Vertes','plat','Lait','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(31,'Curry de L├®gumes d\'Amour','plat','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(32,'Ravioles aux Truffes','plat','Gluten, Lait, Oeuf','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(33,'C┼ôur Coulant Chocolat','dessert','Gluten, Oeuf, Lait','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(34,'Tiramisu aux Framboises','dessert','Gluten, Oeuf, Lait','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(35,'Panna Cotta Coco-Rose','dessert','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/fondant_chocolat.png'),(36,'Sorbet Passion-Gingembre','dessert','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(37,'Nems au Poulet','entree','Gluten, Oeuf','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/nems_crevettes.png'),(38,'Rouleaux de Printemps Crevettes','entree','Crustac├®s','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(39,'Samossas L├®gumes','entree','Gluten','2026-02-18 02:54:37',1,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(40,'Salade de Papaye Verte','entree','Arachides','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(41,'Pad Tha├» Poulet','plat','Oeuf, Arachides, Poisson','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(42,'Curry Vert Crevettes','plat','Crustac├®s, Poisson','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(43,'Wok de L├®gumes Tofu','plat','Soja, Gluten','2026-02-18 02:54:37',1,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(44,'Curry Rouge V├®g├®tal','plat','Soja','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(45,'Perles de Coco','dessert','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(46,'Mangue Sticky Rice','dessert','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(47,'Salade de Fruits Exotiques','dessert','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/fondant_chocolat.png'),(48,'Mochi Glac├® Th├® Vert','dessert','Lait','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(49,'Houmous et Pain Pita','entree','Gluten, S├®same','2026-02-18 02:54:37',1,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(50,'Taboul├® de Quinoa','entree','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(51,'Velout├® de Courge','entree','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(52,'Tartinade d\'Artichaut','entree','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(53,'Lasagnes V├®g├®tales','plat','Gluten','2026-02-18 02:54:37',1,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(54,'Dahl de Lentilles Corail','plat','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(55,'Burger V├®g├®tal','plat','Gluten, Moutarde, S├®same','2026-02-18 02:54:37',1,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(56,'Buddha Bowl de Saison','plat','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(57,'Cookie Vegan Chocolat','dessert','Gluten, Fruits ├á coque','2026-02-18 02:54:37',1,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(58,'Chia Pudding Lait de Coco','dessert','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(59,'Compote Pomme-Cannelle','dessert','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/fondant_chocolat.png'),(60,'Cake Citron-Pavot','dessert','Gluten','2026-02-18 02:54:37',1,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(61,'Rillettes de Thon','entree','Poisson, Lait','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(62,'Crevettes Sauce Cocktail','entree','Crustac├®s, Oeuf','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(63,'Tartare d\'Algues','entree','Soja','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(64,'Soupe de Poisson','entree','Poisson, Crustac├®s','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(65,'Filet de Bar R├┤ti','plat','Poisson','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(66,'Moules Marini├¿res','plat','Mollusques, Sulfites','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(67,'Paella de la Mer','plat','Crustac├®s, Mollusques','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(68,'Cassolette de la Mer','plat','Poisson, Crustac├®s, Lait, Gluten','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(69,'Baba au Rhum','dessert','Gluten, Oeuf, Lait','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(70,'Ile Flottante','dessert','Oeuf, Lait','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(71,'Sorbet Citron','dessert','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/fondant_chocolat.png'),(72,'Tarte au Citron Meringu├®e','dessert','Gluten, Oeuf, Lait','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(73,'Grattons de Bordeaux','entree','','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/grattons_bordeaux.png'),(74,'Asperges du Blayais','entree','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(75,'Salade Landaise','entree','','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(76,'Hu├«tres d\'Arcachon','entree','Mollusques','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/huitres_bassin.png'),(77,'Entrec├┤te ├á la Bordelaise','plat','Sulfites','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(78,'Lamproie ├á la Bordelaise','plat','Poisson, Gluten, Sulfites','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(79,'Magret de Canard Grill├®','plat','','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(80,'C├¿pes de Bordeaux ├á la Persillade','plat','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(81,'Canel├®s de Bordeaux','dessert','Gluten, Oeuf, Lait','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(82,'Dunes Blanches','dessert','Gluten, Oeuf, Lait','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),(83,'Macarons de Saint-├ëmilion','dessert','Oeuf, Fruits ├á coque','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/fondant_chocolat.png'),(84,'Sorbet au Vin Rouge (sans alcool)','dessert','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_dishes` (
  `menu_id` int(11) NOT NULL,
  `dish_id` int(11) NOT NULL,
  PRIMARY KEY (`menu_id`,`dish_id`),
  KEY `dish_id` (`dish_id`),
  CONSTRAINT `menu_dishes_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  CONSTRAINT `menu_dishes_ibfk_2` FOREIGN KEY (`dish_id`) REFERENCES `dishes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `menu_dishes` VALUES (1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11),(1,12),(2,13),(2,14),(2,15),(2,16),(2,17),(2,18),(2,19),(2,20),(2,21),(2,22),(2,23),(2,24),(3,25),(3,26),(3,27),(3,28),(3,29),(3,30),(3,31),(3,32),(3,33),(3,34),(3,35),(3,36),(4,37),(4,38),(4,39),(4,40),(4,41),(4,42),(4,43),(4,44),(4,45),(4,46),(4,47),(4,48),(5,49),(5,50),(5,51),(5,52),(5,53),(5,54),(5,55),(5,56),(5,57),(5,58),(5,59),(5,60),(6,61),(6,62),(6,63),(6,64),(6,65),(6,66),(6,67),(6,68),(6,69),(6,70),(6,71),(6,72),(7,73),(7,74),(7,75),(7,76),(7,77),(7,78),(7,79),(7,80),(7,81),(7,82),(7,83),(7,84);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `theme` varchar(50) DEFAULT NULL,
  `regime` varchar(50) DEFAULT NULL,
  `personnes_min` int(11) DEFAULT 1,
  `prix_min` decimal(10,2) NOT NULL,
  `stock_dispo` int(11) DEFAULT 0,
  `actif` tinyint(1) DEFAULT 1,
  `conditions_reservation` text DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL DEFAULT 0.00,
  `min_personnes` int(11) DEFAULT 1,
  `stock` int(11) DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `menus` VALUES (1,'Menu No├½l F├®erique','Un repas de f├¬te inoubliable avec des produits nobles et une touche de magie.','No├½l','Classique',1,0.00,0,1,'Commandez 1 semaine ├á l\'avance pour moins de 20 personnes, 2 semaines pour plus de 20.',72.50,2,50,'/Vite-et-gourmand/interface_frontend/ressources/images/menus/noel.png'),(2,'Menu Nouvel An Prestige','Commencez l\'ann├®e en beaut├® avec notre menu gastronomique d\'exception.','Nouvel An','Classique',1,0.00,0,1,'Commandez 1 semaine ├á l\'avance pour moins de 20 personnes, 2 semaines pour plus de 20.',62.50,2,40,'/Vite-et-gourmand/interface_frontend/ressources/images/menus/valentin.png'),(3,'Menu Saint Valentin Romantique','Un d├«ner en t├¬te-├á-t├¬te pour c├®l├®brer l\'amour avec finesse.','Saint Valentin','Classique',1,0.00,0,1,'Commandez 1 semaine ├á l\'avance pour moins de 20 personnes, 2 semaines pour plus de 20.',77.50,2,100,'/Vite-et-gourmand/interface_frontend/ressources/images/menus/mariage.png'),(4,'Menu Voyage en Asie','D├®couvrez les saveurs exotiques de l\'Asie.','Voyage en Asie','Classique',1,0.00,0,1,'Commandez 1 semaine ├á l\'avance pour moins de 20 personnes, 2 semaines pour plus de 20.',47.50,2,50,'/Vite-et-gourmand/interface_frontend/ressources/images/menus/asie.png'),(5,'Menu 100% V├®g├®tal','Un menu sain et gourmand, respectueux de la nature.','V├®g├®tarien','V├®gan',1,0.00,0,1,'Commandez 1 semaine ├á l\'avance pour moins de 20 personnes, 2 semaines pour plus de 20.',42.50,1,50,'/Vite-et-gourmand/interface_frontend/ressources/images/menus/vegetal.png'),(6,'Menu Poisson & Crustac├®s','La fra├«cheur de l\'oc├®an dans votre assiette.','Mer','Pesc├®tarien',1,0.00,0,1,'Commandez 1 semaine ├á l\'avance pour moins de 20 personnes, 2 semaines pour plus de 20.',57.50,2,30,'/Vite-et-gourmand/interface_frontend/ressources/images/menus/poisson.png'),(7,'Sp├®cialit├®s de Bordeaux','Le meilleur du terroir bordelais par nos chefs.','Terroir','Classique',1,0.00,0,1,'Commandez 1 semaine ├á l\'avance pour moins de 20 personnes, 2 semaines pour plus de 20.',52.50,2,60,'/Vite-et-gourmand/interface_frontend/ressources/images/menus/bordeaux.png');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  `prix_unitaire` decimal(10,2) DEFAULT NULL,
  `choices` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `menu_id` (`menu_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `order_items` VALUES (1,1,1,2,45.00,NULL),(2,2,1,2,45.00,NULL),(3,3,1,2,75.00,NULL),(4,4,7,2,75.00,NULL),(5,5,2,2,75.00,NULL),(6,6,3,2,75.00,NULL),(7,7,1,2,75.00,NULL),(8,8,7,2,75.00,NULL),(9,9,2,2,75.00,NULL),(10,10,7,2,75.00,NULL),(11,11,1,2,75.00,NULL),(12,12,2,2,75.00,NULL);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `date_livraison` date DEFAULT NULL,
  `heure_livraison` time DEFAULT NULL,
  `lieu_livraison` varchar(255) DEFAULT NULL,
  `prix_total` decimal(10,2) DEFAULT NULL,
  `statut` varchar(50) DEFAULT 'en_attente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `orders` VALUES (1,1,'2026-02-18',NULL,'Bordeaux',45.00,'terminee','2026-02-18 03:39:34'),(2,1,'2026-02-18',NULL,'Bordeaux',45.00,'terminee','2026-02-18 03:39:34'),(3,2,'2026-02-18','20:00:00','Bordeaux',150.00,'terminee','2026-02-18 05:19:08'),(4,3,'2026-02-18','20:00:00','Bordeaux',150.00,'terminee','2026-02-18 05:19:08'),(5,4,'2026-02-18','20:00:00','Bordeaux',150.00,'terminee','2026-02-18 05:19:08'),(6,5,'2026-02-18','20:00:00','Bordeaux',150.00,'terminee','2026-02-18 05:19:08'),(7,6,'2026-02-18','20:00:00','Bordeaux',150.00,'terminee','2026-02-18 05:19:08'),(8,7,'2026-02-18','20:00:00','Bordeaux',150.00,'terminee','2026-02-18 05:19:08'),(9,8,'2026-02-18','20:00:00','Bordeaux',150.00,'terminee','2026-02-18 05:19:08'),(10,9,'2026-02-18','20:00:00','Bordeaux',150.00,'terminee','2026-02-18 05:19:08'),(11,10,'2026-02-18','20:00:00','Bordeaux',150.00,'terminee','2026-02-18 05:19:08'),(12,11,'2026-02-18','20:00:00','Bordeaux',150.00,'terminee','2026-02-18 05:19:08');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `note` int(11) NOT NULL,
  `commentaire` text NOT NULL,
  `valide` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `reviews` VALUES (1,1,1,5,'Une exp├®rience inoubliable ! Le service ├®tait parfait et les plats d├®licieux.',1,'2026-02-18 04:39:34'),(2,1,1,4,'Tr├¿s bon repas, livraison ├á l\'heure. Je recommande.',1,'2026-02-18 04:39:34'),(3,1,1,5,'Julie et Jos├® sont au top ! Merci pour ce moment.',1,'2026-02-18 04:39:34'),(4,1,2,5,'Une exp├®rience inoubliable ! Le service ├®tait parfait et les plats d├®licieux.',1,'2026-02-18 04:39:34'),(5,1,2,4,'Tr├¿s bon repas, livraison ├á l\'heure. Je recommande.',1,'2026-02-18 04:39:34'),(6,1,2,5,'Julie et Jos├® sont au top ! Merci pour ce moment.',1,'2026-02-18 04:39:34'),(7,2,3,5,'Un moment magique ! Le Menu Nouvel An ├®tait divin, surtout le homard. Service impeccable.',1,'2026-02-18 06:19:08'),(8,3,4,5,'Excellent rapport qualit├®-prix. Les sp├®cialit├®s bordelaises sont authentiques.',1,'2026-02-18 06:19:08'),(9,4,5,5,'Command├® pour notre anniversaire. Le menu Saint Valentin ├®tait tr├¿s romantique et d├®licieux.',1,'2026-02-18 06:19:08'),(10,5,6,4,'Livraison ponctuelle et plats encore chauds. Je recommande le menu classique.',1,'2026-02-18 06:19:08'),(11,6,7,5,'Une explosion de saveurs. Le foie gras est ├á tomber par terre.',1,'2026-02-18 06:19:08'),(12,7,8,4,'Parfait pour un repas d\'entreprise. Tout le monde a ador├®.',1,'2026-02-18 06:19:08'),(13,8,9,5,'C\'est ma troisi├¿me commande et je ne suis jamais d├®├ºue. L\'art de recevoir est au RDV.',1,'2026-02-18 06:19:08'),(14,9,10,5,'Tr├¿s bonne exp├®rience. Les vins sugg├®r├®s ├®taient parfaits.',1,'2026-02-18 06:19:08'),(15,10,11,5,'Produits frais et de saison. On sent la passion du m├®tier.',1,'2026-02-18 06:19:08'),(16,11,12,5,'Une logistique impressionnante pour 50 personnes. Merci ├á Julie et Jos├® !',1,'2026-02-18 06:19:08');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `roles` VALUES (1,'admin'),(2,'employe'),(3,'utilisateur');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `gsm` varchar(20) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `avatar_url` varchar(255) DEFAULT 'https://ui-avatars.com/api/?name=User&background=800020&color=fff',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `users` VALUES (1,'Martin','Sophie','sophie.martin@example.com','0600000000','1 rue de la Paix','password123hash',3,'2026-02-18 03:39:02','https://ui-avatars.com/api/?name=User&background=800020&color=fff'),(2,'L.','Sophie','sophie0@example.com',NULL,NULL,'$2y$10$XIJduafI19ZFv02mSAFlFOGaeN/AVhDeO8ebGncRKcg/0ZDiS.JWW',2,'2026-02-18 05:19:08','https://i.pravatar.cc/150?u=sophie'),(3,'D.','Marc','marc1@example.com',NULL,NULL,'$2y$10$G6GaEjR0/94ze/w2p9lEDuDaX7Sed7dZUnknrfAceJjwMRZzd.GRC',2,'2026-02-18 05:19:08','https://i.pravatar.cc/150?u=marc'),(4,'B.','Julie','julie2@example.com',NULL,NULL,'$2y$10$07Ahvh0wadyl9bryIeaMJuHWpLiCpyVdYmCtLBDpxhb0ZaiuhG36u',2,'2026-02-18 05:19:08','https://i.pravatar.cc/150?u=julie'),(5,'M.','Thomas','thomas3@example.com',NULL,NULL,'$2y$10$zMHjy/6upHy1PIIfDnYGhucTKgTsA.u9haLyKvan6FYQWA9DhDNlG',2,'2026-02-18 05:19:08','https://i.pravatar.cc/150?u=thomas'),(6,'R.','Elodie','elodie4@example.com',NULL,NULL,'$2y$10$E5DejvpX05y0rmi5Lml5r.bm6XeP1E5vsibUyn5GrHA7uy.sxUTzy',2,'2026-02-18 05:19:08','https://i.pravatar.cc/150?u=elodie'),(7,'V.','Antoine','antoine5@example.com',NULL,NULL,'$2y$10$n.b0mRx6vmKgxZZ.2154qOjWIUUafWx4CqhXAoATPTYHnuzSxSoW6',2,'2026-02-18 05:19:08','https://i.pravatar.cc/150?u=antoine'),(8,'P.','Lucie','lucie6@example.com',NULL,NULL,'$2y$10$/lQgnfViTTdanVzcvoVl3eZRYf8EYW76PUqyhmiX0x3KxrxlvtkG.',2,'2026-02-18 05:19:08','https://i.pravatar.cc/150?u=lucie'),(9,'G.','Pierre','pierre7@example.com',NULL,NULL,'$2y$10$dw/mrwv5p3atb3B43IgyXeBH/dgrO26P3DQHw4OtUq76aJEpnitQ.',2,'2026-02-18 05:19:08','https://i.pravatar.cc/150?u=pierre'),(10,'S.','Marie','marie8@example.com',NULL,NULL,'$2y$10$LNUXJ2mOa4pirv2jsxSBT.N.CcaaYPWCeoXmtoRaLGqQ8GcZfI.J2',2,'2026-02-18 05:19:08','https://i.pravatar.cc/150?u=marie'),(11,'W.','Nicolas','nicolas9@example.com',NULL,NULL,'$2y$10$EUkrNKfu4G6TQ4a26A.Ir.g3NOfDis4hww6T4q8WkxEUeQh.mWAMe',2,'2026-02-18 05:19:08','https://i.pravatar.cc/150?u=nicolas');
