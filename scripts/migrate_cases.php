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

$projects = [
    [
        'title' => 'Ações Estratégicas MPT',
        'slug' => 'mpt-acoes-estrategicas',
        'category' => 'Editorial',
        'summary' => 'Modernização de peças editoriais e comunicação visual para o Ministério Público do Trabalho.',
        'main_image' => '/images/MPT/main.jpg',
        'tools' => ['Adobe InDesign', 'Photoshop', 'Branding']
    ],
    [
        'title' => 'CNMP Portal Framework',
        'slug' => 'cnmp-portal',
        'category' => 'Web',
        'summary' => 'Desenvolvimento de interface e arquitetura de componentes para o Portal do Conselho Nacional do Ministério Público.',
        'main_image' => '/images/CNMP/main.jpg',
        'tools' => ['Python', 'Django', 'Tailwind CSS']
    ],
    [
        'title' => 'Otimização DETRAN-DF',
        'slug' => 'detran-otimizacao',
        'category' => 'Dashboards',
        'summary' => 'Sistema de visualização de dados e análise de performance de atendimento.',
        'main_image' => '/images/DETRAN/main.jpg',
        'tools' => ['PowerBI', 'SQL', 'Python']
    ],
    [
        'title' => 'Marinha do Brasil - Editorial',
        'slug' => 'marinha-editorial',
        'category' => 'Editorial',
        'summary' => 'Design de informativos técnicos e peças de comunicação estratégica para a Marinha do Brasil.',
        'main_image' => '/images/MARINHA/main.jpg',
        'tools' => ['Adobe Suite']
    ],
    [
        'title' => 'Ministério da Saúde - Campanhas',
        'slug' => 'ms-campanhas',
        'category' => 'Social Media',
        'summary' => 'Criação de assets digitais para grandes campanhas nacionais de conscientização.',
        'main_image' => '/images/ministerio-da-saude/main.jpg',
        'tools' => ['Photoshop', 'Illustrator', 'Social Media Marketing']
    ]
];

foreach ($projects as $proj) {
    // Check category
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = ?");
    $stmt->execute([$proj['category']]);
    $cat_id = $stmt->fetchColumn();

    if (!$cat_id) {
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
        $stmt->execute([$proj['category'], strtolower($proj['category'])]);
        $cat_id = $pdo->lastInsertId();
    }

    $stmt = $pdo->prepare("INSERT IGNORE INTO projects (title, slug, category_id, summary, main_image, tools_used) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $proj['title'],
        $proj['slug'],
        $cat_id,
        $proj['summary'],
        $proj['main_image'],
        json_encode($proj['tools'])
    ]);
    
    echo "Inserted: " . $proj['title'] . "\n";
}

echo "Migration done.\n";
