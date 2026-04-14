<?php
session_start();

// Database configuration
$db_host = getenv('DB_HOST') ?: 'localhost';
$db_name = getenv('MYSQL_DATABASE') ?: 'u449430520_joelton';
$db_user = getenv('MYSQL_USER') ?: 'u449430520_userjoelton';
$db_pass = getenv('MYSQL_PASSWORD') ?: '@Mijo0409';

// BASE_URL detection
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
// Adjust scriptName if we are deep in admin folder
$scriptName = str_replace('/admin', '', $scriptName);
if ($scriptName === '/' || $scriptName === '\\') {
    $scriptName = '';
}
if (!defined('BASE_URL')) {
    define('BASE_URL', $scriptName);
}

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Fetch global settings
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
function checkAuth() {
    if (!isset($_SESSION['user_id'])) {
        redirect("/admin/login.php");
    }
}

function redirect($path) {
    if (str_starts_with($path, '/')) {
        $path = BASE_URL . $path;
    }
    header("Location: $path");
    exit;
}
