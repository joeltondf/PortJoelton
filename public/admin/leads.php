<?php
require_once 'config.php';
checkAuth();

// Fetch all leads
$stmt = $pdo->query("SELECT * FROM leads ORDER BY created_at DESC");
$leads = $stmt->fetchAll();

// Mark specifically seen ? maybe not necessary for this scope, let's just show them.
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leads & Hub de Contatos | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #050f1e; color: #c9d1d9; }
        .glass-panel { background: rgba(22, 27, 34, 0.8); backdrop-filter: blur(10px); border: 1px solid rgba(48, 54, 61, 1); }
        .sidebar { background: #020814; border-right: 1px solid #30363d; width: 260px; height: 100vh; position: fixed; left: 0; top: 0; z-index: 50;}
        .main-content { margin-left: 260px; padding: 40px; }
        .gradient-text { background: linear-gradient(45deg, #0875e9, #8309ee); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .bg-custom-gradient { background: linear-gradient(45deg, #0875e9, #8309ee); }
    </style>
</head>
<body>
    <div class="sidebar p-8">
        <div class="text-sm font-bold tracking-widest mb-12 uppercase gradient-text">ADMIN.HUB</div>
        <nav class="space-y-6">
            <a href="dashboard.php" class="block text-xs font-bold uppercase tracking-widest text-white/50 hover:text-[#0875e9] transition">Dashboard</a>
            <a href="projects.php" class="block text-xs font-bold uppercase tracking-widest text-white/50 hover:text-[#0875e9] transition">Gerenciar Projetos</a>
            <a href="leads.php" class="block text-xs font-bold uppercase tracking-widest text-[#0875e9] transition">Leads / Contatos</a>
            <a href="settings.php" class="block text-xs font-bold uppercase tracking-widest text-white/50 hover:text-[#0875e9] transition">Configurações</a>
        </nav>
    </div>

    <main class="main-content">
        <header class="flex justify-between items-center mb-12">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-white mb-2">Mensagens & Leads</h1>
                <p class="text-sm text-white/30 tracking-wide uppercase">Histórico de contatos enviados através do portfólio.</p>
            </div>
        </header>

        <div class="glass-panel overflow-hidden rounded-xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/5 border-b border-white/10">
                        <th class="p-4 text-xs font-bold uppercase tracking-widest text-white/40">Data/Hora</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-widest text-white/40">Nome</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-widest text-white/40">E-mail</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-widest text-white/40">Mensagem</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-widest text-white/40 text-center">n8n Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($leads)): ?>
                    <tr><td colspan="5" class="p-8 text-center text-white/20 italic text-sm">Nenhum contato recebido ainda.</td></tr>
                    <?php else: ?>
                        <?php foreach($leads as $lead): ?>
                        <tr class="border-b border-white/5 hover:bg-white/5 transition">
                            <td class="p-4 font-mono text-[10px] text-white/40 whitespace-nowrap">
                                <?php echo date('d/m/Y H:i', strtotime($lead['created_at'])); ?>
                            </td>
                            <td class="p-4 font-bold text-white"><?php echo htmlspecialchars($lead['name']); ?></td>
                            <td class="p-4">
                                <a href="mailto:<?php echo htmlspecialchars($lead['email']); ?>" class="text-[#0875e9] hover:underline text-sm font-bold">
                                    <?php echo htmlspecialchars($lead['email']); ?>
                                </a>
                            </td>
                            <td class="p-4">
                                <div class="text-sm text-white/70 max-w-md overflow-hidden bg-white/5 p-3 rounded" style="max-height: 100px; overflow-y: auto;">
                                    <?php echo nl2br(htmlspecialchars($lead['message'])); ?>
                                </div>
                            </td>
                            <td class="p-4 text-center">
                                <?php 
                                    $statusColor = 'text-gray-500';
                                    if ($lead['n8n_status'] === 'sent') $statusColor = 'text-green-500';
                                    if ($lead['n8n_status'] === 'error') $statusColor = 'text-red-500';
                                ?>
                                <span class="text-[10px] uppercase font-bold tracking-widest <?php echo $statusColor; ?>">
                                    <?php echo htmlspecialchars($lead['n8n_status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
