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
    <!-- Fonts - Outfit & Playfair Display for Premium Look -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;900&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
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
            font-family: 'Outfit', sans-serif;
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
        
        .playfair { font-family: 'Playfair Display', serif; }
        
        .editorial-title {
            font-family: 'Outfit', sans-serif;
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
            font-family: 'Outfit', monospace;
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
            left: var(--mouse-x, 50%);
            width: 400px;
            height: 400px;
            background: radial-gradient(circle closest-side, rgba(255,255,255,0.1), transparent);
            transform: translate(-50%, -50%);
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.5s ease;
            z-index: 10;
        }

        .spotlight-wrapper:hover::after {
            opacity: 1;
        }

        /* Header Logo Styles */
        .header-logo {
            font-family: 'Outfit', sans-serif;
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

    </style>
    <script>
        window.BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
</head>
<body class="bg-[#050f1e] selection:bg-blue-500/30">

    <!-- Header Navigation -->
    <nav class="fixed top-0 w-full z-50 bg-transparent backdrop-blur-md px-10 py-8 flex justify-between items-center transition-all duration-500" id="main-nav">
        <a href="<?php echo BASE_URL; ?>/" class="header-logo text-2xl font-black gradient-text"><?php echo htmlspecialchars($settings['company_name'] ?? 'PORTFOLIO.BSB'); ?></a>
        <div class="hidden md:flex space-x-8 text-sm font-medium">
            <a href="<?php echo BASE_URL; ?>/#hero" class="hover:text-[#0875e9] transition uppercase tracking-tighter text-[10px] font-bold">Início</a>
            <a href="<?php echo BASE_URL; ?>/#skills" class="hover:text-[#0875e9] transition uppercase tracking-tighter text-[10px] font-bold">Expertise</a>
            <a href="<?php echo BASE_URL; ?>/#projects" class="hover:text-[#0875e9] transition uppercase tracking-tighter text-[10px] font-bold">Cases</a>
            <a href="<?php echo BASE_URL; ?>/#contact" class="hover:text-[#0875e9] transition uppercase tracking-tighter text-[10px] font-bold">Contato</a>
        </div>
    </nav>

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

    </script>
</body>
</html>
