<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme de Suivi des Activités</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="min-h-screen flex flex-col">
        <!-- Section Héro avec Carrousel -->
        <div class="hero-section">
            <!-- Carrousel d'arrière-plan -->
            <div class="carousel-container">
                <div class="carousel-track">
                    <div class="carousel-slide active">
                    <img src="images/bio-guera-2.jpg" alt="Planning et organisation"  >
                    <div class="slide-overlay"></div>
                    </div>
                    <div class="carousel-slide">
                        <img src="images/cm001.jpg" alt="Développement et suivi">
                        <div class="slide-overlay"></div>
                    </div>
                    <div class="carousel-slide">
                        <img src="images/statue.jpg" alt="Rapports et évaluations">
                        <div class="slide-overlay"></div>
                    </div>
                </div>
                
                <div class="carousel-controls">
                    <button class="carousel-btn prev-btn"><i class="fas fa-chevron-left"></i></button>
                    <button class="carousel-btn next-btn"><i class="fas fa-chevron-right"></i></button>
                </div>
                
                <div class="carousel-indicators">
                    <span class="indicator active" data-index="0"></span>
                    <span class="indicator" data-index="1"></span>
                    <span class="indicator" data-index="2"></span>
                </div>
            </div>

            <!-- Barre de navigation -->
            <div class="navbar-container" style="margin-top: 30px;">
                <nav class="navbar">
                    <img src="images/logo_masm.png" alt="Logo" class="logo">
                    <ul class="nav-menu">
                        <li><a href="acceuil.php" class="nav-link active">Accueil</a></li>
                        <li><a href="login.php" class="nav-link" style="color:black;">Tâches</a></li>
                        <li><a href="login.php" class="nav-link" style="color:black;" >Rapports</a></li>
                        <li><a href="login.php" class="nav-link connexion">
                            <i class="fas fa-calendar"></i>Connexion
                        </a></li>
                    </ul>
                    <button class="mobile-menu-btn">
                        <i class="fas fa-bars"></i>
                    </button>
                </nav>
            </div>

            <!-- Contenu principal -->
            <div class="hero-content">
                <div class="hero-text animate-fade-in">
                    <h1>Plateforme de Suivi des Activités</h1>
                    <p>
                        Bienvenue dans votre espace de gestion des tâches ! Cette plateforme vous offre une vision d'ensemble 
                        complète et intuitive sur vos missions et leur progression. Elle est conçue pour vous aider à 
                        suivre facilement vos objectifs, prioriser vos actions, et rester informé des tâches.
                    </p>
                    <div class="hero-buttons">
                        <a href="login.php" class="btn primary-btn">
                            Commencer maintenant
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <a href="register.php" class="btn outline-btn">
                            Créer un compte
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Fonctionnalités -->
        <div class="features-section">
            <div class="container">
                <h2>Fonctionnalités principales</h2>
                <p class="section-desc">
                    Découvrez les outils et fonctionnalités qui vous aideront à organiser et suivre efficacement vos tâches
                </p>
                
                <div class="cards-grid">
                    <div class="mission-card">
                        <div class="icon"><i class="fas fa-calendar"></i></div>
                        <h3>Gestion des Tâches</h3>
                        <p>Créez, organisez et suivez vos tâches quotidiennes avec des rappels et des priorités.</p>
                    </div>
                    <div class="mission-card">
                        <div class="icon"><i class="fas fa-chart-bar"></i></div>
                        <h3>Rapports Détaillés</h3>
                        <p>Générez des rapports personnalisés pour analyser les progrès et identifier les tendances.</p>
                    </div>
                    <div class="mission-card">
                        <div class="icon"><i class="fas fa-book"></i></div>
                        <h3>Documentation</h3>
                        <p>Centralisez vos documents et ressources pour un accès facile et rapide.</p>
                    </div>
                    <div class="mission-card">
                        <div class="icon"><i class="fas fa-users"></i></div>
                        <h3>Collaboration</h3>
                        <p>Travaillez en équipe, partagez des tâches et communiquez efficacement sur les projets.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section À propos -->
        <div class="about-section">
            <div class="container">
                <h2>À propos de nous</h2>
                <div class="cards-grid">
                    <div class="mission-card">
                        <div class="icon"><i class="fas fa-bullseye"></i></div>
                        <h3>Mission</h3>
                        <p>Renforcer la cohésion sociale et promouvoir l'accès aux ressources économiques pour les citoyens les plus vulnérables.</p>
                    </div>
                    <div class="mission-card">
                        <div class="icon"><i class="fas fa-eye"></i></div>
                        <h3>Vision</h3>
                        <p>Un pays où chaque citoyen bénéficie d'une égalité des chances et d'un soutien pour améliorer sa qualité de vie.</p>
                    </div>
                    <div class="mission-card">
                        <div class="icon"><i class="fas fa-heart"></i></div>
                        <h3>Valeurs</h3>
                        <p>Solidarité, équité, inclusion et innovation pour répondre aux besoins des plus démunis.</p>
                    </div>
                    <div class="mission-card">
                        <div class="icon"><i class="fas fa-history"></i></div>
                        <h3>Histoire</h3>
                        <p>Créé pour améliorer les conditions sociales et économiques des populations les plus vulnérables à travers des initiatives stratégiques.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="footer">
            <div class="container">
                <p>&copy; 2025 Ministère des Affaires Sociales et de la Microfinance. Tous droits réservés.</p>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    line-height: 1.6;
    color: #333;
}

