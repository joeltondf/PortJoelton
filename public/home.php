<?php
// Task 3 — Helper de otimização de imagens (WebP + lazy loading)
require_once __DIR__ . '/api/image_helper.php';
?>
<!-- Hero Section -->
<section id="hero" class="relative pt-40 pb-32 overflow-hidden min-h-screen flex flex-col justify-center">
    <!-- Abstract Background Ambient Light -->
    <div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-blue-600/10 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] bg-green-600/5 blur-[100px] rounded-full pointer-events-none"></div>

    <!-- Main Content -->
    <div class="relative z-10 w-full max-w-7xl mx-auto px-10 text-center mb-20">
        <span class="hero-tag text-[10px] font-bold uppercase tracking-[0.5em] text-white/30 block mb-8">Designer & Developer Full-Stack</span>
        <h1 class="hero-title editorial-title text-[9vw] lg:text-[7vw] leading-[0.9] text-white mb-10 tracking-tighter">
            ESTRATÉGIA <br> <i class="playfair italic font-normal">VISUAL.</i>
        </h1>
        <p class="hero-p text-sm lg:text-base text-white/40 max-w-xl mx-auto font-medium leading-relaxed mb-12">
            Fusão entre design de alta fidelidade e arquitetura de software escalável para marcas que buscam autoridade digital.
        </p>
        <div class="hero-btns flex flex-col sm:flex-row items-center justify-center gap-8">
            <a href="#projects" class="group px-8 py-4 bg-white text-black text-[10px] font-black uppercase tracking-widest hover:bg-white/90 transition-all flex items-center gap-4">
                Ver Cases Selecionados <span class="group-hover:translate-x-1 transition-transform">→</span>
            </a>
            <a href="#contact" class="px-8 py-4 border border-white/10 text-white text-[10px] font-black uppercase tracking-widest hover:bg-white/5 transition-all">Começar Projeto</a>
        </div>
    </div>

    <!-- Dual Visual Mockups (Vector HTML/CSS) -->
    <div class="relative z-10 w-full max-w-5xl mx-auto px-10 hero-visuals">
        <style>
            @keyframes float { 0%, 100% { transform: translateY(0) rotate(3deg); } 50% { transform: translateY(-10px) rotate(1deg); } }
            @keyframes pulse-soft { 0%, 100% { opacity: 0.15; } 50% { opacity: 0.4; } }
            @keyframes scanline { 0% { transform: translateY(-100%); } 100% { transform: translateY(800%); } }
            @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0; } }
            .animate-float { animation: float 6s ease-in-out infinite; }
            .animate-pulse-slow { animation: pulse-soft 4s ease-in-out infinite; }
            .scanline { animation: scanline 8s linear infinite; }
            .cursor-blink { animation: blink 1s step-end infinite; }
            .mockup-node { transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1); }
            .hero-visuals { transform-style: preserve-3d; will-change: transform; }
        </style>

        <div class="grid md:grid-cols-2 gap-8 lg:gap-12 items-center">
            <!-- Left Side: Design Process (Refined Vector) -->
            <div class="group relative">
                <div class="absolute -inset-2 bg-blue-500/10 blur-[80px] opacity-20 group-hover:opacity-40 transition duration-1000"></div>
                <div class="relative glass-card overflow-hidden rounded-2xl border border-white/5 h-[380px] bg-[#080808] flex">
                    <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_20%_20%,rgba(59,130,246,0.05)_0%,transparent_50%)]"></div>
                    <div class="absolute bottom-0 right-0 w-full h-full bg-[radial-gradient(circle_at_80%_80%,rgba(59,130,246,0.03)_0%,transparent_50%)]"></div>
                    <div class="w-12 border-r border-white/5 bg-[#141414] p-2 flex flex-col space-y-3 hidden sm:flex z-10">
                        <div class="w-full aspect-square bg-blue-500/20 rounded-sm"></div>
                        <div class="w-full h-0.5 bg-white/5 rounded"></div>
                        <div class="grid grid-cols-2 gap-1">
                            <div class="h-4 bg-white/5 rounded-sm"></div>
                            <div class="h-4 bg-white/5 rounded-sm"></div>
                        </div>
                        <div class="flex-1"></div>
                        <div class="w-full aspect-square bg-white/5 rounded-full"></div>
                    </div>
                    <div class="flex-1 flex flex-col relative overflow-hidden">
                        <div class="bg-[#141414] px-4 py-2 border-b border-white/5 flex items-center justify-between z-10">
                            <div class="flex items-center space-x-2">
                                <span class="bg-[#A259FF] text-white text-[6px] font-black px-1.5 py-0.5 rounded">F</span>
                                <span class="text-[8px] text-white/30 uppercase tracking-[0.2em] font-bold">PORTFOLIO_SYSTEM</span>
                            </div>
                            <div class="flex -space-x-1">
                                <div class="w-3 h-3 rounded-full border border-black bg-blue-400"></div>
                                <div class="w-3 h-3 rounded-full border border-black bg-pink-400"></div>
                            </div>
                        </div>
                        <div class="flex-1 relative bg-[#0e0e0e] overflow-hidden">
                            <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image: radial-gradient(white 0.5px, transparent 0.5px); background-size: 15px 15px;"></div>
                            <div class="p-8 h-full flex flex-col">
                                <div class="w-full h-10 border border-blue-500/10 rounded bg-blue-500/[0.02] p-3 flex items-center justify-between mockup-node group-hover:border-blue-500/30 transition-all">
                                    <div class="w-20 h-1 bg-blue-500/20 rounded"></div>
                                    <div class="flex space-x-1.5">
                                        <div class="w-4 h-4 rounded-sm bg-white/5"></div>
                                        <div class="w-4 h-4 rounded-sm bg-white/10"></div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-3 flex-1 my-6">
                                    <div class="col-span-2 border border-white/5 bg-white/[0.02] rounded-lg p-3 overflow-hidden relative group-hover:bg-white/[0.04] transition">
                                        <div class="w-full h-full bg-gradient-to-br from-blue-500/[0.05] to-transparent"></div>
                                        <div class="absolute top-0 left-0 w-full h-[1px] bg-blue-500/20 scanline"></div>
                                    </div>
                                    <div class="flex flex-col gap-3">
                                        <div class="h-1/3 bg-white/5 rounded-lg border border-white/5"></div>
                                        <div class="flex-1 bg-blue-500/10 rounded-lg border border-blue-500/10"></div>
                                    </div>
                                </div>
                                <div class="flex space-x-2 items-center">
                                    <div class="w-6 h-6 rounded bg-[#0875e9]"></div>
                                    <div class="w-6 h-6 rounded bg-white/5"></div>
                                    <div class="flex-1 h-[1px] bg-white/5 ml-2"></div>
                                </div>
                            </div>
                            <div class="absolute top-1/3 left-1/2 flex flex-col items-start pointer-events-none transition-all duration-700 group-hover:translate-x-6 group-hover:-translate-y-2">
                                <svg width="10" height="10" viewBox="0 0 12 12" fill="none" class="text-blue-500 drop-shadow-lg">
                                    <path d="M0 0L12 3.5L4.5 5.5L2.5 12L0 0Z" fill="currentColor"/>
                                </svg>
                                <span class="bg-blue-500 text-white text-[6px] font-bold px-1.5 py-0.5 rounded shadow-lg">Joelton</span>
                            </div>
                            <div class="absolute bottom-4 right-4 w-24 bg-[#141414] border border-white/10 rounded-lg p-2.5 shadow-2xl animate-float pointer-events-none opacity-80">
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-2"><div class="w-1.5 h-1.5 rounded bg-purple-500"></div><div class="h-0.5 flex-1 bg-white/10 rounded"></div></div>
                                    <div class="flex items-center space-x-2"><div class="w-1.5 h-1.5 rounded bg-blue-500"></div><div class="h-0.5 flex-1 bg-white/10 rounded"></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Code Editor (Refined Vector) -->
            <div class="group relative">
                <div class="absolute -inset-2 bg-green-500/10 blur-[80px] opacity-10 group-hover:opacity-30 transition duration-1000"></div>
                <div class="relative glass-card overflow-hidden rounded-2xl border border-white/5 h-[380px] bg-[#0a0d12] flex flex-col font-mono">
                    <div class="bg-[#11161d] px-5 py-3 border-b border-white/5 flex items-center space-x-4">
                        <div class="flex space-x-1.5">
                            <div class="w-2 h-2 rounded-full bg-[#ff5f56]"></div>
                            <div class="w-2 h-2 rounded-full bg-[#ffbd2e]"></div>
                            <div class="w-2 h-2 rounded-full bg-[#27c93f]"></div>
                        </div>
                        <span class="text-[8px] text-white/20 uppercase tracking-[0.2em]">Logic.php</span>
                    </div>
                    <div class="flex-1 flex overflow-hidden">
                        <div class="w-10 bg-[#0a0d12] border-r border-white/5 flex flex-col items-center py-6 space-y-1.5 text-[7px] text-white/10">
                            <?php for($i=1; $i<=10; $i++): ?><span><?php echo $i; ?></span><?php endfor; ?>
                        </div>
                        <div class="flex-1 p-8 text-[10px] leading-5 relative">
                            <div class="text-[#8b949e] italic mb-4 opacity-40">/* Project Autoria */</div>
                            <div class="space-y-1 pointer-events-none">
                                <div><span class="text-[#ff7b72]">class</span> <span class="text-[#d2a8ff]">Portfolio</span> {</div>
                                <div class="pl-5"><span class="text-[#ff7b72]">public</span> <span class="text-[#d2a8ff]">function</span> <span class="text-[#d2a8ff]">optimize</span>() {</div>
                                <div class="pl-10"><span class="text-[#ff7b72]">return</span> [</div>
                                <div class="pl-15 text-[#a5d6ff]">'speed' => '99%',</div>
                                <div class="pl-15 text-[#a5d6ff]">'style' => 'premium'</div>
                                <div class="pl-10">];</div>
                                <div class="pl-5">}</div>
                                <div>} <span class="w-1 h-3.5 bg-green-500 inline-block align-middle cursor-blink ml-1"></span></div>
                            </div>
                            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-40 h-40 bg-green-500/5 blur-[50px] pointer-events-none rounded-full animate-pulse-slow"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-14 text-center">
            <span class="text-[10px] font-black uppercase tracking-[0.4em] text-white/20 block mb-12">
                DO CONCEITO AO CÓDIGO: DESIGN & DESENVOLVIMENTO FULL-STACK.
            </span>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-8">
                <a href="#projects" class="group relative px-12 py-6 bg-white text-black text-[10px] font-black uppercase tracking-[0.3em] overflow-hidden transition-all duration-300 hover:pr-14 hover:shadow-[0_0_30px_rgba(255,255,255,0.3)]">
                    Ver Portfólio
                    <span class="absolute right-6 opacity-0 group-hover:opacity-100 transition-all duration-300">→</span>
                </a>
                <a href="#contact" class="text-[10px] font-bold uppercase tracking-[0.3em] text-white/40 hover:text-white transition group flex items-center gap-4">
                    Entre em contato
                    <span class="w-8 h-[1px] bg-white/20 group-hover:w-12 transition-all duration-500"></span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Clients Section (Carousel adjusted for new style) -->
