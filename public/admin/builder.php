<?php
require_once 'config.php';
checkAuth();

$project_id = $_GET['id'] ?? 0;
if (!$project_id) {
    header("Location: projects.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->execute([$project_id]);
$project = $stmt->fetch();

if (!$project) {
    die("Projeto não encontrado.");
}

$stmt = $pdo->prepare("SELECT * FROM project_blocks WHERE project_id = ? ORDER BY display_order ASC");
$stmt->execute([$project_id]);
$blocks = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Construtor de Case | <?php echo htmlspecialchars($project['title']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #050f1e; color: #c9d1d9; }
        .glass-panel { background: rgba(22, 27, 34, 0.8); backdrop-filter: blur(10px); border: 1px solid rgba(48, 54, 61, 1); }
        .gradient-text { background: linear-gradient(45deg, #0875e9, #8309ee); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .bg-custom-gradient { background: linear-gradient(45deg, #0875e9, #8309ee); }
    </style>
</head>
<body class="p-10">
    <div class="max-w-4xl mx-auto">
        <header class="flex justify-between items-center mb-12">
            <div>
                <a href="projects.php" class="text-white/40 hover:text-white text-xs uppercase font-bold tracking-widest flex items-center mb-4"><i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Voltar</a>
                <h1 class="text-3xl font-bold tracking-tight text-white mb-2">Editor de Blocos</h1>
                <p class="text-sm text-white/30 tracking-wide uppercase">Case: <?php echo htmlspecialchars($project['title']); ?></p>
            </div>
            <div class="flex gap-4">
                <button onclick="addBlock('text')" class="px-6 py-2 bg-white/5 border border-white/10 hover:border-blue-500 rounded text-xs uppercase font-bold">Txt</button>
                <button onclick="addBlock('image')" class="px-6 py-2 bg-white/5 border border-white/10 hover:border-blue-500 rounded text-xs uppercase font-bold">Img</button>
                <button onclick="addBlock('link')" class="px-6 py-2 bg-white/5 border border-white/10 hover:border-blue-500 rounded text-xs uppercase font-bold">Link</button>
            </div>
        </header>

        <form action="api/save_blocks.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
            
            <div id="blocks-container" class="space-y-6">
                <!-- Blocks via JS -->
            </div>

            <div class="mt-12 text-center">
                <button type="submit" class="w-full max-w-sm px-8 py-4 bg-custom-gradient text-white font-bold uppercase tracking-widest text-xs rounded hover:scale-105 transition shadow-lg">Salvar Construção do Case</button>
            </div>
        </form>
    </div>

    <script>
        lucide.createIcons();
        const container = document.getElementById('blocks-container');
        let blockCount = 0;
        
        const existingBlocks = <?php echo json_encode($blocks); ?>;
        
        function renderExisting() {
            existingBlocks.forEach(b => addBlock(b.block_type, b.content, b.id));
        }

        function addBlock(type, initialContent = '', id = null) {
            blockCount++;
            const tpl = document.createElement('div');
            tpl.className = 'glass-panel p-6 rounded-xl relative group';
            
            let contentHtml = '';
            if (type === 'text') {
                contentHtml = `<textarea name="blocks[${blockCount}][content]" rows="4" class="w-full bg-[#020814] border border-white/10 p-4 text-white text-sm focus:border-blue-500">${initialContent}</textarea>`;
            } else if (type === 'image') {
                // if it has existing image, show it
                let prevImg = initialContent ? `<img src="../${initialContent}" class="h-32 object-cover mb-4 rounded border border-white/10"> <input type="hidden" name="blocks[${blockCount}][existing]" value="${initialContent}">` : '';
                contentHtml = `${prevImg} <input type="file" name="block_images_${blockCount}" class="text-sm text-white/50 w-full">`;
            } else if (type === 'link') {
                contentHtml = `<input type="url" name="blocks[${blockCount}][content]" value="${initialContent}" placeholder="https://..." class="w-full bg-[#020814] border border-white/10 p-4 text-white text-sm focus:border-blue-500">`;
            }

            tpl.innerHTML = `
                <div class="flex justify-between items-center mb-4">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-white/40">BLOCO: ${type}</span>
                    <div class="flex gap-2">
                        <button type="button" class="text-xs w-6 h-6 bg-red-900/50 hover:bg-red-500 text-white rounded flex items-center justify-center" onclick="this.parentElement.parentElement.parentElement.remove()">✕</button>
                    </div>
                </div>
                <input type="hidden" name="blocks[${blockCount}][type]" value="${type}">
                ${id ? `<input type="hidden" name="blocks[${blockCount}][id]" value="${id}">` : ''}
                ${contentHtml}
            `;
            container.appendChild(tpl);
        }

        window.onload = renderExisting;
    </script>
</body>
</html>
