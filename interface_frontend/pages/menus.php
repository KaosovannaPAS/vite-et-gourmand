<?php
session_start();
include __DIR__ . '/../../noyau_backend/configuration/db.php';
?>
<?php include __DIR__ . '/../composants/header.php'; ?>

<style>
    /* Slider Ovale Doré */
    .oval-slider {
        -webkit-appearance: none;
        width: 100%;
        height: 10px;
        border-radius: 5px;
        background: rgba(255, 255, 255, 0.2);
        outline: none;
        border: none;
    }
    .oval-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 30px; 
        height: 18px; 
        border-radius: 10px; 
        background: var(--secondary-color);
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
    }
    .oval-slider::-moz-range-thumb {
        width: 30px;
        height: 18px;
        border-radius: 10px;
        background: var(--secondary-color);
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
    }

    /* ===== MENU CARDS (BAROQUE STYLE) ===== */
    .menu-card-inner {
        background: #121212; /* Dark BG */
        border: 2px solid var(--secondary-color); /* Gold Border */
        border-radius: 4px; /* Sharper corners for greek style */
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
    }
    /* Inner border effect */
    .menu-card-inner::after {
        content: '';
        position: absolute;
        inset: 4px; /* 4px spacing */
        border: 1px solid var(--secondary-color);
        pointer-events: none;
        z-index: 10;
    }
    
    .menu-card-inner:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 48px rgba(0,0,0,0.5);
    }

    /* Image */
    .menu-img-wrap {
        position: relative;
        height: 230px;
        flex-shrink: 0;
        overflow: hidden;
        border-bottom: 2px solid var(--secondary-color);
    }
    .menu-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.5s ease;
    }
    .menu-card-inner:hover .menu-img-wrap img {
        transform: scale(1.05);
    }
    .menu-img-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.6) 100%);
    }

    /* Price badge */
    .menu-price-badge {
        position: absolute;
        top: 14px;
        right: 14px;
        background: #121212;
        color: var(--secondary-color);
        border: 1px solid var(--secondary-color);
        font-weight: 900;
        font-size: 1.25rem;
        padding: 8px 14px;
        line-height: 1;
        box-shadow: 0 4px 14px rgba(0,0,0,0.5);
        text-align: center;
        z-index: 20;
    }
    .menu-price-badge span {
        display: block;
        font-size: 0.65rem;
        font-weight: 600;
        color: #fff;
        margin-top: 2px;
    }

    /* Stock badges */
    .menu-badge {
        position: absolute;
        top: 14px;
        left: 14px;
        padding: 5px 10px;
        border-radius: 0;
        font-size: 0.78rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 5px;
        z-index: 20;
        border: 1px solid rgba(255,255,255,0.2);
    }
    .badge-rupture { background: #5a0015; color: #fff; border-color: #c0392b; }
    .badge-urgent  { background: #e67e22; color: #000; }

    /* Body */
    .menu-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        flex: 1;
        gap: 0.75rem;
        text-align: center; /* Centered text for baroque look */
    }
    .menu-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.6rem;
        color: var(--secondary-color); /* Gold title */
        margin: 0;
        line-height: 1.25;
        border-bottom: 1px solid rgba(212, 175, 55, 0.3);
        padding-bottom: 0.6rem;
        font-style: italic;
    }
    .menu-desc {
        font-size: 0.92rem;
        color: #ddd; /* Light text */
        line-height: 1.55;
        margin: 0;
    }

    /* Pills */
    .menu-pills {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        justify-content: center;
    }
    .menu-pill {
        background: rgba(255, 255, 255, 0.05);
        color: #eee;
        border: 1px solid var(--secondary-color);
        border-radius: 0;
        padding: 4px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .menu-pill i { color: var(--secondary-color); }

    /* Dishes */
    .menu-dishes {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(212, 175, 55, 0.2);
        padding: 0.75rem 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        text-align: left;
    }
    .dish-row {
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: #ccc;
    }
    .dish-label {
        font-weight: 700;
        color: var(--secondary-color);
        min-width: 55px;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Conditions */
    .menu-conditions {
        font-size: 0.78rem;
        color: #888;
        margin: 0;
        display: flex;
        gap: 5px;
        align-items: flex-start;
        line-height: 1.4;
    }
    .menu-conditions i { color: var(--secondary-color); margin-top: 2px; flex-shrink: 0; }

    /* CTA */
    .menu-cta {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: auto;
        padding: 14px;
        background: var(--primary-color);
        color: var(--secondary-color);
        font-weight: 700;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-radius: 10px;
        text-decoration: none;
        transition: background 0.25s, transform 0.2s;
    }
    .menu-cta:hover {
        background: #5a0015;
        transform: translateY(-2px);
    }
    .menu-cta i { transition: transform 0.2s; }
    .menu-cta:hover i { transform: translateX(4px); }

    /* ===== THEME SECTIONS ===== */
    #menus-grid {
        display: block !important; /* override inline grid for grouped layout */
    }
    .theme-section-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin: 2.5rem 0 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--secondary-color);
    }
    .theme-section-header:first-child { margin-top: 0; }
    .theme-icon {
        font-size: 1.8rem;
        line-height: 1;
    }
    .theme-section-header > span:last-child {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        color: var(--primary-color);
        font-weight: 700;
    }
    .theme-cards-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.75rem;
        margin-bottom: 1rem;
    }
    /* Flat grid when filters active */
    #menus-grid:has(> .menu-card) {
        display: grid !important;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.75rem;
    }
    .menu-card { height: 100%; }
