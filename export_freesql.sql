SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE='';

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `roles` VALUES (1,'admin'),(2,'employe'),(3,'utilisateur');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `gsm` varchar(20) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` VALUES
(1,'Martin','Sophie','sophie.martin@example.com','0600000000','1 rue de la Paix','password123hash',3,'2026-02-18 03:39:02','https://ui-avatars.com/api/?name=User&background=800020&color=fff'),
(2,'L.','Sophie','sophie0@example.com',NULL,NULL,'$2y$10$XIJduafI19ZFv02mSAFlFOGaeN/AVhDeO8ebGncRKcg/0ZDiS.JWW',2,'2026-02-18 05:19:08','https://i.pravatar.cc/150?u=sophie'),
(3,'D.','Marc','marc1@example.com',NULL,NULL,'$2y$10$G6GaEjR0/94ze/w2p9lEDuDaX7Sed7dZUnknrfAceJjwMRZzd.GRC',2,'2026-02-18 05:19:08','https://i.pravatar.cc/150?u=marc'),
(4,'B.','Julie','julie2@example.com',NULL,NULL,'$2y$10$07Ahvh0wadyl9bryIeaMJuHWpLiCpyVdYmCtLBDpxhb0ZaiuhG36u',2,'2026-02-18 05:19:08','https://i.pravatar.cc/150?u=julie'),
(5,'M.','Thomas','thomas3@example.com',NULL,NULL,'$2y$10$zMHjy/6upHy1PIIfDnYGhucTKgTsA.u9haLyKvan6FYQWA9DhDNlG',2,'2026-02-18 05:19:08','https://i.pravatar.cc/150?u=thomas'),
(6,'R.','Elodie','elodie4@example.com',NULL,NULL,'$2y$10$E5DejvpX05y0rmi5Lml5r.bm6XeP1E5vsibUyn5GrHA7uy.sxUTzy',2,'2026-02-18 05:19:08','https://i.pravatar.cc/150?u=elodie'),
(7,'V.','Antoine','antoine5@example.com',NULL,NULL,'$2y$10$n.b0mRx6vmKgxZZ.2154qOjWIUUafWx4CqhXAoATPTYHnuzSxSoW6',2,'2026-02-18 05:19:08','https://i.pravatar.cc/150?u=antoine'),
(8,'P.','Lucie','lucie6@example.com',NULL,NULL,'$2y$10$/lQgnfViTTdanVzcvoVl3eZRYf8EYW76PUqyhmiX0x3KxrxlvtkG.',2,'2026-02-18 05:19:08','https://i.pravatar.cc/150?u=lucie'),
(9,'G.','Pierre','pierre7@example.com',NULL,NULL,'$2y$10$dw/mrwv5p3atb3B43IgyXeBH/dgrO26P3DQHw4OtUq76aJEpnitQ.',2,'2026-02-18 05:19:08','https://i.pravatar.cc/150?u=pierre'),
(10,'S.','Marie','marie8@example.com',NULL,NULL,'$2y$10$LNUXJ2mOa4pirv2jsxSBT.N.CcaaYPWCeoXmtoRaLGqQ8GcZfI.J2',2,'2026-02-18 05:19:08','https://i.pravatar.cc/150?u=marie'),
(11,'W.','Nicolas','nicolas9@example.com',NULL,NULL,'$2y$10$EUkrNKfu4G6TQ4a26A.Ir.g3NOfDis4hww6T4q8WkxEUeQh.mWAMe',2,'2026-02-18 05:19:08','https://i.pravatar.cc/150?u=nicolas');

DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `theme` varchar(50) DEFAULT NULL,
  `regime` varchar(50) DEFAULT NULL,
  `personnes_min` int(11) DEFAULT 1,
  `prix_min` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock_dispo` int(11) DEFAULT 0,
  `actif` tinyint(1) DEFAULT 1,
  `conditions_reservation` text DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL DEFAULT 0.00,
  `min_personnes` int(11) DEFAULT 1,
  `stock` int(11) DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `menus` VALUES
