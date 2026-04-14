<?php
if (!defined('BASE_URL')) exit;
checkAuth();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $name = $_POST['name'] ?? '';
            $category = $_POST['category'] ?? '';
            $power_level = (int)($_POST['power_level'] ?? 0);
            $icon_type = $_POST['icon_type'] ?? 'lucide';
            $icon_value = $_POST['icon_value'] ?? 'code';
            $custom_color = $_POST['custom_color'] ?? '';
            
            if ($name && $category) {
                try {
                    $stmt = $pdo->prepare("INSERT INTO skills (name, category, power_level, icon_type, icon_value, custom_color) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$name, $category, $power_level, $icon_type, $icon_value, $custom_color]);
                    redirect('/admin/skills.php?msg=added');
                } catch (PDOException $e) {
                    if ($e->getCode() == 23000) {
                        $error = "Erro: Já existe uma skill com o nome '" . htmlspecialchars($name) . "'.";
                    } else {
                        $error = "Erro ao adicionar: " . $e->getMessage();
                    }
                }
            }
        } elseif ($_POST['action'] === 'update' && isset($_POST['id'])) {
            $name = $_POST['name'] ?? '';
            $category = $_POST['category'] ?? '';
            $power_level = (int)($_POST['power_level'] ?? 0);
            $icon_type = $_POST['icon_type'] ?? 'lucide';
            $icon_value = $_POST['icon_value'] ?? 'code';
            $custom_color = $_POST['custom_color'] ?? '';
            
            if ($name && $category) {
                try {
                    $stmt = $pdo->prepare("UPDATE skills SET name = ?, category = ?, power_level = ?, icon_type = ?, icon_value = ?, custom_color = ? WHERE id = ?");
                    $stmt->execute([$name, $category, $power_level, $icon_type, $icon_value, $custom_color, $_POST['id']]);
                    redirect('/admin/skills.php?msg=updated');
                } catch (PDOException $e) {
                    if ($e->getCode() == 23000) {
                        $error = "Erro: Já existe outra skill com o nome '" . htmlspecialchars($name) . "'.";
                    } else {
                        $error = "Erro ao atualizar: " . $e->getMessage();
                    }
                }
            }
        } elseif ($_POST['action'] === 'delete' && isset($_POST['id'])) {
            $stmt = $pdo->prepare("DELETE FROM skills WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            redirect('/admin/skills.php?msg=deleted');
        }
    }
}

