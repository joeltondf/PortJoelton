<?php
header('Content-Type: application/json');

// Include global configuration for DB connection
require_once __DIR__ . '/../admin/config.php';

// Get data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'No data received']);
    exit;
}

try {
    // DB Connection already exists in config.php as $pdo
    // Insert into DB
    $stmt = $pdo->prepare("INSERT INTO leads (name, email, message) VALUES (?, ?, ?)");
    $stmt->execute([$data['name'], $data['email'], $data['message']]);
    $lead_id = $pdo->lastInsertId();

} catch (PDOException $e) {
    // If local DB fails, still try n8n
    $lead_id = null;
}

// Send to n8n Webhook (Replace with actual URL from user)
$n8n_webhook_url = getenv('N8N_WEBHOOK_URL') ?: 'http://n8n-server:5678/webhook-test/contact-form';

$options = [
    'http' => [
        'header'  => "Content-Type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data),
        'timeout' => 5 // 5 seconds timeout
    ]
];

$context  = stream_context_create($options);
$result = @file_get_contents($n8n_webhook_url, false, $context);

// Update status in DB if possible
if ($lead_id && $pdo) {
    $status = ($result !== false) ? 'sent' : 'error';
    $stmt = $pdo->prepare("UPDATE leads SET n8n_status = ? WHERE id = ?");
    $stmt->execute([$status, $lead_id]);
}

// Return success anyway to user (don't block frontend)
echo json_encode(['status' => 'success', 'lead_id' => $lead_id]);
