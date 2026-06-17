<?php
if (!defined('BASE_URL')) exit;

// Task 3 — Helper de otimização de imagens (WebP + lazy loading)
require_once __DIR__ . '/api/image_helper.php';
?>
<!-- Hero Section -->
<section id="hero" class="relative pt-40 pb-32 overflow-hidden min-h-screen flex flex-col justify-center">
    <!-- Abstract Background Ambient Light -->
    <div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-blue-600/10 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] bg-green-600/5 blur-[100px] rounded-full pointer-events-none"></div>

    <!-- Main Content -->
    <div class="relative z-10 w-full max-w-7xl mx-auto px-10 text-center">
        <span class="hero-tag text-[10px] font-bold uppercase tracking-[0.5em] text-white/30 block mb-8">MARKETING • DESIGN GRÁFICO • WEB TECH</span>
        <h1 class="hero-title editorial-title text-[9vw] lg:text-[7vw] leading-[0.9] text-white mb-10 tracking-tighter">
            ESTRATÉGIA, <br> <i class="playfair italic font-normal">DESIGN & TECH.</i>
        </h1>
        <p class="hero-p text-sm lg:text-base text-white/40 max-w-2xl mx-auto font-medium leading-relaxed mb-16">
            Formado em Marketing com sólida experiência em Design Gráfico Editorial e Desenvolvimento Full-Stack. Alinho inteligência estratégica de mercado a identidades visuais de alto impacto e tecnologia sob medida para posicionar marcas e órgãos públicos com autoridade.
        </p>
        <div class="hero-btns flex flex-col sm:flex-row items-center justify-center gap-8 mb-16">
            <a href="#projects" class="group relative px-12 py-6 bg-white text-black text-[10px] font-black uppercase tracking-[0.3em] overflow-hidden transition-all duration-300 hover:pr-14 hover:shadow-[0_0_30px_rgba(255,255,255,0.3)]">
                Ver Cases Selecionados
                <span class="absolute right-6 opacity-0 group-hover:opacity-100 transition-all duration-300">→</span>
            </a>
            <a href="#contact" class="px-12 py-6 border border-white/10 text-white text-[10px] font-black uppercase tracking-[0.3em] hover:bg-white/5 transition-all">Começar Projeto</a>
        </div>
        <span class="text-[10px] font-black uppercase tracking-[0.4em] text-white/20 block">
            DO CONCEITO AO CÓDIGO: MARKETING, DESIGN & DESENVOLVIMENTO WEB.
        </span>
    </div>
</section>

