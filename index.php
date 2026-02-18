<?php
// ============================================
// PARTIE NOYAU (BACK-END) : LOGIQUE METIER & DONNEES
// ============================================
session_start();
include __DIR__ . '/noyau_backend/configuration/db.php';
include __DIR__ . '/interface_frontend/composants/header.php';
?>

<!-- ============================================
     PARTIE INTERFACE (FRONT-END) : PRESENTATION & VUE
     ============================================ -->

<style>
/* Hero Slideshow Styles */
.hero {
    position: relative;
    overflow: hidden;
}
.hero-slideshow {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
}
.hero-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    opacity: 0;
    transition: opacity 2s ease-in-out;
}
.hero-slide.active {
    opacity: 1;
}

/* History Carousel Styles */
.history-carousel {
    position: relative;
    width: 100%;
    height: 400px;
    border-radius: 2px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}
.history-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    opacity: 0;
    transition: opacity 1s ease-in-out;
}
.history-slide.active, .hero-slide.active, .team-slide.active {
    opacity: 1;
}

.team-slideshow {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
}

.team-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    opacity: 0;
    transition: opacity 1.5s ease-in-out;
}
</style>

<section class="hero" style="height: 80vh; display: flex; align-items: center; justify-content: center; color: #000000; text-align: center;">
    <!-- Hero Background Slides -->
    <div class="hero-slideshow">
        <div class="hero-slide active" style="background-image: url('https://images.unsplash.com/photo-1555244162-803834f70033?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');"></div>
        <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1519167758481-83f550bb49b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');"></div> <!-- Banquet -->
        <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1414235077428-338989a2e8c0?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');"></div> <!-- Gourmet Plate -->
    </div>
    
    <div style="background: rgba(255,255,255,0.6); padding: 3rem; border-radius: 2px; border: 2px solid var(--secondary-color); max-width: 800px; position: relative; z-index: 1; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <h1 style="font-size: 3.5rem; margin-bottom: 1rem; color: var(--primary-color); font-family: var(--font-heading);">Vite & Gourmand</h1>
        <p style="font-size: 1.4rem; margin-bottom: 2.5rem; font-family: var(--font-heading); font-style: italic; color: #000000;">"L'art de recevoir, l'excellence de servir."</p>
        <a href="<?php echo BASE_URL; ?>/interface_frontend/pages/menus.php" class="btn btn-primary" style="font-size: 1.1rem; padding: 15px 30px; border: 1px solid var(--secondary-color); color: #000000;">Découvrir nos Collections</a>
    </div>
</section>

<section id="history" class="container" style="padding: 6rem 2rem;">
    <h2 style="text-align: center; margin-bottom: 4rem; font-size: 2.5rem;">Notre Histoire</h2>
    <div style="display: flex; gap: 4rem; align-items: center; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 300px;">
            <h3 style="color: var(--primary-color); margin-bottom: 1.5rem; font-size: 2.5rem; font-family: var(--font-heading);">Julie & José</h3>
            <div style="font-size: 1.4rem; line-height: 1.6; color: #000000;">
                <p style="margin-bottom: 1.5rem; text-align: justify;">
                    Tout a commencé il y a 25 ans, lors d'un prestigieux gala au cœur des vignobles bordelais. <strong>Julie</strong>, jeune cheffe prodige formée dans les plus grandes maisons étoilées, dirigeait les cuisines avec une précision chirurgicale. <strong>José</strong>, maître d'hôtel reconnu pour son élégance et son sens inné de l'organisation, orchestrait le service en salle tel un chef d'orchestre.
                </p>
                <p style="margin-bottom: 1.5rem; text-align: justify;">
                    De cette rencontre est née une évidence : unir la <strong>haute gastronomie</strong> à une <strong>logistique événementielle sans faille</strong>. C'est ainsi que <em>Vite & Gourmand</em> a vu le jour.
                </p>
                <p style="text-align: justify; font-style: italic; opacity: 0.9;">
                    Aujourd'hui, ce duo complémentaire continue de réinventer l'art de recevoir, mêlant tradition culinaire française et modernité du service, pour faire de chacun de vos événements un moment d'exception.
                </p>
            </div>
        </div>
        <div style="flex: 1; min-width: 300px; position: relative;">
            <div style="position: absolute; top: -20px; left: -20px; width: 100%; height: 100%; border: 2px solid var(--secondary-color); border-radius: 2px; z-index: 0;"></div>
            
            <div style="position: relative; width: 100%; height: 450px; border-radius: 2px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border: 1px solid var(--secondary-color);">
                <img src="https://images.unsplash.com/photo-1507048331197-7d4ac70811cf?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" alt="Julie & José cuisinant" style="width: 100%; height: 100%; object-fit: cover; filter: brightness(1.05) contrast(1.02);">
            </div>
        </div>
    </div>
