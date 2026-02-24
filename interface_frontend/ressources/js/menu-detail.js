document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const menuId = urlParams.get('id');

    if (!menuId) {
        window.location.href = '/interface_frontend/pages/menus.html';
        return;
    }

    const fmt = v => new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(v);

    fetch(`${BASE_URL}/backend_prod/api/v1/menus.php?id=${menuId}`)
        .then(response => response.json())
        .then(menu => {
            if (menu.error || !menu.id) {
                window.location.href = '/interface_frontend/pages/menus.html';
                return;
            }

            // Reveal container
            document.getElementById('menu-app').style.display = 'block';

            // Hero Banner
            let heroImg = menu.image_url ? (menu.image_url.startsWith('http') || menu.image_url.startsWith('/') ? menu.image_url : BASE_URL + '/' + menu.image_url) : '';
            if (!heroImg) heroImg = 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=1200&q=80';

            const imgEl = document.getElementById('hero-img');
            imgEl.src = heroImg;
            imgEl.onerror = () => { imgEl.src = 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=1200&q=80'; };

            document.getElementById('hero-title').textContent = menu.titre;

            let metaHtml = `<span class="hero-badge">${fmt(menu.prix)} <small style="font-size:0.6em;font-weight:600">/pers.</small></span>
                            <span class="hero-pill"><i class="fas fa-users"></i> Min. ${Math.max(2, menu.min_personnes)} pers.</span>`;
            if (menu.regime) {
                const regimeText = menu.regime.toLowerCase().includes('gan') ? 'Végan' : menu.regime;
                metaHtml += `<span class="hero-pill"><i class="fas fa-leaf"></i> ${regimeText}</span>`;
            }
            if (menu.theme) {
                metaHtml += `<span class="hero-pill"><i class="fas fa-star"></i> ${menu.theme}</span>`;
            }
            document.getElementById('hero-meta').innerHTML = metaHtml;

            // Description and conditions
            document.getElementById('menu-desc').innerHTML = menu.description ? menu.description.replace(/\n)/g, '<br>') : '';
            if (menu.conditions_reservation) {
                document.getElementById('menu-conditions').innerHTML = `
                    <div style="display:flex;align-items:center;gap:10px;background:rgba(212,175,55,0.1);border-left:4px solid var(--secondary-color);padding:0.75rem 1rem;border-radius:0 8px 8px 0;margin-top:1rem;font-size:0.88rem;color:#555;">
                        <i class="fas fa-calendar-alt" style="color:var(--secondary-color);"></i>
                        ${menu.conditions_reservation}
                    </div>`;
            }

            // Dishes Logic
            const dishesContainer = document.getElementById('dishes-container');
            if (!menu.allDishes || menu.allDishes.length === 0) {
                dishesContainer.innerHTML = '<p style="color:#888;font-style:italic;margin-top:2rem;">La composition détaillée sera personnalisée selon vos envies et la saison.</p>';
            } else {
                const standard = { entree: [], plat: [], dessert: [] };
                const vegan = { entree: null, plat: null, dessert: null };
                const glutenFree = { entree: null, plat: null, dessert: null };

                menu.allDishes.forEach(dish => {
                    const t = dish.type;
                    if (!['entree', 'plat', 'dessert'].includes(t)) return;

                    if (dish.is_vegan && !vegan[t]) vegan[t] = dish;
                    else if (dish.is_gluten_free && !dish.is_vegan && !glutenFree[t]) glutenFree[t] = dish;
                    else if (standard[t].length < 2) standard[t].push(dish);
                });

                let dishesHtml = '';

                // Render helper
                const renderDish = (dish, extraClass = '') => {
                    let img = dish.image_url ? (dish.image_url.startsWith('http') || dish.image_url.startsWith('/') ? dish.image_url : BASE_URL + '/' + dish.image_url) : '';
                    let tags = '';
                    if (dish.is_vegan) tags += '<span class="dish-tag tag-vegan"><i class="fas fa-leaf"></i> Vegan</span>';
                    if (dish.is_gluten_free) tags += '<span class="dish-tag tag-gluten"><i class="fas fa-wheat-awn"></i> Sans Gluten</span>';
                    if (dish.allergenes) tags += `<span class="dish-tag tag-allergen"><i class="fas fa-exclamation-triangle"></i> ${dish.allergenes}</span>`;

                    const imgHtml = img
                        ? `<img class="dish-img" src="${img}" alt="${dish.nom}" loading="lazy" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"><div class="dish-img-placeholder" style="display:none"><i class="fas fa-utensils"></i></div>`
                        : `<div class="dish-img-placeholder"><i class="fas fa-utensils"></i></div>`;

                    const stepperHtml = `
                    <div style="display:flex; justify-content:flex-end; margin-top:auto; padding-top:0.5rem;">
                        <div class="qty-stepper">
                            <button class="qty-btn" onclick="qtyChange('qty_dish_${dish.id}',-1)">−</button>
                            <span class="qty-num" id="qty_dish_${dish.id}">0</span>
                            <button class="qty-btn" onclick="qtyChange('qty_dish_${dish.id}',1)">+</button>
                        </div>
                    </div>`;

                    return `
                    <div class="dish-card ${extraClass}">
                        ${imgHtml}
                        <div class="dish-info" style="display:flex; flex-direction:column;">
                            <h4 class="dish-name">${dish.nom}</h4>
                            ${dish.description ? `<p class="dish-desc">${dish.description}</p>` : ''}
                            ${tags ? `<div class="dish-tags">${tags}</div>` : ''}
                            ${stepperHtml}
                        </div>
                    </div>`;
                };

                const types = [
                    { key: 'entree', label: 'Entrées', icon: 'fa-seedling' },
                    { key: 'plat', label: 'Plats Principaux', icon: 'fa-drumstick-bite' },
                    { key: 'dessert', label: 'Desserts', icon: 'fa-ice-cream' }
                ];

                // Standard
                types.forEach(type => {
                    if (standard[type.key].length > 0) {
                        dishesHtml += `
                        <div class="section-label">
                            <i class="fas ${type.icon}"></i>
                            <span>${type.label}</span>
                        </div>
                        <div class="dish-grid">
                            ${standard[type.key].map(d => renderDish(d)).join('')}
                        </div>`;
                    }
                });

                // Vegan
                if (vegan.entree || vegan.plat || vegan.dessert) {
                    dishesHtml += `
                    <div class="diet-header vegan">
                        <i class="fas fa-leaf"></i> Options 100% Véganes
                    </div>
                    <div class="dish-grid">
                        ${[vegan.entree, vegan.plat, vegan.dessert].filter(d => d).map(d => renderDish(d, 'vegan-card')).join('')}
                    </div>`;
                }

                // Gluten Free
                if (glutenFree.entree || glutenFree.plat || glutenFree.dessert) {
                    dishesHtml += `
                    <div class="diet-header glutenfree">
                        <i class="fas fa-ban"></i> Options Sans Gluten
                    </div>
                    <div class="dish-grid">
                        ${[glutenFree.entree, glutenFree.plat, glutenFree.dessert].filter(d => d).map(d => renderDish(d, 'gluten-card')).join('')}
                    </div>`;
                }

                dishesContainer.innerHTML = dishesHtml;
            }

            // SIMULATOR LOGIC
            const guestInput = document.getElementById('guest_count');
            const guestDisplay = document.getElementById('guest_display');
            const countSummary = document.getElementById('count_summary');
            const basePriceDisplay = document.getElementById('base_price_total');
            const discountRow = document.getElementById('discount_row');
            const discountAmount = document.getElementById('discount_amount');
            const neededSpan = document.getElementById('needed_for_discount');
            const discountInfo = document.getElementById('discount_info');
            const totalDisplay = document.getElementById('total_price');
            const actionBtn = document.getElementById('action-btn');

            const pricePerPerson = parseFloat(menu.prix);
            const minGuests = Math.max(2, parseInt(menu.min_personnes));

            document.getElementById('sidebar-price').innerHTML = `${fmt(pricePerPerson)} <span>/ personne</span>`;
            document.getElementById('sidebar-min').textContent = `Min. ${minGuests} personnes`;
            guestInput.min = minGuests;
            guestInput.value = minGuests;
            guestDisplay.textContent = minGuests;

            function updateSimulator() {
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
                    actionBtn.href = `${BASE_URL}/interface_frontend/pages/order.html?menu_id=${menu.id}&quantite=${count}`;
                    actionBtn.style.background = 'var(--primary-color)';
                } else {
                    actionBtn.textContent = 'Demander un devis';
                    actionBtn.href = `${BASE_URL}/interface_frontend/pages/contact.html?sujet=Devis&menu=${encodeURIComponent(menu.titre)}&guests=${count}`;
                    actionBtn.style.background = '#2c3e50';
                }
            }

            guestInput.addEventListener('input', updateSimulator);
            updateSimulator();
        })
        .catch(err => {
            console.error('Erreur API:', err);
            document.getElementById('menu-app').innerHTML = '<p style="text-align:center;padding:4rem;">Erreur de chargement des données. Veuillez réessayer plus tard.</p>';
            document.getElementById('menu-app').style.display = 'block';
        });
});

// Quantity steppers function (global)
window.qtyChange = function (id, delta) {
    const el = document.getElementById(id);
    if (!el) return;
    let v = parseInt(el.textContent) || 0;
    v = Math.max(0, v + delta);
    el.textContent = v;
};
