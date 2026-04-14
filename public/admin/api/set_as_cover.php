<?php
require_once '../config.php';
checkAuth();

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['project_id']) && isset($data['image_path'])) {
    $id = $data['project_id'];
    $path = $data['image_path'];
    
    // Optional: could delete the OLD main_image file if it's not in the gallery
    // but for simplicity and safety, we just update the path.
    
    $stmt = $pdo->prepare("UPDATE projects SET main_image = ? WHERE id = ?");
    $stmt->execute([$path, $id]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Nenhuma alteração feita ou projeto não encontrado.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Dados insuficientes.']);
}