</style>

<div class="container" style="padding: 2rem 0; display: flex; gap: 2rem;">
    
    <!-- Sidebar Filtres -->
    <aside style="width: 280px; flex-shrink: 0;">
        <div class="baroque-card" style="padding: 2rem; color: white;">
            <h3 class="baroque-title" style="margin-bottom: 2rem; border-bottom: 1px solid var(--secondary-color); padding-bottom: 0.5rem;">Filtres</h3>
            
            <div class="filter-group" style="margin-bottom: 1.5rem;">
                <label for="theme" style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: var(--secondary-color);">Thème</label>
                <select id="theme" class="form-control" style="width: 100%; padding: 0.8rem; background: rgba(0,0,0,0.2); color: white; border: 1px solid var(--secondary-color);">
                    <option value="" style="background: var(--primary-color);">🍽️ Tous les événements</option>
                    <option value="Noël" style="background: var(--primary-color);">🎄 Noël</option>
                    <option value="Prestige" style="background: var(--primary-color);">⭐ Prestige Festif</option>
                    <option value="Saint Valentin" style="background: var(--primary-color);">❤️ Saint-Valentin</option>
                    <option value="Voyage en Asie" style="background: var(--primary-color);">🌏 Voyage en Asie</option>
                    <option value="Végane" style="background: var(--primary-color);">🌿 Végane</option>
                    <option value="Mer" style="background: var(--primary-color);">🌊 Saveurs de la Mer</option>
                    <option value="Terroir" style="background: var(--primary-color);">🍷 Terroir Bordelais</option>
                </select>
            </div>

            <div class="filter-group" style="margin-bottom: 1.5rem;">
                <label for="regime" style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: var(--secondary-color);">Régime</label>
                <select id="regime" class="form-control" style="width: 100%; padding: 0.8rem; background: rgba(0,0,0,0.2); color: white; border: 1px solid var(--secondary-color);">
                    <option value="" style="background: var(--primary-color);">Tous</option>
                    <option value="Classique" style="background: var(--primary-color);">Classique</option>
                    <option value="Végan" style="background: var(--primary-color);">Végan</option>
                    <option value="Végétarien" style="background: var(--primary-color);">Végétarien</option>
                    <option value="Pescétarien" style="background: var(--primary-color);">Pescétarien</option>
                </select>
            </div>

            <div class="filter-group" style="margin-bottom: 1.5rem;">
                <label for="min_people" style="display: block; margin-bottom: 0.8rem; font-weight: bold; color: var(--secondary-color);">Nb Personnes Min.</label>
                <input type="range" id="min_people" min="1" max="50" value="2" class="oval-slider" style="background: rgba(255,255,255,0.1);">
                <div style="text-align: center; font-weight: bold; margin-top: 0.5rem;"><span id="min_people_display">2</span> Pers.</div>
            </div>

            <div class="filter-group" style="margin-bottom: 1.5rem;">
                <label for="max_price" style="display: block; margin-bottom: 0.8rem; font-weight: bold; color: var(--secondary-color);">Budget Max / Pers. (€)</label>
                <input type="range" id="max_price" min="10" max="150" value="150" class="oval-slider" style="background: rgba(255,255,255,0.1);">
                <div style="text-align: center; font-weight: bold; margin-top: 0.5rem;"><span id="price_display">150 €</span></div>
            </div>
            
            <!-- BOUTON RESET -->
            <button id="reset_filters" class="btn btn-primary" style="width: 100%; margin-top: 1rem; background: rgba(0,0,0,0.3); border: 1px solid var(--secondary-color); color: var(--secondary-color);">
                <i class="fas fa-undo"></i> Effacer les filtres
            </button>

            <hr style="border: 0; border-top: 1px solid var(--secondary-color); margin: 2rem 0; opacity: 0.3;">

            <!-- SIMULATEUR -->
            <div style="margin-top: 2rem;">
                <h3 class="baroque-title" style="margin-bottom: 1.5rem; font-size: 1.4rem;">Simulateur</h3>
                
                <div style="margin-bottom: 1.5rem;">
                    <label for="sim_people" style="display: block; margin-bottom: 0.8rem; color: var(--secondary-color);">Nombre d'invités</label>
                    <input type="range" id="sim_people" min="2" max="100" value="10" class="oval-slider" style="background: rgba(255,255,255,0.1);">
                    <div style="text-align: center; font-weight: bold; margin-top: 0.5rem;"><span id="sim_people_display">10</span> Personnes</div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="sim_date" style="display: block; margin-bottom: 0.5rem; color: var(--secondary-color);">Date Prestation</label>
                    <input type="date" id="sim_date" style="width: 100%; padding: 0.8rem; background: rgba(0,0,0,0.2); color: white; border: 1px solid var(--secondary-color);">
                </div>
                
                <div style="margin-bottom: 1.5rem;">
                    <label for="sim_address" style="display: block; margin-bottom: 0.5rem; color: var(--secondary-color);">Ville Livraison</label>
                    <input type="text" id="sim_address" placeholder="Ex: Bordeaux" style="width: 100%; padding: 0.8rem; background: rgba(0,0,0,0.2); color: white; border: 1px solid var(--secondary-color);">
                    <input type="number" id="sim_distance" placeholder="Distance (km)" style="width: 100%; padding: 0.8rem; margin-top: 0.5rem; display: none; background: rgba(0,0,0,0.2); color: white; border: 1px solid var(--secondary-color);">
                    <small style="color: rgba(255,255,255,0.7); font-style: italic; display: block; margin-top: 0.5rem;">Gratuit sur Bordeaux. Autre : 5€ de base + 0.59€/km.</small>
                </div>

                <div id="logistics_fee_display" style="background: var(--secondary-color); color: var(--primary-color); padding: 1rem; border-radius: 5px; text-align: center; margin-bottom: 1rem; font-weight: bold; box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
                    Frais de livraison : <strong>0.00 €</strong>
                </div>
                
                <div id="discount_msg" style="display: none; color: #2ecc71; font-weight: bold; text-align: center; font-size: 0.9rem; margin-top: 1rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
                    <i class="fas fa-gift"></i> Réduction de 10% appliquée !
                </div>
            </div>
        </div>
    </aside>

    <section style="flex: 1;">
        <h2 style="margin-bottom: 2rem;">Nos propositions gourmandes</h2>
        <div id="menus-grid" style="display: block;">
            <?php