<section class="py-24 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-10">
        <style>
            .clients-swiper .swiper-slide {
                transition: all 0.8s cubic-bezier(0.22, 1, 0.36, 1);
                opacity: 0.15;
                transform: scale(0.7) translateZ(-100px);
                filter: grayscale(1);
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .clients-swiper .swiper-slide-active {
                opacity: 1 !important;
                transform: scale(1.25) translateZ(0) !important;
                filter: grayscale(0) !important;
                z-index: 10;
            }
        </style>
        <div class="swiper clients-swiper py-20">
            <div class="swiper-wrapper flex items-center">
                <?php 
                $clients = [
                    ['src' => 'Logo-CFM.png', 'alt' => 'CFM'],
                    ['src' => 'Logo-CNMP.png', 'alt' => 'CNMP'],
                    ['src' => 'Logo-CONFEA.png', 'alt' => 'CONFEA'],
                    ['src' => 'Logo-DETRAN.png', 'alt' => 'DETRAN'],
                    ['src' => 'Logo-MPT.png', 'alt' => 'MPT'],
                    ['src' => 'Logo-Ministerio-da-saude.png', 'alt' => 'MS'],
                    ['src' => 'Logo-Marinha.png', 'alt' => 'Marinha'],
                    ['src' => 'Logo-Poder-da-capital.png', 'alt' => 'Poder da Capital']
                ];
                foreach($clients as $client): 
                ?>
                <div class="swiper-slide flex items-center justify-center cursor-pointer">
                    <img src="<?php echo BASE_URL; ?>/logos_clientes/<?php echo $client['src']; ?>" 
                         class="h-10 md:h-12 object-contain grayscale opacity-30 hover:opacity-100 hover:grayscale-0 transition-all duration-700" 
                         alt="<?php echo $client['alt']; ?>">
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Expertise Section - Hardcoded Skills -->
<section id="skills" class="py-40 px-10">
    <div class="max-w-7xl mx-auto">
        <div class="mb-20">
            <span class="text-[10px] font-bold uppercase tracking-[0.5em] text-white/30 block mb-6">Expertise</span>
            <h2 class="text-6xl font-bold tracking-tighter text-white">Stack <i class="playfair italic font-normal">Completo</i></h2>
        </div>

        <?php
        $skillCategories = [
            'Design Gráfico' => [
                ['name' => 'Adobe Photoshop', 'level' => 100, 'svg' => 'photoshop.svg', 'color' => '#31A8FF'],
                ['name' => 'Adobe Illustrator', 'level' => 100, 'svg' => 'ilustrador.svg', 'color' => '#FF9A00'],
                ['name' => 'Adobe InDesign', 'level' => 100, 'svg' => 'indesign.svg', 'color' => '#FF3366'],
                ['name' => 'Adobe After Effects', 'level' => 70, 'svg' => 'after-effects.svg', 'color' => '#9999FF'],
                ['name' => 'Adobe Premiere', 'level' => 85, 'svg' => 'premiere-pro.svg', 'color' => '#9999FF'],
                ['name' => 'Canva', 'level' => 100, 'svg' => 'canva.svg', 'color' => '#00C4CC'],
                ['name' => 'PowerPoint', 'level' => 100, 'svg' => 'power-point.svg', 'color' => '#B7472A'],
            ],
            'Development' => [
                ['name' => 'PHP', 'level' => 90, 'svg' => 'php.svg', 'color' => '#777BB4'],
                ['name' => 'Python', 'level' => 80, 'svg' => 'python.svg', 'color' => '#3776AB'],
                ['name' => 'Node.js', 'level' => 80, 'svg' => 'node.svg', 'color' => '#339933'],
                ['name' => 'Astro', 'level' => 60, 'svg' => 'astro.svg', 'color' => '#FF5A03'],
                ['name' => 'Docker', 'level' => 70, 'svg' => 'docker.svg', 'color' => '#2496ED'],
                ['name' => 'Bootstrap', 'level' => 70, 'svg' => 'bootstrap.svg', 'color' => '#7952B3'],
                ['name' => 'Tailwind CSS', 'level' => 95, 'svg' => 'tailwind.svg', 'color' => '#06B6D4'],
            ],
            'Databases' => [
                ['name' => 'MySQL', 'level' => 90, 'svg' => 'mysql.svg', 'color' => '#4479A1'],
                ['name' => 'PostgreSQL', 'level' => 80, 'svg' => 'postgresql.svg', 'color' => '#4169E1'],
            ],
            'Automation & Marketing' => [
                ['name' => 'n8n', 'level' => 70, 'svg' => 'n8n.svg', 'color' => '#FF6E6E'],
                ['name' => 'WordPress', 'level' => 100, 'svg' => 'wordpress.svg', 'color' => '#21759B'],
                ['name' => 'Mailchimp', 'level' => 100, 'svg' => 'mailchimp.svg', 'color' => '#FFE01B'],
                ['name' => 'RD Station', 'level' => 80, 'svg' => 'RD_Station.svg', 'color' => '#3A2374'],
                ['name' => 'Power BI', 'level' => 75, 'svg' => 'power-bi.svg', 'color' => '#F2C811'],
            ]
        ];
        ?>

        <div class="grid lg:grid-cols-2 gap-16">
            <?php foreach($skillCategories as $category => $skills): ?>
            <div class="space-y-8">
                <div class="border-l-2 border-white/20 pl-6">
                    <h3 class="text-2xl font-bold text-white mb-1"><?php echo $category; ?></h3>
                    <div class="h-[1px] w-12 bg-gradient-to-r from-white/40 to-transparent mt-3"></div>
                </div>
                
                <div class="space-y-6">
                    <?php foreach($skills as $skill): ?>
                    <div class="group transition-all duration-300 hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <?php if(isset($skill['svg'])): ?>
                                    <div class="w-6 h-6 flex items-center justify-center bg-white/5 rounded p-1 opacity-60 group-hover:opacity-100 transition-all duration-300 group-hover:scale-110" style="transition: all 0.3s ease;">
                                        <img src="<?php echo BASE_URL; ?>/images/icones svg/<?php echo $skill['svg']; ?>" alt="<?php echo $skill['name']; ?>" class="w-full h-full object-contain filter drop-shadow hover:drop-shadow-lg">
                                    </div>
                                <?php endif; ?>
                                <span class="text-sm font-semibold text-white/80 group-hover:text-white transition-colors duration-300"><?php echo $skill['name']; ?></span>
                            </div>
                            <span class="text-xs font-bold text-white/40 group-hover:text-white transition-colors duration-300 font-mono"><?php echo $skill['level']; ?>%</span>
                        </div>
                        <div class="relative h-1 bg-white/5 overflow-hidden rounded-full">
                            <div class="h-full bg-white/20 transition-all duration-700 ease-out group-hover:opacity-0" style="width: <?php echo $skill['level']; ?>%;"></div>
                            <div class="absolute top-0 left-0 h-full opacity-0 group-hover:opacity-100 transition-all duration-500 ease-out" style="width: <?php echo $skill['level']; ?>%; background-color: <?php echo $skill['color']; ?>; box-shadow: 0 0 10px <?php echo $skill['color']; ?>80;"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Stats Summary -->
        <div class="mt-20 pt-20 border-t border-white/10 grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-4xl font-black text-white mb-2">7</div>
                <span class="text-[10px] uppercase font-bold text-white/40 tracking-widest">Softwares Adobe</span>
            </div>
            <div class="text-center">
                <div class="text-4xl font-black text-white mb-2">7</div>
                <span class="text-[10px] uppercase font-bold text-white/40 tracking-widest">Linguagens/Frameworks</span>
            </div>
            <div class="text-center">
                <div class="text-4xl font-black text-white mb-2">2</div>
                <span class="text-[10px] uppercase font-bold text-white/40 tracking-widest">Bancos de Dados</span>
            </div>
            <div class="text-center">
                <div class="text-4xl font-black text-white mb-2">5</div>
                <span class="text-[10px] uppercase font-bold text-white/40 tracking-widest">Plataformas Marketing</span>
            </div>
        </div>
    </div>
</section>

<!-- Projects -->
<section id="projects" class="py-40 bg-[#0a0a0a]">
    <div class="max-w-7xl mx-auto px-10">
        <div class="mb-24 flex flex-col md:flex-row justify-between items-end">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-[0.5em] text-white/30 block mb-6">Trabalhos Selecionados</span>
                <h2 class="text-7xl font-bold tracking-tighter text-white">Projetos <i class="playfair italic font-normal">Criados.</i></h2>
            </div>
            <div class="flex space-x-8 mt-12 md:mt-0">
                <button class="text-[10px] font-bold uppercase tracking-widest text-white/40 hover:text-white filter-btn active" data-filter="all">Tudo</button>
                <?php foreach($categories as $category): ?>
                    <button class="text-[10px] font-bold uppercase tracking-widest text-white/40 hover:text-white filter-btn" data-filter="<?php echo htmlspecialchars($category['slug']); ?>">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="columns-1 md:columns-2 gap-x-20" id="projects-grid">
            <?php foreach($projects as $index => $project): ?>
            <div class="project-item group cursor-pointer break-inside-avoid mb-20 relative spotlight-container" 
                 id="proj-<?php echo $project['id']; ?>" 
                 data-category='<?php echo json_encode(explode(",", $project['category_slugs'] ?? "")); ?>' 
                 onclick="window.location.href='<?php echo BASE_URL; ?>/project/<?php echo $project['slug']; ?>'">
                <div class="relative overflow-hidden mb-8 bg-[#111] spotlight-wrapper rounded-xl">
                    <?php
                    // Task 3 — WebP otimizado + lazy loading
                    $rawSrc = $project['main_image'] ?? '';
                    $isExternal = str_starts_with($rawSrc, 'http');
                    if ($isExternal) {
                        $imgSrc = $rawSrc;
                    } else {
                        $imgSrc = BASE_URL . '/' . getOptimizedImageUrl($rawSrc);
                    }
                    // Task 5 — Alt text automático
                    $imgAlt = htmlspecialchars($project['title']) . ' - Trabalho de ' . htmlspecialchars($project['category_names'] ?? 'Design') . ' por Joelton Souza';
                    ?>
                    <img 
                        src="<?php echo $imgSrc; ?>" 
                        class="w-full h-full object-cover transition duration-700 group-hover:scale-105" 
                        alt="<?php echo $imgAlt; ?>"
                        loading="lazy"
                        decoding="async"
                    >
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center">
                        <span class="px-8 py-4 bg-white text-black text-[10px] font-black uppercase tracking-widest">Ver Case</span>
                    </div>
                </div>
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-white/30 mb-2 block"><?php echo htmlspecialchars($project['category_names']); ?></span>
                        <h3 class="text-3xl font-bold text-white tracking-tighter"><?php echo htmlspecialchars($project['title']); ?></h3>
                    </div>
                    <span class="text-sm font-light text-white/20 italic playfair"><?php echo date('Y', strtotime($project['created_at'])); ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Contact -->
<section id="contact" class="py-40 bg-white text-black">
    <div class="max-w-7xl mx-auto px-10">
        <div class="grid lg:grid-cols-2 gap-24 items-center">
            <div>
                <h2 class="text-8xl font-black tracking-tighter mb-12">VAMOS <br> CRIAR.</h2>
                <p class="text-xl font-medium max-w-sm leading-relaxed mb-12">
                    Disponível para projetos selecionados que buscam excelência visual.
                </p>
                <div class="space-y-6">
                    <a href="mailto:joeltondf@gmail.com" class="block text-2xl font-bold border-b-2 border-black pb-2 hover:border-black/20 transition-colors w-fit">joeltondf@gmail.com</a>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 pt-4">
                        <span class="text-2xl font-bold">61 98104-0691</span>
                        <a href="https://wa.me/5561981040691" target="_blank" rel="noopener" class="w-fit px-6 py-3 bg-[#25D366] text-white text-xs font-bold uppercase tracking-widest hover:bg-[#128C7E] transition flex items-center gap-2">
                            <i data-lucide="message-circle" class="w-4 h-4"></i> WhatsApp
                        </a>
                    </div>
                </div>
            </div>
            <div class="bg-black p-16 text-white min-h-[500px] flex items-center justify-center">
                <form id="contact-form" class="space-y-10 w-full">
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-widest text-white/40 block mb-4">Nome</label>
                        <input type="text" name="name" required class="w-full bg-transparent border-b border-white/20 py-4 focus:border-white outline-none transition-colors">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-widest text-white/40 block mb-4">E-mail</label>
                        <input type="email" name="email" required class="w-full bg-transparent border-b border-white/20 py-4 focus:border-white outline-none transition-colors">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-widest text-white/40 block mb-4">Telefone Celular</label>
                        <input type="text" name="phone" class="w-full bg-transparent border-b border-white/20 py-4 focus:border-white outline-none transition-colors">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-widest text-white/40 block mb-4">Briefing</label>
                        <textarea name="message" rows="4" required class="w-full bg-transparent border-b border-white/20 py-4 focus:border-white outline-none transition-colors resize-none"></textarea>
                    </div>
                    <button type="submit" class="w-full py-6 bg-white text-black font-black uppercase tracking-[0.3em] text-[10px] hover:bg-gray-200 transition">Enviar Mensagem</button>
                </form>

                <div id="form-success" class="hidden text-center space-y-8 animate-in fade-in zoom-in duration-700">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto shadow-[0_0_30px_#fff]">
                        <i data-lucide="check" class="text-black w-10 h-10"></i>
                    </div>
                    <div class="space-y-4">
                        <h3 class="text-3xl font-bold tracking-tighter">MENSAGEM ENVIADA.</h3>
                        <p class="text-white/40 text-sm max-w-[280px] mx-auto font-medium">
                            Obrigado pelo contato! <br> Retornarei em até 24 horas para iniciarmos sua estratégia visual.
                        </p>
                    </div>
                    <button onclick="window.location.reload()" class="text-[10px] font-black uppercase tracking-widest text-white/40 hover:text-white transition underline underline-offset-8">Enviar outra</button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiper = new Swiper('.clients-swiper', {
            slidesPerView: 1.5,
            spaceBetween: 40,
            centeredSlides: true,
            loop: true,
            speed: 1200,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            breakpoints: {
                640: { slidesPerView: 3, spaceBetween: 60 },
                1024: { slidesPerView: 5, spaceBetween: 100 },
            },
        });
    });
</script>
