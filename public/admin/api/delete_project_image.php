<?php
if (!defined('BASE_URL')) exit;
checkAuth();

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['image_id'])) {
    $stmt = $pdo->prepare("SELECT image_path FROM project_images WHERE id = ?");
    $stmt->execute([$data['image_id']]);
    $img = $stmt->fetch();
    
    if ($img) {
        $file_path = __DIR__ . '/../../' . $img['image_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        
        // Check if this image is being used as a cover for any project and clear it
        $pdo->prepare("UPDATE projects SET main_image = '' WHERE main_image = ?")->execute([$img['image_path']]);

        $pdo->prepare("DELETE FROM project_images WHERE id = ?")->execute([$data['image_id']]);
        echo json_encode(['success' => true]);
        exit;
    }
}
echo json_encode(['success' => false]);
