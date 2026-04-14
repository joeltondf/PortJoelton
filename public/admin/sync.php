<?php
if (!defined('BASE_URL')) exit;
checkAuth();

$ran = false;
$syncOutput = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['run_sync'])) {
    $ran = true;
    ob_start();
    require __DIR__ . '/../../scripts/auto_index.php';
    $syncOutput = ob_get_clean();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sincronização de Imagens | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; background: #050f1e; color: white; }</style>
</head>
<body class="min-h-screen p-10">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-10 flex items-center justify-between">
            <div>
                <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" class="text-white/40 hover:text-white text-xs uppercase tracking-widest font-bold mb-4 block">← Voltar ao Dashboard</a>
                <h1 class="text-4xl font-black tracking-tighter">Auto-Indexer</h1>
                <p class="text-white/40 text-sm mt-2">Sincronize as imagens de <code class="bg-white/10 px-2 py-0.5 rounded text-xs">public/images/</code> com o banco de dados.</p>
            </div>
        </div>

        <!-- Info Box -->
        <div class="glass-card p-6 mb-8 border border-white/10 rounded-lg bg-white/5">
            <h2 class="text-sm font-bold uppercase tracking-widest text-white/50 mb-4">Como funciona</h2>
            <ul class="space-y-2 text-sm text-white/60">
                <li>✓ Varre todas as subpastas de <code class="text-blue-400">public/images/</code></li>
                <li>✓ Ignora: <code class="text-orange-400">assets, uploads, logos_clientes, projects</code></li>
                <li>✓ Cria projetos novos com título = nome da pasta</li>
                <li>✓ Imagem com "capa" no nome → definida como capa do projeto</li>
                <li>✓ Gera slugs automáticos (URL amigável)</li>
                <li>✓ Nunca sobrescreve dados editados manualmente</li>
            </ul>
        </div>

        <!-- Form -->
        <form method="POST" class="mb-10">
            <input type="hidden" name="run_sync" value="1">
            <button type="submit" class="w-full py-5 bg-white text-black font-black uppercase tracking-widest text-sm hover:bg-gray-200 transition rounded-none">
                🔄 Executar Sincronização
            </button>
        </form>

        <!-- Output -->
        <?php if ($ran): ?>
        <div class="border border-white/10 rounded-lg overflow-hidden">
            <div class="bg-black/40 px-6 py-4 border-b border-white/10">
                <h2 class="text-sm font-bold uppercase tracking-widest text-white/50">Log de Execução</h2>
            </div>
            <div class="p-6 bg-[#020814]">
                <ul class="space-y-1 text-sm font-mono text-green-400 list-none">
                    <?php echo $syncOutput ?? '<li class="text-red-400">Nenhum output gerado.</li>'; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
