<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>DIWJIB — Livraison intelligente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #7B241C;
            --secondary: #f8f9fa;
            --accent: #a02c1f;
            --text: #333;
        }
        * { font-family: 'Poppins', sans-serif; }
        body {
            background-color: var(--secondary);
            color: var(--text);
            overflow-x: hidden;
        }
        header {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 15px 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: 0.3s;
        }
        header.scrolled {
            padding: 10px 20px;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(6px);
        }
        .logo { 
            height: 50px; 
            width: auto;
        }
        @media (min-width: 768px) {
            .logo { height: 70px; }
            header { padding: 15px 40px; }
        }
        .btn-login {
            color: var(--primary);
            font-size: 1.5rem;
            transition: 0.2s;
        }
        @media (min-width: 768px) {
            .btn-login { font-size: 1.8rem; }
        }
        .btn-login:hover { color: var(--accent); }
        
        .hero {
            height: 70vh;
            background: url('{{ asset('cd.jpg') }}') center/cover no-repeat;
            position: relative;
        }
        @media (min-width: 768px) {
            .hero { height: 85vh; }
        }
        .hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
        }
        .hero-content {
            position: relative;
            z-index: 2;
            color: white;
            text-align: center;
            top: 50%;
            transform: translateY(-50%);
            animation: fadeIn 1.5s ease-in;
            padding: 0 20px;
        }
        .hero h1 {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 15px;
        }
        @media (min-width: 768px) {
            .hero h1 { font-size: 3.2rem; }
        }
        .hero p {
            font-size: 1rem;
            margin-top: 10px;
            opacity: 0.9;
            line-height: 1.5;
        }
        @media (min-width: 768px) {
            .hero p { font-size: 1.1rem; }
        }
        .btn-main {
            background-color: var(--primary);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 30px;
            transition: 0.3s;
            font-size: 1rem;
            margin-top: 20px;
        }
        @media (min-width: 768px) {
            .btn-main { 
                padding: 12px 35px;
                font-size: 1.1rem;
            }
        }
        .btn-main:hover {
            background-color: var(--accent);
            transform: translateY(-2px);
        }
        .section { 
            padding: 50px 0; 
        }
        @media (min-width: 768px) {
            .section { padding: 70px 0; }
        }
        .section-title {
            font-weight: 700;
            margin-bottom: 30px;
            color: var(--primary);
            font-size: 1.8rem;
        }
        @media (min-width: 768px) {
            .section-title { 
                font-size: 2.5rem;
                margin-bottom: 50px;
            }
        }
        .icon-box {
            background: #fff;
            border-radius: 15px;
            padding: 30px 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            transition: 0.3s;
            margin-bottom: 20px;
            height: 100%;
        }
        @media (min-width: 768px) {
            .icon-box { 
                padding: 40px 25px;
                margin-bottom: 0;
            }
        }
        .icon-box:hover {
            transform: translateY(-6px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }
        .icon-box i {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 15px;
        }
        @media (min-width: 768px) {
            .icon-box i { font-size: 2.5rem; }
        }
        .icon-box h5 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 15px;
        }
        @media (min-width: 768px) {
            .icon-box h5 { font-size: 1.3rem; }
        }
        .icon-box p {
            font-size: 0.9rem;
            line-height: 1.5;
            color: #666;
        }
        @media (min-width: 768px) {
            .icon-box p { font-size: 1rem; }
        }
        .contact p { 
            font-size: 1rem;
            margin-bottom: 10px;
        }
        .contact p i { 
            color: var(--primary);
            margin-right: 10px;
        }
        .footer {
            background-color: #111;
            color: #bbb;
            padding: 20px 0;
            text-align: center;
            font-size: 0.9rem;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .floating-login {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--primary);
            color: #fff;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            z-index: 999;
            transition: 0.3s;
            font-size: 1.2rem;
        }
        .floating-login:hover {
            background: var(--accent);
            transform: scale(1.1);
        }
        .slogan {
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary);
            line-height: 1.3;
        }
        @media (min-width: 768px) {
            .slogan { font-size: 1.4rem; }
        }
        @media (max-width: 576px) {
            .hero h1 { font-size: 1.8rem; }
            .section-title { font-size: 1.6rem; }
            .hero { height: 60vh; }
        }
        
        /* Améliorations spécifiques mobile */
        .mobile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
        @media (min-width: 768px) {
            .mobile-header { display: none; }
        }
        .desktop-header {
            display: none;
        }
        @media (min-width: 768px) {
            .desktop-header { 
                display: flex; 
                justify-content: space-between;
                align-items: center;
                width: 100%;
            }
        }
        .mobile-slogan {
            font-size: 0.9rem;
            text-align: center;
            margin-top: 10px;
            color: var(--primary);
            font-weight: 600;
        }
        @media (min-width: 768px) {
            .mobile-slogan { display: none; }
        }
    </style>
