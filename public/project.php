<?php
if (!defined('BASE_URL')) exit;
/**
 * project.php — Página de Detalhe do Projeto
 * 
 * Tasks implementadas:
 *   Task 2 — MacBook frame + scroll automático para Web Design
 *   Task 3 — Lazy loading + WebP + GLightbox
 *   Task 4 — Classes GSAP para stagger
 *   Task 5 — Alt text automático + $pageTitle dinâmico
 */

// Task 3 — Carregar helper de imagens
require_once __DIR__ . '/api/image_helper.php';

// Project Slug is passed from index.php in URI cleaning
if (!isset($projectSlug) || !$projectSlug) {
    header("Location: " . BASE_URL . "/");
    exit;
}

// Fetch project with multiple categories
$stmt = $pdo->prepare("
    SELECT p.*, GROUP_CONCAT(c.name SEPARATOR ', ') as category_name, GROUP_CONCAT(c.slug SEPARATOR ', ') as category_slug 
    FROM projects p 
    LEFT JOIN project_category pc ON p.id = pc.project_id 
    LEFT JOIN categories c ON pc.category_id = c.id 
    WHERE p.slug = ?
    GROUP BY p.id
");
$stmt->execute([$projectSlug]);
$project = $stmt->fetch();

if (!$project) {
    echo "<h1>Case '$projectSlug' não encontrado.</h1>";
    echo "<p>Verifique se o slug está correto no painel Admin.</p>";
    echo "<a href='" . BASE_URL . "/'>Voltar para Home</a>";
    exit;
}

// Task 5 — Título dinâmico para layout.php
$pageTitle = $project['title'];

// Task 5 — Alt text padrão
$categoryName = $project['category_name'] ?? 'Design';
$altText = htmlspecialchars($project['title']) . ' - Trabalho de ' . htmlspecialchars($categoryName) . ' por Joelton Souza';

// Task 2 — Detectar se é categoria Web Design (para MacBook frame)
$isWebDesign = false;
$webDesignKeywords = ['web', 'digital', 'sistema', 'app', 'portal', 'site'];
$categoryLower = strtolower($categoryName);
foreach ($webDesignKeywords as $kw) {
    if (str_contains($categoryLower, $kw)) {
        $isWebDesign = true;
        break;
    }
}
// Verificar também pelo slug do projeto
$webDesignSlugs = ['poder-da-capital'];
if (in_array($project['slug'], $webDesignSlugs)) {
    $isWebDesign = true;
}

// Fetch Blocks
$stmt = $pdo->prepare("SELECT * FROM project_blocks WHERE project_id = ? ORDER BY display_order ASC");
$stmt->execute([$project['id']]);
$blocks = $stmt->fetchAll();

// Fetch traditional gallery images
$stmt = $pdo->prepare("SELECT * FROM project_images WHERE project_id = ? ORDER BY display_order ASC");
$stmt->execute([$project['id']]);
$gallery = $stmt->fetchAll();

// Tags
$tags = json_decode($project['tools_used'], true) ?: [];

ob_start();
?>
<!-- Project View — Task 4: GSAP classes adicionadas -->
<section class="min-h-screen pt-40 pb-20 px-8">
    <div class="max-w-6xl mx-auto">

        <!-- Header do Projeto -->
        <div class="mb-12 project-header">
            <a href="<?php echo BASE_URL; ?>/#projects" class="text-white/40 hover:text-white flex items-center space-x-2 text-xs uppercase font-bold tracking-widest transition mb-10">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Voltar para Portfolio</span>
            </a>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-end">
                <div>
                   <span class="gradient-text text-sm font-bold uppercase tracking-[0.3em] block mb-4 italic"><?php echo htmlspecialchars($project['category_name']); ?></span>
                   <h1 class="text-6xl md:text-8xl font-black tracking-tighter text-white leading-none"><?php echo htmlspecialchars($project['title']); ?></h1>
                </div>
                <div class="flex flex-wrap gap-3">
                    <?php foreach($tags as $tag): ?>
                        <span class="px-4 py-2 bg-white/5 border border-white/10 rounded-full text-[10px] uppercase font-bold text-white/60"><?php echo htmlspecialchars($tag); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Task 2 — Main Media (MacBook frame para Web Design / normal para outros) -->
        <?php if ($project['main_image']): ?>
            <?php $mainImgSrc = BASE_URL . '/' . getOptimizedImageUrl($project['main_image']); ?>
            
            <?php if ($isWebDesign): ?>
            <!-- MacBook Pro Frame -->
            <div class="macbook-wrapper mb-20">
                <div class="macbook-frame">
                    <!-- Topo: barra de status -->
                    <div class="macbook-bar">
                        <span class="mac-dot" style="background:#ff5f56"></span>
                        <span class="mac-dot" style="background:#ffbd2e"></span>
                        <span class="mac-dot" style="background:#27c93f"></span>
                        <div class="mac-address-bar">
                            <span><?php echo htmlspecialchars($project['external_link'] ?? 'https://' . strtolower($project['title']) . '.com.br'); ?></span>
                        </div>
                    </div>
                    <!-- Tela com scroll automático no hover -->
                    <div class="macbook-screen">
                        <div class="macbook-scroll-container <?php echo $isWebDesign ? 'auto-scroll' : ''; ?>">
                            <img 
                                src="<?php echo $mainImgSrc; ?>" 
                                class="macbook-screenshot" 
                                alt="<?php echo $altText; ?>"
                                loading="eager"
                                decoding="async"
                            >
                        </div>
                    </div>
                </div>
                <!-- Base MacBook -->
                <div class="macbook-base">
                    <div class="macbook-notch"></div>
                </div>
            </div>
            
            <?php else: ?>
            <!-- Imagem Normal com parallax -->
            <div class="glass-card mb-20 overflow-hidden relative group parallax-container">
                <img 
                    src="<?php echo $mainImgSrc; ?>" 
                    class="w-full h-auto object-cover max-h-[70vh] parallax-bg" 
                    alt="<?php echo $altText; ?>"
                    loading="eager"
                    decoding="async"
                >
                <!-- Overlay gradient sutil -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent pointer-events-none"></div>
            </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Content -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 mb-20">
            <!-- Sidebar Sticky -->
            <div class="lg:col-span-4">
                <div class="sticky top-32 space-y-12">
                    <div>
                        <span class="text-[10px] uppercase font-bold tracking-widest text-white/30 block mb-4">Resumo do Case</span>
                        <p class="text-xl font-bold text-white/80 leading-relaxed italic border-l-2 border-[#0875e9] pl-6"><?php echo nl2br(htmlspecialchars($project['summary'])); ?></p>
                    </div>
                    <?php if ($project['external_link']): ?>
                    <a href="<?php echo $project['external_link']; ?>" target="_blank" rel="noopener" class="block w-full py-6 bg-custom-gradient text-white text-center font-black uppercase tracking-widest text-[10px] shadow-2xl hover:scale-105 transition">VISUALIZAR PROJETO AO VIVO</a>
                    <?php endif; ?>
                    
                    <!-- Task 5 — Botão de contato -->
                    <a href="<?php echo BASE_URL; ?>/#contact" class="block w-full py-4 border border-white/20 text-white text-center font-bold uppercase tracking-widest text-[10px] hover:bg-white hover:text-black transition">Solicitar Projeto Similar</a>
                </div>
            </div>
            
            <!-- Content Column -->
            <div class="lg:col-span-8">
                <!-- Fallback description -->
                <?php if (empty(array_filter($blocks, fn($b) => $b['block_type'] === 'text')) && !empty($project['description'])): ?>
                <div class="prose prose-invert max-w-none mb-12">
                    <h3 class="text-3xl font-bold text-white mb-6">Execução e Estratégia</h3>
                    <div class="text-lg text-white/60 leading-relaxed space-y-6">
                        <?php echo nl2br(htmlspecialchars($project['description'])); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Dynamic Blocks -->
                <div class="space-y-16">
                    <?php foreach($blocks as $block): ?>
                        <?php if($block['block_type'] === 'text'): ?>
                            <div class="prose prose-invert max-w-none text-lg text-white/70 leading-relaxed">
                                <?php echo nl2br(htmlspecialchars($block['content'])); ?>
                            </div>
                        <?php elseif($block['block_type'] === 'image'): ?>
                            <!-- Task 3 — GLightbox em blocos de imagem | Task 4 — gallery-item -->
                            <div class="glass-card overflow-hidden group w-full cursor-pointer gallery-item glightbox-trigger" 
                                 data-glightbox="<?php echo htmlspecialchars($block['content']); ?>"
                                 data-src="<?php echo BASE_URL . '/' . getOptimizedImageUrl($block['content']); ?>"
                                 data-title="<?php echo htmlspecialchars($project['title']); ?>">
                                <img 
                                    src="<?php echo BASE_URL . '/' . getOptimizedImageUrl($block['content']); ?>" 
                                    class="w-full h-auto object-cover hover:scale-105 transition duration-1000" 
                                    alt="<?php echo $altText; ?>"
                                    loading="lazy"
                                    decoding="async"
                                >
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition flex items-center justify-center">
                                    <span class="opacity-0 group-hover:opacity-100 transition bg-white text-black text-[10px] font-black uppercase tracking-widest px-4 py-2">🔍 Ampliar</span>
                                </div>
                            </div>
                        <?php elseif($block['block_type'] === 'link'): ?>
                            <div class="p-8 border-l-4 border-[#0875e9] bg-[#020814]/50">
                                <a href="<?php echo htmlspecialchars($block['content']); ?>" target="_blank" rel="noopener" class="text-xl font-bold text-white hover:text-[#0875e9] transition flex items-center">
                                    Acessar Recurso Externo <i data-lucide="external-link" class="w-5 h-5 ml-3"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <!-- Task 3 + 4 — Galeria com GLightbox e classes GSAP -->
                <?php if ($gallery): ?>
                <div class="mt-20">
                    <h3 class="text-3xl font-bold text-white mb-10">Exploração <i class="playfair italic">Visual</i></h3>
                    
                    <!-- Task 4 — gallery-grid para GSAP stagger -->
                    <div class="gallery-grid grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach($gallery as $index => $img): 
                            $imgSrc = BASE_URL . '/' . getOptimizedImageUrl($img['image_path']);
                            $imgAlt = $altText . ' — imagem ' . ($index + 1);
                        ?>
                        <!-- Task 4 — gallery-item | Task 3 — GLightbox a href -->
                        <a href="<?php echo BASE_URL . '/' . $img['image_path']; ?>" 
                           class="gallery-item glightbox glass-card overflow-hidden group cursor-pointer relative block"
                           data-gallery="gallery-<?php echo $project['id']; ?>"
                           data-glightbox="title: <?php echo htmlspecialchars($project['title']); ?>"
                           data-description="<?php echo $imgAlt; ?>">
                            <img 
                                src="<?php echo $imgSrc; ?>" 
                                class="w-full h-auto object-cover hover:scale-110 transition duration-1000" 
                                alt="<?php echo htmlspecialchars($imgAlt); ?>"
                                loading="lazy"
                                decoding="async"
                            >
                            <!-- Hover overlay -->
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all duration-500 flex items-center justify-center">
                                <div class="opacity-0 group-hover:opacity-100 transition-all duration-300 transform scale-90 group-hover:scale-100">
                                    <span class="bg-white text-black text-[10px] font-black uppercase tracking-widest px-6 py-3">🔍 Ampliar</span>
                                </div>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
