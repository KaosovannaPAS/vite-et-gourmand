<?php
session_start();
include __DIR__ . '/../../noyau_backend/configuration/db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: menus.php');
    exit;
}

$menuId = intval($_GET['id']);

try {
    $stmt = $pdo->prepare("SELECT * FROM menus WHERE id = ? AND actif = 1");
    $stmt->execute([$menuId]);
    $menu = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$menu) {
        header('Location: menus.php');
        exit;
    }

    // Fetch all dishes linked to this menu
    $stmt = $pdo->prepare("
        SELECT d.*
        FROM dishes d
        JOIN menu_dishes md ON d.id = md.dish_id
        WHERE md.menu_id = ?
        ORDER BY FIELD(d.type, 'apero', 'entree', 'plat', 'dessert')
    ");
    $stmt->execute([$menuId]);
    $allDishes = $stmt->fetchAll(PDO::FETCH_ASSOC);

}
catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}

// Organise dishes by type and diet
$standard = ['entree' => [], 'plat' => [], 'dessert' => []];
$vegan = ['entree' => null, 'plat' => null, 'dessert' => null];
$glutenFree = ['entree' => null, 'plat' => null, 'dessert' => null];

foreach ($allDishes as $dish) {
    $type = $dish['type'];
    if (!in_array($type, ['entree', 'plat', 'dessert']))
        continue;

    if ($dish['is_vegan'] && $vegan[$type] === null) {
        $vegan[$type] = $dish;
    }
    elseif ($dish['is_gluten_free'] && !$dish['is_vegan'] && $glutenFree[$type] === null) {
        $glutenFree[$type] = $dish;
    }
    else {
        if (count($standard[$type]) < 2) {
            $standard[$type][] = $dish;
        }
    }
}

// Fix image path for Vercel
function fixImg($url)
{
    return str_replace('/Vite-et-gourmand/', '/', $url ?? '');
}
?>
<?php include __DIR__ . '/../composants/header.php'; ?>

<style>
/* ===== MENU DETAIL PAGE ===== */
.detail-hero {
    position: relative;
    height: 380px;
    overflow: hidden;
    border-radius: 0 0 24px 24px;
    margin-bottom: 3rem;
}
.detail-hero img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.detail-hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.15) 0%, rgba(80,0,20,0.75) 100%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 2.5rem 3rem;
}
.detail-hero-overlay h1 {
    font-family: 'Playfair Display', serif;
    color: #fff;
    font-size: 2.8rem;
    margin: 0 0 0.5rem;
    text-shadow: 0 2px 8px rgba(0,0,0,0.5);
}
.detail-hero-overlay .hero-meta {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
    align-items: center;
}
.hero-badge {
    background: var(--secondary-color);
    color: var(--primary-color);
    font-weight: 800;
    font-size: 1.4rem;
    padding: 6px 18px;
    border-radius: 8px;
}
.hero-pill {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(6px);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 20px;
    padding: 5px 14px;
    font-size: 0.9rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Layout */
.detail-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 3rem;
    align-items: start;
}
@media (max-width: 900px) {
    .detail-layout { grid-template-columns: 1fr; }
}

/* Section titles */
.section-label {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 2.5rem 0 1.5rem;
}
.section-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(to right, var(--secondary-color), transparent);
}
.section-label span {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem;
    color: var(--primary-color);
    white-space: nowrap;
}
.section-label i {
    color: var(--secondary-color);
    font-size: 1.1rem;
}

