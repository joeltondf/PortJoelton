<?php
if (!defined('BASE_URL')) exit;
checkAuth();

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['project_id'])) {
    $id = $data['project_id'];
    
    // Get current image path to delete file
    $stmt = $pdo->prepare("SELECT main_image FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    $project = $stmt->fetch();
    
    if ($project && $project['main_image']) {
        $file_path = __DIR__ . '/../../' . $project['main_image'];
        if (file_exists($file_path)) {
            // Check if this image is used by other projects or in gallery before deleting
            // (Simplification: just delete if not in project_images)
            $stmt = $pdo->prepare("SELECT id FROM project_images WHERE image_path = ?");
            $stmt->execute([$project['main_image']]);
            if (!$stmt->fetch()) {
                unlink($file_path);
            }
        }
        
        $stmt = $pdo->prepare("UPDATE projects SET main_image = '' WHERE id = ?");
        $stmt->execute([$id]);
        
        echo json_encode(['success' => true]);
        exit;
    }
}
echo json_encode(['success' => false]);
