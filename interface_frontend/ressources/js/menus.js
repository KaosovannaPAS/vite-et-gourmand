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

        fetch(`${BASE_URL}/noyau_backend/api/v1/menus.php?${params.toString()}`)
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

                    // Fix image path: strip /Vite-et-gourmand prefix for Vercel
                    let imgSrc = menu.image_url || '';
                    imgSrc = imgSrc.replace('/Vite-et-gourmand/', '/');
                    if (!imgSrc) imgSrc = 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600&q=80';

                    // Stock badge
                    let stockBadge = '';
                    if (menu.stock === 0) {
                        stockBadge = `<span class="menu-badge badge-rupture"><i class="fas fa-times-circle"></i> Rupture</span>`;
                    } else if (menu.stock < 10) {
                        stockBadge = `<span class="menu-badge badge-urgent"><i class="fas fa-fire"></i> Plus que ${menu.stock} !</span>`;
                    }

                    // Dishes preview
                    let dishesHtml = '';
                    if (menu.condensed_dishes) {
                        dishesHtml = `
                            <div class="menu-dishes">
                                <div class="dish-row"><span class="dish-label">Entrée</span><span>${menu.condensed_dishes.entree}</span></div>
                                <div class="dish-row"><span class="dish-label">Plat</span><span>${menu.condensed_dishes.plat}</span></div>
                                <div class="dish-row"><span class="dish-label">Dessert</span><span>${menu.condensed_dishes.dessert}</span></div>
                            </div>`;
                    }

                    card.innerHTML = `
                        <div class="menu-card-inner">
                            <div class="menu-img-wrap">
                                <img src="${imgSrc}" alt="${menu.titre}" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600&q=80'">
                                <div class="menu-img-overlay"></div>
                                <div class="menu-price-badge">${menu.prix}€<span>/pers.</span></div>
                                ${stockBadge}
                            </div>
                            <div class="menu-body">
                                <h3 class="menu-title">${menu.titre}</h3>
                                <p class="menu-desc">${menu.description}</p>
                                <div class="menu-pills">
                                    <span class="menu-pill"><i class="fas fa-users"></i> Min. ${menu.min_personnes} pers.</span>
                                    <span class="menu-pill"><i class="fas fa-leaf"></i> ${menu.regime || 'Classique'}</span>
                                </div>
                                ${dishesHtml}
                                ${menu.conditions_reservation ? `<p class="menu-conditions"><i class="fas fa-info-circle"></i> ${menu.conditions_reservation}</p>` : ''}
                                <a href="${BASE_URL}/interface_frontend/pages/menu-detail.php?id=${menu.id}" class="menu-cta">
                                    Voir le détail <i class="fas fa-arrow-right"></i>
                                </a>
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
