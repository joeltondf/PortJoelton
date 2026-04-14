<?php
// 1. Configurações de Erro
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. Helpers
function view($file, $data = []) {
    extract($data);
    ob_start();
    include __DIR__ . '/' . $file . '.php';
    return ob_get_clean();
}

// 3. Tratamento de URI e Roteamento
// Detecta a BASE_URL automaticamente (ex: /portifolio ou vazio)
$scriptName = $_SERVER['SCRIPT_NAME']; // ex: /portifolio/public/index.php
$publicPath = str_replace('\\', '/', __DIR__); // ex: C:/.../portifolio/public
$documentRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']); // ex: C:/.../www

// Calcula o caminho relativo da pasta public em relação ao root do servidor
$basePath = str_replace($documentRoot, '', dirname($scriptName)); 
// Se o root .htaccess redireciona para public, precisamos remover o '/public' do final do basePath para a URL amigável
$basePath = str_replace('/public', '', $basePath);
if ($basePath === '/' || $basePath === '\\') {
    $basePath = '';
}

if (!defined('BASE_URL')) {
    define('BASE_URL', $basePath);
}

// Limpeza da URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove o basePath da URI para que o roteamento seja independente da pasta
if ($basePath !== '' && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

// Fallback para index
if ($uri === "" || $uri === false) { $uri = "/"; }
if (!str_starts_with($uri, '/')) { $uri = '/' . $uri; }

// 4. Configuração do Banco de Dados (Movido para fora da public)
require_once __DIR__ . '/../src/config.php';

// 5. Roteador Robusto
$route = $uri;

// Verifica rotas de projeto
if (str_starts_with($route, '/project/')) {
    $uriParts = explode('/', trim($route, '/'));
    $projectSlug = end($uriParts);
    
    if (file_exists(__DIR__ . '/project.php')) {
        include __DIR__ . '/project.php';
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "Erro: Arquivo project.php não encontrado.";
    }
    exit;
}

// Verifica rota admin
if (str_starts_with($uri, '/admin')) {
    $adminUri = str_replace('/admin', '', $uri);
    $adminUri = trim($adminUri, '/');
    
    if ($adminUri === '') {
        $adminFile = 'index.php';
    } else {
        $adminFile = $adminUri;
        if (!str_ends_with($adminFile, '.php')) {
            $adminFile .= '.php';
        }
    }

    $adminPath = __DIR__ . '/admin/' . $adminFile;
    if (file_exists($adminPath)) {
        require_once $adminPath;
    } else {
        header("HTTP/1.0 404 Not Found");
        die("<h1>404 - Página Administrativa Não Encontrada</h1>");
    }
    exit;
}

// 6. Home Page (Rota Padrão)
if ($uri === '/') {
    // Fetch Dynamic Data
    $skills = $pdo->query("SELECT * FROM skills ORDER BY power_level DESC, name ASC")->fetchAll();
    
    $stmt = $pdo->query("
        SELECT p.*, GROUP_CONCAT(c.name SEPARATOR ', ') as category_names, GROUP_CONCAT(c.slug) as category_slugs 
        FROM projects p 
        LEFT JOIN project_category pc ON p.id = pc.project_id 
        LEFT JOIN categories c ON pc.category_id = c.id 
        GROUP BY p.id 
        ORDER BY p.is_featured DESC, p.created_at DESC
    ");
    $projects = $stmt->fetchAll();
    
    $categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

    // Renderização
    $content = view('home', [
        'skills' => $skills,
        'projects' => $projects,
        'categories' => $categories
    ]);

    if (file_exists(__DIR__ . '/layout.php')) {
        include __DIR__ . '/layout.php';
    } else {
        echo $content;
    }
} else {
    $file = __DIR__ . $uri;
    if (is_dir($file)) { $file = rtrim($file, '/') . '/index.php'; }
    if (!str_ends_with($file, '.php')) { $file .= '.php'; }

    if (file_exists($file) && is_file($file)) {
        require_once $file;
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 - Página não encontrada</h1>";
    }
}