<?php
/**
 * setup_dishes.php
 * 1. Inserts all 84 dishes into the `dishes` table
 * 2. Links them to menus via `menu_dishes`
 * Delete after use.
 */
include __DIR__ . '/noyau_backend/configuration/db.php';

// ---- 1. TRUNCATE & INSERT DISHES ----
$pdo->exec("DELETE FROM menu_dishes");
$pdo->exec("DELETE FROM dishes");

$dishes = [
    // ID => [nom, type, image_url, description, allergenes, is_vegan, is_gluten_free]
    1 => ['Foie Gras Maison au Sauternes', 'entree', 'https://images.unsplash.com/photo-1626082927389-d31c6d30a86c?auto=format&fit=crop&w=600&q=80', 'Foie gras de canard mi-cuit, servi avec une gelée de Sauternes et des toasts briochés. Un classique festif aux saveurs douces et sucrées.', 'Gluten, Lait, Œufs', 0, 0],
    2 => ['Saumon Gravlax et Blinis', 'entree', 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=600&q=80', 'Saumon mariné au sel, aneth et citron, tranché finement et accompagné de blinis moelleux et crème fraîche.', 'Poisson, Gluten, Lait, Œufs', 0, 0],
    3 => ['Velouté de Châtaignes aux Truffes', 'entree', 'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=600&q=80', 'Velouté onctueux de châtaignes parfumé à la truffe noire du Périgord, avec une touche de crème fraîche.', 'Lait, Céleri', 0, 1],
    4 => ['Tartare de Saint-Jacques aux Agrumes', 'entree', 'https://images.unsplash.com/photo-1559410545-0bdcd187e0a6?auto=format&fit=crop&w=600&q=80', 'Tartare de noix de Saint-Jacques fraîches marinées aux agrumes (citron, orange, pamplemousse) et coriandre.', 'Mollusques, Crustacés', 0, 1],
    5 => ['Dinde Rôtie aux Marrons', 'plat', 'https://images.unsplash.com/photo-1574672280600-4accfa5b6f98?auto=format&fit=crop&w=600&q=80', 'Dinde fermière rôtie lentement avec une farce aux marrons, champignons et herbes fraîches. Le plat emblématique de Noël.', 'Gluten, Céleri', 0, 0],
    6 => ['Filet de Bœuf en Croûte (Wellington)', 'plat', 'https://images.unsplash.com/photo-1600891964092-4316c288032e?auto=format&fit=crop&w=600&q=80', 'Filet de bœuf enveloppé dans une duxelles de champignons et une pâte feuilletée dorée. Le Wellington, symbole de la gastronomie britannique.', 'Gluten, Lait, Œufs, Moutarde', 0, 0],
    7 => ['Rôti de Butternut Farci aux Champignons', 'plat', 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80', 'Butternut rôti farci d\'un mélange de champignons sauvages, riz sauvage et herbes aromatiques. Option végane festive.', 'Céleri', 1, 1],
    8 => ['Risotto aux Truffes et Parmesan', 'plat', 'https://images.unsplash.com/photo-1476124369491-e7addf5db371?auto=format&fit=crop&w=600&q=80', 'Risotto crémeux aux truffes noires et parmesan affiné 24 mois. Un plat végétarien d\'exception.', 'Lait, Gluten', 0, 0],
    9 => ['Bûche Glacée Marron-Poire', 'dessert', 'https://images.unsplash.com/photo-1587314168485-3236d6710814?auto=format&fit=crop&w=600&q=80', 'Bûche glacée aux saveurs de marron glacé et poire Williams, sur un biscuit Joconde. Fraîcheur et gourmandise.', 'Lait, Œufs, Gluten, Fruits à coque', 0, 0],
    10 => ['Pavlova aux Fruits Rouges', 'dessert', 'https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&w=600&q=80', 'Meringue croustillante garnie de crème chantilly légère et de fruits rouges frais (framboises, myrtilles, fraises).', 'Œufs', 0, 1],
    11 => ['Mousse au Chocolat Noir Intense', 'dessert', 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?auto=format&fit=crop&w=600&q=80', 'Mousse aérienne au chocolat noir 70% de cacao, servie dans une verrine avec des éclats de fèves de cacao.', 'Lait, Œufs, Soja', 0, 1],
    12 => ['Salade d\'Oranges Épicée', 'dessert', 'https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=600&q=80', 'Salade d\'oranges sanguines et navel, parfumée à la cannelle, cardamome et eau de fleur d\'oranger. Légère et végane.', 'Aucun allergène majeur', 1, 1],

    13 => ['Huîtres du Bassin n°3', 'entree', 'https://images.unsplash.com/photo-1606731219412-3b1e9b9a0e8e?auto=format&fit=crop&w=600&q=80', 'Huîtres creuses n°3 du Bassin d\'Arcachon, servies sur lit d\'algues avec mignonette au vinaigre de Xérès.', 'Mollusques', 0, 1],
    14 => ['Lobster Roll Deluxe', 'entree', 'https://images.unsplash.com/photo-1559410545-0bdcd187e0a6?auto=format&fit=crop&w=600&q=80', 'Pain brioché toasté garni de chair de homard, mayonnaise maison au citron et ciboulette fraîche. Un luxe accessible.', 'Crustacés, Gluten, Œufs, Lait', 0, 0],
    15 => ['Toast de Faux-Gras Végétal', 'entree', 'https://images.unsplash.com/photo-1626082927389-d31c6d30a86c?auto=format&fit=crop&w=600&q=80', 'Toast de faux-gras végétal à base de noix de cajou et champignons, avec confiture de figues. 100% végane.', 'Fruits à coque, Gluten', 1, 0],
    16 => ['Carpaccio de Betteraves Fumé', 'entree', 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=600&q=80', 'Betteraves fumées à froid, tranchées en carpaccio avec huile d\'olive, câpres et herbes fraîches. Végane et sans gluten.', 'Aucun allergène majeur', 1, 1],
    17 => ['Chapon Fin aux Morilles', 'plat', 'https://images.unsplash.com/photo-1574672280600-4accfa5b6f98?auto=format&fit=crop&w=600&q=80', 'Chapon fermier rôti aux morilles fraîches et sauce à la crème, accompagné d\'une purée de céleri-rave.', 'Lait, Céleri', 0, 1],
    18 => ['Dos de Cabillaud Sauce Champagne', 'plat', 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=600&q=80', 'Dos de cabillaud nacré cuit à basse température, nappé d\'une sauce champagne et accompagné d\'asperges vertes.', 'Poisson, Lait, Mollusques', 0, 1],
    19 => ['Seitan Laqué aux Épices', 'plat', 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80', 'Seitan laqué aux cinq épices, sauce soja et miel d\'acacia, servi avec un wok de légumes croquants. Option végane.', 'Gluten, Soja, Sésame', 1, 0],
    20 => ['Risotto de Sarrasin aux Légumes Oubliés', 'plat', 'https://images.unsplash.com/photo-1476124369491-e7addf5db371?auto=format&fit=crop&w=600&q=80', 'Risotto de sarrasin (sans gluten) aux légumes anciens (panais, topinambour, rutabaga) et huile de truffe.', 'Lait', 0, 1],
    21 => ['Omelette Norvégienne', 'dessert', 'https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=600&q=80', 'Génoise imbibée de rhum, garnie d\'une crème glacée vanille et flambée au moment du service. Un spectacle gustatif.', 'Lait, Œufs, Gluten', 0, 0],
    22 => ['Macarons Foie Gras & Figue', 'dessert', 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?auto=format&fit=crop&w=600&q=80', 'Macarons fins garnis d\'une ganache foie gras et confiture de figues. L\'alliance du sucré-salé en une bouchée.', 'Œufs, Fruits à coque, Lait', 0, 1],
    23 => ['Fondant Chocolat-Avocat', 'dessert', 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?auto=format&fit=crop&w=600&q=80', 'Fondant au chocolat noir enrichi d\'avocat pour une texture ultra-crémeuse. 100% végane et sans produits laitiers.', 'Gluten, Soja', 1, 0],
    24 => ['Carpaccio d\'Ananas Rôti', 'dessert', 'https://images.unsplash.com/photo-1587314168485-3236d6710814?auto=format&fit=crop&w=600&q=80', 'Ananas Victoria rôti au beurre et cassonade, servi en carpaccio avec un sorbet coco et menthe fraîche.', 'Lait', 0, 1],

    25 => ['Duo de Saumon Cœur', 'entree', 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=600&q=80', 'Duo de saumon fumé et saumon mi-cuit, dressé en cœur avec crème citronnée et œufs de truite.', 'Poisson, Lait, Œufs', 0, 1],
    26 => ['Cappuccino de Champignons', 'entree', 'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=600&q=80', 'Cappuccino de champignons des bois (cèpes, girolles) avec une mousse de lait truffée et chips de parmesan.', 'Lait', 0, 1],
    27 => ['Tartare de Tomates Cœur de Bœuf', 'entree', 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=600&q=80', 'Tartare de tomates cœur de bœuf assaisonnées à l\'huile d\'olive, basilic et fleur de sel. Frais et végane.', 'Aucun allergène majeur', 1, 1],
    28 => ['Aumônière de Chèvre Miel', 'entree', 'https://images.unsplash.com/photo-1626082927389-d31c6d30a86c?auto=format&fit=crop&w=600&q=80', 'Aumônière de pâte filo croustillante garnie de chèvre frais, miel de lavande et noix concassées.', 'Gluten, Lait, Fruits à coque', 0, 0],
    29 => ['Magret de Canard Sauce Griottes', 'plat', 'https://images.unsplash.com/photo-1600891964092-4316c288032e?auto=format&fit=crop&w=600&q=80', 'Magret de canard rosé, sauce aux griottes et porto, accompagné d\'un gratin dauphinois à la truffe.', 'Lait, Sulfites', 0, 1],
    30 => ['Risotto aux Asperges Vertes', 'plat', 'https://images.unsplash.com/photo-1476124369491-e7addf5db371?auto=format&fit=crop&w=600&q=80', 'Risotto crémeux aux asperges vertes du Blayais, parmesan et zeste de citron. Végétarien et raffiné.', 'Lait', 0, 1],
    31 => ['Curry de Légumes d\'Amour', 'plat', 'https://images.unsplash.com/photo-1455619452474-d2be8b1e70cd?auto=format&fit=crop&w=600&q=80', 'Curry doux de légumes de saison (potimarron, pois chiches, épinards) au lait de coco et curcuma. 100% végane.', 'Aucun allergène majeur', 1, 1],
    32 => ['Ravioles aux Truffes', 'plat', 'https://images.unsplash.com/photo-1476124369491-e7addf5db371?auto=format&fit=crop&w=600&q=80', 'Ravioles fraîches farcies aux truffes noires et ricotta, nappées d\'un beurre noisette et sauge.', 'Gluten, Lait, Œufs', 0, 0],
    33 => ['Cœur Coulant Chocolat', 'dessert', 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?auto=format&fit=crop&w=600&q=80', 'Fondant au chocolat noir 70% avec cœur coulant, servi chaud avec une quenelle de glace vanille Bourbon.', 'Lait, Œufs, Gluten', 0, 0],
    34 => ['Tiramisu aux Framboises', 'dessert', 'https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=600&q=80', 'Tiramisu revisité aux framboises fraîches et coulis, avec biscuits cuillère imbibés de sirop de framboise.', 'Lait, Œufs, Gluten', 0, 0],
    35 => ['Panna Cotta Coco-Rose', 'dessert', 'https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&w=600&q=80', 'Panna cotta légère au lait de coco et eau de rose, décorée de pétales de rose cristallisés. Végane et sans gluten.', 'Aucun allergène majeur', 1, 1],
    36 => ['Sorbet Passion-Gingembre', 'dessert', 'https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&w=600&q=80', 'Sorbet intense aux fruits de la passion et gingembre frais, servi dans une demi-coque de fruit de la passion.', 'Aucun allergène majeur', 1, 1],

    37 => ['Nems au Poulet', 'entree', 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=600&q=80', 'Nems croustillants au poulet, vermicelles de riz et légumes, servis avec sauce nuoc-mâm et salade fraîche.', 'Gluten, Poisson, Soja, Sésame', 0, 0],
    38 => ['Rouleaux de Printemps Crevettes', 'entree', 'https://images.unsplash.com/photo-1559410545-0bdcd187e0a6?auto=format&fit=crop&w=600&q=80', 'Rouleaux de printemps frais aux crevettes, vermicelles, menthe et carottes, avec sauce cacahuète.', 'Crustacés, Gluten, Arachides, Soja', 0, 0],
    39 => ['Samossas Légumes', 'entree', 'https://images.unsplash.com/photo-1626082927389-d31c6d30a86c?auto=format&fit=crop&w=600&q=80', 'Samossas croustillants aux légumes épicés (pommes de terre, petits pois, cumin), servis avec chutney menthe.', 'Gluten', 1, 0],
    40 => ['Salade de Papaye Verte', 'entree', 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=600&q=80', 'Salade de papaye verte râpée à la thaïlandaise, avec tomates cerises, cacahuètes et sauce citron-piment.', 'Arachides, Poisson', 0, 1],
    41 => ['Pad Thaï Poulet', 'plat', 'https://images.unsplash.com/photo-1600891964092-4316c288032e?auto=format&fit=crop&w=600&q=80', 'Pad Thaï authentique au poulet, vermicelles de riz, œufs, germes de soja et cacahuètes concassées.', 'Gluten, Œufs, Arachides, Soja, Poisson', 0, 0],
    42 => ['Curry Vert Crevettes', 'plat', 'https://images.unsplash.com/photo-1455619452474-d2be8b1e70cd?auto=format&fit=crop&w=600&q=80', 'Curry vert thaï aux crevettes royales, lait de coco, aubergines et feuilles de kaffir. Parfumé et légèrement piquant.', 'Crustacés, Poisson, Lait', 0, 1],
    43 => ['Wok de Légumes Tofu', 'plat', 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80', 'Wok de légumes croquants (brocoli, poivrons, champignons shiitake) et tofu ferme, sauce soja-gingembre. Végane.', 'Soja, Sésame', 1, 1],
    44 => ['Curry Rouge Végétal', 'plat', 'https://images.unsplash.com/photo-1455619452474-d2be8b1e70cd?auto=format&fit=crop&w=600&q=80', 'Curry rouge végétal au lait de coco, pois chiches, épinards et patate douce. 100% végane et sans gluten.', 'Aucun allergène majeur', 1, 1],
    45 => ['Perles de Coco', 'dessert', 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?auto=format&fit=crop&w=600&q=80', 'Perles de tapioca au lait de coco sucré, servies tièdes avec un coulis de mangue et graines de sésame.', 'Sésame', 1, 1],
    46 => ['Mangue Sticky Rice', 'dessert', 'https://images.unsplash.com/photo-1587314168485-3236d6710814?auto=format&fit=crop&w=600&q=80', 'Riz gluant à la mangue fraîche, lait de coco sucré et graines de sésame torréfiées. Dessert thaï emblématique.', 'Sésame', 1, 1],
    47 => ['Salade de Fruits Exotiques', 'dessert', 'https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=600&q=80', 'Assortiment de fruits exotiques frais (mangue, papaye, litchi, carambole) avec sirop de citronnelle.', 'Aucun allergène majeur', 1, 1],
    48 => ['Mochi Glacé Thé Vert', 'dessert', 'https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=600&q=80', 'Mochi glacé au thé matcha, enveloppe de pâte de riz gluant, cœur de glace thé vert. Délicat et rafraîchissant.', 'Lait, Soja', 0, 1],

    49 => ['Houmous et Pain Pita', 'entree', 'https://images.unsplash.com/photo-1626082927389-d31c6d30a86c?auto=format&fit=crop&w=600&q=80', 'Houmous onctueux de pois chiches au tahini, huile d\'olive et paprika fumé, avec pain pita chaud. Végane.', 'Gluten, Sésame', 1, 0],
    50 => ['Taboulé de Quinoa', 'entree', 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=600&q=80', 'Taboulé de quinoa aux herbes fraîches (menthe, persil), tomates cerises, concombre et citron. Sans gluten.', 'Aucun allergène majeur', 1, 1],
    51 => ['Velouté de Courge', 'entree', 'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=600&q=80', 'Velouté de courge butternut rôtie, lait de coco et gingembre frais. Doux, réconfortant et 100% végane.', 'Aucun allergène majeur', 1, 1],
    52 => ['Tartinade d\'Artichaut', 'entree', 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=600&q=80', 'Tartinade crémeuse d\'artichauts marinés, câpres et citron, servie avec des crackers sans gluten.', 'Aucun allergène majeur', 1, 1],
    53 => ['Lasagnes Végétales', 'plat', 'https://images.unsplash.com/photo-1476124369491-e7addf5db371?auto=format&fit=crop&w=600&q=80', 'Lasagnes végétales aux légumes grillés (courgettes, aubergines, poivrons) et béchamel à l\'avoine.', 'Gluten, Soja', 1, 0],
    54 => ['Dahl de Lentilles Corail', 'plat', 'https://images.unsplash.com/photo-1455619452474-d2be8b1e70cd?auto=format&fit=crop&w=600&q=80', 'Dahl de lentilles corail au lait de coco, curcuma, cumin et coriandre fraîche. Protéiné et sans gluten.', 'Aucun allergène majeur', 1, 1],
    55 => ['Burger Végétal', 'plat', 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80', 'Burger végétal avec steak de haricots noirs, avocat, tomate et sauce chipotle végane dans un pain artisanal.', 'Gluten, Soja, Sésame', 1, 0],
    56 => ['Buddha Bowl de Saison', 'plat', 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80', 'Buddha bowl coloré avec quinoa, légumes rôtis, pois chiches croustillants, avocat et sauce tahini-citron.', 'Sésame', 1, 1],
    57 => ['Cookie Vegan Chocolat', 'dessert', 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?auto=format&fit=crop&w=600&q=80', 'Cookie moelleux au chocolat noir, beurre de cacahuète et flocons d\'avoine. 100% végane et généreux.', 'Gluten, Arachides, Soja', 1, 0],
    58 => ['Chia Pudding Lait de Coco', 'dessert', 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?auto=format&fit=crop&w=600&q=80', 'Pudding de graines de chia au lait de coco, vanille et coulis de fruits rouges. Riche en oméga-3.', 'Aucun allergène majeur', 1, 1],
    59 => ['Compote Pomme-Cannelle', 'dessert', 'https://images.unsplash.com/photo-1587314168485-3236d6710814?auto=format&fit=crop&w=600&q=80', 'Compote de pommes Golden à la cannelle et cardamome, servie tiède avec un crumble d\'avoine végane.', 'Gluten', 1, 0],
    60 => ['Cake Citron-Pavot', 'dessert', 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?auto=format&fit=crop&w=600&q=80', 'Cake moelleux au citron et graines de pavot, glaçage au citron vert. Végane et plein de fraîcheur.', 'Gluten, Soja', 1, 0],

    61 => ['Rillettes de Thon', 'entree', 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=600&q=80', 'Rillettes de thon albacore à l\'huile d\'olive, câpres et citron, servies avec des toasts grillés.', 'Poisson, Gluten', 0, 0],
    62 => ['Crevettes Sauce Cocktail', 'entree', 'https://images.unsplash.com/photo-1559410545-0bdcd187e0a6?auto=format&fit=crop&w=600&q=80', 'Crevettes roses décortiquées, sauce cocktail maison (ketchup, mayonnaise, Tabasco) et salade verte.', 'Crustacés, Œufs, Moutarde', 0, 1],
    63 => ['Tartare d\'Algues', 'entree', 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=600&q=80', 'Tartare d\'algues (wakamé, nori) à l\'huile de sésame, gingembre et citron vert. Végane et iodé.', 'Sésame, Mollusques', 1, 1],
    64 => ['Soupe de Poisson', 'entree', 'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=600&q=80', 'Soupe de poisson traditionnelle avec rouille maison, croûtons grillés et gruyère râpé.', 'Poisson, Gluten, Lait, Œufs, Moutarde', 0, 0],
    65 => ['Filet de Bar Rôti', 'plat', 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=600&q=80', 'Filet de bar de ligne rôti au beurre citronné, accompagné de légumes de saison et sauce vierge.', 'Poisson, Lait', 0, 1],
    66 => ['Moules Marinières', 'plat', 'https://images.unsplash.com/photo-1606731219412-3b1e9b9a0e8e?auto=format&fit=crop&w=600&q=80', 'Moules de bouchot marinières au vin blanc, échalotes et persil frais. Servies avec frites maison.', 'Mollusques, Gluten, Lait, Sulfites', 0, 0],
    67 => ['Paella de la Mer', 'plat', 'https://images.unsplash.com/photo-1534080564583-6be75777b70a?auto=format&fit=crop&w=600&q=80', 'Paella valenciana aux fruits de mer (crevettes, moules, palourdes), poulet et chorizo, safran et paprika.', 'Crustacés, Mollusques, Gluten', 0, 1],
    68 => ['Cassolette de la Mer', 'plat', 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=600&q=80', 'Cassolette de fruits de mer (Saint-Jacques, crevettes, moules) en sauce crème et vin blanc, gratinée.', 'Crustacés, Mollusques, Lait, Gluten', 0, 0],
    69 => ['Baba au Rhum', 'dessert', 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?auto=format&fit=crop&w=600&q=80', 'Baba au rhum traditionnel, imbibé de sirop vanillé et rhum ambré, avec crème chantilly et fruits frais.', 'Gluten, Lait, Œufs', 0, 0],
    70 => ['Ile Flottante', 'dessert', 'https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=600&q=80', 'Île flottante classique : blancs en neige pochés sur crème anglaise vanillée, caramel et amandes effilées.', 'Lait, Œufs, Fruits à coque', 0, 1],
    71 => ['Sorbet Citron', 'dessert', 'https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&w=600&q=80', 'Sorbet citron de Menton, servi dans une coque de citron givré avec zestes confits. Frais et acidulé.', 'Aucun allergène majeur', 1, 1],
    72 => ['Tarte au Citron Meringuée', 'dessert', 'https://images.unsplash.com/photo-1587314168485-3236d6710814?auto=format&fit=crop&w=600&q=80', 'Tarte au citron meringuée avec une pâte sablée croustillante, crème citron intense et meringue italienne.', 'Gluten, Lait, Œufs', 0, 0],

    73 => ['Grattons de Bordeaux', 'entree', 'https://images.unsplash.com/photo-1626082927389-d31c6d30a86c?auto=format&fit=crop&w=600&q=80', 'Grattons de porc confits, croustillants et fondants, spécialité bordelaise servie avec moutarde à l\'ancienne.', 'Moutarde', 0, 1],
    74 => ['Asperges du Blayais', 'entree', 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=600&q=80', 'Asperges blanches du Blayais, cuites à la vapeur, vinaigrette à l\'huile de noix et œuf mimosa.', 'Œufs, Fruits à coque, Moutarde', 0, 1],
    75 => ['Salade Landaise', 'entree', 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=600&q=80', 'Salade landaise traditionnelle : gésiers confits, magret fumé, foie gras poêlé, pignons et vinaigrette chaude.', 'Fruits à coque, Moutarde', 0, 1],
    76 => ['Huîtres d\'Arcachon', 'entree', 'https://images.unsplash.com/photo-1606731219412-3b1e9b9a0e8e?auto=format&fit=crop&w=600&q=80', 'Huîtres creuses d\'Arcachon n°2, servies sur glace avec pain de seigle beurré et citron. La mer dans l\'assiette.', 'Mollusques, Gluten, Lait', 0, 0],
    77 => ['Entrecôte à la Bordelaise', 'plat', 'https://images.unsplash.com/photo-1600891964092-4316c288032e?auto=format&fit=crop&w=600&q=80', 'Entrecôte de bœuf Bazas grillée, sauce bordelaise au vin rouge et moelle, accompagnée de pommes sarladaises.', 'Sulfites', 0, 1],
    78 => ['Lamproie à la Bordelaise', 'plat', 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=600&q=80', 'Lamproie à la bordelaise, mijotée dans une sauce au vin rouge de Saint-Émilion et poireaux. Spécialité rare.', 'Poisson, Sulfites', 0, 1],
    79 => ['Magret de Canard Grillé', 'plat', 'https://images.unsplash.com/photo-1600891964092-4316c288032e?auto=format&fit=crop&w=600&q=80', 'Magret de canard des Landes grillé à la braise, servi rosé avec une sauce aux cèpes et pommes de terre à la graisse de canard.', 'Aucun allergène majeur', 0, 1],
    80 => ['Cèpes de Bordeaux à la Persillade', 'plat', 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80', 'Cèpes de Bordeaux sautés à la persillade (ail, persil, échalote), servis sur toast de pain de campagne grillé.', 'Gluten', 1, 0],
    81 => ['Canelés de Bordeaux', 'dessert', 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?auto=format&fit=crop&w=600&q=80', 'Canelés bordelais authentiques : croûte caramélisée et cœur moelleux à la vanille et rhum. La pâtisserie emblématique.', 'Lait, Œufs, Gluten', 0, 0],
    82 => ['Dunes Blanches', 'dessert', 'https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&w=600&q=80', 'Dunes blanches : meringues légères fourrées de crème chantilly et framboises. Spécialité du Bassin d\'Arcachon.', 'Lait, Œufs', 0, 1],
    83 => ['Macarons de Saint-Émilion', 'dessert', 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?auto=format&fit=crop&w=600&q=80', 'Macarons de Saint-Émilion à base d\'amandes, blancs d\'œufs et sucre. Moelleux et parfumés, sans farine.', 'Fruits à coque, Œufs', 0, 1],
    84 => ['Sorbet au Vin Rouge (sans alcool)', 'dessert', 'https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&w=600&q=80', 'Sorbet au vin rouge de Bordeaux (sans alcool résiduel), avec notes de cassis et épices douces. Original et élégant.', 'Sulfites', 1, 1],
];

$insertDish = $pdo->prepare("
    INSERT INTO dishes (id, nom, type, image_url, description, allergenes, is_vegan, is_gluten_free)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE
        nom=VALUES(nom), type=VALUES(type), image_url=VALUES(image_url),
        description=VALUES(description), allergenes=VALUES(allergenes),
        is_vegan=VALUES(is_vegan), is_gluten_free=VALUES(is_gluten_free)
");

$inserted = 0;
foreach ($dishes as $id => $d) {
    $insertDish->execute([$id, $d[0], $d[1], $d[2], $d[3], $d[4], $d[5], $d[6]]);
    $inserted++;
}
echo "<p>✅ $inserted plats insérés/mis à jour</p>";

// ---- 2. LINK DISHES TO MENUS ----
// Fetch all menus to know their IDs and themes
$menus = $pdo->query("SELECT id, titre, theme FROM menus ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
echo "<h3>Menus trouvés :</h3><ul>";
foreach ($menus as $m)
    echo "<li>ID {$m['id']} — {$m['titre']} (thème: {$m['theme']})</li>";
echo "</ul>";

// Map menu IDs to dish groups
// We'll assign dishes based on theme
$menuDishMap = [];
foreach ($menus as $m) {
    $theme = strtolower($m['theme'] ?? '');
    $mid = $m['id'];

    if (str_contains($theme, 'no') && str_contains($theme, 'l')) {
        // Noël
        $menuDishMap[$mid] = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
    }
    elseif (str_contains($theme, 'prestige') || str_contains($theme, 'festif')) {
        $menuDishMap[$mid] = [13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24];
    }
    elseif (str_contains($theme, 'valentin') || str_contains($theme, 'amour')) {
        $menuDishMap[$mid] = [25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36];
    }
    elseif (str_contains($theme, 'asie') || str_contains($theme, 'voyage') || str_contains($theme, 'exotique')) {
        $menuDishMap[$mid] = [37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48];
    }
    elseif (str_contains($theme, 'v') && (str_contains($theme, 'gan') || str_contains($theme, 'g'))) {
        $menuDishMap[$mid] = [49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60];
    }
    elseif (str_contains($theme, 'mer') || str_contains($theme, 'ocean') || str_contains($theme, 'poisson')) {
        $menuDishMap[$mid] = [61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72];
    }
    elseif (str_contains($theme, 'bordeaux') || str_contains($theme, 'terroir') || str_contains($theme, 'classique')) {
        $menuDishMap[$mid] = [73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84];
    }
    else {
        // Default: assign first 12 dishes
        $menuDishMap[$mid] = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
    }
}

$insertLink = $pdo->prepare("INSERT IGNORE INTO menu_dishes (menu_id, dish_id) VALUES (?, ?)");
$links = 0;
foreach ($menuDishMap as $menuId => $dishIds) {
    foreach ($dishIds as $dishId) {
        $insertLink->execute([$menuId, $dishId]);
        $links++;
    }
}
echo "<p>✅ $links liaisons menu-plat créées</p>";
echo "<p>🎉 Tout est prêt ! Supprimez ce fichier.</p>";
?>