.min-h-screen {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

.container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

h1 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    color: white;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

h2 {
    font-size: 2rem;
    margin-bottom: 1rem;
    text-align: center;
    position: relative;
    padding-bottom: 1rem;
}

h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background-color: #3b82f6;
}

h3 {
    font-size: 1.3rem;
    margin-bottom: 0.8rem;
    color: #333;
}

p {
    margin-bottom: 1rem;
}

.section-desc {
    text-align: center;
    max-width: 800px;
    margin: 0 auto 3rem;
    color: #666;
}

/* Boutons */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 1.5rem;
    border-radius: 9999px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.primary-btn {
    background-color: #3b82f6;
    color: white;
}

.primary-btn:hover {
    background-color: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
}

.outline-btn {
    background-color: rgba(255, 255, 255, 0.9);
    color: #333;
    border: 1px solid #e5e7eb;
}

.outline-btn:hover {
    background-color: rgba(255, 255, 255, 1);
    transform: translateY(-2px);
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
}

.hero-buttons {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    flex-wrap: wrap;
}

.btn i {
    margin-left: 0.5rem;
}

/* Hero Section & Carousel */
.hero-section {
    position: relative;
    height: 100vh;
    overflow: hidden;
}

.carousel-container {
    position: absolute;
    inset: 0;
    z-index: 0;
    overflow: hidden;
}

.carousel-track {
    height: 100%;
    width: 100%;
    position: relative;
}

.carousel-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 1s ease-in-out;
}

.carousel-slide.active {
    opacity: 1;
}

.carousel-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.slide-overlay {
    position: absolute;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.5);
}

.carousel-controls {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
    z-index: 20;
}

.carousel-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.8);
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #333;
    transition: all 0.3s ease;
}

.carousel-btn:hover {
    background-color: white;
    transform: scale(1.1);
}

.carousel-indicators {
    position: absolute;
    bottom: 70px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
    z-index: 20;
}

.indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.5);
    cursor: pointer;
    transition: all 0.3s ease;
}

.indicator.active {
    background-color: white;
    transform: scale(1.2);
}

/* Navbar */
.navbar-container {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    z-index: 30;
    padding: 1.5rem 1rem;
}

.navbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 9999px;
    padding: 0.75rem 1.5rem;
    max-width: 1200px;
    margin: 0 auto;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.logo {
    height: 60px;
    pointer-events: none;
}

.nav-menu {
    display: none;
    list-style: none;
    gap: 2rem;
    align-items: center;
}

@media (min-width: 768px) {
    .nav-menu {
        display: flex;
    }
    
    .mobile-menu-btn {
        display: none;
    }
}

.nav-link {
    color: #4b5563;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    text-transform: uppercase;
    font-size: 0.9rem;
}

.nav-link.active {
    color: #3b82f6;
}

.nav-link:hover {
    color: #3b82f6;
}

