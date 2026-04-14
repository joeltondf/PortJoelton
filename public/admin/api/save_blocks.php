<?php
if (!defined('BASE_URL')) exit;
checkAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = $_POST['project_id'] ?? 0;
    $blocks = $_POST['blocks'] ?? [];

    if (!$project_id) {
        die("ID do projeto inválido.");
    }

    // Since we are overriding the blocks layout, it's easier to clear and recreate to maintain display order
    $pdo->prepare("DELETE FROM project_blocks WHERE project_id = ?")->execute([$project_id]);

    $order = 0;
    foreach ($blocks as $index => $block_data) {
        $type = $block_data['type'];
        $content = $block_data['content'] ?? '';

        // Handle Image Uploads for this specific block
        if ($type === 'image') {
            $file_key = "block_images_$index";
            if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === 0) {
                // Determine format
                $ext = strtolower(pathinfo($_FILES[$file_key]['name'], PATHINFO_EXTENSION));
                $new_filename = 'block_' . $project_id . '_' . time() . '_' . $order . '.' . $ext;
                $upload_dir = '../../images/projects/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                
                $dest = $upload_dir . $new_filename;
                
                // IF WEBP OPTIMIZATION IS REQUESTED (Phase 4 Setup), try converting
                if (in_array($ext, ['jpg', 'jpeg', 'png']) && function_exists('imagecreatefromjpeg')) {
                    if ($ext == 'png') {
                        $img = imagecreatefrompng($_FILES[$file_key]['tmp_name']);
                    } else {
                        $img = imagecreatefromjpeg($_FILES[$file_key]['tmp_name']);
                    }
                    if ($img) {
                        $webp_name = 'block_' . $project_id . '_' . time() . '_' . $order . '.webp';
                        imagewebp($img, $upload_dir . $webp_name, 80);
                        imagedestroy($img);
                        $content = 'images/projects/' . $webp_name;
                    } else {
                        move_uploaded_file($_FILES[$file_key]['tmp_name'], $dest);
                        $content = 'images/projects/' . $new_filename;
                    }
                } else {
                    move_uploaded_file($_FILES[$file_key]['tmp_name'], $dest);
                    $content = 'images/projects/' . $new_filename;
                }
            } else {
                // Keep existing image if no new upload
                $content = $block_data['existing'] ?? '';
            }
        }

        if (empty($content) && $type != 'image') continue; // skip empty text/link blocks

        $stmt = $pdo->prepare("INSERT INTO project_blocks (project_id, block_type, content, display_order) VALUES (?, ?, ?, ?)");
        $stmt->execute([$project_id, $type, $content, $order]);
        $order++;
    }

    header("Location: ../builder.php?id=$project_id&msg=saved");
    exit;
}
