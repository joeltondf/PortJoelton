<?php
// Database configuration
$db_host = getenv('DB_HOST') ?: 'db';
$db_name = getenv('MYSQL_DATABASE') ?: 'portfolio_db';
$db_user = getenv('MYSQL_USER') ?: 'user';
$db_pass = getenv('MYSQL_PASSWORD') ?: 'user_password';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "DB Connected.\n";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$images_dir = __DIR__ . '/../images';
$subdirs = array_filter(glob($images_dir . '/*'), 'is_dir');

foreach ($subdirs as $dir) {
    $case_name = basename($dir);
    $images = glob($dir . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
    
    if (empty($images)) continue;

    // Use absolute path for web
    $main_image = '/images/' . $case_name . '/' . basename($images[0]);
    $slug = strtolower(str_replace(' ', '-', $case_name));
    
    // Categorization based on name (Simple heuristic)
    $category = 'Web';
    if (stripos($case_name, 'Editorial') !== false || stripos($case_name, 'Livro') !== false || stripos($case_name, 'MPT') !== false || stripos($case_name, 'Marinha') !== false) {
        $category = 'Editorial';
    } else if (stripos($case_name, 'DETRAN') !== false) {
        $category = 'Dashboards';
    } else if (stripos($case_name, 'Social') !== false || stripos($case_name, 'Saude') !== false) {
        $category = 'Social Media';
    }

    echo "Found Case: $case_name ($category) -> $main_image\n";

    // Insert/Update Category
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = ?");
    $stmt->execute([$category]);
    $cat_id = $stmt->fetchColumn();

    if (!$cat_id) {
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
        $stmt->execute([$category, strtolower($category)]);
        $cat_id = $pdo->lastInsertId();
    }

    // Insert Project
    $stmt = $pdo->prepare("INSERT IGNORE INTO projects (title, slug, category_id, summary, main_image, tools_used) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $case_name,
        $slug,
        $cat_id,
        "Solução profissional desenvolvida para " . $case_name . " com foco em alta performance e design premium.",
        $main_image,
        json_encode(['Professional Suite', 'Strategic Design'])
    ]);
}

echo "Dynamic Migration done.\n";