// Fetch all skills
$skills = $pdo->query("SELECT * FROM skills ORDER BY category ASC, power_level DESC, name ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skills & Tech Stack | Admin Portfolio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #050f1e; color: #c9d1d9; }
        .glass-panel { background: rgba(22, 27, 34, 0.8); backdrop-filter: blur(10px); border: 1px solid rgba(48, 54, 61, 1); }
        .sidebar { background: #020814; border-right: 1px solid #30363d; width: 260px; height: 100vh; position: fixed; left: 0; top: 0; z-index: 50;}
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
            <a href="projects.php" class="block text-xs font-bold uppercase tracking-widest text-white/50 hover:text-[#0875e9] transition">Gerenciar Projetos</a>
            <a href="skills.php" class="block text-xs font-bold uppercase tracking-widest text-[#0875e9] transition">Skills & Tech Stack</a>
            <a href="leads.php" class="block text-xs font-bold uppercase tracking-widest text-white/50 hover:text-[#0875e9] transition">Leads / Contatos</a>
            <a href="settings.php" class="block text-xs font-bold uppercase tracking-widest text-white/50 hover:text-[#0875e9] transition">Configurações</a>
        </nav>
    </div>

    <main class="main-content">
        <header class="flex justify-between items-center mb-12">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-white mb-2">Skills & Tech Stack</h1>
                <p class="text-sm text-white/30 tracking-wide uppercase">Gerencie as ferramentas, ícones e cores do seu stack.</p>
            </div>
            <button onclick="openAddModal()" class="px-8 py-3 bg-custom-gradient text-white font-bold uppercase tracking-widest text-[10px] hover:scale-105 transition duration-500 rounded-full shadow-lg">
                + Adicionar Skill
            </button>
        </header>

        <?php if(isset($_GET['msg'])): ?>
            <div class="bg-blue-500/20 border border-blue-500/50 text-blue-400 p-4 mb-6 rounded text-sm">
                Ação realizada com sucesso!
            </div>
        <?php endif; ?>

        <?php if(isset($error)): ?>
            <div class="bg-red-500/20 border border-red-500/50 text-red-400 p-4 mb-6 rounded text-sm">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="glass-panel overflow-hidden rounded-xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/5 border-b border-white/10">
                        <th class="p-4 text-xs font-bold uppercase tracking-widest text-white/40">Ícone</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-widest text-white/40">Nome</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-widest text-white/40">Categoria</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-widest text-white/40">Domínio</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-widest text-white/40 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($skills)): ?>
                    <tr><td colspan="5" class="p-8 text-center text-white/20 italic text-sm">Nenhuma skill cadastrada. Adicione sua primeira ferramenta.</td></tr>
                    <?php else: ?>
                        <?php foreach($skills as $skill): ?>
                        <tr class="border-b border-white/5 hover:bg-white/5 transition">
                            <td class="p-4">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-white/5 border border-white/10" style="border-bottom-color: <?php echo $skill['custom_color'] ?: 'transparent'; ?>; border-bottom-width: 2px;">
                                    <?php if ($skill['icon_type'] === 'lucide'): ?>
                                        <i data-lucide="<?php echo $skill['icon_value']; ?>" class="w-5 h-5" style="color: <?php echo $skill['custom_color'] ?: '#fff'; ?>"></i>
                                    <?php else: ?>
                                        <span class="text-[8px] opacity-30 uppercase font-black">IMG</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-white"><?php echo htmlspecialchars($skill['name']); ?></div>
                                <div class="text-[8px] font-mono text-white/20 uppercase"><?php echo htmlspecialchars($skill['custom_color'] ?: 'Tema Padrão'); ?></div>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 bg-white/10 rounded text-[10px] uppercase font-bold text-white/60">
                                    <?php echo htmlspecialchars($skill['category']); ?>
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="w-full bg-white/10 rounded-full h-1.5 mb-1 max-w-[150px]">
                                    <div class="h-1.5 rounded-full" style="width: <?php echo $skill['power_level']; ?>%; background-color: <?php echo $skill['custom_color'] ?: '#0875e9'; ?>;"></div>
                                </div>
                                <span class="text-[10px] text-white/40"><?php echo $skill['power_level']; ?>%</span>
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex justify-center space-x-2">
                                    <button onclick='openEditModal(<?php echo json_encode($skill); ?>)' class="w-8 h-8 bg-blue-900/40 border border-blue-500/50 rounded flex items-center justify-center text-xs text-blue-500 hover:bg-blue-500 hover:text-white transition">✎</button>
                                    <form action="skills.php" method="POST" onsubmit="return confirm('Tem certeza que deseja remover esta skill?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $skill['id']; ?>">
                                        <button type="submit" class="w-8 h-8 bg-red-900/40 border border-red-500/50 rounded flex items-center justify-center text-xs text-red-500 hover:bg-red-500 hover:text-white transition">✕</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Modal Adicionar/Editar -->
    <div id="skillModal" class="fixed inset-0 bg-black/80 backdrop-blur-md hidden z-[100] flex items-center justify-center p-10">
        <div class="glass-panel w-full max-w-lg overflow-hidden rounded-2xl p-10 relative">
            <button onclick="closeModal()" class="absolute top-6 right-6 text-2xl text-white/30 hover:text-white">&times;</button>
            <h2 id="modalTitle" class="text-2xl font-bold tracking-tight mb-8">Gestão de <i class="playfair italic">Skill</i></h2>
            
            <form action="skills.php" method="POST" class="space-y-4">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="id" id="skillId">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-widest text-white/40 mb-2">Nome</label>
                        <input type="text" name="name" id="skillName" required class="w-full bg-white/5 border border-white/10 py-3 px-4 focus:border-blue-500 transition text-sm text-white">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-widest text-white/40 mb-2">Categoria</label>
                        <select name="category" id="skillCategory" class="w-full bg-white/5 border border-white/10 py-3 px-4 focus:border-blue-500 transition text-sm text-white">
                            <option value="Tech">Tech</option>
                            <option value="Design">Design</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-widest text-white/40 mb-2">Tipo de Ícone</label>
                        <select name="icon_type" id="skillIconType" class="w-full bg-white/5 border border-white/10 py-3 px-4 focus:border-blue-500 transition text-sm text-white">
                            <option value="lucide">Lucide Icons (Interface)</option>
                            <option value="simpleicons">Simple Icons (Marcas/Empresas)</option>
                            <option value="image">URL da Imagem / SVG</option>
                        </select>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-[10px] uppercase font-bold tracking-widest text-white/40">Valor do Ícone</label>
                            <div class="flex space-x-3">
                                <a href="https://lucide.dev/icons" target="_blank" class="text-[9px] text-blue-400 hover:underline flex items-center">
                                    Interface
                                </a>
                                <span class="text-white/10 text-[9px]">|</span>
                                <a href="https://simpleicons.org/" target="_blank" class="text-[9px] text-purple-400 hover:underline flex items-center">
                                    Marcas (Adobe, etc)
                                </a>
                            </div>
                        </div>
                        <input type="text" name="icon_value" id="skillIconValue" class="w-full bg-white/5 border border-white/10 py-3 px-4 focus:border-blue-500 transition text-sm text-white" placeholder="ex: adobephotoshop, figma, react...">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-widest text-white/40 mb-2">Domínio (0-100)</label>
                        <input type="number" name="power_level" id="skillPowerLevel" min="0" max="100" class="w-full bg-white/5 border border-white/10 py-3 px-4 focus:border-blue-500 transition text-sm text-white">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-widest text-white/40 mb-2">Cor Customizada (HEX)</label>
                        <div class="flex space-x-2">
                            <input type="color" id="colorPicker" class="w-12 h-11 bg-white/5 border border-white/10 p-1 cursor-pointer" oninput="document.getElementById('skillCustomColor').value = this.value">
                            <input type="text" name="custom_color" id="skillCustomColor" class="flex-1 bg-white/5 border border-white/10 py-3 px-4 focus:border-blue-500 transition text-sm text-white" placeholder="#0875e9">
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-custom-gradient text-white font-bold uppercase tracking-widest text-[10px] hover:scale-105 transition duration-500 rounded">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('modalTitle').innerText = 'Nova Skill';
            document.getElementById('formAction').value = 'add';
            document.getElementById('skillId').value = '';
            document.getElementById('skillName').value = '';
            document.getElementById('skillPowerLevel').value = '80';
            document.getElementById('skillIconValue').value = 'code';
            document.getElementById('skillCustomColor').value = '';
            document.getElementById('skillModal').classList.remove('hidden');
        }

        function openEditModal(skill) {
            document.getElementById('modalTitle').innerText = 'Editar Skill';
            document.getElementById('formAction').value = 'update';
            document.getElementById('skillId').value = skill.id;
            document.getElementById('skillName').value = skill.name;
            document.getElementById('skillCategory').value = skill.category;
            document.getElementById('skillPowerLevel').value = skill.power_level;
            document.getElementById('skillIconType').value = skill.icon_type;
            document.getElementById('skillIconValue').value = skill.icon_value;
            document.getElementById('skillCustomColor').value = skill.custom_color;
            if(skill.custom_color) document.getElementById('colorPicker').value = skill.custom_color;
            document.getElementById('skillModal').classList.remove('hidden');
        }

        function closeModal() { document.getElementById('skillModal').classList.add('hidden'); }
        lucide.createIcons();
    </script>
</body>
</html>
