<?php
require_once __DIR__ . '/../includes/auth_logic.php';
require_once __DIR__ . '/../templates/header.php';

if (!Auth::isLoggedIn()) {
    header("Location: ../login.php");
    exit;
}

// Fetch shop items
// Fetch user points
$stmt = $pdo->prepare("SELECT points FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user_points = $stmt->fetchColumn();

// Fetch shop items
$stmt = $pdo->query("SELECT * FROM shop_items ORDER BY price ASC");
$items = $stmt->fetchAll();

// Mock items if empty (for demonstration)
if (!$items) {
    $items = [
        ['id' => 1, 'name' => '1,000 $TEST Coin', 'description' => '基本遊戲後備虛擬幣', 'price' => 500, 'icon' => '💰'],
        ['id' => 2, 'name' => 'Neon Katana Skin', 'description' => '絕版稀有武器塗裝', 'price' => 2500, 'icon' => '⚔️'],
        ['id' => 3, 'name' => '10,000 $TEST Coin', 'description' => '大額虛擬幣包', 'price' => 4500, 'icon' => '💎'],
    ];
}
?>

<main class="pt-32 pb-24 px-[5%]">
    <div class="max-w-[1200px] mx-auto">
        <div class="flex justify-between items-end mb-16 animate-fade-in-down">
            <input type="hidden" id="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
            <div>
                <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">積分 <span
                        class="text-secondary">商城</span></h2>
                <p class="text-white/40 text-xs tracking-widest uppercase">Rewards & Marketplace</p>
            </div>
            <div class="bg-white/5 border border-white/10 px-6 py-4 rounded-2xl">
                <span class="text-white/40 text-[10px] font-black uppercase block mb-1">您的餘額</span>
                <span class="text-2xl font-black text-secondary" id="user-points"><?php echo number_format($user_points); ?> PTS</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($items as $item): ?>
                <div
                    class="group bg-white/5 border border-white/10 rounded-[30px] p-8 hover:border-secondary transition-all animate-fade-in-up">
                    <div
                        class="h-32 bg-white/5 rounded-2xl flex items-center justify-center text-5xl mb-6 group-hover:scale-110 transition-transform">
                        <?php echo $item['icon'] ?? '🎁'; ?>
                    </div>
                    <h3 class="text-xl font-bold mb-2">
                        <?php echo htmlspecialchars($item['name']); ?>
                    </h3>
                    <p class="text-sm text-white/40 mb-8">
                        <?php echo htmlspecialchars($item['description']); ?>
                    </p>
                    <div class="flex items-center justify-between mt-auto">
                        <span class="text-2xl font-black text-white">
                            <?php echo number_format($item['price']); ?> <span
                                class="text-[10px] text-white/30 uppercase">PTS</span>
                        </span>
                        <button
                            onclick="buyItem(<?php echo $item['id']; ?>, '<?php echo htmlspecialchars($item['name']); ?>')"
                            class="px-6 py-2 bg-secondary text-bg-dark text-xs font-black uppercase rounded-full hover:scale-105 transition-all">兌換</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<script>
async function buyItem(id, name) {
    if(!confirm(`確定要購買 ${name} 嗎？`)) return;
    
    const csrf = document.getElementById('csrf_token').value;
    const formData = new FormData();
    formData.append('item_id', id);
    formData.append('csrf_token', csrf);
    
    try {
        const response = await fetch('../auth/shop_handler.php', {
            method: 'POST',
            body: formData
        });
        const res = await response.json();
        
        if(res.success) {
            alert('購買成功！');
            document.getElementById('user-points').innerText = Number(res.new_balance).toLocaleString() + ' PTS';
        } else {
            alert('錯誤: ' + res.message);
        }
    } catch(err) {
        alert('網路錯誤');
    }
}
</script>

<?php include '../templates/footer.php'; ?>