</head>
<body>
    <!-- Header Mobile -->
    <div class="mobile-header">
        <img src="{{ asset('diwjib.png') }}" alt="Logo DIWJIB" class="logo">
        <a href="{{ route('login') }}" class="btn-login">
            <i class="bi bi-person-circle"></i>
        </a>
    </div>
    <div class="mobile-slogan">
        Livraison rapide, intelligente et fiable
    </div>

    <!-- Header Desktop -->
    <header class="desktop-header">
        <img src="{{ asset('diwjib.png') }}" alt="Logo DIWJIB" class="logo">
        <div class="flex-grow-1 text-center">
            <span class="slogan">
                Livraison rapide, intelligente et fiable
            </span>
        </div>
        <a href="{{ route('login') }}" class="btn-login">
            <i class="bi bi-person-circle"></i>
        </a>
    </header>

    <section class="hero">
        <div class="hero-content container">
            <h1>Livraisons optimisées, service intelligent</h1>
            <p>Bienvenue sur DIWJIB — votre solution simple et efficace pour la logistique urbaine</p>
            <a href="{{ route('register') }}" class="btn-main">🚀 Commencez maintenant</a>
        </div>
    </section>

    <section class="section text-center bg-light">
        <div class="container">
            <h2 class="section-title">Nos Services</h2>
            <div class="row g-4">
                <div class="col-12 col-md-4">
                    <div class="icon-box">
                        <i class="bi bi-geo-alt-fill"></i>
                        <h5>Cartographie</h5>
                        <p>Visualisez toutes vos livraisons sur une carte interactive en temps réel.</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="icon-box">
                        <i class="bi bi-lightning-fill"></i>
                        <h5>Optimisation</h5>
                        <p>Itinéraires intelligents et calculs automatiques selon vos priorités.</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="icon-box">
                        <i class="bi bi-upload"></i>
                        <h5>Importation CSV</h5>
                        <p>Ajoutez facilement des centaines d'adresses à partir d'un fichier CSV.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section text-center">
        <div class="container">
            <h2 class="section-title">Pourquoi choisir DIWJIB ?</h2>
            <div class="row g-4">
                <div class="col-12 col-md-6">
                    <div class="icon-box">
                        <i class="bi bi-speedometer2"></i>
                        <h5>Performance</h5>
                        <p>Des trajets plus rapides et économiques grâce à des algorithmes avancés.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="icon-box">
                        <i class="bi bi-shield-lock-fill"></i>
                        <h5>Sécurité</h5>
                        <p>Vos données et livraisons sont protégées avec les plus hauts standards.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section bg-light text-center contact">
        <div class="container">
            <h2 class="section-title">Contactez-nous</h2>
            <p class="mb-3">Notre équipe est à votre écoute</p>
            <p><i class="bi bi-envelope-fill"></i>support@diwjib.com</p>
            <p><i class="bi bi-telephone-fill"></i>+212 6 00 00 00 00</p>
            <p><i class="bi bi-geo-alt-fill"></i>Casablanca, Maroc</p>
        </div>
    </section>

    <footer class="footer">
        <p>&copy; {{ date('Y') }} DIWJIB. Tous droits réservés.</p>
    </footer>

    <a href="{{ route('login') }}" class="floating-login d-md-none">
        <i class="bi bi-person-fill"></i>
    </a>

    <script>
        window.addEventListener("scroll", () => {
            const header = document.querySelector("header");
            if (header) {
                header.classList.toggle("scrolled", window.scrollY > 50);
            }
        });

        // Optimisation du chargement des images
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                img.loading = 'lazy';
            });
        });
    </script>

    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/68fe2d9b511129194ce15981/1j8ge49tn';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
</body>
</html>