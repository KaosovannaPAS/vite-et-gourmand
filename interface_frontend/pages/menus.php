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

    /* ===== MENU CARDS ===== */
    .menu-card-inner {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .menu-card-inner:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 48px rgba(0,0,0,0.18);
    }

    /* Image */
    .menu-img-wrap {
        position: relative;
        height: 230px;
        flex-shrink: 0;
        overflow: hidden;
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
        background: linear-gradient(to bottom, rgba(0,0,0,0.05) 0%, rgba(0,0,0,0.35) 100%);
    }

    /* Price badge */
    .menu-price-badge {
        position: absolute;
        top: 14px;
        right: 14px;
        background: var(--secondary-color);
        color: var(--primary-color);
        font-weight: 900;
        font-size: 1.25rem;
        padding: 8px 14px;
        border-radius: 10px;
        line-height: 1;
        box-shadow: 0 4px 14px rgba(0,0,0,0.3);
        text-align: center;
    }
    .menu-price-badge span {
        display: block;
        font-size: 0.65rem;
        font-weight: 600;
        opacity: 0.85;
        margin-top: 2px;
    }

    /* Stock badges */
    .menu-badge {
        position: absolute;
        top: 14px;
        left: 14px;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .badge-rupture { background: #c0392b; color: #fff; }
    .badge-urgent  { background: #e67e22; color: #fff; }

    /* Body */
    .menu-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        flex: 1;
        gap: 0.75rem;
    }
    .menu-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.35rem;
        color: var(--primary-color);
        margin: 0;
        line-height: 1.25;
        border-bottom: 2px solid var(--secondary-color);
        padding-bottom: 0.6rem;
    }
    .menu-desc {
        font-size: 0.92rem;
        color: #555;
        line-height: 1.55;
        margin: 0;
    }

    /* Pills */
    .menu-pills {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .menu-pill {
        background: rgba(128, 0, 32, 0.08);
        color: var(--primary-color);
        border: 1px solid rgba(128, 0, 32, 0.2);
        border-radius: 20px;
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
        background: #fafafa;
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }
    .dish-row {
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: #444;
    }
    .dish-label {
        font-weight: 700;
        color: var(--primary-color);
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
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.75rem;
        margin-bottom: 1rem;
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

    <!-- Liste des Menus -->
    <section style="flex: 1;">
        <h2 style="margin-bottom: 2rem;">Nos propositions gourmandes</h2>
        <div id="menus-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2.5rem;">
            <!-- Les menus seront injectés ici par JS -->
            <p>Chargement des menus...</p>
        </div>
    </section>

</div>

<script src="<?php echo BASE_URL; ?>/interface_frontend/ressources/js/menus.js"></script>
<?php include __DIR__ . '/../composants/footer.php'; ?>
