<?php
/**
 * Auto-Indexer — Sincronização de Imagens com o Banco de Dados
 * 
 * Uso CLI:  php scripts/auto_index.php
 * Uso Web:  /admin/sync.php (protegido por checkAuth)
 * 
 * Lógica:
 * - Varre subpastas de public/images/
 * - Ignora pastas de sistema: assets, uploads, logos_clientes, projects
 * - Cria projetos e imagens no banco se não existirem
 * - Define featured_image pela imagem com "capa" no nome ou primeira da lista
 */

// Detectar contexto de execução
$isCLI = (php_sapi_name() === 'cli');

// Bootstrap
if ($isCLI) {
    define('BASE_URL', '');
    $configPath = __DIR__ . '/../public/admin/config.php';
    
    // Suprimir session_start no CLI
    if (!session_id()) {
        // No CLI não iniciamos sessão
    }
    
    // Incluir apenas PDO do config sem session_start
    $db_host = 'localhost';
    $db_name = 'portifolio';
    $db_user = 'joeltondf';
    $db_pass = '@Mijo0409';
    
    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erro de conexão: " . $e->getMessage() . "\n");
    }
    
    function log_msg(string $msg): void {
        echo $msg . "\n";
    }
} else {
    // Web context — será incluído via sync.php com PDO já disponível
    function log_msg(string $msg): void {
        echo "<li>" . htmlspecialchars($msg) . "</li>\n";
    }
}

// -----------------------------------------------------------------------
// Configuração
// -----------------------------------------------------------------------

$imagesRoot = __DIR__ . '/../public/images';
$imagesWebRoot = 'images'; // caminho relativo para o DB

// Pastas a ignorar
$ignoreFolders = ['assets', 'uploads', 'logos_clientes', 'projects', '.', '..'];

// Extensões de imagem aceitas
$imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

// -----------------------------------------------------------------------
// Funções Auxiliares
// -----------------------------------------------------------------------

/**
 * Gera slug a partir de string
 */
