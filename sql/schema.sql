-- =============================================================
-- sql/schema.sql — TEST GAME 完整資料庫結構（最終版）
-- 更新時間：2026-04
-- =============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. 角色權限
CREATE TABLE IF NOT EXISTS roles (
    id        INT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO roles (id, role_name) VALUES
    (1, '一般使用者'),
    (2, '開發者'),
    (3, '管理員'),
    (4, '小幫手'),
    (5, '實習')
ON DUPLICATE KEY UPDATE role_name = VALUES(role_name);

-- 2. 使用者（含所有新欄位）
CREATE TABLE IF NOT EXISTS users (
    id                 INT AUTO_INCREMENT PRIMARY KEY,
    username           VARCHAR(50)  NOT NULL UNIQUE,
    email              VARCHAR(100) NOT NULL UNIQUE,
    password           VARCHAR(255) NOT NULL,
    session_token      VARCHAR(255) NULL,
    google_id          VARCHAR(255) NULL UNIQUE,
    discord_id         VARCHAR(100) NULL,
    avatar_url         VARCHAR(500) NULL,
    roblox_id          VARCHAR(50)  NULL,
    points             INT          DEFAULT 0,
    role_id            INT          DEFAULT 1,
    is_banned          TINYINT(1)   DEFAULT 0,
    created_by_admin   TINYINT(1)   DEFAULT 0,
    email_verified     TINYINT(1)   DEFAULT 0,
    email_verify_token VARCHAR(255) NULL,
    admin_email        VARCHAR(100) NULL,
    security_code      VARCHAR(100) NULL,
    last_login_at      TIMESTAMP    NULL,
    last_login_ip      VARCHAR(45)  NULL,
    created_at         TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. 意見回饋
CREATE TABLE IF NOT EXISTS feedback (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT          NULL,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(100) NOT NULL,
    message    TEXT         NOT NULL,
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. 每日簽到（含 streak 連續簽到天數）
CREATE TABLE IF NOT EXISTS checkins (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    user_id       INT  NOT NULL,
    checkin_date  DATE NOT NULL,
    points_earned INT  DEFAULT 100,
    streak        INT  DEFAULT 1,
    UNIQUE KEY uq_checkin (user_id, checkin_date),
    INDEX idx_checkin_date (checkin_date),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. 商城物品
CREATE TABLE IF NOT EXISTS shop_items (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255) NOT NULL,
    description TEXT,
    price       INT          NOT NULL,
    image_url   VARCHAR(255) NULL,
    stock       INT          DEFAULT -1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. 兌換序號
CREATE TABLE IF NOT EXISTS codes (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    code         VARCHAR(50)  UNIQUE NOT NULL,
    reward_type  VARCHAR(50)  NOT NULL,
    reward_value VARCHAR(255) NOT NULL,
    max_uses     INT          DEFAULT 1,
    used_count   INT          DEFAULT 0,
    expires_at   TIMESTAMP    NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. 序號兌換紀錄
CREATE TABLE IF NOT EXISTS code_redemptions (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    code_id     INT NOT NULL,
    redeemed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_code (user_id, code_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (code_id) REFERENCES codes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. 成就
CREATE TABLE IF NOT EXISTS achievements (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255) NOT NULL,
    description TEXT,
    icon        VARCHAR(100) NULL,
    condition_type  VARCHAR(50) NULL,
    condition_value INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. 使用者成就
CREATE TABLE IF NOT EXISTS user_achievements (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    user_id        INT NOT NULL,
    achievement_id INT NOT NULL,
    unlocked_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_ach (user_id, achievement_id),
    FOREIGN KEY (user_id)        REFERENCES users(id)        ON DELETE CASCADE,
    FOREIGN KEY (achievement_id) REFERENCES achievements(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 10. 社群貼文
CREATE TABLE IF NOT EXISTS community_posts (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT  NOT NULL,
    content    TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_post_created (created_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 11. 客服/私訊
CREATE TABLE IF NOT EXISTS messages (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    sender_id      INT  NOT NULL,
    receiver_id    INT  NULL,
    content        TEXT NOT NULL,
    status         ENUM('unread','read') DEFAULT 'unread',
    is_admin_reply TINYINT(1) DEFAULT 0,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_msg_sender   (sender_id),
    INDEX idx_msg_receiver (receiver_id),
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 12. 通知中心（新增）
CREATE TABLE IF NOT EXISTS notifications (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT          NOT NULL,
    title      VARCHAR(255) NOT NULL,
    content    TEXT         NOT NULL,
    type       ENUM('system','reward','social','admin') DEFAULT 'system',
    is_read    TINYINT(1)   DEFAULT 0,
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_notif_user (user_id, is_read),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 13. 密碼重設（新增）
CREATE TABLE IF NOT EXISTS password_resets (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT          NOT NULL,
    token      VARCHAR(255) NOT NULL UNIQUE,
    expires_at TIMESTAMP    NOT NULL,
    used       TINYINT(1)   DEFAULT 0,
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 14. 登入歷史（新增）
CREATE TABLE IF NOT EXISTS login_logs (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT         NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    login_at   TIMESTAMP   DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_login_user (user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 15. 管理員操作日誌（新增）
CREATE TABLE IF NOT EXISTS admin_logs (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 16. 頻率限制（新增）
CREATE TABLE IF NOT EXISTS rate_limits (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    ip_address    VARCHAR(45) NOT NULL,
    action        VARCHAR(50) NOT NULL,
    attempts      INT         DEFAULT 1,
    first_attempt TIMESTAMP   DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_rate (ip_address, action)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;
