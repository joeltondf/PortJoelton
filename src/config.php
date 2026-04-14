<?php
if (!defined('BASE_URL')) {
    // Detect BASE_URL if not already defined (fallback for direct file access)
    $scriptName = $_SERVER['SCRIPT_NAME']; // ex: /portifolio/public/admin/login.php
    $documentRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    
    // Basic detection: find where /public is
    $publicPos = strpos($scriptName, '/public');
    if ($publicPos !== false) {
        define('BASE_URL', substr($scriptName, 0, $publicPos));
    } else {
        // Fallback or assume root
        define('BASE_URL', '');
    }
}

session_start();

// Database credentials
$is_local = ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['REMOTE_ADDR'] === '127.0.0.1');

if ($is_local) {
    $host = 'localhost';
    $db   = 'portifolio';
    $user = 'root';
    $pass = '';
} else {
    $host = '193.203.175.141';
    $db   = 'u449430520_joelton';
    $user = 'u449430520_userjoelton';
    $pass = '@Mijo0409';
}

$charset = 'utf8mb4';

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Fetch global settings if table exists
    $settings = [];
    try {
        $stmt = $pdo->query("SELECT * FROM settings");
        while ($row = $stmt->fetch()) {
            $settings[$row['name']] = $row['value'];
        }
    } catch (PDOException $e) {
        // Fallback or ignore if table doesn't exist yet
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Global functions
if (!function_exists('checkAuth')) {
    function checkAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            redirect("/admin/login.php");
        }
    }
}

if (!function_exists('redirect')) {
    function redirect($path)
    {
        if (str_starts_with($path, '/')) {
            $path = BASE_URL . $path;
        }
        header("Location: $path");
        exit;
    }
}
