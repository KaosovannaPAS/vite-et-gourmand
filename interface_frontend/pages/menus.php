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
</style>

<div class="container" style="padding: 2rem 0; display: flex; gap: 2rem;">
    
    <!-- Sidebar Filtres -->
    <aside style="width: 280px; flex-shrink: 0;">
        <div class="baroque-card" style="padding: 2rem; color: white;">
            <h3 class="baroque-title" style="margin-bottom: 2rem; border-bottom: 1px solid var(--secondary-color); padding-bottom: 0.5rem;">Filtres</h3>
            
            <div class="filter-group" style="margin-bottom: 1.5rem;">
                <label for="theme" style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: var(--secondary-color);">Thème</label>
                <select id="theme" class="form-control" style="width: 100%; padding: 0.8rem; background: rgba(0,0,0,0.2); color: white; border: 1px solid var(--secondary-color);">
                    <option value="" style="background: var(--primary-color);">Tous</option>
                    <option value="Noel" style="background: var(--primary-color);">Noël</option>
                    <option value="Paques" style="background: var(--primary-color);">Pâques</option>
                    <option value="Classique" style="background: var(--primary-color);">Classique</option>
                    <option value="Evenement" style="background: var(--primary-color);">Événement</option>
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
<?php include '../composants/footer.php'; ?>
