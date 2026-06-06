<?php
/**
 * db.php - 資料庫連線與自動遷移
 * 使用 PDO 防範 SQL Injection
 */

$db_host    = 'localhost';
$db_name    = 'test_game_db';
$db_user    = 'root';
$db_pass    = '';
$db_charset = 'utf8mb4';

$dsn     = "mysql:host=$db_host;dbname=$db_name;charset=$db_charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);

    // ===== 自動欄位遷移 (users 表) =====
    $autoColumns = [
        'points'             => "ALTER TABLE users ADD COLUMN points INT DEFAULT 0 AFTER roblox_id",
        'created_by_admin'   => "ALTER TABLE users ADD COLUMN created_by_admin TINYINT(1) DEFAULT 0 AFTER role_id",
        'session_token'      => "ALTER TABLE users ADD COLUMN session_token VARCHAR(255) NULL AFTER password",
        'is_banned'          => "ALTER TABLE users ADD COLUMN is_banned TINYINT(1) DEFAULT 0 AFTER role_id",
        'google_id'          => "ALTER TABLE users ADD COLUMN google_id VARCHAR(255) NULL UNIQUE AFTER email",
        'avatar_url'         => "ALTER TABLE users ADD COLUMN avatar_url VARCHAR(500) NULL AFTER google_id",
        'last_login_at'      => "ALTER TABLE users ADD COLUMN last_login_at TIMESTAMP NULL",
        'last_login_ip'      => "ALTER TABLE users ADD COLUMN last_login_ip VARCHAR(45) NULL",
        'email_verified'     => "ALTER TABLE users ADD COLUMN email_verified TINYINT(1) DEFAULT 0",
        'email_verify_token' => "ALTER TABLE users ADD COLUMN email_verify_token VARCHAR(255) NULL",
        'admin_email'        => "ALTER TABLE users ADD COLUMN admin_email VARCHAR(100) NULL",
        'security_code'      => "ALTER TABLE users ADD COLUMN security_code VARCHAR(100) NULL",
        'system_verified'    => "ALTER TABLE users ADD COLUMN system_verified TINYINT(1) DEFAULT 0",
    ];

    foreach ($autoColumns as $col => $sql) {
        $exists = $pdo->query("SHOW COLUMNS FROM users LIKE '$col'")->fetch();
        if (!$exists) {
            $pdo->exec($sql);
        }
    }

    // ===== ⚠️ 階梯式驗證重置 (已執行完成，故移除) =====

    // checkins 表 — streak 欄位
    $hasStreak = $pdo->query("SHOW COLUMNS FROM checkins LIKE 'streak'")->fetch();
    if (!$hasStreak) {
        try {
            $pdo->exec("ALTER TABLE checkins ADD COLUMN streak INT DEFAULT 1 AFTER points_earned");
        } catch (\PDOException $e) { /* checkins 不存在時跳過 */ }
    }

    // ===== 自動建立所有功能資料表 =====
    $pdo->exec("CREATE TABLE IF NOT EXISTS roles (
        id        INT PRIMARY KEY,
        role_name VARCHAR(50) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // 初始化角色
    $pdo->exec("INSERT INTO roles (id, role_name) VALUES
        (1,'一般使用者'),(2,'開發者'),(3,'管理員'),(4,'小幫手'),(5,'實習')
        ON DUPLICATE KEY UPDATE role_name = VALUES(role_name);");

    $pdo->exec("CREATE TABLE IF NOT EXISTS checkins (
        id           INT AUTO_INCREMENT PRIMARY KEY,
        user_id      INT  NOT NULL,
        checkin_date DATE NOT NULL,
        points_earned INT DEFAULT 100,
        streak       INT  DEFAULT 1,
        UNIQUE KEY uq_checkin (user_id, checkin_date),
        INDEX idx_checkin_date (checkin_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS messages (
        id             INT AUTO_INCREMENT PRIMARY KEY,
        sender_id      INT  NOT NULL,
        receiver_id    INT  NULL,
        content        TEXT NOT NULL,
        status         ENUM('unread','read') DEFAULT 'unread',
        is_admin_reply TINYINT(1) DEFAULT 0,
        created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_msg_sender   (sender_id),
        INDEX idx_msg_receiver (receiver_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS codes (
        id           INT AUTO_INCREMENT PRIMARY KEY,
        code         VARCHAR(50) UNIQUE NOT NULL,
        reward_type  VARCHAR(50)  NOT NULL,
        reward_value VARCHAR(255) NOT NULL,
        max_uses     INT DEFAULT 1,
        used_count   INT DEFAULT 0,
        expires_at   TIMESTAMP NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS shop_items (
        id          INT AUTO_INCREMENT PRIMARY KEY,
        name        VARCHAR(255) NOT NULL,
        description TEXT,
        price       INT          NOT NULL,
        image_url   VARCHAR(255) NULL,
        stock       INT          DEFAULT -1
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS code_redemptions (
        id          INT AUTO_INCREMENT PRIMARY KEY,
        user_id     INT NOT NULL,
        code_id     INT NOT NULL,
        redeemed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uq_user_code (user_id, code_id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (code_id) REFERENCES codes(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS community_posts (
        id         INT AUTO_INCREMENT PRIMARY KEY,
        user_id    INT  NOT NULL,
        content    TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_post_created (created_at),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS notifications (
        id         INT AUTO_INCREMENT PRIMARY KEY,
        user_id    INT          NOT NULL,
        title      VARCHAR(255) NOT NULL,
        content    TEXT         NOT NULL,
        type       ENUM('system','reward','social','admin') DEFAULT 'system',
        is_read    TINYINT(1)   DEFAULT 0,
        created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_notif_user (user_id, is_read),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS password_resets (
        id         INT AUTO_INCREMENT PRIMARY KEY,
        user_id    INT          NOT NULL,
        token      VARCHAR(255) NOT NULL UNIQUE,
        expires_at TIMESTAMP    NOT NULL,
        used       TINYINT(1)   DEFAULT 0,
        created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS login_logs (
        id         INT AUTO_INCREMENT PRIMARY KEY,
        user_id    INT         NOT NULL,
        ip_address VARCHAR(45) NOT NULL,
        user_agent TEXT,
        login_at   TIMESTAMP   DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_login_user (user_id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS admin_logs (
        id          INT AUTO_INCREMENT PRIMARY KEY,
        admin_id    INT          NOT NULL,
        action      VARCHAR(100) NOT NULL,
        target_type VARCHAR(50)  NULL,
        target_id   INT          NULL,
        details     TEXT         NULL,
        ip_address  VARCHAR(45)  NULL,
        created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_admin_log (admin_id, created_at),
        FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS rate_limits (
        id            INT AUTO_INCREMENT PRIMARY KEY,
        ip_address    VARCHAR(45) NOT NULL,
        action        VARCHAR(50) NOT NULL,
        attempted_at  INT NOT NULL,
        INDEX idx_rate (ip_address, action, attempted_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

} catch (\PDOException $e) {
    error_log('[DB ERROR] ' . $e->getMessage());
    die("資料庫連線失敗，請聯繫管理員。");
}
?>