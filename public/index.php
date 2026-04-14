<?php
// 1. Configurações de Erro
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. Helpers
function view($file, $data = []) {
    extract($data);
    ob_start();
    // Garante que o caminho do include está correto a partir de public/
    include __DIR__ . '/' . $file . '.php';
    return ob_get_clean();
}

// 3. Tratamento de URI e Roteamento
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Detecta se estamos em um subdiretório (importante para assets)
$basePath = dirname($_SERVER['SCRIPT_NAME']);
if ($basePath === '/' || $basePath === '\\') {
    $basePath = '';
}

// Define a BASE_URL para carregar CSS/JS corretamente
if (!defined('BASE_URL')) {
    define('BASE_URL', $basePath);
}

// Limpeza da URI: Remove o '/public' caso ele apareça na URL por erro ou cache
if (strpos($uri, '/public') === 0) {
    $uri = substr($uri, 7);
}

// Garante que a URI comece com / e não esteja vazia
if ($uri === "" || $uri === false) { $uri = "/"; }
if (!str_starts_with($uri, '/')) { $uri = '/' . $uri; }

// 4. Configuração do Banco de Dados
require_once __DIR__ . '/admin/config.php';

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

// Verifica rota admin (Deixa o Apache servir os arquivos físicos se existirem)
if (str_starts_with($uri, '/admin')) {
    // Se o arquivo físico não existir, cai aqui
    if ($uri === '/admin' || $uri === '/admin/') {
        include __DIR__ . '/admin/index.php';
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
    // Caso não caia em nenhuma rota
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 - Página não encontrada</h1>";
}