// SSR LOGIC
// 1. Fetch Menus
$stmt = $pdo->query("SELECT * FROM menus WHERE actif = 1 ORDER BY FIELD(theme, 'Noël', 'Prestige', 'Saint Valentin', 'Voyage en Asie', 'Végane', 'Mer', 'Terroir', 'Classique', 'Événement'), titre");
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. Fetch Condensed Dishes
$dishesByMenu = [];
if (!empty($menus)) {
    $menuIds = array_column($menus, 'id');
    $inQuery = implode(',', array_fill(0, count($menuIds), '?'));
    $dStmt = $pdo->prepare("
                    SELECT md.menu_id, d.type, d.nom
                    FROM menu_dishes md
                    JOIN dishes d ON md.dish_id = d.id
                    WHERE md.menu_id IN ($inQuery)
                    ORDER BY FIELD(d.type, 'apero', 'entree', 'plat', 'dessert')
                ");
    $dStmt->execute($menuIds);
    while ($row = $dStmt->fetch(PDO::FETCH_ASSOC)) {
        $mid = $row['menu_id'];
        $type = $row['type'];
        if (!isset($dishesByMenu[$mid]))
            $dishesByMenu[$mid] = [];
        // Keep one dish per type as representative
        if (!isset($dishesByMenu[$mid][$type])) {
            $dishesByMenu[$mid][$type] = $row['nom'];
        }
    }
}

// 3. Helper Render Function
function renderMenuCard($menu, $dishesByMenu)
{
    $imgSrc = str_replace('/Vite-et-gourmand/', '/', $menu['image_url'] ?? '');
    if (!$imgSrc)
        $imgSrc = 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600&q=80';

    $stockBadge = '';
    if ($menu['stock'] === 0) {
        $stockBadge = '<span class="menu-badge badge-rupture"><i class="fas fa-times-circle"></i> Rupture</span>';
    }
    elseif ($menu['stock'] < 10) {
        $stockBadge = '<span class="menu-badge badge-urgent"><i class="fas fa-fire"></i> Plus que ' . $menu['stock'] . ' !</span>';
    }

    $dishesHtml = '';
    if (isset($dishesByMenu[$menu['id']])) {
        $cd = $dishesByMenu[$menu['id']];
        $dishesHtml = '<div class="menu-dishes">';
        if (isset($cd['entree']))
            $dishesHtml .= '<div class="dish-row"><span class="dish-label">Entrée</span><span>' . htmlspecialchars($cd['entree']) . '</span></div>';
        if (isset($cd['plat']))
            $dishesHtml .= '<div class="dish-row"><span class="dish-label">Plat</span><span>' . htmlspecialchars($cd['plat']) . '</span></div>';
        if (isset($cd['dessert']))
            $dishesHtml .= '<div class="dish-row"><span class="dish-label">Dessert</span><span>' . htmlspecialchars($cd['dessert']) . '</span></div>';
        $dishesHtml .= '</div>';
    }

    // Clean description
    $desc = str_replace("En attente du retour de matériel", "", $menu['description']);
    if (strlen($desc) > 150)
        $desc = substr($desc, 0, 150) . '...';

    // We intentionally SKIP rendering conditions_reservation here to solve user issue

    return '
                    <div class="menu-card" data-price="' . $menu['prix'] . '" data-min="' . $menu['min_personnes'] . '">
                        <div class="menu-card-inner">
                            <div class="menu-img-wrap">
                                <img src="' . htmlspecialchars($imgSrc) . '" alt="' . htmlspecialchars($menu['titre']) . '" loading="lazy"
                                     onerror="this.src=\'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600&q=80\'">
                                <div class="menu-img-overlay"></div>
                                <div class="menu-price-badge">' . number_format($menu['prix'], 2) . '€<span>/pers.</span></div>
                                ' . $stockBadge . '
                            </div>
                            <div class="menu-body">
                                <h3 class="menu-title">' . htmlspecialchars($menu['titre']) . '</h3>
                                <p class="menu-desc">' . htmlspecialchars($desc) . '</p>
                                <div class="menu-pills">
                                    <span class="menu-pill"><i class="fas fa-users"></i> Min. ' . $menu['min_personnes'] . ' pers.</span>
                                    <span class="menu-pill"><i class="fas fa-leaf"></i> ' . htmlspecialchars($menu['regime'] ?: 'Classique') . '</span>
                                    ' . ($menu['theme'] ? '<span class="menu-pill"><i class="fas fa-star"></i> ' . htmlspecialchars($menu['theme']) . '</span>' : '') . '
                                </div>
                                ' . $dishesHtml . '
                                <a href="' . BASE_URL . '/interface_frontend/pages/menu-detail.php?id=' . $menu['id'] . '" class="menu-cta">
                                    Voir le détail <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>';
}

// 4. Group & Render
if (empty($menus)) {
    echo '<p style="color:#888;padding:2rem;text-align:center;">Aucun menu disponible pour le moment.</p>';
}
else {
    $groups = [];
    foreach ($menus as $m) {
        $t = $m['theme'] ?: 'Autres';
        $groups[$t][] = $m;
    }

    // If only 1 group, flat grid (SSR handles this by context, but grouped is safer default)
    // Use grouped layout by default for SSR
    $themeIcons = [
        'Noël' => '🎄', 'Prestige' => '⭐', 'Saint Valentin' => '❤️',
        'Voyage en Asie' => '🌏', 'Végane' => '🌿', 'Mer' => '🌊',
        'Terroir' => '🍷', 'Classique' => '🍽️', 'Événement' => '🎉'
    ];

    foreach ($groups as $groupTheme => $groupMenus) {
        $icon = $themeIcons[$groupTheme] ?? '🍽️';
        echo '<div class="theme-section-header">';
        echo '<span class="theme-icon">' . $icon . '</span><span>' . htmlspecialchars($groupTheme) . '</span>';
        echo '</div>';

        echo '<div class="theme-cards-row">';
        foreach ($groupMenus as $m) {
            echo renderMenuCard($m, $dishesByMenu);
        }
        echo '</div>';
    }
}
?>
        </div>
    </section>

</div>

<script src="<?php echo BASE_URL; ?>/interface_frontend/ressources/js/menus.js"></script>
<?php include __DIR__ . '/../composants/footer.php'; ?>
