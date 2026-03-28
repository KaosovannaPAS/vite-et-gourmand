// Simple Carousel Logic
function startCarousel(className, intervalTime) {
    const slides = document.querySelectorAll('.' + className);
    let currentSlide = 0;

    if (slides.length === 0) return;

    setInterval(() => {
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide + 1) % slides.length;
        slides[currentSlide].classList.add('active');
    }, intervalTime);
}

document.addEventListener('DOMContentLoaded', async () => {
    startCarousel('hero-slide', 5000); // 5s for hero
    startCarousel('team-slide', 6000); // 6s for team

    // Fetch Reviews
    try {
        const response = await fetch('/noyau_backend/api/v1/reviews.php');
        if (!response.ok) throw new Error('Network response was not ok');
        const avis = await response.json();

        const track = document.getElementById('reviews-track');
        const container = document.getElementById('reviews-container');
        const noReviewsMsg = document.getElementById('no-reviews-msg');

        if (avis.length > 0) {
            container.style.display = 'block';
            // On duplique les avis pour l'effet infini (x2)
            const avisDisplay = [...avis, ...avis];

            avisDisplay.forEach(avi => {
                const note = parseInt(avi.note);
                const starsHtml = '<i class="fas fa-star"></i>'.repeat(note) + '<i class="far fa-star"></i>'.repeat(5 - note);

                const card = document.createElement('div');
                card.className = 'review-card';
                card.innerHTML = `
                    <img class="client-photo" src="${avi.avatar_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(avi.prenom) + '+' + encodeURIComponent(avi.nom) + '&background=random'}" alt="Photo de ${avi.prenom}" loading="lazy">
                    <div class="client-name">${avi.prenom} ${avi.nom}</div>
                    <div class="client-stars">${starsHtml}</div>
                    <p class="client-comment">&laquo;&nbsp;${avi.commentaire}&nbsp;&raquo;</p>
                    ${avi.menu_titre ? '<p class="client-menu"><i class="fas fa-utensils" style="color: var(--primary-color); margin-right: 4px;"></i> ' + avi.menu_titre + '</p>' : ''}
                `;
                track.appendChild(card);
            });
        } else {
            noReviewsMsg.style.display = 'block';
        }
    } catch (error) {
        console.error('Erreur de chargement des avis:', error);
        document.getElementById('no-reviews-msg').style.display = 'block';
        document.getElementById('no-reviews-msg').textContent = 'Erreur lors du chargement des avis.';
    }
});