/* Diet section headers */
.diet-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 18px;
    border-radius: 30px;
    font-weight: 700;
    font-size: 0.9rem;
    margin: 2rem 0 1rem;
    width: fit-content;
}
.diet-header.vegan   { background: #e8f5e9; color: #2e7d32; border: 1.5px solid #a5d6a7; }
.diet-header.glutenfree { background: #fff3e0; color: #e65100; border: 1.5px solid #ffcc80; }

/* Dish cards */
/* Dish cards (Baroque) */
.dish-card {
    display: flex;
    gap: 1.25rem;
    align-items: flex-start;
    background: #5a0015; /* Bordeaux BG */
    border-radius: 4px;
    padding: 1.25rem;
    margin-bottom: 1rem;
    box-shadow: 0 4px 14px rgba(0,0,0,0.4);
    border: 1px solid var(--secondary-color);
    transition: box-shadow 0.25s, transform 0.25s;
    position: relative;
    color: #eee;
}
.dish-card::after {
    content: '';
    position: absolute;
    inset: 3px;
    border: 1px solid rgba(212, 175, 55, 0.3);
    pointer-events: none;
}
.dish-card:hover {
    box-shadow: 0 8px 28px rgba(0,0,0,0.6);
    transform: translateY(-2px);
    border-color: #fff;
}
.dish-card.vegan-card   { border-left: 4px solid #66bb6a; }
.dish-card.gluten-card  { border-left: 4px solid #ffa726; }

.dish-img {
    width: 90px;
    height: 90px;
    border-radius: 12px;
    object-fit: cover;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12);
}
.dish-img-placeholder {
    width: 90px;
    height: 90px;
    border-radius: 12px;
    background: linear-gradient(135deg, #f5e6c8, #e8d5b0);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 1.8rem;
    color: var(--secondary-color);
}
.dish-info { flex: 1; }
.dish-name {
    font-family: 'Playfair Display', serif;
    font-size: 1.15rem;
    color: var(--secondary-color);
    margin: 0 0 0.4rem;
    font-weight: 700;
}
.dish-desc {
    font-size: 0.88rem;
    color: #ccc;
    line-height: 1.5;
    margin: 0 0 0.6rem;
}
.dish-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}
.dish-tag {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 4px;
}
.tag-vegan     { background: #e8f5e9; color: #2e7d32; }
.tag-gluten    { background: #fff3e0; color: #e65100; }
.tag-allergen  { background: #fce4ec; color: #c62828; }

/* Sidebar */
/* Sidebar */
.sidebar-card {
    background: #5a0015; /* Bordeaux BG */
    border-radius: 4px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    border: 2px solid var(--secondary-color);
    position: relative;
    color: #fff;
    /* Removed sticky from here, moving to wrapper */
}
.sidebar-card::after {
    content: '';
    position: absolute;
    inset: 5px;
    border: 1px solid var(--secondary-color);
    pointer-events: none;
}
.sidebar-price {
    font-size: 2.2rem;
    font-weight: 900;
    color: var(--secondary-color);
    line-height: 1;
}
.sidebar-price span {
    font-size: 1rem;
    font-weight: 500;
    color: #ccc;
}
.sidebar-divider {
    border: none;
    border-top: 1px solid #eee;
    margin: 1.5rem 0;
}
.simulator-label {
    font-weight: 700;
    color: var(--secondary-color);
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.simulator-value {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--secondary-color);
    text-align: center;
    margin-bottom: 0.75rem;
}
.price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    font-size: 0.9rem;
    color: #555;
}
.price-row.total {
    padding-top: 0.75rem;
    font-weight: 800;
    font-size: 1.1rem;
    color: var(--secondary-color);
}
.price-row.discount { color: #27ae60; font-weight: 700; }
.cta-btn {
    display: block;
    width: 100%;
    text-align: center;
    padding: 16px;
    background: var(--primary-color);
    color: var(--secondary-color);
    font-weight: 800;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-radius: 12px;
    text-decoration: none;
    margin-top: 1.5rem;
    transition: background 0.25s, transform 0.2s;
}
.cta-btn:hover { background: #5a0015; transform: translateY(-2px); }
.info-box {
    background: #fffcf0;
    border: 1px solid var(--secondary-color);
    border-radius: 10px;
    padding: 1rem 1.25rem;
    font-size: 0.8rem;
    color: #555;
    line-height: 1.5;
    margin-top: 1.25rem;
}
.info-box strong { color: var(--primary-color); }

/* Gold slider */
.gold-slider {
    -webkit-appearance: none;
    width: 100%;
    height: 8px;
    background: #eee;
    border-radius: 5px;
    outline: none;
}
.gold-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 28px; height: 18px;
    border-radius: 9px;
    background: var(--secondary-color);
    cursor: pointer;
    border: 2px solid white;
    box-shadow: 0 0 8px rgba(212,175,55,0.5);
}
.gold-slider::-moz-range-thumb {
    width: 28px; height: 18px;
    border-radius: 9px;
    background: var(--secondary-color);
    cursor: pointer;
    border: 2px solid white;
}

/* 2-column dish grid */
.dish-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-bottom: 0.5rem;
}
@media (max-width: 700px) {
    .dish-grid { grid-template-columns: 1fr; }
}
.dish-card { margin-bottom: 0; }

/* Quantity stepper */
/* Quantity stepper (Custom Image Design) */
.qty-stepper-row {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0;
    margin: 0.5rem 0 1.5rem;
}
.qty-stepper-row label {
    font-size: 0.9rem;
    color: var(--secondary-color); /* Gold label on dark card */
    margin-right: 12px;
    font-weight: 700;
}
.qty-stepper {
    display: inline-flex;
    align-items: center;
    border: 2px solid var(--secondary-color);
    border-radius: 50px; /* Pill shape */
    background: #fff;
    overflow: hidden;
    height: 38px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
}
.qty-btn {
    width: 40px;
    height: 100%;
    border: none;
    background: #fff;
    color: #000;
    font-size: 1.4rem;
    font-weight: 600; /* Thinner plus/minus */
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
    line-height: 0;
    padding-bottom: 4px; /* Centering adjustment */
}
.qty-btn:hover { background: #f5f5f5; }
.qty-num {
    height: 100%;
    min-width: 45px;
    background: #fffcf0; /* Cream center */
    color: #5a0015; /* Bordeaux number */
    font-weight: 700;
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-left: 1px solid var(--secondary-color);
    border-right: 1px solid var(--secondary-color);
}

/* Gold discount info */
.discount-info-text {
    font-size: 1rem;
    font-weight: 800;
    color: var(--secondary-color);
    font-style: italic;
}
</style>

<div class="container" style="padding: 2rem 0 4rem;">
    <a href="menus.php" style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 1.5rem; color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 0.95rem;">
        <i class="fas fa-arrow-left"></i> Retour aux menus
    </a>

    <!-- Hero Banner -->
    <div class="detail-hero">
        <?php
$heroImg = fixImg($menu['image_url']);
if (!$heroImg)
    $heroImg = 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=1200&q=80';
?>
        <img src="<?php echo htmlspecialchars($heroImg); ?>"
             alt="<?php echo htmlspecialchars($menu['titre']); ?>"
             onerror="this.src='https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=1200&q=80'">
        <div class="detail-hero-overlay">
            <h1><?php echo htmlspecialchars($menu['titre']); ?></h1>
            <div class="hero-meta">
                <span class="hero-badge"><?php echo number_format($menu['prix'], 2); ?> € <small style="font-size:0.6em;font-weight:600">/pers.</small></span>
                <span class="hero-pill"><i class="fas fa-users"></i> Min. <?php echo $menu['min_personnes']; ?> pers.</span>
                <?php if (!empty($menu['regime'])): ?>
                <span class="hero-pill"><i class="fas fa-leaf"></i> <?php echo htmlspecialchars($menu['regime']); ?></span>
                <?php
endif; ?>
                <?php if (!empty($menu['theme'])): ?>
                <span class="hero-pill"><i class="fas fa-star"></i> <?php echo htmlspecialchars($menu['theme']); ?></span>
                <?php
endif; ?>
            </div>
        </div>
    </div>

    <div class="detail-layout">
        <!-- LEFT: Composition -->
        <div>
            <p style="font-size:1.05rem; color:#444; line-height:1.7; margin-bottom:0.5rem;">
                <?php echo nl2br(htmlspecialchars($menu['description'])); ?>
            </p>
            <?php if (!empty($menu['conditions_reservation'])): ?>
            <div style="display:flex;align-items:center;gap:10px;background:rgba(212,175,55,0.1);border-left:4px solid var(--secondary-color);padding:0.75rem 1rem;border-radius:0 8px 8px 0;margin-top:1rem;font-size:0.88rem;color:#555;">
                <i class="fas fa-calendar-alt" style="color:var(--secondary-color);"></i>
                <?php echo htmlspecialchars($menu['conditions_reservation']); ?>
            </div>
            <?php
endif; ?>

            <?php
// Helper to render a dish card
function renderDish($dish, $extraClass = '')
{
    $img = fixImg($dish['image_url'] ?? '');
    $tags = '';
    if ($dish['is_vegan'])
        $tags .= '<span class="dish-tag tag-vegan"><i class="fas fa-leaf"></i> Vegan</span>';
    if ($dish['is_gluten_free'])
        $tags .= '<span class="dish-tag tag-gluten"><i class="fas fa-wheat-awn"></i> Sans Gluten</span>';
    if (!empty($dish['allergenes'])) {
        $tags .= '<span class="dish-tag tag-allergen"><i class="fas fa-exclamation-triangle"></i> ' . htmlspecialchars($dish['allergenes']) . '</span>';
    }
    $imgHtml = $img
        ? '<img class="dish-img" src="' . htmlspecialchars($img) . '" alt="' . htmlspecialchars($dish['nom']) . '" loading="lazy" onerror="this.style.display=\'none\';this.nextElementSibling.style.display=\'flex\'"><div class="dish-img-placeholder" style="display:none"><i class="fas fa-utensils"></i></div>'
        : '<div class="dish-img-placeholder"><i class="fas fa-utensils"></i></div>';
    $desc = !empty($dish['description']) ? '<p class="dish-desc">' . htmlspecialchars($dish['description']) . '</p>' : '';
    echo '<div class="dish-card ' . $extraClass . '">'
        . $imgHtml
        . '<div class="dish-info">'
        . '<h4 class="dish-name">' . htmlspecialchars($dish['nom']) . '</h4>'
        . $desc
        . ($tags ? '<div class="dish-tags">' . $tags . '</div>' : '')
        . '</div></div>';
}

// ---- STANDARD MENU ----
$types = [
    'entree' => ['label' => 'Entrées', 'icon' => 'fa-seedling', 'id' => 'qty_entree'],
    'plat' => ['label' => 'Plats Principaux', 'icon' => 'fa-drumstick-bite', 'id' => 'qty_plat'],
    'dessert' => ['label' => 'Desserts', 'icon' => 'fa-ice-cream', 'id' => 'qty_dessert'],
];
foreach ($types as $type => $meta):
    if (!empty($standard[$type])):
?>
            <div class="section-label">
                <i class="fas <?php echo $meta['icon']; ?>"></i>
                <span><?php echo $meta['label']; ?></span>
            </div>
            <div class="dish-grid">
            <?php foreach ($standard[$type] as $dish):
            renderDish($dish);
        endforeach; ?>
            </div>
            <div class="qty-stepper-row">
                <label>Nb. <?php echo $meta['label']; ?> :</label>
                <div class="qty-stepper">
                    <button class="qty-btn" onclick="qtyChange('<?php echo $meta['id']; ?>',-1)">−</button>
                    <span class="qty-num" id="<?php echo $meta['id']; ?>">0</span>
                    <button class="qty-btn" onclick="qtyChange('<?php echo $meta['id']; ?>',1)">+</button>
                </div>
            </div>
            <?php
    endif;
endforeach; ?>

            <!-- ---- VEGAN SECTION ---- -->
            <?php $hasVegan = array_filter($vegan);
if (!empty($hasVegan)): ?>
            <div class="diet-header vegan">
                <i class="fas fa-leaf"></i> Options 100% Véganes
            </div>
            <div class="dish-grid">
            <?php foreach (['entree', 'plat', 'dessert'] as $t):
        if ($vegan[$t]):
            renderDish($vegan[$t], 'vegan-card');
        endif;
    endforeach; ?>
            </div>
            <div class="qty-stepper-row">
                <label>Nb. Options Véganes :</label>
                <div class="qty-stepper">
                    <button class="qty-btn" onclick="qtyChange('qty_vegan',-1)">−</button>
                    <span class="qty-num" id="qty_vegan">0</span>
                    <button class="qty-btn" onclick="qtyChange('qty_vegan',1)">+</button>
                </div>
            </div>
            <?php
endif; ?>

            <!-- ---- GLUTEN-FREE SECTION ---- -->
            <?php $hasGF = array_filter($glutenFree);
if (!empty($hasGF)): ?>
            <div class="diet-header glutenfree">
                <i class="fas fa-ban"></i> Options Sans Gluten
            </div>
            <div class="dish-grid">
            <?php foreach (['entree', 'plat', 'dessert'] as $t):
        if ($glutenFree[$t]):
            renderDish($glutenFree[$t], 'gluten-card');
        endif;
    endforeach; ?>
            </div>
            <div class="qty-stepper-row">
                <label>Nb. Sans Gluten :</label>
                <div class="qty-stepper">
                    <button class="qty-btn" onclick="qtyChange('qty_glutenfree',-1)">−</button>
                    <span class="qty-num" id="qty_glutenfree">0</span>
                    <button class="qty-btn" onclick="qtyChange('qty_glutenfree',1)">+</button>
                </div>
            </div>
            <?php
endif; ?>

            <?php if (empty($allDishes)): ?>
            <p style="color:#888;font-style:italic;margin-top:2rem;">La composition détaillée sera personnalisée selon vos envies et la saison.</p>
            <?php
endif; ?>
        </div>

        <!-- RIGHT: Sidebar Simulator -->
        <div>
            <div style="position: sticky; top: 100px;">
                <div class="sidebar-card">
                <div class="sidebar-price">
                    <?php echo number_format($menu['prix'], 2); ?> €
                    <span>/ personne</span>
                </div>
                <p style="font-size:0.85rem;color:#888;margin:0.4rem 0 0;">Min. <?php echo $menu['min_personnes']; ?> personnes</p>

                <hr class="sidebar-divider">

                <div class="simulator-label">Simulateur de budget</div>
                <div class="simulator-value"><span id="guest_display"><?php echo max($menu['min_personnes'], 2); ?></span> pers.</div>

                <input type="range" id="guest_count"
                       min="<?php echo $menu['min_personnes']; ?>"
                       max="100"
                       value="<?php echo max($menu['min_personnes'], 2); ?>"
                       class="gold-slider">

                <div style="margin-top:1.25rem;background:#fafafa;border-radius:10px;padding:1rem 1.25rem;border:1px solid #eee;">
                    <div class="price-row">
                        <span>Menu (<span id="count_summary">0</span> pers.)</span>
                        <span id="base_price_total" style="font-weight:700;">0,00 €</span>
                    </div>
                    <div class="price-row discount" id="discount_row" style="display:none;">
                        <span>Réduction fidélité (−10%)</span>
                        <span id="discount_amount">−0,00 €</span>
                    </div>
                    <div class="price-row">
                        <span>Livraison (base)</span>
                        <span style="font-weight:700;">5,00 €</span>
                    </div>
                    <div class="price-row total">
                        <span>Total estimé</span>
                        <span id="total_price">0,00 €</span>
                    </div>
                    <div id="discount_info" style="margin-top:0.75rem;">
                        <span class="discount-info-text"><i class="fas fa-tag"></i> Encore <span id="needed_for_discount">X</span> pers. pour −10% !</span>
                    </div>
                </div>

                <a id="action-btn" href="#" class="cta-btn">Commander</a>
            </div>

            <!-- Conditions & Livraison -->
            <div style="margin-top: 1.5rem; background: #5a0015; border-radius: 4px; border: 1px solid var(--secondary-color); padding: 1.5rem; font-size: 0.9rem; color: #ccc; position: relative;">
                <h4 style="color:var(--secondary-color); font-size:1rem; margin:0 0 1rem; font-family:'Playfair Display',serif; font-weight:700; border-bottom:1px solid rgba(212,175,55,0.3); padding-bottom:0.5rem;">
                    <i class="fas fa-info-circle" style="color:var(--secondary-color); margin-right:8px;"></i> Informations
                </h4>
                
                
                <p style="margin-bottom:0.5rem; font-weight:700; color:var(--secondary-color);">Conditions de commande :</p>
                <ul style="margin:0 0 1.25rem 1.2rem; padding:0;">
                    <li style="margin-bottom:0.25rem;">1 semaine à l'avance pour < 30 pers.</li>
                    <li>2 semaines à l'avance pour > 30 pers.</li>
                </ul>

                <p style="margin-bottom:0.5rem; font-weight:700; color:var(--secondary-color);">Frais de livraison :</p>
                <p style="margin:0; line-height:1.5;">
                    5,00 € <small>(majoré de 0,59€ / km)</small> si livraison hors Bordeaux.
                </p>
            </div>
            </div> <!-- End sticky wrapper -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const guestInput       = document.getElementById('guest_count');
    const guestDisplay     = document.getElementById('guest_display');
    const countSummary     = document.getElementById('count_summary');
    const basePriceDisplay = document.getElementById('base_price_total');
    const discountRow      = document.getElementById('discount_row');
    const discountAmount   = document.getElementById('discount_amount');
    const neededSpan       = document.getElementById('needed_for_discount');
    const discountInfo     = document.getElementById('discount_info');
    const totalDisplay     = document.getElementById('total_price');
    const actionBtn        = document.getElementById('action-btn');

    const menuId       = <?php echo $menuId; ?>;
    const menuTitle    = "<?php echo addslashes($menu['titre']); ?>";
    const pricePerPerson = <?php echo floatval($menu['prix']); ?>;
    const minGuests    = <?php echo intval($menu['min_personnes']); ?>;
    const fmt = v => new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(v);

    function update() {
        const count = parseInt(guestInput.value) || 0;
        guestDisplay.textContent = count;
        countSummary.textContent = count;

        const base = count * pricePerPerson;
        basePriceDisplay.textContent = fmt(base);

        const threshold = minGuests + 5;
        let discount = 0;
        if (count >= threshold) {
            discount = base * 0.1;
            discountRow.style.display = 'flex';
            discountAmount.textContent = '−' + fmt(discount);
            discountInfo.style.display = 'none';
        } else {
            discountRow.style.display = 'none';
            discountInfo.style.display = 'block';
            neededSpan.textContent = threshold - count;
        }

        totalDisplay.textContent = fmt(base - discount + 5);

        if (count < 30) {
            actionBtn.textContent = 'Commander';
            actionBtn.href = `${BASE_URL}/interface_frontend/pages/order.php?menu_id=${menuId}&quantite=${count}`;
            actionBtn.style.background = 'var(--primary-color)';
        } else {
            actionBtn.textContent = 'Demander un devis';
            actionBtn.href = `${BASE_URL}/interface_frontend/pages/contact.php?sujet=Devis&menu=${encodeURIComponent(menuTitle)}&guests=${count}`;
            actionBtn.style.background = '#2c3e50';
        }
    }

    guestInput.addEventListener('input', update);
    update();
});

// Quantity steppers
function qtyChange(id, delta) {
    const el = document.getElementById(id);
    if (!el) return;
    let v = parseInt(el.textContent) || 0;
    v = Math.max(0, v + delta);
    el.textContent = v;
}
</script>

<?php include __DIR__ . '/../composants/footer.php'; ?>