function slugify(string $text): string {
    // Converter acentos
    $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

/**
 * Verificar se é arquivo de imagem
 */
function isImage(string $filename, array $extensions): bool {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($ext, $extensions);
}

/**
 * Ordena imagens, priorizando as que contém "capa"
 */
function sortImages(array $images): array {
    usort($images, function ($a, $b) {
        $aHasCapa = stripos($a, 'capa') !== false;
        $bHasCapa = stripos($b, 'capa') !== false;
        if ($aHasCapa && !$bHasCapa) return -1;
        if (!$aHasCapa && $bHasCapa) return 1;
        return strnatcasecmp($a, $b);
    });
    return $images;
}

// -----------------------------------------------------------------------
// Garantir categoria padrão
// -----------------------------------------------------------------------

function ensureDefaultCategory(PDO $pdo): int {
    // Tenta encontrar categoria de Design
    $stmt = $pdo->query("SELECT id FROM categories WHERE slug = 'design' OR slug = 'design-institucional' LIMIT 1");
    $cat = $stmt->fetch();
    
    if ($cat) {
        return (int) $cat['id'];
    }
    
    // Cria categoria padrão
    $pdo->exec("INSERT INTO categories (name, slug) VALUES ('Design Institucional', 'design-institucional')");
    log_msg("[CATEGORIA] Criada: Design Institucional");
    return (int) $pdo->lastInsertId();
}

// -----------------------------------------------------------------------
// Sincronização Principal
// -----------------------------------------------------------------------

$stats = ['created' => 0, 'updated' => 0, 'skipped' => 0, 'images_added' => 0];

if (!is_dir($imagesRoot)) {
    log_msg("[ERRO] Pasta não encontrada: $imagesRoot");
    if (!$isCLI) return $stats;
    exit(1);
}

$defaultCategoryId = ensureDefaultCategory($pdo);

$dir = new DirectoryIterator($imagesRoot);

foreach ($dir as $folder) {
    // Ignorar arquivos e pastas de sistema
    if (!$folder->isDir()) continue;
    if (in_array($folder->getFilename(), $ignoreFolders)) continue;
    
    $folderName = $folder->getFilename();
    $folderPath = $folder->getRealPath();
    $slug = slugify($folderName);
    
    // -----------------------------------------------------------------------
    // Verificar/Criar Projeto no DB
    // -----------------------------------------------------------------------
    
    $stmt = $pdo->prepare("SELECT id, main_image FROM projects WHERE slug = ?");
    $stmt->execute([$slug]);
    $project = $stmt->fetch();
    
    if (!$project) {
        // Coletar imagens da pasta
        $images = [];
        $innerDir = new DirectoryIterator($folderPath);
        foreach ($innerDir as $file) {
            if ($file->isFile() && isImage($file->getFilename(), $imageExtensions)) {
                $images[] = $file->getFilename();
            }
        }
        
        $images = sortImages($images);
        $featuredImage = !empty($images) ? $imagesWebRoot . '/' . $folderName . '/' . $images[0] : null;
        
        // Inserir projeto
        $stmt = $pdo->prepare("
            INSERT INTO projects (category_id, title, slug, summary, main_image, is_featured, created_at)
            VALUES (?, ?, ?, ?, ?, 0, NOW())
        ");
        $stmt->execute([
            $defaultCategoryId,
            $folderName,
            $slug,
            'Projeto de design ' . $folderName . ' — indexado automaticamente.',
            $featuredImage
        ]);
        $projectId = (int) $pdo->lastInsertId();
        $stats['created']++;
        log_msg("[CRIADO] Projeto: $folderName (slug: $slug)");
        
    } else {
        $projectId = (int) $project['id'];
        
        // Re-coletar imagens para verificar novas
        $images = [];
        $innerDir = new DirectoryIterator($folderPath);
        foreach ($innerDir as $file) {
            if ($file->isFile() && isImage($file->getFilename(), $imageExtensions)) {
                $images[] = $file->getFilename();
            }
        }
        $images = sortImages($images);
        $stats['skipped']++;
        log_msg("[EXISTE] Projeto: $folderName (id: $projectId) — verificando imagens...");
    }
    
    // -----------------------------------------------------------------------
    // Sincronizar Imagens da Galeria
    // -----------------------------------------------------------------------
    
    $innerDir = new DirectoryIterator($folderPath);
    $allImages = [];
    foreach ($innerDir as $file) {
        if ($file->isFile() && isImage($file->getFilename(), $imageExtensions)) {
            $allImages[] = $file->getFilename();
        }
    }
    $allImages = sortImages($allImages);
    
    $order = 0;
    foreach ($allImages as $imgFile) {
        $relPath = $imagesWebRoot . '/' . $folderName . '/' . $imgFile;
        
        // Verificar se já existe na tabela project_images
        $stmt = $pdo->prepare("SELECT id FROM project_images WHERE project_id = ? AND image_path = ?");
        $stmt->execute([$projectId, $relPath]);
        
        if (!$stmt->fetch()) {
            $stmt = $pdo->prepare("
                INSERT INTO project_images (project_id, image_path, display_order)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$projectId, $relPath, $order]);
            $stats['images_added']++;
            log_msg("  [IMG] Adicionada: $imgFile");
        }
        
        $order++;
    }
    
    // Atualizar main_image se estiver vazia
    $stmt = $pdo->prepare("SELECT main_image FROM projects WHERE id = ?");
    $stmt->execute([$projectId]);
    $proj = $stmt->fetch();
    
    if (empty($proj['main_image']) && !empty($allImages)) {
        $featured = $imagesWebRoot . '/' . $folderName . '/' . $allImages[0];
        $pdo->prepare("UPDATE projects SET main_image = ? WHERE id = ?")->execute([$featured, $projectId]);
        log_msg("  [CAPA] Definida: " . $allImages[0]);
        $stats['updated']++;
    }
}

// -----------------------------------------------------------------------
// Resultado Final
// -----------------------------------------------------------------------

log_msg("-------------------------------------");
log_msg("[CONCLUÍDO] Projetos criados: {$stats['created']}");
log_msg("[CONCLUÍDO] Projetos existentes verificados: {$stats['skipped']}");
log_msg("[CONCLUÍDO] Imagens adicionadas ao DB: {$stats['images_added']}");
log_msg("[CONCLUÍDO] Capas atualizadas: {$stats['updated']}");

if (!$isCLI) {
    return $stats;
}