(1,'Menu Noel Feerique','Un repas de fete inoubliable avec des produits nobles et une touche de magie.','Noel','Classique',1,0.00,0,1,'Commandez 1 semaine a l avance pour moins de 20 personnes, 2 semaines pour plus de 20.',72.50,2,50,'/Vite-et-gourmand/interface_frontend/ressources/images/menus/noel.png'),
(2,'Menu Nouvel An Prestige','Commencez l annee en beaute avec notre menu gastronomique d exception.','Nouvel An','Classique',1,0.00,0,1,'Commandez 1 semaine a l avance pour moins de 20 personnes, 2 semaines pour plus de 20.',62.50,2,40,'/Vite-et-gourmand/interface_frontend/ressources/images/menus/valentin.png'),
(3,'Menu Saint Valentin Romantique','Un diner en tete-a-tete pour celebrer l amour avec finesse.','Saint Valentin','Classique',1,0.00,0,1,'Commandez 1 semaine a l avance pour moins de 20 personnes, 2 semaines pour plus de 20.',77.50,2,100,'/Vite-et-gourmand/interface_frontend/ressources/images/menus/mariage.png'),
(4,'Menu Voyage en Asie','Decouvrez les saveurs exotiques de l Asie.','Voyage en Asie','Classique',1,0.00,0,1,'Commandez 1 semaine a l avance pour moins de 20 personnes, 2 semaines pour plus de 20.',47.50,2,50,'/Vite-et-gourmand/interface_frontend/ressources/images/menus/asie.png'),
(5,'Menu 100% Vegetal','Un menu sain et gourmand, respectueux de la nature.','Vegetarien','Vegan',1,0.00,0,1,'Commandez 1 semaine a l avance pour moins de 20 personnes, 2 semaines pour plus de 20.',42.50,1,50,'/Vite-et-gourmand/interface_frontend/ressources/images/menus/vegetal.png'),
(6,'Menu Poisson et Crustaces','La fraicheur de l ocean dans votre assiette.','Mer','Pescetarien',1,0.00,0,1,'Commandez 1 semaine a l avance pour moins de 20 personnes, 2 semaines pour plus de 20.',57.50,2,30,'/Vite-et-gourmand/interface_frontend/ressources/images/menus/poisson.png'),
(7,'Specialites de Bordeaux','Le meilleur du terroir bordelais par nos chefs.','Terroir','Classique',1,0.00,0,1,'Commandez 1 semaine a l avance pour moins de 20 personnes, 2 semaines pour plus de 20.',52.50,2,60,'/Vite-et-gourmand/interface_frontend/ressources/images/menus/bordeaux.png');

DROP TABLE IF EXISTS `dishes`;
CREATE TABLE `dishes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(191) NOT NULL,
  `type` enum('entree','plat','dessert') NOT NULL,
  `allergenes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `is_vegan` tinyint(1) DEFAULT 0,
  `is_gluten_free` tinyint(1) DEFAULT 0,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `dishes` VALUES