</section>

<script>
// Simple Carousel Logic
function startCarousel(className, intervalTime) {
    const slides = document.querySelectorAll('.' + className);
    let currentSlide = 0;
    
    setInterval(() => {
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide + 1) % slides.length;
        slides[currentSlide].classList.add('active');
    }, intervalTime);
}

document.addEventListener('DOMContentLoaded', () => {
    startCarousel('hero-slide', 5000); // 5s for hero
    startCarousel('team-slide', 6000); // 6s for team
});
</script>

<section class="team-section" style="position: relative; padding: 6rem 2rem; overflow: hidden; color: var(--primary-color);">
    <!-- Section Background Slideshow -->
    <div class="team-slideshow">
        <div class="team-slide active" style="background-image: url('https://images.unsplash.com/photo-1556910103-1c02745aae4d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');"></div> <!-- Professional kitchen -->
        <div class="team-slide" style="background-image: url('https://images.unsplash.com/photo-1512485800893-b08ec1ea59b1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');"></div> <!-- Plating -->
        <div class="team-slide" style="background-image: url('https://images.unsplash.com/photo-1414235077428-338989a2e8c0?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');"></div> <!-- Gourmet vibe -->
    </div>
    <div class="bg-overlay" style="background: rgba(255,255,255,0.3); z-index: 1;"></div>

    <div class="container" style="position: relative; z-index: 2;">
        <h2 style="text-align: center; margin-bottom: 4rem; color: var(--primary-color); font-family: var(--font-heading); font-size: 2.5rem;">L'excellence de notre équipe</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 3rem;">
            <div style="background: rgba(255,255,255,0.8); padding: 3rem 2rem; border-radius: 5px; text-align: center; border: 1px solid var(--secondary-color); transition: transform 0.3s; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <i class="fas fa-certificate" style="font-size: 3.5rem; color: var(--primary-color); margin-bottom: 1.5rem;"></i>
                <h3 style="margin-bottom: 1.5rem; color: var(--primary-color); font-size: 1.6rem;">Savoir-faire artisanal</h3>
                <p style="color: var(--primary-color); font-size: 1.1rem; font-weight: 700;">Tous nos plats sont préparés le jour même avec des ingrédients sélectionnés avec rigueur.</p>
            </div>
            <div style="background: rgba(255,255,255,0.8); padding: 3rem 2rem; border-radius: 5px; text-align: center; border: 1px solid var(--secondary-color); transition: transform 0.3s; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <i class="fas fa-truck" style="font-size: 3.5rem; color: var(--primary-color); margin-bottom: 1.5rem;"></i>
                <h3 style="margin-bottom: 1.5rem; color: var(--primary-color); font-size: 1.6rem;">Logistique Maîtrisée</h3>
                <p style="color: var(--primary-color); font-size: 1.1rem; font-weight: 700;">Nous livrons sur Bordeaux et ses alentours dans le respect absolu de la chaîne du froid.</p>
            </div>
            <div style="background: rgba(255,255,255,0.8); padding: 3rem 2rem; border-radius: 5px; text-align: center; border: 1px solid var(--secondary-color); transition: transform 0.3s; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <i class="fas fa-heart" style="font-size: 3.5rem; color: var(--primary-color); margin-bottom: 1.5rem;"></i>
                <h3 style="margin-bottom: 1.5rem; color: var(--primary-color); font-size: 1.6rem;">Passion du Service</h3>
                <p style="color: var(--primary-color); font-size: 1.1rem; font-weight: 700;">Une équipe dévouée, orchestrée par José, pour faire de votre événement un succès.</p>
            </div>
        </div>
    </div>