.nav-link.connexion {
    background-color: #3b82f6;
    color: white;
    padding: 0.5rem 1.25rem;
    border-radius: 9999px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.nav-link.connexion:hover {
    background-color: #2563eb;
}

.mobile-menu-btn {
    background: none;
    border: none;
    color: #4b5563;
    font-size: 1.5rem;
    cursor: pointer;
}

/* Hero Content */
.hero-content {
    position: absolute;
    inset: 0;
    z-index: 10;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 1rem;
}

.hero-text {
    max-width: 800px;
    animation: fadeIn 1s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.hero-text p {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1.125rem;
    margin-bottom: 2rem;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

/* Features Section */
.features-section {
    padding: 5rem 1rem;
    background: linear-gradient(to bottom, white, #f9fafb);
}

/* About Section */
.about-section {
    padding: 5rem 1rem;
    background-color: white;
}

/* Cards Grid */
.cards-grid {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: 2rem;
    margin-top: 2rem;
}

@media (min-width: 640px) {
    .cards-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .cards-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

/* Mission Card */
.mission-card {
    background-color: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.mission-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}

.mission-card .icon {
    margin-bottom: 1rem;
    color: #3b82f6;
    font-size: 2rem;
}

.mission-card p {
    color: #6b7280;
}

/* Footer */
.footer {
    background-color: #1f2937;
    color: white;
    padding: 2rem 1rem;
    margin-top: auto;
}

.footer p {
    color: #9ca3af;
    text-align: center;
    margin: 0;
}

/* Animations */
.animate-fade-in {
    animation: fadeIn 1s ease-out;
}

@media (max-width: 640px) {
    h1 {
        font-size: 2rem;
    }
    
    .hero-buttons {
        flex-direction: column;
        width: 100%;
    }
    
    .btn {
        width: 100%;
    }
}


</style>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Carrousel
    const slides = document.querySelectorAll('.carousel-slide');
    const indicators = document.querySelectorAll('.indicator');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    let currentSlide = 0;
    let slideInterval;

    // Initialisation du carrousel automatique
    function startSlideInterval() {
        slideInterval = setInterval(() => {
            nextSlide();
        }, 5000); // Change de slide toutes les 5 secondes
    }

    function showSlide(index) {
        // Enlever la classe active de tous les slides et indicateurs
        slides.forEach(slide => slide.classList.remove('active'));
        indicators.forEach(indicator => indicator.classList.remove('active'));
        
        // Ajouter la classe active au slide et à l'indicateur courant
        slides[index].classList.add('active');
        indicators[index].classList.add('active');
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    function prevSlide() {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(currentSlide);
    }

    // Événements pour les boutons suivant et précédent
    nextBtn.addEventListener('click', () => {
        clearInterval(slideInterval); // Arrêter l'intervalle lorsqu'un utilisateur clique
        nextSlide();
        startSlideInterval(); // Redémarrer l'intervalle
    });

    prevBtn.addEventListener('click', () => {
        clearInterval(slideInterval);
        prevSlide();
        startSlideInterval();
    });

    // Événements pour les indicateurs
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            clearInterval(slideInterval);
            currentSlide = index;
            showSlide(currentSlide);
            startSlideInterval();
        });
    });

    // Démarrer le carrousel
    startSlideInterval();

    // Menu mobile
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navMenu = document.querySelector('.nav-menu');

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            
            // Créer et ajouter le menu mobile s'il n'existe pas déjà
            if (!document.querySelector('.mobile-nav')) {
                const mobileNav = document.createElement('div');
                mobileNav.className = 'mobile-nav';
                
                const links = Array.from(navMenu.querySelectorAll('a'));
                const mobileNavContent = document.createElement('div');
                mobileNavContent.className = 'mobile-nav-content';
                
                links.forEach(link => {
                    const clone = link.cloneNode(true);
                    const menuItem = document.createElement('div');
                    menuItem.className = 'mobile-nav-item';
                    menuItem.appendChild(clone);
                    mobileNavContent.appendChild(menuItem);
                });
                
                mobileNav.appendChild(mobileNavContent);
                document.body.appendChild(mobileNav);
                
                // Ajouter du style pour le menu mobile
                const style = document.createElement('style');
                style.textContent = `
                    .mobile-nav {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background-color: rgba(0, 0, 0, 0.8);
                        z-index: 40;
                        display: none;
                        backdrop-filter: blur(5px);
                    }
                    
                    .mobile-nav.active {
                        display: flex;
                        animation: fadeIn 0.3s ease-out;
                    }
                    
                    .mobile-nav-content {
                        width: 100%;
                        max-width: 300px;
                        margin: 80px auto 0;
                        background-color: white;
                        border-radius: 10px;
                        padding: 20px;
                        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                    }
                    
                    .mobile-nav-item {
                        margin-bottom: 15px;
                        border-bottom: 1px solid #eee;
                        padding-bottom: 15px;
                    }
                    
                    .mobile-nav-item:last-child {
                        border-bottom: none;
                        margin-bottom: 0;
                    }
                    
                    .mobile-nav-item a {
                        display: block;
                        font-size: 18px;
                        padding: 10px 0;
                    }
                    
                    .mobile-nav-close {
                        position: absolute;
                        top: 20px;
                        right: 20px;
                        color: white;
                        font-size: 30px;
                        cursor: pointer;
                    }
                `;
                document.head.appendChild(style);
                
                // Ajouter un bouton de fermeture
                const closeBtn = document.createElement('div');
                closeBtn.className = 'mobile-nav-close';
                closeBtn.innerHTML = '&times;';
                closeBtn.addEventListener('click', () => {
                    document.querySelector('.mobile-nav').classList.remove('active');
                });
                mobileNav.appendChild(closeBtn);
            }
            
            // Afficher/masquer le menu mobile
            const mobileNav = document.querySelector('.mobile-nav');
            mobileNav.classList.toggle('active');
        });
    }

    // Détection du scroll pour effet de parallaxe
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Effet de parallaxe pour les sections
        const heroSection = document.querySelector('.hero-section');
        if (heroSection) {
            const overlay = heroSection.querySelector('.slide-overlay');
            if (overlay) {
                overlay.style.opacity = 0.5 + scrollTop * 0.001;
            }
        }
        
        // Animation à l'apparition des éléments
        const animateElements = document.querySelectorAll('.mission-card, .section-desc, h2');
        animateElements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (elementPosition < windowHeight - 100) {
                element.classList.add('animate-fade-in');
            }
        });
    });
});

</script>


</html>