(1,'Foie Gras Maison au Sauternes','entree','Sulfites, Lait','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/foie_gras_maison.png'),
(2,'Saumon Gravlax et Blinis','entree','Poisson, Gluten, Oeuf','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/saumon_gravlax_blinis.png'),
(3,'Veloute de Chataignes aux Truffes','entree','Fruits a coque','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/veloute_chataignes.png'),
(4,'Tartare de Saint-Jacques aux Agrumes','entree','Mollusques','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/tartare_st_jacques.png'),
(5,'Dinde Rotie aux Marrons','plat','Fruits a coque','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(6,'Filet de Boeuf en Croute (Wellington)','plat','Gluten, Oeuf, Moutarde','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/fondant_chocolat.png'),
(7,'Roti de Butternut Farci aux Champignons','plat','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(8,'Risotto aux Truffes et Parmesan','plat','Lait','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(9,'Buche Glacee Marron-Poire','dessert','Lait, Oeuf, Fruits a coque','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(10,'Pavlova aux Fruits Rouges','dessert','Oeuf','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(11,'Mousse au Chocolat Noir Intense','dessert','Soja','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/fondant_chocolat.png'),
(12,'Salade d Oranges Epicee','dessert','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(13,'Huitres du Bassin n3','entree','Mollusques','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/huitres_bassin.png'),
(14,'Lobster Roll Deluxe','entree','Crustaces, Gluten, Lait','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/lobster_roll.png'),
(15,'Toast de Faux-Gras Vegetal','entree','Gluten, Soja','2026-02-18 02:54:37',1,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/faux_gras.png'),
(16,'Carpaccio de Betteraves Fume','entree','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(17,'Chapon Fin aux Morilles','plat','Lait','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(18,'Dos de Cabillaud Sauce Champagne','plat','Poisson, Sulfites, Lait','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(19,'Seitan Laque aux Epices','plat','Gluten, Soja','2026-02-18 02:54:37',1,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(20,'Risotto de Sarrasin aux Legumes Oublies','plat','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(21,'Omelette Norvegienne','dessert','Lait, Oeuf, Gluten','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(22,'Macarons Foie Gras et Figue','dessert','Oeuf, Fruits a coque','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(23,'Fondant Chocolat-Avocat','dessert','Soja','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/fondant_chocolat.png'),
(24,'Carpaccio d Ananas Roti','dessert','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(25,'Duo de Saumon Coeur','entree','Poisson','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(26,'Cappuccino de Champignons','entree','Lait','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(27,'Tartare de Tomates Coeur de Boeuf','entree','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(28,'Aumoniere de Chevre Miel','entree','Lait, Gluten','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(29,'Magret de Canard Sauce Griottes','plat','','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/magret_canard.png'),
(30,'Risotto aux Asperges Vertes','plat','Lait','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(31,'Curry de Legumes d Amour','plat','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(32,'Ravioles aux Truffes','plat','Gluten, Lait, Oeuf','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(33,'Coeur Coulant Chocolat','dessert','Gluten, Oeuf, Lait','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(34,'Tiramisu aux Framboises','dessert','Gluten, Oeuf, Lait','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(35,'Panna Cotta Coco-Rose','dessert','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/fondant_chocolat.png'),
(36,'Sorbet Passion-Gingembre','dessert','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(37,'Nems au Poulet','entree','Gluten, Oeuf','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/nems_crevettes.png'),
(38,'Rouleaux de Printemps Crevettes','entree','Crustaces','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(39,'Samossas Legumes','entree','Gluten','2026-02-18 02:54:37',1,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(40,'Salade de Papaye Verte','entree','Arachides','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(41,'Pad Thai Poulet','plat','Oeuf, Arachides, Poisson','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(42,'Curry Vert Crevettes','plat','Crustaces, Poisson','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(43,'Wok de Legumes Tofu','plat','Soja, Gluten','2026-02-18 02:54:37',1,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(44,'Curry Rouge Vegetal','plat','Soja','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(45,'Perles de Coco','dessert','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(46,'Mangue Sticky Rice','dessert','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(47,'Salade de Fruits Exotiques','dessert','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/fondant_chocolat.png'),
(48,'Mochi Glace The Vert','dessert','Lait','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(49,'Houmous et Pain Pita','entree','Gluten, Sesame','2026-02-18 02:54:37',1,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(50,'Tabolue de Quinoa','entree','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(51,'Veloute de Courge','entree','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(52,'Tartinade d Artichaut','entree','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(53,'Lasagnes Vegetales','plat','Gluten','2026-02-18 02:54:37',1,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(54,'Dahl de Lentilles Corail','plat','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(55,'Burger Vegetal','plat','Gluten, Moutarde, Sesame','2026-02-18 02:54:37',1,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(56,'Buddha Bowl de Saison','plat','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(57,'Cookie Vegan Chocolat','dessert','Gluten, Fruits a coque','2026-02-18 02:54:37',1,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(58,'Chia Pudding Lait de Coco','dessert','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(59,'Compote Pomme-Cannelle','dessert','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/fondant_chocolat.png'),
(60,'Cake Citron-Pavot','dessert','Gluten','2026-02-18 02:54:37',1,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(61,'Rillettes de Thon','entree','Poisson, Lait','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(62,'Crevettes Sauce Cocktail','entree','Crustaces, Oeuf','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(63,'Tartare d Algues','entree','Soja','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(64,'Soupe de Poisson','entree','Poisson, Crustaces','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(65,'Filet de Bar Roti','plat','Poisson','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(66,'Moules Marinieres','plat','Mollusques, Sulfites','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(67,'Paella de la Mer','plat','Crustaces, Mollusques','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(68,'Cassolette de la Mer','plat','Poisson, Crustaces, Lait, Gluten','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(69,'Baba au Rhum','dessert','Gluten, Oeuf, Lait','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(70,'Ile Flottante','dessert','Oeuf, Lait','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(71,'Sorbet Citron','dessert','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/fondant_chocolat.png'),
(72,'Tarte au Citron Meringuee','dessert','Gluten, Oeuf, Lait','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(73,'Grattons de Bordeaux','entree','','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/grattons_bordeaux.png'),
(74,'Asperges du Blayais','entree','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(75,'Salade Landaise','entree','','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(76,'Huitres d Arcachon','entree','Mollusques','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/huitres_bassin.png'),
(77,'Entrecote a la Bordelaise','plat','Sulfites','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(78,'Lamproie a la Bordelaise','plat','Poisson, Gluten, Sulfites','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(79,'Magret de Canard Grille','plat','','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(80,'Cepes de Bordeaux a la Persillade','plat','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(81,'Caneles de Bordeaux','dessert','Gluten, Oeuf, Lait','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(82,'Dunes Blanches','dessert','Gluten, Oeuf, Lait','2026-02-18 02:54:37',0,0,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png'),
(83,'Macarons de Saint-Emilion','dessert','Oeuf, Fruits a coque','2026-02-18 02:54:37',0,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/fondant_chocolat.png'),
(84,'Sorbet au Vin Rouge (sans alcool)','dessert','','2026-02-18 02:54:37',1,1,NULL,'/Vite-et-gourmand/interface_frontend/ressources/images/dishes/premium_dish.png');

DROP TABLE IF EXISTS `menu_dishes`;
CREATE TABLE `menu_dishes` (
  `menu_id` int(11) NOT NULL,
  `dish_id` int(11) NOT NULL,
  PRIMARY KEY (`menu_id`,`dish_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `menu_dishes` VALUES (1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11),(1,12),(2,13),(2,14),(2,15),(2,16),(2,17),(2,18),(2,19),(2,20),(2,21),(2,22),(2,23),(2,24),(3,25),(3,26),(3,27),(3,28),(3,29),(3,30),(3,31),(3,32),(3,33),(3,34),(3,35),(3,36),(4,37),(4,38),(4,39),(4,40),(4,41),(4,42),(4,43),(4,44),(4,45),(4,46),(4,47),(4,48),(5,49),(5,50),(5,51),(5,52),(5,53),(5,54),(5,55),(5,56),(5,57),(5,58),(5,59),(5,60),(6,61),(6,62),(6,63),(6,64),(6,65),(6,66),(6,67),(6,68),(6,69),(6,70),(6,71),(6,72),(7,73),(7,74),(7,75),(7,76),(7,77),(7,78),(7,79),(7,80),(7,81),(7,82),(7,83),(7,84);

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `date_livraison` date DEFAULT NULL,
  `heure_livraison` time DEFAULT NULL,
  `lieu_livraison` varchar(255) DEFAULT NULL,
  `prix_total` decimal(10,2) DEFAULT NULL,
  `statut` varchar(50) DEFAULT 'en_attente',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `orders` VALUES
(1,1,'2026-02-18',NULL,'Bordeaux',45.00,'terminee','2026-02-18 03:39:34'),
(2,1,'2026-02-18',NULL,'Bordeaux',45.00,'terminee','2026-02-18 03:39:34'),
(3,2,'2026-02-18','20:00:00','Bordeaux',150.00,'terminee','2026-02-18 05:19:08'),
(4,3,'2026-02-18','20:00:00','Bordeaux',150.00,'terminee','2026-02-18 05:19:08'),
(5,4,'2026-02-18','20:00:00','Bordeaux',150.00,'terminee','2026-02-18 05:19:08'),
(6,5,'2026-02-18','20:00:00','Bordeaux',150.00,'terminee','2026-02-18 05:19:08'),
(7,6,'2026-02-18','20:00:00','Bordeaux',150.00,'terminee','2026-02-18 05:19:08'),
(8,7,'2026-02-18','20:00:00','Bordeaux',150.00,'terminee','2026-02-18 05:19:08'),
(9,8,'2026-02-18','20:00:00','Bordeaux',150.00,'terminee','2026-02-18 05:19:08'),
(10,9,'2026-02-18','20:00:00','Bordeaux',150.00,'terminee','2026-02-18 05:19:08'),
(11,10,'2026-02-18','20:00:00','Bordeaux',150.00,'terminee','2026-02-18 05:19:08'),
(12,11,'2026-02-18','20:00:00','Bordeaux',150.00,'terminee','2026-02-18 05:19:08');

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  `prix_unitaire` decimal(10,2) DEFAULT NULL,
  `choices` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `order_items` VALUES (1,1,1,2,45.00,NULL),(2,2,1,2,45.00,NULL),(3,3,1,2,75.00,NULL),(4,4,7,2,75.00,NULL),(5,5,2,2,75.00,NULL),(6,6,3,2,75.00,NULL),(7,7,1,2,75.00,NULL),(8,8,7,2,75.00,NULL),(9,9,2,2,75.00,NULL),(10,10,7,2,75.00,NULL),(11,11,1,2,75.00,NULL),(12,12,2,2,75.00,NULL);

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `note` int(11) NOT NULL,
  `commentaire` text NOT NULL,
  `valide` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `reviews` VALUES
(1,1,1,5,'Une experience inoubliable ! Le service etait parfait et les plats delicieux.',1,'2026-02-18 04:39:34'),
(2,1,1,4,'Tres bon repas, livraison a l heure. Je recommande.',1,'2026-02-18 04:39:34'),
(3,1,1,5,'Julie et Jose sont au top ! Merci pour ce moment.',1,'2026-02-18 04:39:34'),
(4,1,2,5,'Une experience inoubliable ! Le service etait parfait et les plats delicieux.',1,'2026-02-18 04:39:34'),
(5,1,2,4,'Tres bon repas, livraison a l heure. Je recommande.',1,'2026-02-18 04:39:34'),
(6,1,2,5,'Julie et Jose sont au top ! Merci pour ce moment.',1,'2026-02-18 04:39:34'),
(7,2,3,5,'Un moment magique ! Le Menu Nouvel An etait divin, surtout le homard. Service impeccable.',1,'2026-02-18 06:19:08'),
(8,3,4,5,'Excellent rapport qualite-prix. Les specialites bordelaises sont authentiques.',1,'2026-02-18 06:19:08'),
(9,4,5,5,'Commande pour notre anniversaire. Le menu Saint Valentin etait tres romantique et delicieux.',1,'2026-02-18 06:19:08'),
(10,5,6,4,'Livraison ponctuelle et plats encore chauds. Je recommande le menu classique.',1,'2026-02-18 06:19:08'),
(11,6,7,5,'Une explosion de saveurs. Le foie gras est a tomber par terre.',1,'2026-02-18 06:19:08'),
(12,7,8,4,'Parfait pour un repas d entreprise. Tout le monde a adore.',1,'2026-02-18 06:19:08'),
(13,8,9,5,'C est ma troisieme commande et je ne suis jamais decue. L art de recevoir est au RDV.',1,'2026-02-18 06:19:08'),
(14,9,10,5,'Tres bonne experience. Les vins suggeres etaient parfaits.',1,'2026-02-18 06:19:08'),
(15,10,11,5,'Produits frais et de saison. On sent la passion du metier.',1,'2026-02-18 06:19:08'),
(16,11,12,5,'Une logistique impressionnante pour 50 personnes. Merci a Julie et Jose !',1,'2026-02-18 06:19:08');

DROP TABLE IF EXISTS `commandes`;
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
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS=1;
