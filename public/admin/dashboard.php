<?php
require_once 'config.php';
checkAuth();

// Get some stats
$stmt = $pdo->query("SELECT COUNT(*) FROM projects");
$project_count = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM leads");
$lead_count = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT * FROM leads ORDER BY created_at DESC LIMIT 5");
$recent_leads = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Admin Portfolio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #050f1e; color: #c9d1d9; }
        .glass-panel { background: rgba(22, 27, 34, 0.8); backdrop-filter: blur(10px); border: 1px solid rgba(48, 54, 61, 1); }
        .sidebar { background: #020814; border-right: 1px solid #30363d; width: 260px; height: 100vh; position: fixed; left: 0; top: 0; }
        .main-content { margin-left: 260px; padding: 40px; }
        .gradient-text { background: linear-gradient(45deg, #0875e9, #8309ee); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .bg-custom-gradient { background: linear-gradient(45deg, #0875e9, #8309ee); }
    </style>
</head>
<body>
    <div class="sidebar p-8">
        <div class="text-sm font-bold tracking-widest mb-12 uppercase gradient-text">ADMIN.HUB</div>
        <nav class="space-y-6">
            <a href="dashboard.php" class="block text-xs font-bold uppercase tracking-widest text-[#0875e9] transition">Dashboard</a>
            <a href="projects.php" class="block text-xs font-bold uppercase tracking-widest text-white/50 hover:text-[#0875e9] transition">Gerenciar Projetos</a>
            <a href="leads.php" class="block text-xs font-bold uppercase tracking-widest text-white/50 hover:text-[#0875e9] transition relative">
                Leads / Contatos 
                <?php if ($lead_count > 0): ?>
                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xxs font-medium bg-custom-gradient text-white"><?php echo $lead_count; ?></span>
                <?php endif; ?>
            </a>
            <a href="settings.php" class="block text-xs font-bold uppercase tracking-widest text-white/50 hover:text-[#0875e9] transition">Configurações</a>
            <div class="pt-10 border-t border-white/5">
                <a href="logout.php" class="text-xs font-bold uppercase tracking-widest text-red-500/50 hover:text-red-500 transition">Sair</a>
            </div>
        </nav>
    </div>

    <main class="main-content">
        <header class="flex justify-between items-center mb-12">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-white mb-2">Painel de Controle</h1>
                <p class="text-sm text-white/30 tracking-wide uppercase">Visão geral do seu ecossistema digital.</p>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-xs text-white/30 uppercase font-bold tracking-widest">Olá, <?php echo $_SESSION['username'] ?? 'Especialista'; ?></span>
                <div class="w-10 h-10 rounded-full bg-custom-gradient border border-black shadow-lg"></div>
            </div>
        </header>

        <section class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="glass-panel p-8 rounded-xl shadow-2xl relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 text-white/5 group-hover:text-white/10 transition text-7xl font-bold italic">PRO</div>
                <h3 class="text-xs font-bold uppercase tracking-[0.3em] text-white/40 mb-2">Total de Projetos</h3>
                <div class="text-5xl font-bold text-white mb-4"><?php echo $project_count; ?></div>
                <a href="projects.php" class="text-xs font-bold text-[#0875e9] uppercase tracking-widest hover:text-white transition">Gerenciar &rarr;</a>
            </div>
            
            <div class="glass-panel p-8 rounded-xl shadow-2xl relative overflow-hidden group">
                 <div class="absolute -right-4 -bottom-4 text-white/5 group-hover:text-white/10 transition text-7xl font-bold italic">LEA</div>
                <h3 class="text-xs font-bold uppercase tracking-[0.3em] text-white/40 mb-2">Novos Leads</h3>
                <div class="text-5xl font-bold text-[#0875e9] mb-4"><?php echo $lead_count; ?></div>
                <a href="leads.php" class="text-xs font-bold text-white/50 uppercase tracking-widest hover:text-white transition">Ver Todos &rarr;</a>
            </div>

            <div class="glass-panel p-8 rounded-xl shadow-2xl relative overflow-hidden group">
                 <div class="absolute -right-4 -bottom-4 text-white/5 group-hover:text-white/10 transition text-7xl font-bold italic">AUT</div>
                <h3 class="text-xs font-bold uppercase tracking-[0.3em] text-white/40 mb-2">Status Automação</h3>
                <div class="text-xl font-bold text-green-500 mb-4 flex items-center">
                    <span class="w-3 h-3 bg-green-500 rounded-full mr-3 animate-pulse"></span>
                    n8n Ativo
                </div>
                <p class="text-[10px] text-white/30 uppercase tracking-widest">Fluxo de webhook operacional.</p>
            </div>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="glass-panel p-8 rounded-xl">
                 <h3 class="text-xs font-bold uppercase tracking-[0.3em] text-white/40 mb-8 border-b border-white/5 pb-4">Leads Recentes</h3>
                 <?php if (empty($recent_leads)): ?>
                    <p class="text-center py-10 text-white/20 italic text-sm">Nenhum lead recebido ainda.</p>
                 <?php else: ?>
                    <div class="space-y-6">
                        <?php foreach($recent_leads as $lead): ?>
                            <div class="flex justify-between items-start border-b border-white/5 pb-4 last:border-0 hover:bg-white/5 transition px-2 py-2 rounded">
                                <div>
                                    <h4 class="text-sm font-bold text-white"><?php echo htmlspecialchars($lead['name']); ?></h4>
                                    <p class="text-xs text-white/30"><?php echo htmlspecialchars($lead['email']); ?></p>
                                </div>
                                <div class="text-[10px] text-white/20 font-mono">
                                    <?php echo date('d/m H:i', strtotime($lead['created_at'])); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                 <?php endif; ?>
            </div>

            <div class="glass-panel p-8 rounded-xl relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-tr from-yellow-700/5 to-transparent"></div>
                <h3 class="text-xs font-bold uppercase tracking-[0.3em] text-white/40 mb-8 border-b border-white/5 pb-4">Acesso Rápido</h3>
                <div class="grid grid-cols-2 gap-4 relative z-10">
                    <button onclick="location.href='projects.php'" class="p-6 bg-white/5 border border-white/5 rounded-lg text-center hover:bg-white/10 hover:border-[#0875e9] transition">
                        <div class="text-2xl mb-2">📁</div>
                        <div class="text-[10px] font-bold uppercase tracking-widest">Novo Projeto</div>
                    </button>
                    <button onclick="location.href='settings.php'" class="p-6 bg-white/5 border border-white/5 rounded-lg text-center hover:bg-white/10 hover:border-[#0875e9] transition">
                        <div class="text-2xl mb-2">⚙️</div>
                        <div class="text-[10px] font-bold uppercase tracking-widest">Configurações</div>
                    </button>
                    <button class="p-6 bg-white/5 border border-white/5 rounded-lg text-center hover:bg-white/10 hover:border-[#d4af37] transition">
                        <div class="text-2xl mb-2">🎨</div>
                        <div class="text-[10px] font-bold uppercase tracking-widest">Customizar UI</div>
                    </button>
                    <button class="p-6 bg-white/5 border border-white/5 rounded-lg text-center hover:bg-white/10 hover:border-[#d4af37] transition">
                        <div class="text-2xl mb-2">📊</div>
                        <div class="text-[10px] font-bold uppercase tracking-widest">Audit Logs</div>
                    </button>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
