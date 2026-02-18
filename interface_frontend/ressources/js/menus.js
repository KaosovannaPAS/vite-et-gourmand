document.addEventListener('DOMContentLoaded', () => {
    // Elements DOM
    const menusGrid = document.getElementById('menus-grid');
    const themeSelect = document.getElementById('theme');
    const regimeSelect = document.getElementById('regime');
    const priceRange = document.getElementById('max_price');
    const priceDisplay = document.getElementById('price_display');
    const minPeopleInput = document.getElementById('min_people');
    const minPeopleDisplay = document.getElementById('min_people_display');

    // Simulateur Elements
    const simPeople = document.getElementById('sim_people');
    const simPeopleDisplay = document.getElementById('sim_people_display');
    const simAddress = document.getElementById('sim_address');
    const simDistance = document.getElementById('sim_distance');
    const logisticsFeeDisplay = document.getElementById('logistics_fee_display');
    const resetFiltersBtn = document.getElementById('reset_filters');

    let currentDeliveryFee = 0;

    // --- EVENT LISTENERS ---

    // Filtres
    priceRange.addEventListener('input', (e) => {
        priceDisplay.textContent = e.target.value + ' €';
        fetchMenus();
    });
    themeSelect.addEventListener('change', fetchMenus);
    regimeSelect.addEventListener('change', fetchMenus);
    minPeopleInput.addEventListener('input', (e) => {
        if (minPeopleDisplay) minPeopleDisplay.textContent = e.target.value;
        fetchMenus();
    });

    // Simulateur
    simPeople.addEventListener('input', (e) => {
        simPeopleDisplay.textContent = e.target.value;
        updateSimulatorTotals();
    });

    simAddress.addEventListener('input', updateLogistics);
    simDistance.addEventListener('input', updateLogistics);

    // Reset Filtres
    resetFiltersBtn.addEventListener('click', () => {
        themeSelect.value = '';
        regimeSelect.value = '';
        priceRange.value = 150;
        priceDisplay.textContent = '150 €';
        minPeopleInput.value = 2;
        if (minPeopleDisplay) minPeopleDisplay.textContent = '2';

        // Reset Simulateur
        simPeople.value = 10;
        simPeopleDisplay.textContent = '10';
        simAddress.value = '';
        updateLogistics();

        fetchMenus();
    });

    // --- FUNCTIONS ---

    function updateLogistics() {
        const city = simAddress.value.trim().toLowerCase();

        if (city === '' || city.includes('bordeaux')) {
            simDistance.style.display = 'none';
            currentDeliveryFee = 0;
        } else {
            simDistance.style.display = 'block';
            const dist = parseFloat(simDistance.value) || 0;
            currentDeliveryFee = 5 + (0.59 * dist);
        }

        logisticsFeeDisplay.innerHTML = `Frais de livraison : <strong>${currentDeliveryFee.toFixed(2)} €</strong>`;
        updateSimulatorTotals();
    }

    function updateSimulatorTotals() {
        const discountMsg = document.getElementById('discount_msg');
        const peopleCount = parseInt(simPeople.value);
        const cards = document.querySelectorAll('.menu-card');
        let discountAppliedGlobal = false;

        cards.forEach(card => {
            const unitPrice = parseFloat(card.dataset.price);
            const minGuestsStr = card.querySelector('.fa-users').parentElement.textContent.match(/\d+/);
            const minGuests = minGuestsStr ? parseInt(minGuestsStr[0]) : 1;
            const discountThreshold = minGuests + 5;

            if (peopleCount >= discountThreshold) {
                discountAppliedGlobal = true;
            }
        });

        if (discountMsg) {
            discountMsg.style.display = discountAppliedGlobal ? 'block' : 'none';
        }
    }

    function fetchMenus() {
        const theme = themeSelect.value;
        const regime = regimeSelect.value;
        const maxPrice = priceRange.value;
        const minPeople = minPeopleInput.value;

        const params = new URLSearchParams({
            theme: theme,
            regime: regime,
            max_price: maxPrice,
            min_people: minPeople
        });

        menusGrid.innerHTML = '<p>Chargement...</p>';

        fetch(`/Vite-et-gourmand/noyau_backend/api/v1/menus.php?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                menusGrid.innerHTML = '';
                if (data.length === 0) {
                    menusGrid.innerHTML = '<p>Aucun menu ne correspond à vos critères.</p>';
                    return;
                }

                data.forEach(menu => {
                    const card = document.createElement('div');
                    card.className = 'menu-card';
                    card.dataset.price = menu.prix;

                    // Stock Warning
                    let stockDisplay = '';
                    if (menu.stock < 10 && menu.stock > 0) {
                        stockDisplay = `<div style="color: #d35400; font-weight: bold; margin-bottom: 0.5rem; font-size: 0.9rem;">
                            <i class="fas fa-exclamation-circle"></i> Plus que ${menu.stock} menus dispos !
                        </div>`;
                    } else if (menu.stock === 0) {
                        stockDisplay = `<div style="color: #c0392b; font-weight: bold; margin-bottom: 0.5rem; font-size: 0.9rem;">
                            <i class="fas fa-times-circle"></i> Rupture de stock
                        </div>`;
                    }

                    // Conditions
                    const conditions = menu.conditions_reservation ?
                        `<div style="font-size: 0.8rem; background: #f9f9f9; padding: 0.5rem; border-radius: 5px; margin-top: 0.5rem; color: #555;">
                            <i class="fas fa-info-circle"></i> ${menu.conditions_reservation}
                         </div>` : '';

                    card.innerHTML = `
                        <div class="baroque-card" style="height: 100%; display: flex; flex-direction: column; overflow: hidden; text-align: center; position: relative;">
                            <!-- Badge Prix -->
                            <div style="position: absolute; top: 15px; right: 15px; background: var(--secondary-color); color: var(--primary-color); padding: 8px 15px; border-radius: 5px; font-weight: 800; font-size: 1.1rem; box-shadow: 0 4px 15px rgba(0,0,0,0.4); z-index: 10; border: 2px solid var(--primary-color); transform: rotate(2deg);">
                                ${menu.prix}€ <span style="font-size: 0.7rem; display: block; margin-top: -3px; opacity: 0.9;">/ pers.</span>
                            </div>

                            <div class="baroque-img-container" style="height: 220px; flex-shrink: 0;">
                                <img src="${menu.image_url || 'https://via.placeholder.com/300x200?text=Menu'}" alt="${menu.titre}" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                            </div>
                            <div style="padding: 2rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between; align-items: center;">
                                <div style="width: 100%;">
                                    <div style="margin-bottom: 1.5rem; border-bottom: 2px solid rgba(212, 175, 55, 0.4); padding-bottom: 0.8rem; display: flex; justify-content: center;">
                                        <h3 class="baroque-title" style="margin: 0; font-size: 1.6rem; line-height: 1.2; text-align: center;">${menu.titre}</h3>
                                    </div>
                                    <div class="baroque-text" style="font-size: 1.1rem; margin-bottom: 1.5rem; opacity: 0.95; line-height: 1.5; text-align: center;">${menu.description}</div>
                                    
                                    ${stockDisplay}
                                    
                                    <div style="background: rgba(0,0,0,0.03); padding: 1.2rem; border-radius: 5px; margin-bottom: 1.5rem; display: flex; flex-direction: column; align-items: center;">
                                        <div class="baroque-text" style="font-size: 1rem; margin-bottom: 0.5rem; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-users" style="color: var(--secondary-color); margin-right: 10px;"></i> Min. ${menu.min_personnes} pers.
                                        </div>
                                        <div class="baroque-text" style="font-size: 1rem; margin-bottom: 1rem; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-leaf" style="color: var(--secondary-color); margin-right: 10px;"></i> ${menu.regime || 'Classique'}
                                        </div>
                                        
                                        <div style="border-top: 1px solid rgba(212, 175, 55, 0.2); padding-top: 1rem; width: 100%;">
                                            <strong style="color: var(--secondary-color); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; display: block; margin-bottom: 0.5rem; text-align: center;">Exemple de Menu :</strong>
                                            ${menu.condensed_dishes ? `
                                                <div style="font-size: 1rem; line-height: 1.6; color: #000; text-align: center;">
                                                    <span style="font-weight: bold; color: var(--primary-color);">Entrée :</span> ${menu.condensed_dishes.entree}<br>
                                                    <span style="font-weight: bold; color: var(--primary-color);">Plat :</span> ${menu.condensed_dishes.plat}<br>
                                                    <span style="font-weight: bold; color: var(--primary-color);">Dessert :</span> ${menu.condensed_dishes.dessert}
                                                </div>
                                            ` : '<div style="font-style: italic; font-size: 0.9rem; text-align: center;">Composition variable selon arrivage</div>'}
                                        </div>
                                    </div>
                                </div>
                                
                                <div style="width: 100%; display: flex; flex-direction: column; align-items: center;">
                                    ${conditions}
                                    <a href="/Vite-et-gourmand/interface_frontend/pages/menu-detail.php?id=${menu.id}" class="btn btn-secondary" style="width: 100%; text-align: center; margin-top: 1rem; font-size: 1.1rem; font-weight: bold; padding: 18px; text-transform: uppercase; letter-spacing: 1px; display: inline-block;">Voir le détail</a>
                                </div>
                            </div>
                        </div>
                    `;
                    menusGrid.appendChild(card);
                });

                // Update totals initially
                updateSimulatorTotals();
            })
            .catch(error => {
                console.error('Erreur:', error);
                menusGrid.innerHTML = '<p>Une erreur est survenue lors du chargement des menus.</p>';
            });
    }

    // Chargement initial
    fetchMenus();
});
