<?php
/**
 * migrate_db.php - 自動修復資料庫結構
 * 請在瀏覽器執行此檔案一次：http://localhost/其他1/test_game遊戲官網/migrate_db.php
 */

require_once __DIR__ . '/includes/db.php';

try {
    echo "<h2>資料庫結構修復中...</h2>";

    // 1. 為 users 表格新增 points 欄位
    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN points INT DEFAULT 0 AFTER roblox_id");
        echo "✅ 已新增 'points' 欄位至 users 表格。<br>";
    } catch (PDOException $e) {
        if ($e->getCode() == '42S21') {
            echo "ℹ️ 'points' 欄位已存在，跳過。<br>";
        } else {
            throw $e;
        }
    }

    // 2. 建立 news 表格
    $sql_news = "CREATE TABLE IF NOT EXISTS news (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        category VARCHAR(50) DEFAULT 'System',
        content TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;";
    $pdo->exec($sql_news);
    echo "✅ 'news' 表格已確認/建立。<br>";

    // 3. 建立 10 大功能的其餘表格
    $sql_features = "
    CREATE TABLE IF NOT EXISTS checkins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        checkin_date DATE NOT NULL,
        points_earned INT DEFAULT 100,
        UNIQUE KEY (user_id, checkin_date),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS shop_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        price INT NOT NULL,
        image_url VARCHAR(255),
        stock INT DEFAULT -1
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS codes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        code VARCHAR(50) UNIQUE NOT NULL,
        reward_type VARCHAR(50) NOT NULL,
        reward_value VARCHAR(255) NOT NULL,
        max_uses INT DEFAULT 1,
        used_count INT DEFAULT 0,
        expires_at TIMESTAMP NULL
    ) ENGINE=InnoDB;
    ";
    $pdo->exec($sql_features);
    // 4. 為 users 表格新增 admin_email 與 security_code 欄位 (二次驗證用)
    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN admin_email VARCHAR(100) AFTER email");
        echo "✅ 已新增 'admin_email' 欄位至 users 表格。<br>";
    } catch (PDOException $e) {
        if ($e->getCode() == '42S21') {
            echo "ℹ️ 'admin_email' 欄位已存在，跳過。<br>";
        } else {
            throw $e;
        }
    }

    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN security_code VARCHAR(100) AFTER admin_email");
        echo "✅ 已新增 'security_code' 欄位至 users 表格。<br>";
    } catch (PDOException $e) {
        if ($e->getCode() == '42S21') {
            echo "ℹ️ 'security_code' 欄位已存在，跳過。<br>";
        } else {
            throw $e;
        }
    }

    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN created_by_admin TINYINT(1) DEFAULT 0 AFTER role_id");
        echo "✅ 已新增 'created_by_admin' 欄位至 users 表格。<br>";
    } catch (PDOException $e) {
        if ($e->getCode() == '42S21') {
            echo "ℹ️ 'created_by_admin' 欄位已存在，跳過。<br>";
        } else {
            throw $e;
        }
    }

    // 5. 插入指定管理員帳號
    $admins = [
        [
            'username' => 'sean_admin',
            'email' => 'sean_backup@example.com',
            'admin_email' => 'sean@game.web.com',
            'security_code' => 'y10700011',
            'password' => password_hash('Aa123456_', PASSWORD_DEFAULT),
            'role_id' => 3
        ],
        [
            'username' => 'chanray',
            'email' => 'chanray_backup@example.com',
            'admin_email' => 'ray@game.web.com',
            'security_code' => 'ray0804',
            'password' => password_hash('chan_ray', PASSWORD_DEFAULT),
            'role_id' => 3
        ]
    ];

    foreach ($admins as $adm) {
        $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $check->execute([$adm['username']]);
        $existing = $check->fetch();
        if (!$existing) {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, admin_email, security_code, password, role_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$adm['username'], $adm['email'], $adm['admin_email'], $adm['security_code'], $adm['password'], $adm['role_id']]);
            echo "✅ 管理員帳號 '{$adm['username']}' 已建立。<br>";
        } else {
            // 更新密碼與資料
            $stmt = $pdo->prepare("UPDATE users SET password = ?, admin_email = ?, security_code = ?, role_id = ? WHERE username = ?");
            $stmt->execute([$adm['password'], $adm['admin_email'], $adm['security_code'], $adm['role_id'], $adm['username']]);
            echo "✅ 管理員帳號 '{$adm['username']}' 資料已更新。<br>";
        }
    }

    // 6. 建立 messages 表格 (客服系統)
    $sql_messages = "CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        sender_id INT NOT NULL,
        receiver_id INT DEFAULT NULL, -- NULL 表示發送給全體管理員/客服
        content TEXT NOT NULL,
        status ENUM('unread', 'read') DEFAULT 'unread',
        is_admin_reply TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB;";
    $pdo->exec($sql_messages);
    // 7. 更新/建立 roles 表格與權限
    $pdo->exec("CREATE TABLE IF NOT EXISTS roles (
        id INT PRIMARY KEY,
        role_name VARCHAR(50) NOT NULL
    ) ENGINE=InnoDB;");

    $roles_list = [
        [1, '一般使用者'],
        [2, '開發者'],
        [3, '管理員'],
        [4, '小幫手'],
        [5, '實習']
    ];

    foreach ($roles_list as $r) {
        $stmt = $pdo->prepare("INSERT INTO roles (id, role_name) VALUES (?, ?) ON DUPLICATE KEY UPDATE role_name = ?");
        $stmt->execute([$r[0], $r[1], $r[1]]);
    }
    echo "✅ 'roles' 表格與權限等級已初始化。<br>";

    echo "<br><h3 style='color:green'>修復完成！現在請重新整理網頁即可正常運作。</h3>";
    echo "<p>基於安全考量，修復完畢後建議刪除此檔案 (migrate_db.php)。</p>";

} catch (PDOException $e) {
    die("<h3 style='color:red'>錯誤：" . $e->getMessage() . "</h3>");
}
?>