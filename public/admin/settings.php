<?php
if (!defined('BASE_URL')) exit;
checkAuth();

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        $stmt = $pdo->prepare("INSERT INTO settings (name, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = ?");
        $stmt->execute([$key, $value, $value]);
    }
    redirect('/admin/settings.php?msg=updated');
}

// Fetch current settings
$stmt = $pdo->query("SELECT * FROM settings");
$current_settings = [];
while ($row = $stmt->fetch()) {
    $current_settings[$row['name']] = $row['value'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações | Admin Portfolio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #050f1e; color: #c9d1d9; }
        .glass-panel { background: rgba(22, 27, 34, 0.8); backdrop-filter: blur(10px); border: 1px solid rgba(48, 54, 61, 1); }
        .sidebar { background: #020814; border-right: 1px solid #30363d; width: 260px; height: 100vh; position: fixed; left: 0; top: 0; }
        .main-content { margin-left: 260px; padding: 40px; }
        .gradient-text { background: linear-gradient(45deg, #0875e9, #8309ee); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .bg-custom-gradient { background: linear-gradient(45deg, #0875e9, #8309ee); }
        select, input { color: white !important; }
        select option { background: #0d1117; color: white; }
    </style>
</head>
<body>
    <div class="sidebar p-8">
        <div class="text-sm font-bold tracking-widest mb-12 uppercase gradient-text">ADMIN.HUB</div>
        <nav class="space-y-6">
            <a href="dashboard.php" class="block text-xs font-bold uppercase tracking-widest text-white/50 hover:text-[#0875e9] transition">Dashboard</a>
            <a href="projects.php" class="block text-xs font-bold uppercase tracking-widest text-white/50 hover:text-[#0875e9] transition">Gerenciar Projetos</a>
            <a href="leads.php" class="block text-xs font-bold uppercase tracking-widest text-white/50 hover:text-[#0875e9] transition">Leads / Contatos</a>
            <a href="settings.php" class="block text-xs font-bold uppercase tracking-widest text-[#0875e9] transition">Configurações</a>
        </nav>
    </div>

    <main class="main-content">
        <header class="flex justify-between items-center mb-12">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-white mb-2">Configurações Gerais</h1>
                <p class="text-sm text-white/30 tracking-wide uppercase">Ajuste a identidade visual e dados da empresa.</p>
            </div>
        </header>

        <?php if(isset($_GET['msg'])): ?>
            <div class="bg-blue-500/20 border border-blue-500/50 text-blue-400 p-4 mb-6 rounded text-sm">
                Configurações atualizadas com sucesso!
            </div>
        <?php endif; ?>

        <div class="max-w-2xl">
            <form action="settings.php" method="POST" class="space-y-8">
                <div class="glass-panel p-8 rounded-xl space-y-6">
                    <h3 class="text-xs font-bold uppercase tracking-[0.3em] text-white/40 mb-6 border-b border-white/5 pb-4">Identidade</h3>
                    
                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-widest text-white/40 mb-2">Nome da Empresa / Marca</label>
                        <input type="text" name="company_name" value="<?php echo htmlspecialchars($current_settings['company_name'] ?? ''); ?>" class="w-full bg-white/5 border border-white/10 py-3 px-4 focus:outline-none focus:border-[#0875e9] transition text-sm">
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-widest text-white/40 mb-2">URL da Logo (Caminho Relativo)</label>
                        <input type="text" name="company_logo" value="<?php echo htmlspecialchars($current_settings['company_logo'] ?? ''); ?>" class="w-full bg-white/5 border border-white/10 py-3 px-4 focus:outline-none focus:border-[#0875e9] transition text-sm" placeholder="/logos_clientes/Logo-CFM.png">
                    </div>
                </div>

                <div class="glass-panel p-8 rounded-xl space-y-6">
                    <h3 class="text-xs font-bold uppercase tracking-[0.3em] text-white/40 mb-6 border-b border-white/5 pb-4">Social & Links</h3>
                    
                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-widest text-white/40 mb-2">LinkedIn URL</label>
                        <input type="text" name="linkedin_url" value="<?php echo htmlspecialchars($current_settings['linkedin_url'] ?? ''); ?>" class="w-full bg-white/5 border border-white/10 py-3 px-4 focus:outline-none focus:border-[#0875e9] transition text-sm">
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-widest text-white/40 mb-2">Instagram URL</label>
                        <input type="text" name="instagram_url" value="<?php echo htmlspecialchars($current_settings['instagram_url'] ?? ''); ?>" class="w-full bg-white/5 border border-white/10 py-3 px-4 focus:outline-none focus:border-[#0875e9] transition text-sm">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-custom-gradient text-white font-bold uppercase tracking-widest text-[10px] hover:scale-105 transition duration-500 rounded-lg shadow-xl shadow-blue-500/20">
                        Salvar Todas as Alterações
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