</section>

<section id="news" class="container" style="padding: 6rem 2rem;">
    <h2 style="text-align: center; margin-bottom: 4rem; font-size: 2.5rem;">Nos Actualités</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem;">
        
        <!-- News 1 -->
        <article style="background: rgba(255,255,255,0.95); box-shadow: 0 4px 20px rgba(0,0,0,0.15); border-radius: 5px; overflow: hidden; transition: transform 0.3s; border: 1px solid var(--secondary-color);">
            <div style="height: 200px; background: url('<?php echo BASE_URL; ?>/interface_frontend/ressources/images/news/spring_menu.png') center/cover; filter: contrast(1.1) brightness(1.05); border-bottom: 1px solid var(--secondary-color);"></div>
            <div style="padding: 2rem;">
                <span style="color: var(--primary-color); font-weight: bold; font-size: 0.9rem; text-transform: uppercase;">15 Février 2026</span>
                <h3 style="margin: 0.5rem 0 1rem 0; font-size: 1.5rem; color: var(--primary-color);">La Carte Printemps-Été</h3>
                <p style="color: #333; margin-bottom: 1.5rem; font-weight: 500;">Julie a sélectionné pour vous les meilleures asperges de Blaye et les fraises de Carpentras pour une carte pleine de fraîcheur.</p>
                <a href="#" style="color: var(--secondary-color); font-weight: bold; text-decoration: none; background: var(--primary-color); padding: 5px 15px; border-radius: 3px;">Lire la suite &rarr;</a>
            </div>
        </article>

        <!-- News 2 -->
        <article style="background: rgba(255,255,255,0.95); box-shadow: 0 4px 20px rgba(0,0,0,0.15); border-radius: 5px; overflow: hidden; transition: transform 0.3s; border: 1px solid var(--secondary-color);">
            <div style="height: 200px; background: url('<?php echo BASE_URL; ?>/interface_frontend/ressources/images/news/label_rse.png') center/cover; filter: contrast(1.1) brightness(1.05); border-bottom: 1px solid var(--secondary-color);"></div>
            <div style="padding: 2rem;">
                <span style="color: var(--primary-color); font-weight: bold; font-size: 0.9rem; text-transform: uppercase;">02 Février 2026</span>
                <h3 style="margin: 0.5rem 0 1rem 0; font-size: 1.5rem; color: var(--primary-color);">Label "Traiteur Responsable"</h3>
                <p style="color: #333; margin-bottom: 1.5rem; font-weight: 500;">Vite & Gourmand est fier d'annoncer l'obtention du label RSE niveau "Excellence" pour notre gestion des déchets.</p>
                <a href="#" style="color: var(--secondary-color); font-weight: bold; text-decoration: none; background: var(--primary-color); padding: 5px 15px; border-radius: 3px;">Lire la suite &rarr;</a>
            </div>
        </article>

        <!-- News 3 -->
        <article style="background: rgba(255,255,255,0.95); box-shadow: 0 4px 20px rgba(0,0,0,0.15); border-radius: 5px; overflow: hidden; transition: transform 0.3s; border: 1px solid var(--secondary-color);">
            <div style="height: 200px; background: url('<?php echo BASE_URL; ?>/interface_frontend/ressources/images/news/chateau_margaux.png') center/cover; filter: contrast(1.1) brightness(1.05); border-bottom: 1px solid var(--secondary-color);"></div>
            <div style="padding: 2rem;">
                <span style="color: var(--primary-color); font-weight: bold; font-size: 0.9rem; text-transform: uppercase;">20 Janvier 2026</span>
                <h3 style="margin: 0.5rem 0 1rem 0; font-size: 1.5rem; color: var(--primary-color);">Partenariat Château Margaux</h3>
                <p style="color: #333; margin-bottom: 1.5rem; font-weight: 500;">Nous devenons le traiteur officiel des réceptions privées du prestigieux Château Margaux pour cette année.</p>
                <a href="#" style="color: var(--secondary-color); font-weight: bold; text-decoration: none; background: var(--primary-color); padding: 5px 15px; border-radius: 3px;">Lire la suite &rarr;</a>
            </div>
        </article>

    </div>
