<?php
if (!defined('BASE_URL')) exit;
checkAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = $_POST['project_id'] ?? 0;
    $title = $_POST['title'] ?? '';
    // Use existing slug if editing, or generate new one
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $category_ids = $_POST['category_ids'] ?? [];
    $summary = $_POST['summary'] ?? '';
    $description = $_POST['description'] ?? '';
    $external_link = $_POST['external_link'] ?? '';
    $tools = $_POST['tools'] ?? '';
    $tools_json = json_encode(array_filter(array_map('trim', explode(';', $tools))));
    $grid_size = $_POST['grid_size'] ?? 'medium';

    if ($project_id) {
        // UPDATE EXISTING
        $stmt = $pdo->prepare("UPDATE projects SET title = ?, summary = ?, description = ?, external_link = ?, tools_used = ?, grid_size = ? WHERE id = ?");
        $stmt->execute([$title, $summary, $description, $external_link, $tools_json, $grid_size, $project_id]);
        
        // Update main image if new one uploaded
        if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === 0) {
            $ext = pathinfo($_FILES['main_image']['name'], PATHINFO_EXTENSION);
            $filename = 'proj_' . time() . '.' . $ext;
            $upload_dir = __DIR__ . '/../../images/projects/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            move_uploaded_file($_FILES['main_image']['tmp_name'], $upload_dir . $filename);
            $main_image = 'images/projects/' . $filename;
            $pdo->prepare("UPDATE projects SET main_image = ? WHERE id = ?")->execute([$main_image, $project_id]);
        }
    } else {
        // INSERT NEW
        $main_image = '';
        if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === 0) {
            $ext = pathinfo($_FILES['main_image']['name'], PATHINFO_EXTENSION);
            $filename = 'proj_' . time() . '.' . $ext;
            $upload_dir = __DIR__ . '/../../images/projects/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            move_uploaded_file($_FILES['main_image']['tmp_name'], $upload_dir . $filename);
            $main_image = 'images/projects/' . $filename;
        }

        $stmt = $pdo->prepare("INSERT INTO projects (title, slug, summary, description, main_image, external_link, tools_used, grid_size) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $summary, $description, $main_image, $external_link, $tools_json, $grid_size]);
        $project_id = $pdo->lastInsertId();
    }

    // Sync categories
    $pdo->prepare("DELETE FROM project_category WHERE project_id = ?")->execute([$project_id]);
    if (!empty($category_ids)) {
        $catStmt = $pdo->prepare("INSERT INTO project_category (project_id, category_id) VALUES (?, ?)");
        foreach ($category_ids as $cid) {
            $catStmt->execute([$project_id, $cid]);
        }
    }


    // Handle Gallery Uploads (Always add to gallery if uploaded)
    if (isset($_FILES['gallery']) && !empty($_FILES['gallery']['name'][0])) {
        $upload_dir = __DIR__ . '/../../images/projects/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        $files = $_FILES['gallery'];
        foreach ($files['name'] as $i => $name) {
            if ($files['error'][$i] === 0) {
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $filename = 'gal_' . $project_id . '_' . $i . '_' . time() . '.' . $ext;
                move_uploaded_file($files['tmp_name'][$i], $upload_dir . $filename);
                $path = 'images/projects/' . $filename;
                
                $stmt = $pdo->prepare("INSERT INTO project_images (project_id, image_path, display_order) VALUES (?, ?, ?)");
                $stmt->execute([$project_id, $path, $i]);
            }
        }
    }

    header("Location: ../projects.php?msg=saved");
    exit;
}
