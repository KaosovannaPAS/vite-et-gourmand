<?php
session_start();
include __DIR__ . '/../../noyau_backend/configuration/db.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: menus.php');
    exit;
}

$menuId = intval($_GET['id']);

// Fetch menu details
try {
    $stmt = $pdo->prepare("SELECT * FROM menus WHERE id = ? AND actif = 1");
    $stmt->execute([$menuId]);
    $menu = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$menu) {
        header('Location: menus.php');
        exit;
    }

    // Fetch related dishes
    $stmt = $pdo->prepare("
        SELECT d.* 
        FROM dishes d
        JOIN menu_dishes md ON d.id = md.dish_id
        WHERE md.menu_id = ?
        ORDER BY FIELD(d.type, 'apero', 'entree', 'plat', 'dessert')
    ");
    $stmt->execute([$menuId]);
    $dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);

}
catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}
?>
<?php include __DIR__ . '/../composants/header.php'; ?>

<div class="container" style="padding: 2rem 0;">
    <a href="menus.php" style="display: inline-block; margin-bottom: 2rem; color: var(--secondary-color); text-decoration: none;">
        <i class="fas fa-arrow-left"></i> Retour aux menus
    </a>

    <div style="display: flex; gap: 4rem; flex-wrap: wrap;">
        <!-- Image & Info -->
        <div style="flex: 1; min-width: 300px;">
            <img src="<?php echo htmlspecialchars($menu['image_url']); ?>" alt="<?php echo htmlspecialchars($menu['titre']); ?>" style="width: 100%; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 2rem;">
            
            <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                <h1 style="font-family: 'Playfair Display', serif; color: var(--primary-color); margin-bottom: 1rem;"><?php echo htmlspecialchars($menu['titre']); ?></h1>
                <p style="font-size: 1.2rem; margin-bottom: 2rem;"><?php echo nl2br(htmlspecialchars($menu['description'])); ?></p>
                
                <div style="margin-bottom: 2rem;">
                    <span style="font-size: 1.5rem; font-weight: bold; color: var(--secondary-color);"><?php echo number_format($menu['prix'], 2); ?> € / pers.</span>
                    <br>
                    <small style="color: #000000; font-weight: 600;">Min. <?php echo $menu['min_personnes']; ?> personnes</small>
                </div>

                <div style="margin-bottom: 2rem;">
                    <label for="guest_count" style="display: block; margin-bottom: 1rem; font-weight: bold; color: var(--primary-color);">Nombre de convives : <span id="guest_display" style="color: var(--secondary-color); font-size: 1.4rem;"><?php echo max($menu['min_personnes'], 10); ?></span></label>
                    
                    <div class="simulator-container" style="background: rgba(128, 0, 32, 0.05); padding: 2rem; border-radius: 15px; border: 1px solid rgba(212, 175, 55, 0.3);">
                        <input type="range" id="guest_count" 
                               min="<?php echo $menu['min_personnes']; ?>" 
                               max="200" 
                               value="<?php echo max($menu['min_personnes'], 10); ?>" 
                               style="width: 100%; cursor: pointer;"
                               class="gold-slider">
                        
                        <div id="price_breakdown" style="margin-top: 1.5rem; padding: 1.5rem; background: white; border-radius: 10px; border: 1px solid rgba(212, 175, 55, 0.2); font-size: 1rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.8rem;">
                                <span style="color: #000000; font-weight: 500;">Prix Menu (<span id="count_summary">0</span> pers.) :</span>
                                <span id="base_price_total" style="font-weight: 700;">0.00 €</span>
                            </div>
                            <div id="discount_row" style="display: none; justify-content: space-between; margin-bottom: 0.8rem; color: #27ae60; font-weight: 700;">
                                <span>Réduction Fidélité (-10%) :</span>
                                <span id="discount_amount">-0.00 €</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.8rem;">
                                <span style="color: #000000; font-weight: 500;">Frais de livraison (base) :</span>
                                <span style="font-weight: 700;">5,00 €</span>
                            </div>
                            <div style="border-top: 1px solid #eee; margin-top: 1rem; padding-top: 1rem; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-weight: bold; color: var(--primary-color);">Budget Total Estimé :</span>
                                <span id="total_price" style="font-size: 1.8rem; font-weight: bold; color: var(--primary-color);">0.00 €</span>
                            </div>
                            <div id="discount_info" style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--primary-color); font-style: italic;">
                                <i class="fas fa-tag"></i> Rajoutez <span id="needed_for_discount">X</span> personnes pour bénéficier de -10% !
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                .gold-slider {
                    -webkit-appearance: none;
                    height: 10px;
                    background: #ddd;
                    border-radius: 5px;
                    outline: none;
                }
                .gold-slider::-webkit-slider-thumb {
                    -webkit-appearance: none;
                    appearance: none;
                    width: 30px;
                    height: 20px;
                    background: var(--secondary-color);
                    border-radius: 10px; /* Oval shape */
                    cursor: pointer;
                    box-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
                    border: 2px solid white;
                }
                .gold-slider::-moz-range-thumb {
                    width: 30px;
                    height: 20px;
                    background: var(--secondary-color);
                    border-radius: 10px;
                    cursor: pointer;
                    box-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
                    border: 2px solid white;
                }
                </style>

                <?php if ($menu['conditions_reservation']): ?>
                    <div style="background: rgba(212, 175, 55, 0.1); padding: 1.5rem; border-radius: 5px; margin-bottom: 2rem; border-left: 5px solid var(--secondary-color); color: #000000; font-weight: 600;">
                        <i class="fas fa-calendar-alt"></i> <?php echo htmlspecialchars($menu['conditions_reservation']); ?>
                    </div>
                <?php