<!-- Clients Section (Carousel adjusted for new style) -->
<section class="py-24 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-10">
        <style>
            .clients-swiper .swiper-slide {
                transition: all 0.8s cubic-bezier(0.25, 1, 0.5, 1);
                opacity: 0.35; /* Logos apagadas aparecem mais */
                transform: scale(0.8);
                filter: grayscale(1);
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            /* Em Swiper com centeredSlides: true, o slide central na tela (3ª coluna) é sempre o swiper-slide-active */
            .clients-swiper .swiper-slide-active {
                opacity: 1.0 !important;
                transform: scale(1.35) !important; /* Terceira coluna (centro real) maior */
                filter: grayscale(0) !important;
                z-index: 10;
            }
            
            /* Slides imediatamente adjacentes (coluna 2 e coluna 4) */
            .clients-swiper .swiper-slide-prev,
            .clients-swiper .swiper-slide-next {
                opacity: 0.55 !important;
                transform: scale(0.95) !important;
                filter: grayscale(0.8) !important;
            }

            /* Slides das extremidades (coluna 1 e coluna 5) */
            .clients-swiper .swiper-slide-prev-prev, /* classe conceitual caso necessária, ou fallback padrão */
            .clients-swiper .swiper-slide-next + .swiper-slide {
                opacity: 0.35 !important;
                transform: scale(0.8) !important;
                filter: grayscale(1) !important;
            }
            .filter-btn.active {
                border-color: rgba(139, 92, 246, 0.5) !important;
                color: #ffffff !important;
                background-color: rgba(255, 255, 255, 0.05);
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

<!-- Quem Sou (Perfil Estratégico) Section -->
<section id="about" class="py-32 px-10 border-t border-white/5 bg-[#080808] relative overflow-hidden">
    <!-- Glowing light in background -->
    <div class="absolute top-1/3 right-1/4 w-[400px] h-[400px] bg-purple-600/5 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="absolute bottom-1/4 left-1/4 w-[300px] h-[300px] bg-blue-600/5 blur-[100px] rounded-full pointer-events-none"></div>
    
    <div class="max-w-7xl mx-auto relative z-10">
        <div class="mb-20">
            <span class="text-[10px] font-bold uppercase tracking-[0.5em] text-white/30 block mb-6 scroll-parallax-x" data-speed="-15">Quem Sou</span>
            <h2 class="text-6xl font-bold tracking-tighter text-white scroll-parallax-x" data-speed="15">Perfil <i class="playfair italic font-normal">Multidisciplinar.</i></h2>
        </div>
        
        <div class="grid lg:grid-cols-12 gap-16 items-center">
            <!-- Coluna Esquerda: Apresentação e Destaque -->
            <div class="lg:col-span-4 space-y-8">
                <h3 class="text-3xl font-bold text-white leading-tight tracking-tight">
                    A convergência entre <span class="gradient-text">Mercado, Arte</span> e <span class="gradient-text">Engenharia.</span>
                </h3>
                <p class="text-white/60 leading-relaxed text-lg font-medium">
                    Acredito que design sem propósito comercial é apenas decoração, e tecnologia sem uma identidade forte e estrutura de vendas é invisível. Unifico estratégia de marketing digital, design gráfico editorial e desenvolvimento full-stack para criar produtos digitais que geram autoridade e trazem resultados reais.
                </p>
                <div class="flex flex-wrap gap-3 pt-4">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#0875e9] bg-[#0875e9]/10 border border-[#0875e9]/20 px-4 py-2 rounded-full">Bacharel em Marketing</span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-purple-400 bg-purple-400/10 border border-purple-400/20 px-4 py-2 rounded-full">Designer Gráfico Editorial</span>
                </div>
            </div>
            
            <!-- Coluna Central: Imagem Parallax -->
            <div class="lg:col-span-4 flex justify-center py-10">
                <div class="relative w-full max-w-[280px] aspect-[2/3] overflow-hidden rounded-2xl border border-white/10 parallax-container shadow-2xl">
                    <img src="<?php echo BASE_URL; ?>/images/about_parallax.png" class="absolute inset-0 w-full h-[130%] object-cover about-parallax-img" alt="Joelton Souza - Perfil Estratégico">
                </div>
            </div>
            
            <!-- Coluna Direita: As Três Vertentes -->
            <div class="lg:col-span-4 space-y-12">
                <!-- Pilar 1 -->
                <div class="vertente-item group">
                    <div>
                        <h4 class="text-xl font-bold text-white mb-2 group-hover:text-[#0875e9] transition duration-300">Marketing Estratégico</h4>
                        <p class="text-white/40 leading-relaxed text-sm">
                            Com formação acadêmica em Marketing, atuo no posicionamento inteligente de marcas. Domino estratégias de funil de vendas, jornada de compra do cliente, SEO/SEM, marketing de conteúdo e otimização de conversão (CRO) para impulsionar negócios e canais digitais.
                        </p>
                    </div>
                </div>

                <!-- Pilar 2 -->
                <div class="vertente-item group">
                    <div>
                        <h4 class="text-xl font-bold text-white mb-2 group-hover:text-purple-400 transition duration-300">Design Gráfico & Comunicação Editorial</h4>
                        <p class="text-white/40 leading-relaxed text-sm">
                            Vasta experiência no desenvolvimento de projetos gráficos complexos e de alto padrão visual. Especialista na diagramação de livros, cartilhas técnicas e relatórios corporativos para órgãos federais e conselhos nacionais (como Marinha, MCTI, CNMP, MPT e CFM).
                        </p>
                    </div>
                </div>

                <!-- Pilar 3 -->
                <div class="vertente-item group">
                    <div>
                        <h4 class="text-xl font-bold text-white mb-2 group-hover:text-green-400 transition duration-300">Desenvolvimento Web & Automação</h4>
                        <p class="text-white/40 leading-relaxed text-sm">
                            Engenharia full-stack robusta para transformar estratégias de marketing em aplicações reais. Criação de sites institucionais otimizados, landing pages de alta conversão, e fluxos de automação de processos via n8n integrados com CRMs e bases de dados.
                        </p>
                    </div>
                </div>
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
            'Marketing Estratégico' => [
                ['name' => 'Posicionamento de Marca & Branding', 'level' => 100, 'lucide' => 'award', 'color' => '#FF5733'],
                ['name' => 'Canais Digitais & Growth', 'level' => 95, 'lucide' => 'trending-up', 'color' => '#8309ee'],
                ['name' => 'CRO (Conversão) & SEO', 'level' => 90, 'lucide' => 'target', 'color' => '#0875e9'],
                ['name' => 'RD Station & Inbound', 'level' => 90, 'svg' => 'RD_Station.svg', 'color' => '#3A2374'],
                ['name' => 'Mailchimp & Email Marketing', 'level' => 100, 'svg' => 'mailchimp.svg', 'color' => '#FFE01B'],
                ['name' => 'Power BI & Analytics', 'level' => 85, 'svg' => 'power-bi.svg', 'color' => '#F2C811'],
            ],
            'Design Editorial & Gráfico' => [
                ['name' => 'Adobe InDesign (Editorial)', 'level' => 100, 'svg' => 'indesign.svg', 'color' => '#FF3366'],
                ['name' => 'Adobe Photoshop', 'level' => 100, 'svg' => 'photoshop.svg', 'color' => '#31A8FF'],
                ['name' => 'Adobe Illustrator', 'level' => 100, 'svg' => 'ilustrador.svg', 'color' => '#FF9A00'],
                ['name' => 'Adobe Premiere', 'level' => 85, 'svg' => 'premiere-pro.svg', 'color' => '#9999FF'],
                ['name' => 'Adobe After Effects', 'level' => 80, 'svg' => 'after-effects.svg', 'color' => '#9999FF'],
                ['name' => 'Canva Pro', 'level' => 100, 'svg' => 'canva.svg', 'color' => '#00C4CC'],
                ['name' => 'Apresentações & Powerpoint', 'level' => 100, 'svg' => 'power-point.svg', 'color' => '#B7472A'],
            ],
            'Engenharia Web & Full-Stack' => [
                ['name' => 'PHP & Arquitetura MVC', 'level' => 90, 'svg' => 'php.svg', 'color' => '#777BB4'],
                ['name' => 'Python & Web Scraping', 'level' => 85, 'svg' => 'python.svg', 'color' => '#3776AB'],
                ['name' => 'Node.js & APIs RESTful', 'level' => 80, 'svg' => 'node.svg', 'color' => '#339933'],
                ['name' => 'Tailwind CSS & Astro', 'level' => 95, 'svg' => 'tailwind.svg', 'color' => '#06B6D4'],
                ['name' => 'Bancos de Dados (MySQL / Postgres)', 'level' => 90, 'svg' => 'mysql.svg', 'color' => '#4479A1'],
                ['name' => 'Docker & Ambientes Virtuais', 'level' => 75, 'svg' => 'docker.svg', 'color' => '#2496ED'],
            ],
            'Automações & Integrações' => [
                ['name' => 'n8n Workflow Automation', 'level' => 90, 'svg' => 'n8n.svg', 'color' => '#FF6E6E'],
                ['name' => 'WordPress Custom Development', 'level' => 100, 'svg' => 'wordpress.svg', 'color' => '#21759B'],
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
                                <?php elseif(isset($skill['lucide'])): ?>
                                    <div class="w-6 h-6 flex items-center justify-center bg-white/5 rounded p-1 opacity-60 group-hover:opacity-100 transition-all duration-300 group-hover:scale-110" style="transition: all 0.3s ease;">
                                        <i data-lucide="<?php echo $skill['lucide']; ?>" class="w-3.5 h-3.5" style="color: <?php echo $skill['color']; ?>;"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="text-sm font-semibold text-white/80 group-hover:text-white transition-colors duration-300"><?php echo $skill['name']; ?></span>
                            </div>
                            <span class="text-xs font-bold text-white/40 group-hover:text-white transition-colors duration-300 font-mono"><?php echo $skill['level']; ?>%</span>
                        </div>
                        <div class="relative h-1 bg-white/5 overflow-hidden rounded-full skill-progress-container">
                            <div class="h-full skill-progress-bar transition-all duration-500" data-level="<?php echo $skill['level']; ?>" style="width: 0%; background-color: <?php echo $skill['color']; ?>; box-shadow: 0 0 10px <?php echo $skill['color']; ?>80;"></div>
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
                <div class="text-4xl font-black text-white mb-2">MKT</div>
                <span class="text-[10px] uppercase font-bold text-white/40 tracking-widest">Formação Acadêmica</span>
            </div>
            <div class="text-center">
                <div class="text-4xl font-black text-white mb-2">5+</div>
                <span class="text-[10px] uppercase font-bold text-white/40 tracking-widest">Softwares Adobe</span>
            </div>
            <div class="text-center">
                <div class="text-4xl font-black text-white mb-2">6+</div>
                <span class="text-[10px] uppercase font-bold text-white/40 tracking-widest">Tecnologias Web</span>
            </div>
            <div class="text-center">
                <div class="text-4xl font-black text-white mb-2">100%</div>
                <span class="text-[10px] uppercase font-bold text-white/40 tracking-widest">Processo Integrado</span>
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
            <div class="flex flex-wrap gap-3 mt-12 md:mt-0 md:justify-end max-w-xl">
                <button class="px-5 py-2.5 rounded-full border border-white/10 text-[10px] font-bold uppercase tracking-widest text-white/40 hover:text-white filter-btn active transition-all duration-300" data-filter="all">Tudo</button>
                <?php foreach($categories as $category): ?>
                    <button class="px-5 py-2.5 rounded-full border border-white/10 text-[10px] font-bold uppercase tracking-widest text-white/40 hover:text-white filter-btn transition-all duration-300" data-filter="<?php echo htmlspecialchars($category['slug']); ?>">
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
                    $imgSrc = getOptimizedImageUrl($rawSrc);
                    // Task 5 — Alt text automático
                    $imgAlt = htmlspecialchars($project['title']) . ' - Trabalho de ' . htmlspecialchars($project['category_names'] ?? 'Design') . ' por Joelton Souza';
                    ?>
                    <img 
                        src="<?php echo $imgSrc; ?>" 
                        class="w-full h-full object-cover ken-burns" 
                        alt="<?php echo $imgAlt; ?>"
                        loading="lazy"
                        decoding="async"
                    >
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center">
                        <span class="ver-case-btn px-8 py-4 bg-white text-black text-[10px] font-black uppercase tracking-widest flex items-center justify-center">
                            <span class="ver-case-label block">Ver Case</span>
                        </span>
                    </div>
                </div>
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-white/30 mb-2 block"><?php echo htmlspecialchars($project['category_names'] ?? ''); ?></span>
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
