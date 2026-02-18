document.addEventListener('DOMContentLoaded', () => {
    const menusGrid = document.getElementById('menus-grid');
    const themeSelect = document.getElementById('theme');
    const regimeSelect = document.getElementById('regime');
    const priceRange = document.getElementById('max_price');
    const priceDisplay = document.getElementById('price_display');
    const minPeopleInput = document.getElementById('min_people');
    const minPeopleDisplay = document.getElementById('min_people_display');
    const simPeople = document.getElementById('sim_people');
    const simPeopleDisplay = document.getElementById('sim_people_display');
    const simAddress = document.getElementById('sim_address');
    const simDistance = document.getElementById('sim_distance');
    const logisticsFeeDisplay = document.getElementById('logistics_fee_display');
    const resetFiltersBtn = document.getElementById('reset_filters');

    let currentDeliveryFee = 0;

    // Event icons per theme
    const themeIcons = {
        'Noël': '🎄',
        'Prestige': '⭐',
        'Saint Valentin': '❤️',
        'Voyage en Asie': '🌏',
        'Végane': '🌿',
        'Mer': '🌊',
        'Terroir': '🍷',
        'Classique': '🍽️',
        'Événement': '🎉',
    };

    // --- EVENT LISTENERS ---
    priceRange.addEventListener('input', e => {
        priceDisplay.textContent = e.target.value + ' €';
        fetchMenus();
    });
    themeSelect.addEventListener('change', fetchMenus);
    regimeSelect.addEventListener('change', fetchMenus);
    minPeopleInput.addEventListener('input', e => {
        if (minPeopleDisplay) minPeopleDisplay.textContent = e.target.value;
        fetchMenus();
    });
    simPeople.addEventListener('input', e => {
        simPeopleDisplay.textContent = e.target.value;
        updateSimulatorTotals();
    });
    simAddress.addEventListener('input', updateLogistics);
    simDistance.addEventListener('input', updateLogistics);
    resetFiltersBtn.addEventListener('click', () => {
        themeSelect.value = '';
        regimeSelect.value = '';
        priceRange.value = 150;
        priceDisplay.textContent = '150 €';
        minPeopleInput.value = 2;
        if (minPeopleDisplay) minPeopleDisplay.textContent = '2';
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
        let discountApplied = false;
        cards.forEach(card => {
            const minGuests = parseInt(card.dataset.min || 1);
            if (peopleCount >= minGuests + 5) discountApplied = true;
        });
        if (discountMsg) discountMsg.style.display = discountApplied ? 'block' : 'none';
    }

    function buildCard(menu) {
        let imgSrc = menu.image_url || '';
        imgSrc = imgSrc.replace('/Vite-et-gourmand/', '/');
        if (!imgSrc) imgSrc = 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600&q=80';

        let stockBadge = '';
        if (menu.stock === 0) {
            stockBadge = `<span class="menu-badge badge-rupture"><i class="fas fa-times-circle"></i> Rupture</span>`;
        } else if (menu.stock < 10) {
            stockBadge = `<span class="menu-badge badge-urgent"><i class="fas fa-fire"></i> Plus que ${menu.stock} !</span>`;
        }

        let dishesHtml = '';
        if (menu.condensed_dishes) {
            const cd = menu.condensed_dishes;
            dishesHtml = `<div class="menu-dishes">
                ${cd.entree ? `<div class="dish-row"><span class="dish-label">Entrée</span><span>${cd.entree}</span></div>` : ''}
                ${cd.plat ? `<div class="dish-row"><span class="dish-label">Plat</span><span>${cd.plat}</span></div>` : ''}
                ${cd.dessert ? `<div class="dish-row"><span class="dish-label">Dessert</span><span>${cd.dessert}</span></div>` : ''}
            </div>`;
        }

        const card = document.createElement('div');
        card.className = 'menu-card';
        card.dataset.price = menu.prix;
        card.dataset.min = menu.min_personnes;
        card.innerHTML = `
            <div class="menu-card-inner">
                <div class="menu-img-wrap">
                    <img src="${imgSrc}" alt="${menu.titre}" loading="lazy"
                         onerror="this.src='https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600&q=80'">
                    <div class="menu-img-overlay"></div>
                    <div class="menu-price-badge">${parseFloat(menu.prix).toFixed(2)}€<span>/pers.</span></div>
                    ${stockBadge}
                </div>
                <div class="menu-body">
                    <h3 class="menu-title">${menu.titre}</h3>
                    <p class="menu-desc">${menu.description || ''}</p>
                    <div class="menu-pills">
                        <span class="menu-pill"><i class="fas fa-users"></i> Min. ${Math.max(2, menu.min_personnes)} pers.</span>
                        <span class="menu-pill"><i class="fas fa-leaf"></i> ${(menu.regime || 'Classique').replace('Végan', 'Végétal')}</span>
                        ${menu.theme ? `<span class="menu-pill"><i class="fas fa-star"></i> ${menu.theme}</span>` : ''}
                    </div>
                    ${dishesHtml}
                    ${dishesHtml}
                    <a href="${BASE_URL}/interface_frontend/pages/menu-detail.php?id=${menu.id}" class="menu-cta">
                        Voir le détail <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>`;
        return card;
    }

    function fetchMenus() {
        const theme = themeSelect.value;
        const regime = regimeSelect.value;
        const maxPrice = priceRange.value;
        const minPeople = minPeopleInput.value;

        const params = new URLSearchParams({ theme, regime, max_price: maxPrice, min_people: minPeople });
        menusGrid.innerHTML = '<p style="color:#888;padding:2rem;text-align:center;"><i class="fas fa-spinner fa-spin"></i> Chargement...</p>';

        fetch(`${BASE_URL}/noyau_backend/api/v1/menus.php?${params.toString()}`)
            .then(r => r.json())
            .then(data => {
                menusGrid.innerHTML = '';
                if (!data.length) {
                    menusGrid.innerHTML = '<p style="color:#888;padding:2rem;text-align:center;">Aucun menu ne correspond à vos critères.</p>';
                    return;
                }

                // Group by theme
                const groups = {};
                data.forEach(menu => {
                    const t = menu.theme || 'Autres';
                    if (!groups[t]) groups[t] = [];
                    groups[t].push(menu);
                });

                // If filtering by theme, no grouping needed
                const isFiltered = theme !== '' || regime !== '';

                if (isFiltered || Object.keys(groups).length === 1) {
                    // Flat grid
                    data.forEach(menu => menusGrid.appendChild(buildCard(menu)));
                } else {
                    // Grouped by event
                    Object.entries(groups).forEach(([groupTheme, menus]) => {
                        const icon = themeIcons[groupTheme] || '🍽️';

                        // Section header
                        const header = document.createElement('div');
                        header.className = 'theme-section-header';
                        header.innerHTML = `<span class="theme-icon">${icon}</span><span>${groupTheme}</span>`;
                        menusGrid.appendChild(header);

                        // Cards row
                        const row = document.createElement('div');
                        row.className = 'theme-cards-row';
                        menus.forEach(menu => row.appendChild(buildCard(menu)));
                        menusGrid.appendChild(row);
                    });
                }

                updateSimulatorTotals();
            })
            .catch(err => {
                console.error('Erreur:', err);
                menusGrid.innerHTML = '<p style="color:#c0392b;padding:2rem;">Une erreur est survenue lors du chargement des menus.</p>';
            });
    }

    // Init
    updateLogistics();

    // SSR Check: If grid has content (not empty and not just "Chargement"), skip initial fetch
    if (menusGrid.children.length > 0 && !menusGrid.textContent.includes('Chargement')) {
        updateSimulatorTotals();
    } else {
        fetchMenus();
    }
});
