<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        redirect('/admin/dashboard.php');
    } else {
        $error = "Credenciais inválidas";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Portfolio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0a0a0a; color: white; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-10 bg-white/5 border border-white/10 backdrop-blur-xl">
        <div class="text-[#d4af37] text-xl font-bold tracking-widest mb-10 text-center uppercase">PORTFOLIO.ADMIN</div>
        
        <?php if (isset($error)): ?>
            <div class="bg-red-900/20 border border-red-500/50 text-red-500 p-4 mb-6 text-sm">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="space-y-6">
            <div>
                <label class="block text-[10px] uppercase font-bold tracking-widest text-white/40 mb-2">Usuário</label>
                <input type="text" name="username" required class="w-full bg-white/5 border border-white/10 py-3 px-4 focus:outline-none focus:border-[#d4af37] transition text-sm text-white">
            </div>
            <div>
                <label class="block text-[10px] uppercase font-bold tracking-widest text-white/40 mb-2">Senha</label>
                <input type="password" name="password" required class="w-full bg-white/5 border border-white/10 py-3 px-4 focus:outline-none focus:border-[#d4af37] transition text-sm text-white">
            </div>
            <button type="submit" class="w-full py-4 bg-[#d4af37] text-black font-bold uppercase tracking-widest text-xs hover:bg-white transition duration-500">
                Acessar Dashboard
            </button>
        </form>
        <div class="mt-8 text-center text-[10px] text-white/20 uppercase tracking-[0.2em]">
            Layman-Proof Dashboard &middot; 2026
        </div>
    </div>
</body>
</html>
