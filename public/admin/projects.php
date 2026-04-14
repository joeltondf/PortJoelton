<?php
if (!defined('BASE_URL')) exit;
checkAuth();

// Handle deletions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'] ?? 0;
    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: projects.php?msg=deleted");
        exit;
    }
}

$stmt = $pdo->query("
    SELECT p.*, GROUP_CONCAT(c.name SEPARATOR ', ') as category_names, GROUP_CONCAT(c.id) as category_ids 
    FROM projects p 
    LEFT JOIN project_category pc ON p.id = pc.project_id 
    LEFT JOIN categories c ON pc.category_id = c.id 
    GROUP BY p.id 
    ORDER BY p.created_at DESC
");
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

// Fetch all project images grouped by project_id
$stmt = $pdo->query("SELECT project_id, id, image_path FROM project_images ORDER BY display_order ASC");
$imagesRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);
$projectImages = [];
foreach ($imagesRaw as $img) {
    $projectImages[$img['project_id']][] = $img;
}
foreach ($projects as &$p) {
    $p['gallery'] = $projectImages[$p['id']] ?? [];
}
unset($p);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Projetos | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #050f1e; color: #c9d1d9; }
        .glass-panel { background: rgba(22, 27, 34, 0.8); backdrop-filter: blur(10px); border: 1px solid rgba(48, 54, 61, 1); }
        .sidebar { background: #020814; border-right: 1px solid #30363d; width: 260px; height: 100vh; position: fixed; left: 0; top: 0; }
        .main-content { margin-left: 260px; padding: 40px; }
        .gradient-text { background: linear-gradient(45deg, #0875e9, #8309ee); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .bg-custom-gradient { background: linear-gradient(45deg, #0875e9, #8309ee); }
        select, input, textarea { color: white !important; }
        select option { background: #0d1117; color: white; }
    </style>
</head>
<body>
    <div class="sidebar p-8">
        <div class="text-sm font-bold tracking-widest mb-12 uppercase gradient-text">ADMIN.HUB</div>
        <nav class="space-y-6">
            <a href="dashboard.php" class="block text-xs font-bold uppercase tracking-widest text-white/50 hover:text-[#0875e9] transition">Dashboard</a>
            <a href="projects.php" class="block text-xs font-bold uppercase tracking-widest text-[#0875e9] transition">Gerenciar Projetos</a>
            <a href="leads.php" class="block text-xs font-bold uppercase tracking-widest text-white/50 hover:text-[#0875e9] transition">Leads / Contatos</a>
            <a href="settings.php" class="block text-xs font-bold uppercase tracking-widest text-white/50 hover:text-[#0875e9] transition">Configurações</a>
        </nav>
    </div>

    <main class="main-content">
        <header class="flex justify-between items-center mb-12">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-white mb-2">Gerenciar Projetos</h1>
                <p class="text-sm text-white/30 tracking-wide uppercase">Crie, edite ou remova cases de sucesso.</p>
            </div>
            <button onclick="openModal()" class="px-8 py-3 bg-custom-gradient text-white font-bold uppercase tracking-widest text-[10px] hover:scale-105 transition duration-500 rounded-full shadow-lg">
                + Novo Projeto
            </button>
        </header>

        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($projects as $project): ?>
            <div class="glass-panel rounded-xl overflow-hidden group">
                <div class="relative h-48 overflow-hidden">
                    <?php if ($project['main_image']): ?>
                    <img src="../<?php echo htmlspecialchars($project['main_image']); ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                    <?php else: ?>
                    <div class="w-full h-full bg-white/5 flex items-center justify-center">
                        <span class="text-4xl opacity-20">📷</span>
                    </div>
                    <?php endif; ?>
                    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/10 transition"></div>
                    <div class="absolute top-4 right-4 flex space-x-2">
                        <button onclick='window.location.href="builder.php?id=<?php echo $project['id']; ?>"' class="w-8 h-8 bg-black/80 rounded-full flex items-center justify-center text-xs hover:bg-purple-500 hover:text-white transition" title="Construtor de Blocos">▤</button>
                        <button onclick='openEditModal(<?php echo htmlspecialchars(json_encode($project), ENT_QUOTES, 'UTF-8'); ?>)' class="w-8 h-8 bg-black/80 rounded-full flex items-center justify-center text-xs hover:bg-[#0875e9] hover:text-white transition">✎</button>
                        <form action="projects.php" method="POST" onsubmit="return confirm('Excluir este projeto permanentemente?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                            <button type="submit" class="w-8 h-8 bg-red-900/80 rounded-full flex items-center justify-center text-xs hover:bg-red-500 transition">✕</button>
                        </form>
                    </div>
                </div>
                <div class="p-6">
                    <div class="gradient-text text-[8px] font-bold uppercase tracking-widest mb-2 italic"><?php echo htmlspecialchars($project['category_names'] ?? 'Sem Categoria'); ?></div>
                    <h3 class="text-lg font-bold text-white mb-2"><?php echo htmlspecialchars($project['title']); ?></h3>
                    <p class="text-xs text-white/40 line-clamp-2 mb-4"><?php echo htmlspecialchars($project['summary']); ?></p>
                    <div class="flex items-center justify-between">
                         <div class="text-[10px] font-bold text-white/20 uppercase tracking-widest">v1.2</div>
                         <div class="flex -space-x-2">
                             <?php $tools = json_decode($project['tools_used'], true) ?: []; foreach(array_slice($tools, 0, 3) as $tool): ?>
                                <div class="w-6 h-6 rounded-full bg-white/5 border border-black flex items-center justify-center text-[8px] font-bold"><?php echo substr($tool, 0, 1); ?></div>
                             <?php endforeach; ?>
                         </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </section>
    </main>

    <!-- Modal for New Project (Simplified) -->
    <div id="projectModal" class="fixed inset-0 bg-black/80 backdrop-blur-md hidden z-[100] flex items-center justify-center p-10">
        <div class="glass-panel w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-2xl p-12 relative">
            <button onclick="closeModal()" class="absolute top-8 right-8 text-2xl text-white/30 hover:text-white">&times;</button>
            <h2 id="modalTitle" class="text-4xl font-bold tracking-tighter mb-10">Configurar <i class="playfair italic">Case</i></h2>
            
            <form action="api/save_project.php" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <input type="hidden" name="project_id" id="projectId">
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-widest text-white/40 mb-2">Título do Projeto</label>
                        <input type="text" name="title" id="projectTitle" required class="w-full bg-white/5 border border-white/10 py-3 px-4 focus:outline-none focus:border-blue-500 transition text-sm text-white">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-widest text-white/40 mb-2">Categorias (Selecione múltiplas)</label>
                        <div id="categoryContainer" class="grid grid-cols-2 gap-2 p-4 bg-white/5 border border-white/10 rounded overflow-y-auto max-h-32">
                            <?php foreach($categories as $cat): ?>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" name="category_ids[]" value="<?php echo $cat['id']; ?>" class="rounded bg-black border-white/20 text-blue-500 focus:ring-0">
                                    <span class="text-[10px] text-white/70 uppercase font-bold"><?php echo $cat['name']; ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-widest text-white/40 mb-2">Resumo Rápido (Home)</label>
                        <textarea name="summary" id="projectSummary" rows="3" class="w-full bg-white/5 border border-white/10 py-3 px-4 focus:outline-none focus:border-blue-500 transition text-sm text-white resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-widest text-white/40 mb-2">Execução & Detalhes (Página do Case)</label>
                        <textarea name="description" id="projectDescription" rows="6" class="w-full bg-white/5 border border-white/10 py-3 px-4 focus:outline-none focus:border-blue-500 transition text-sm text-white" placeholder="Explique o desafio e a solução..."></textarea>
                    </div>
                </div>
                
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-widest text-white/40 mb-2">Tamanho Bento</label>
                            <select name="grid_size" id="projectGridSize" class="w-full bg-white/5 border border-white/10 py-2 px-4 focus:border-blue-500 text-sm">
                                <option value="small">Small (Estreito)</option>
                                <option value="medium" selected>Medium (Standard)</option>
                                <option value="large">Large (Destaque Largo)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-widest text-white/40 mb-2">Link Externo</label>
                            <input type="url" name="external_link" id="projectExternalLink" placeholder="https://..." class="w-full bg-white/5 border border-white/10 py-2 px-4 text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-widest text-white/40 mb-2">Tecnologias (Separadas por ;)</label>
                        <input type="text" name="tools" id="projectTools" placeholder="Python; n8n; React" class="w-full bg-white/5 border border-white/10 py-3 px-4 focus:border-blue-500 transition text-sm text-white">
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-widest text-white/40 mb-2">Cover principal</label>
                            <div class="w-full h-32 border-2 border-dashed border-white/10 rounded-xl flex flex-col items-center justify-center relative overflow-hidden group hover:border-blue-500 transition">
                                 <input type="file" name="main_image" class="absolute inset-0 opacity-0 cursor-pointer" onchange="previewImage(this, 'previewImg')">
                                 <img id="previewImg" class="absolute inset-0 w-full h-full object-cover hidden">
                                 <div class="text-xl">📸</div>
                                 <!-- Remove cover button -->
                                 <button type="button" id="btnRemoveCover" onclick="removeMainImage()" class="absolute top-2 right-2 bg-red-600 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition duration-300 hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                 </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-widest text-white/40 mb-2">Galeria (Várias Fotos)</label>
                            <div class="w-full h-32 border-2 border-dashed border-white/10 rounded-xl flex flex-col items-center justify-center relative group hover:border-purple-500 transition mb-4">
                                 <input type="file" name="gallery[]" multiple class="absolute inset-0 opacity-0 cursor-pointer">
                                 <div class="text-xl">🖼️</div>
                                 <span class="text-[8px] font-black opacity-30">UPLOAD MÚLTIPLO</span>
                            </div>
                            <!-- Existing Gallery Preview Container -->
                            <div id="existingGallery" class="grid grid-cols-3 gap-2 max-h-32 overflow-y-auto pr-2 custom-scroll">
                                <!-- Images injected via JS -->
                            </div>
                        </div>
                    </div>
                    <div class="pt-8">
                        <button type="submit" class="w-full py-5 bg-custom-gradient text-white font-bold uppercase tracking-widest text-[10px] hover:scale-105 transition duration-500 rounded shadow-xl">
                            Salvar e Continuar para Editor de Blocos &rarr;
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modalTitle').innerHTML = 'Configurar <i class="playfair italic">Novo Case</i>';
            document.getElementById('projectId').value = '';
            document.getElementById('projectTitle').value = '';
            document.getElementById('projectSummary').value = '';
            document.getElementById('projectDescription').value = '';
            document.getElementById('projectTools').value = '';
            document.getElementById('projectExternalLink').value = '';
            document.getElementById('previewImg').classList.add('hidden');
            document.getElementById('existingGallery').innerHTML = '';
            document.getElementById('projectModal').classList.remove('hidden');
        }

        window.openEditModal = function(project) {
            document.getElementById('modalTitle').innerHTML = 'Editar <i class="playfair italic">Case Existente</i>';
            document.getElementById('projectId').value = project.id;
            document.getElementById('projectTitle').value = project.title;
            
            // Handle multiple categories
            const catIds = (project.category_ids || "").split(",");
            const checkboxes = document.querySelectorAll('input[name="category_ids[]"]');
            checkboxes.forEach(cb => {
                cb.checked = catIds.includes(cb.value);
            });

            document.getElementById('projectSummary').value = project.summary;
            document.getElementById('projectDescription').value = project.description || '';
            document.getElementById('projectGridSize').value = project.grid_size;
            document.getElementById('projectExternalLink').value = project.external_link;
            
            const tools = JSON.parse(project.tools_used);
            document.getElementById('projectTools').value = Array.isArray(tools) ? tools.join('; ') : '';
            
            if (project.main_image) {
                document.getElementById('previewImg').src = '../' + project.main_image;
                document.getElementById('previewImg').classList.remove('hidden');
                document.getElementById('btnRemoveCover').classList.remove('hidden');
            } else {
                document.getElementById('previewImg').classList.add('hidden');
                document.getElementById('btnRemoveCover').classList.add('hidden');
            }

            // Render existing gallery images
            const galContainer = document.getElementById('existingGallery');
            galContainer.innerHTML = '';
            if (project.gallery && project.gallery.length > 0) {
                project.gallery.forEach(img => {
                    const wrap = document.createElement('div');
                    wrap.className = 'relative group w-full h-16 rounded overflow-hidden border border-white/10';
                    wrap.id = 'gal-img-' + img.id;
                    wrap.innerHTML = `
                        <img src="../${img.image_path}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/60 flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition cursor-default">
                            <button type="button" onclick="setAsCover('${img.image_path}')" class="bg-blue-600 hover:bg-blue-500 text-white p-1 rounded text-[7px] font-bold tracking-tight" title="Definir como Capa">CAPA</button>
                            <button type="button" onclick="deleteGalleryImage(${img.id})" class="bg-red-600 hover:bg-red-500 text-white p-1 rounded text-[7px] font-bold tracking-tight" title="Deletar">LIXO</button>
                        </div>
                    `;
                    galContainer.appendChild(wrap);
                });
            }

            document.getElementById('projectModal').classList.remove('hidden');
        }

        async function deleteGalleryImage(id) {
            if (!confirm('Deletar imagem da galeria?')) return;
            try {
                const res = await fetch('api/delete_project_image.php', {
                    method: 'POST',
                    body: JSON.stringify({ image_id: id }),
                    headers: { 'Content-Type': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    document.getElementById('gal-img-' + id).remove();
                } else {
                    alert('Falha ao deletar.');
                }
            } catch(e) {
                console.error(e);
            }
        }

        async function removeMainImage() {
            const id = document.getElementById('projectId').value;
            if (!id) return;
            if (!confirm('Remover a imagem de capa atual?')) return;
            
            try {
                const res = await fetch('api/remove_main_image.php', {
                    method: 'POST',
                    body: JSON.stringify({ project_id: id }),
                    headers: { 'Content-Type': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    document.getElementById('previewImg').classList.add('hidden');
                    document.getElementById('btnRemoveCover').classList.add('hidden');
                    alert('Capa removida com sucesso!');
                } else {
                    alert('Erro ao remover a capa.');
                }
            } catch(e) {
                console.error(e);
            }
        }

        async function setAsCover(path) {
            const id = document.getElementById('projectId').value;
            if (!id) return;
            if (!confirm('Usar esta imagem da galeria como capa principal?')) return;
            
            try {
                const res = await fetch('api/set_as_cover.php', {
                    method: 'POST',
                    body: JSON.stringify({ project_id: id, image_path: path }),
                    headers: { 'Content-Type': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    document.getElementById('previewImg').src = '../' + path;
                    document.getElementById('previewImg').classList.remove('hidden');
                    document.getElementById('btnRemoveCover').classList.remove('hidden');
                    alert('Capa atualizada com sucesso!');
                } else {
                    alert('Erro ao definir capa.');
                }
            } catch(e) {
                console.error(e);
            }
        }

        function closeModal() { document.getElementById('projectModal').classList.add('hidden'); }
        function previewImage(input, targetId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(targetId).src = e.target.result;
                    document.getElementById(targetId).classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
