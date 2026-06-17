<?php if (!defined('BASE_URL')) exit; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Task 5: Título Dinâmico por Página -->
    <title><?php if (isset($pageTitle) && $pageTitle): ?>Projeto <?php echo htmlspecialchars($pageTitle); ?> | Portfólio Joelton Souza<?php else: ?>Portfólio Joelton Souza | Especialista em Design & Tech<?php endif; ?></title>
    <!-- Task 5: Meta Description Dinâmica -->
    <?php if (isset($pageTitle) && $pageTitle): ?>
    <meta name="description" content="<?php echo 'Case ' . htmlspecialchars($pageTitle) . ' — projeto de ' . htmlspecialchars($categoryName ?? 'Design') . ' por Joelton Souza. Especialista em Design Institucional e Desenvolvimento Web em Brasília.'; ?>">
    <?php else: ?>
    <meta name="description" content="Portfólio de Joelton Souza — Especialista em Design Institucional, Branding e Desenvolvimento Web em Brasília. Cases para Marinha, MCTI, CNMP e outros órgãos federais.">
    <?php endif; ?>
    <meta name="author" content="Joelton Souza">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Plus Jakarta Sans Font family -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <!-- GSAP for animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- Task 3: GLightbox para galeria de projetos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <style>
        :root {
            --primary: #0a0a0a;
            --accent-start: #f3f3f3;
            --accent-end: #a1a1a1;
            --text-main: #ffffff;
            --text-dim: #888888;
            --glass-bg: rgba(255, 255, 255, 0.02);
            --glass-border: rgba(255, 255, 255, 0.05);
            --nav-bg: rgba(10, 10, 10, 0.9);
        }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--primary);
            color: var(--text-main);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Grainy Texture Overlay */
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: url("https://grainy-gradients.vercel.app/noise.svg");
            opacity: 0.05;
            pointer-events: none;
            z-index: 9999;
        }
        
        .playfair { font-family: 'Plus Jakarta Sans', sans-serif; font-style: italic; }
        
        .editorial-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -0.05em;
            line-height: 0.85;
            font-weight: 900;
        }

        .outline-text {
            -webkit-text-stroke: 1px rgba(255,255,255,0.2);
            color: transparent;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 0.5rem; /* rounded-lg (8px) padronizado */
        }
        
        .gradient-text {
            background: linear-gradient(to right, #ffffff, #888888);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .bg-custom-gradient {
            background: #ffffff;
            color: #000000;
        }

        .bg-premium-button {
            background: linear-gradient(135deg, #0875e9 0%, #8309ee 100%);
            box-shadow: 0 10px 30px rgba(8, 117, 233, 0.2);
            transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1);
        }

        .bg-premium-button:hover {
            box-shadow: 0 15px 40px rgba(8, 117, 233, 0.4);
            filter: brightness(1.1);
        }

        .border-custom-gradient {
            border: 1px solid var(--accent-start);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--primary); }
        ::-webkit-scrollbar-thumb { background: #1a2a4a; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--accent-start); }
        
        .chart-bar { transform-origin: bottom; }
        .animate-spin-slow { animation: spin 8s linear infinite; }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

        /* ================================================================
           Task 2 — MacBook Pro Frame (CSS Puro)
           ================================================================ */
        .macbook-wrapper {
            width: 100%;
            max-width: 900px;
            margin: 0 auto 5rem auto;
        }

        .macbook-frame {
            width: 100%;
            background: #1e1e1e;
            border-radius: 12px 12px 0 0;
            border: 1.5px solid #3a3a3a;
            box-shadow: 0 40px 80px rgba(0,0,0,0.8), 0 0 0 1px rgba(255,255,255,0.05);
            overflow: hidden;
            position: relative;
        }

        .macbook-bar {
            height: 36px;
            background: linear-gradient(180deg, #2d2d2d 0%, #222222 100%);
            border-bottom: 1px solid #1a1a1a;
            display: flex;
            align-items: center;
            padding: 0 14px;
            gap: 8px;
            position: relative;
        }

        .mac-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
        }

        .mac-address-bar {
            flex: 1;
            background: rgba(0,0,0,0.3);
            border-radius: 4px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 8px;
            overflow: hidden;
        }

        .mac-address-bar span {
            font-size: 10px;
            color: rgba(255,255,255,0.4);
            font-family: 'Plus Jakarta Sans', monospace;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
            padding: 0 8px;
        }

        .macbook-screen {
            width: 100%;
            overflow: hidden;
            max-height: 520px;
            position: relative;
            background: #fff;
        }

        /* Container interno para scroll */
        .macbook-scroll-container {
            width: 100%;
            overflow: hidden;
            position: relative;
        }

        .macbook-screenshot {
            width: 100%;
            display: block;
            transition: none;
        }

        /* Efeito auto-scroll ao hover para screenshots longas */
        .macbook-scroll-container.auto-scroll {
            max-height: 520px;
        }

        .macbook-scroll-container.auto-scroll .macbook-screenshot {
            transition: transform 4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            transform: translateY(0%);
        }

        .macbook-scroll-container.auto-scroll:hover .macbook-screenshot {
            transform: translateY(calc(-100% + 520px));
        }

        /* Base do MacBook */
        .macbook-base {
            width: 110%;
            margin-left: -5%;
            height: 22px;
            background: linear-gradient(180deg, #2a2a2a 0%, #1a1a1a 100%);
            border-radius: 0 0 12px 12px;
            border: 1px solid #111;
            display: flex;
            justify-content: center;
            align-items: flex-end;
            padding-bottom: 4px;
        }

        .macbook-notch {
            width: 80px;
            height: 6px;
            background: #111;
            border-radius: 0 0 6px 6px;
        }

        /* Gallery item para GLightbox */
        .gallery-item {
            position: relative;
        }

        /* Parallax container */
        .parallax-container {
            overflow: hidden;
        }

        /* Efeito Pelicula/Spotlight (Mouse tracking) */
        .spotlight-wrapper::after {
            content: "";
            position: absolute;
            top: var(--mouse-y, 50%);
            left: var(--mouse-y, 50%); /* corrected tracking variable */
            width: 400px;
            height: 400px;
            background: radial-gradient(circle closest-side, rgba(255,255,255,0.1), transparent);
            transform: translate(-50%, -50%);
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.5s ease;
            z-index: 10;
        }

        /* Border Left Growing Bar on Vertente Hover */
        .vertente-item {
            position: relative;
            padding-left: 24px;
            transition: all 0.4s ease;
        }
        .vertente-item::before {
            content: "";
            position: absolute;
            left: 0;
            top: 50%;
            width: 3px;
            height: 0%;
            background: linear-gradient(180deg, #0875e9 0%, #8309ee 100%);
            transform: translateY(-50%);
            transition: height 0.4s cubic-bezier(0.25, 1, 0.5, 1);
        }
        .vertente-item:hover::before {
            height: 80%;
        }

        .spotlight-wrapper:hover::after {
            opacity: 1;
        }

        /* Header Logo Styles */
        .header-logo {
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -0.02em;
            transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1);
            cursor: pointer;
            position: relative;
        }

        .header-logo:hover {
            letter-spacing: 0.05em;
            opacity: 0.8;
        }

        .scrolled .header-logo {
            font-size: 1.1rem;
            filter: brightness(1.2);
        }

        nav {
            transition: all 0.4s ease;
        }

        nav.scrolled {
            padding-top: 1rem;
            padding-bottom: 1rem;
            background: rgba(5, 15, 30, 0.85);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        /* Swiper Logos Perspective Effects */
        .swiper-slide {
            transition: transform 0.6s ease, filter 0.6s ease, opacity 0.6s ease;
            transform: scale(0.8);
            opacity: 0.25;
            filter: grayscale(100%);
        }
         /* Negative circle cursor overlay on hover for galleries */
        .custom-cursor.gallery-hover-active {
            width: 90px;
            height: 90px;
            background-color: #ffffff;
            border-color: #ffffff;
            mix-blend-mode: difference;
        }
        .custom-cursor.gallery-hover-active .custom-cursor-text {
            display: none !important;
        }

        /* Custom Cursor styling */
        .custom-cursor {
            width: 40px;
            height: 40px;
            border: 1.5px solid rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            position: fixed;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 99999;
            transform: translate(-50%, -50%);
            mix-blend-mode: difference;
            transition: width 0.3s cubic-bezier(0.22, 1, 0.36, 1), height 0.3s cubic-bezier(0.22, 1, 0.36, 1), background-color 0.3s cubic-bezier(0.22, 1, 0.36, 1);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .custom-cursor-dot {
            width: 6px;
            height: 6px;
            background-color: #ffffff;
            border-radius: 50%;
            position: fixed;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 100000;
            transform: translate(-50%, -50%);
            mix-blend-mode: difference;
        }
        .custom-cursor-text {
            color: #000000;
            font-size: 8px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            opacity: 0;
            transition: opacity 0.2s ease;
            white-space: nowrap;
        }
        
        /* Reveal Dinâmico and Easings */
        .mask-reveal {
            clip-path: inset(0 100% 0 0);
        }
        
        /* High-End Ken Burns effect */
        .ken-burns {
            transition: transform 6s cubic-bezier(0.25, 1, 0.5, 1), filter 1.5s cubic-bezier(0.25, 1, 0.5, 1) !important;
        }
        .project-item:hover .ken-burns {
            transform: scale(1.08) !important;
        }
        .project-item:hover h3 {
            font-weight: 800 !important;
            color: #ffffff !important;
        }
    </style>
    <script>
        window.BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
</head>
<body class="bg-[#050f1e] selection:bg-blue-500/30">
    <div class="custom-cursor hidden md:block"><span class="custom-cursor-text">Ver Case</span></div>
    <div class="custom-cursor-dot hidden md:block"></div>

    <!-- Header Navigation -->
    <nav class="fixed top-0 w-full z-50 bg-transparent backdrop-blur-md px-10 py-8 flex justify-between items-center transition-all duration-500" id="main-nav">
        <a href="<?php echo BASE_URL; ?>/" class="header-logo text-2xl font-black gradient-text"><?php echo htmlspecialchars($settings['company_name'] ?? 'PORTFOLIO.BSB'); ?></a>
        <div class="hidden md:flex space-x-8 text-sm font-medium">
            <a href="<?php echo BASE_URL; ?>/#hero" class="hover:text-[#0875e9] transition uppercase tracking-tighter text-[10px] font-bold">Início</a>
            <a href="<?php echo BASE_URL; ?>/#about" class="hover:text-[#0875e9] transition uppercase tracking-tighter text-[10px] font-bold">Quem Sou</a>
            <a href="<?php echo BASE_URL; ?>/#skills" class="hover:text-[#0875e9] transition uppercase tracking-tighter text-[10px] font-bold">Expertise</a>
            <a href="<?php echo BASE_URL; ?>/#projects" class="hover:text-[#0875e9] transition uppercase tracking-tighter text-[10px] font-bold">Cases</a>
            <a href="<?php echo BASE_URL; ?>/#contact" class="hover:text-[#0875e9] transition uppercase tracking-tighter text-[10px] font-bold">Contato</a>
        </div>
        
        <!-- Mobile Menu Toggle Button -->
        <button class="flex md:hidden text-white focus:outline-none cursor-pointer" id="mobile-menu-btn" aria-label="Abrir Menu">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
    </nav>

    <!-- Mobile Drawer Menu Overlay -->
    <div class="fixed inset-0 z-[100] bg-black/60 backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300" id="mobile-drawer-overlay"></div>
    
    <!-- Mobile Drawer Menu Content -->
    <div class="fixed top-0 right-0 w-[280px] h-full z-[101] bg-[#050f1e] border-l border-white/5 p-10 flex flex-col justify-between transform translate-x-full transition-transform duration-300 ease-out" id="mobile-drawer">
        <div>
            <div class="flex justify-between items-center mb-16">
                <span class="text-xs uppercase tracking-[0.2em] text-white/30 font-bold">Navegação</span>
                <button class="text-white hover:text-[#0875e9] transition cursor-pointer" id="mobile-menu-close" aria-label="Fechar Menu">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            
            <div class="flex flex-col space-y-8 text-lg font-bold">
                <a href="<?php echo BASE_URL; ?>/#hero" class="mobile-nav-link text-white hover:text-[#0875e9] transition tracking-tighter">Início</a>
                <a href="<?php echo BASE_URL; ?>/#about" class="mobile-nav-link text-white hover:text-[#0875e9] transition tracking-tighter">Quem Sou</a>
                <a href="<?php echo BASE_URL; ?>/#skills" class="mobile-nav-link text-white hover:text-[#0875e9] transition tracking-tighter">Expertise</a>
                <a href="<?php echo BASE_URL; ?>/#projects" class="mobile-nav-link text-white hover:text-[#0875e9] transition tracking-tighter">Cases</a>
                <a href="<?php echo BASE_URL; ?>/#contact" class="mobile-nav-link text-white hover:text-[#0875e9] transition tracking-tighter">Contato</a>
            </div>
        </div>
        
        <div>
            <div class="text-[9px] uppercase tracking-widest text-white/20 font-mono mb-4">&copy; JOELTON SOUZA 2026</div>
        </div>
    </div>

    <main>
        <?php echo $content; ?>
    </main>

    <footer class="py-20 border-t border-white/5 bg-[#050f1e]">
        <div class="max-w-6xl mx-auto px-10 text-center">
            <div class="mb-12 flex justify-center">
                <a href="<?php echo BASE_URL; ?>/" class="header-logo text-3xl font-black gradient-text"><?php echo htmlspecialchars($settings['company_name'] ?? 'PORTFOLIO.BSB'); ?></a>
            </div>
            <div class="text-xs text-white/30 tracking-widest font-medium">&copy; 2026. Todos os direitos reservados para Joelton de Oliveira Especialista em Design, Marketing e Performance Web.</div>
        </div>
    </footer>

    <script src="<?php echo BASE_URL; ?>/assets/js/main.js"></script>
    <script>
        lucide.createIcons();
        
        // Task 3 — Inicializar GLightbox
        if (typeof GLightbox !== 'undefined') {
            const lightbox = GLightbox({
                selector: '.glightbox',
                touchNavigation: true,
                loop: true,
                autoplayVideos: true,
                openEffect: 'fade',
                closeEffect: 'fade',
                slideEffect: 'slide',
                moreLength: 0,
                skin: 'clean',
                plyr: {
                    css: 'https://cdn.plyr.io/3.6.8/plyr.css',
                    js: 'https://cdn.plyr.io/3.6.8/plyr.js'
                }
            });
        }

        // Scroll Detector
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('main-nav');
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });

        // Mobile Menu Interactivity
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenuClose = document.getElementById('mobile-menu-close');
        const mobileDrawer = document.getElementById('mobile-drawer');
        const mobileOverlay = document.getElementById('mobile-drawer-overlay');
        const mobileLinks = document.querySelectorAll('.mobile-nav-link');

        function openMobileMenu() {
            mobileDrawer.classList.remove('translate-x-full');
            mobileOverlay.classList.remove('opacity-0', 'pointer-events-none');
            mobileOverlay.classList.add('opacity-100', 'pointer-events-auto');
            document.body.style.overflow = 'hidden'; // block page scroll
        }

        function closeMobileMenu() {
            mobileDrawer.classList.add('translate-x-full');
            mobileOverlay.classList.remove('opacity-100', 'pointer-events-auto');
            mobileOverlay.classList.add('opacity-0', 'pointer-events-none');
            document.body.style.overflow = ''; // restore scroll
        }

        if (mobileMenuBtn && mobileDrawer && mobileOverlay) {
            mobileMenuBtn.addEventListener('click', openMobileMenu);
        }
        if (mobileMenuClose) {
            mobileMenuClose.addEventListener('click', closeMobileMenu);
        }
        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', closeMobileMenu);
        }
        mobileLinks.forEach(link => {
            link.addEventListener('click', closeMobileMenu);
        });

    </script>
</body>
</html>