</section>

<style>
/* Carrousel Fluide CSS */
.carousel-container {
    overflow: hidden;
    width: 100%;
    position: relative;
    padding: 1rem 0;
    mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
    -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
}
.carousel-track {
    display: flex;
    gap: 2rem;
    width: max-content;
    animation: scroll 30s linear infinite;
}
.carousel-track:hover {
    animation-play-state: paused;
}
@keyframes scroll {
    to { transform: translateX(-50%); }
}
.review-card {
    min-width: 350px;
    flex-shrink: 0;
    background: white; 
    padding: 1.5rem; 
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border-left: 5px solid var(--primary-color);
    transition: transform 0.3s ease;
}
.review-card:hover {
    transform: translateY(-5px);
}
</style>

<section class="container" style="padding: 4rem 2rem; overflow: hidden;">
    <h2 style="text-align: center; margin-bottom: 3rem;">Nos clients témoignent</h2>
    
    <?php
// Récupération des avis validés avec le menu commandé
// Utilisation d'une sous-requête pour récupérer les menus liés à la commande
$sql = "SELECT r.*, u.prenom, u.nom, u.avatar_url, 
            (SELECT GROUP_CONCAT(DISTINCT m.titre SEPARATOR ', ') 
             FROM order_items oi 
             JOIN menus m ON oi.menu_id = m.id 
             WHERE oi.order_id = r.order_id) as menu_titre
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.valide = 1 
            ORDER BY r.created_at DESC 
            ORDER BY r.created_at DESC 
            LIMIT 20";

$stmt = $pdo->query($sql);
$avis = $stmt->fetchAll();

if (count($avis) > 0) {
    // On duplique les avis pour l'effet infini (x2)
    $avis_display = array_merge($avis, $avis);

    echo '<div class="carousel-container">';
    echo '<div class="carousel-track">';

    foreach ($avis_display as $avi) {
        echo '<div class="review-card">';
        echo '<div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">';
        echo '<img src="' . htmlspecialchars($avi['avatar_url']) . '" style="width: 50px; height: 50px; border-radius: 50%; border: 2px solid var(--secondary-color); object-fit: cover;">';
        echo '<div style="color: #f1c40f; font-size: 1rem;">' . str_repeat('<i class="fas fa-star"></i>', $avi['note']) . '</div>';
        echo '</div>';
        echo '<p style="font-style: italic; font-size: 1.1rem; margin-bottom: 1rem; color: #000000;">"' . htmlspecialchars($avi['commentaire']) . '"</p>';

        // Affichage du menu
        if (!empty($avi['menu_titre'])) {
            echo '<p style="color: #666; font-size: 0.9rem; margin-bottom: 0.5rem;"><i class="fas fa-utensils"></i> A commandé : <strong>' . htmlspecialchars($avi['menu_titre']) . '</strong></p>';
        }

        echo '<div style="display: flex; align-items: center; justify-content: flex-end; margin-top: auto;">';
        echo '<div style="font-weight: bold; font-size: 1rem; color: var(--primary-color);">- ' . htmlspecialchars($avi['prenom']) . ' ' . htmlspecialchars($avi['nom']) . '</div>';
        echo '</div>';
        echo '</div>';
    }

    echo '</div>'; // Fin track
    echo '</div>'; // Fin container
}
else {
    echo '<p style="text-align: center;">Aucun avis pour le moment.</p>';
}
?>
</section>

<?php include 'interface_frontend/composants/footer.php'; ?>