endif; ?>

                <a id="action-btn" href="#" class="btn btn-primary" style="display: block; text-align: center; padding: 1.2rem; font-size: 1.2rem; text-transform: uppercase; letter-spacing: 1px; font-weight: bold;">Commander</a>

                <div style="margin-top: 2rem; padding: 1.5rem; background: #fffcf0; border: 1px solid var(--secondary-color); border-radius: 5px; font-size: 0.9rem; line-height: 1.5; color: #333; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                    <strong style="color: var(--primary-color); text-transform: uppercase; display: block; margin-bottom: 0.5rem; border-bottom: 1px solid rgba(212, 175, 55, 0.3); padding-bottom: 0.3rem; font-size: 0.85rem; letter-spacing: 1px;">
                        <i class="fas fa-info-circle"></i> Info Retour de Matériel
                    </strong>
                    <strong>En attente du retour de matériel :</strong> Si du matériel a été prêté, il doit être restitué dès que ce statut est atteint. Le client recevra un mail de notification : si sous 10 jours ouvrés le matériel n'est pas restitué, des frais de <strong>600€</strong> seront appliqués (voir CGV). Pour toute restitution, merci de prendre contact avec la société.
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const guestInput = document.getElementById('guest_count');
                        const guestDisplay = document.getElementById('guest_display');
                        const countSummary = document.getElementById('count_summary');
                        const basePriceDisplay = document.getElementById('base_price_total');
                        const discountRow = document.getElementById('discount_row');
                        const discountAmountDisplay = document.getElementById('discount_amount');
                        const neededForDiscount = document.getElementById('needed_for_discount');
                        const discountInfo = document.getElementById('discount_info');
                        const totalPriceDisplay = document.getElementById('total_price');
                        const actionBtn = document.getElementById('action-btn');
                        
                        const menuId = <?php echo $menuId; ?>;
                        const menuTitle = "<?php echo addslashes($menu['titre']); ?>";
                        const pricePerPerson = <?php echo floatval($menu['prix']); ?>;
                        const minGuests = <?php echo intval($menu['min_personnes']); ?>;
                        const deliveryFee = 5.00;

                        function updateSimulator() {
                            const count = parseInt(guestInput.value) || 0;
                            guestDisplay.textContent = count;
                            countSummary.textContent = count;
                            
                            const baseTotal = count * pricePerPerson;
                            basePriceDisplay.textContent = new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(baseTotal);

                            // Logic: 10% discount if count >= min + 5
                            const discountThreshold = minGuests + 5;
                            let discount = 0;
                            
                            if (count >= discountThreshold) {
                                discount = baseTotal * 0.1;
                                discountRow.style.display = 'flex';
                                discountAmountDisplay.textContent = '-' + new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(discount);
                                discountInfo.style.display = 'none';
                            } else {
                                discountRow.style.display = 'none';
                                discountInfo.style.display = 'block';
                                neededForDiscount.textContent = discountThreshold - count;
                            }

                            const grandTotal = baseTotal - discount + deliveryFee;
                            totalPriceDisplay.textContent = new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(grandTotal);

                            if (count < 30) {
                                actionBtn.textContent = "Commander";
                                actionBtn.href = `${BASE_URL}/interface_frontend/pages/order.php?menu_id=${menuId}&quantite=${count}`;
                                actionBtn.style.backgroundColor = "var(--primary-color)";
                            } else {
                                actionBtn.textContent = "Demander un devis";
                                actionBtn.href = `${BASE_URL}/interface_frontend/pages/contact.php?sujet=Devis&menu=${encodeURIComponent(menuTitle)}&guests=${count}`;
                                actionBtn.style.backgroundColor = "#2c3e50"; 
                            }
                        }

                        guestInput.addEventListener('input', updateSimulator);
                        updateSimulator(); // Init
                    });
                </script>
            </div>
        </div>

        <!-- Composition du Menu -->
            <div style="background: var(--primary-color); padding: 2.5rem; border-radius: 5px; box-shadow: inset 0 0 20px rgba(0,0,0,0.5), 5px 5px 15px rgba(0,0,0,0.3); border: 2px solid var(--secondary-color);">
                <?php
$currentType = '';
foreach ($dishes as $dish):
    if ($currentType != $dish['type']):
        $currentType = $dish['type'];
?>
                    <h3 style="margin-top: 2rem; margin-bottom: 1rem; color: var(--secondary-color); text-transform: uppercase; font-size: 1rem; letter-spacing: 2px; border-bottom: 1px solid rgba(212, 175, 55, 0.4); padding-bottom: 0.5rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">
                        <?php
        switch ($currentType) {
            case 'apero':
                echo 'Apéritif';
                break;
            case 'entree':
                echo 'Entrée';
                break;
            case 'plat':
                echo 'Plat Principal';
                break;
            case 'dessert':
                echo 'Dessert';
                break;
            default:
                echo ucfirst($currentType);
        }
?>
                    </h3>
                <?php
    endif; ?>

                <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px dashed rgba(0,0,0,0.2); display: flex; align-items: start;">
                     <?php if (!empty($dish['image_url'])): ?>
                        <div style="width: 70px; height: 70px; flex-shrink: 0; border: 2px solid var(--secondary-color); border-radius: 50%; overflow: hidden; margin-right: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                            <img src="<?php echo htmlspecialchars($dish['image_url']); ?>" alt="<?php echo htmlspecialchars($dish['nom']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    <?php
    endif; ?>
                    <div>
                        <h4 style="margin: 0 0 0.5rem 0; font-size: 1.2rem; color: var(--secondary-color); text-shadow: 1px 1px 1px rgba(0,0,0,0.5);"><?php echo htmlspecialchars($dish['nom']); ?></h4>
                        <?php if (!empty($dish['description'])): ?>
                            <p style="margin: 0; font-size: 0.95rem; color: #000000; font-weight: 500;"><?php echo htmlspecialchars($dish['description']); ?></p>
                        <?php
    endif; ?>
                        
                        <div style="margin-top: 0.7rem; font-size: 0.85rem;">
                            <?php if ($dish['is_vegan']): ?>
                                <span style="color: #000000; margin-right: 15px; font-weight: bold;"><i class="fas fa-leaf"></i> Vegan</span>
                            <?php
    endif; ?>
                            <?php if ($dish['is_gluten_free']): ?>
                                <span style="color: #000000; margin-right: 15px; font-weight: bold;"><i class="fas fa-bread-slice"></i> Sans Gluten</span>
                            <?php
    endif; ?>
                            <?php if (!empty($dish['allergenes'])): ?>
                                <span style="color: #000000;"><i class="fas fa-exclamation-triangle"></i> Allergènes : <?php echo htmlspecialchars($dish['allergenes']); ?></span>
                            <?php
    endif; ?>
                        </div>
                    </div>
                </div>
                <?php
endforeach; ?>
            </div>

            <?php if (empty($dishes)): ?>
                <p>La composition détaillée de ce menu sera personnalisée selon vos envies et la saison.</p>
            <?php
endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../composants/footer.php'; ?>
