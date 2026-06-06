<?php
/**
 * auth_logic.php - 核心登入、註冊與權限邏輯
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/db.php';

class Auth
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * 使用者註冊
     */
    public function register($username, $email, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        try {
            return $stmt->execute([$username, $email, $hashedPassword]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * 使用者登入
     */
    public function login($email, $password)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            if ($user['is_banned']) {
                return "BANNED";
            }
            $this->setSession($user);
            return true;
        }
        return false;
    }

    /**
     * 設定 Session
     */
    public function setSession($user)
    {
        // 每次登入產生新 Token
        $token = bin2hex(random_bytes(16));
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        
        $stmt = $this->pdo->prepare("UPDATE users SET session_token = ?, last_login_at = NOW(), last_login_ip = ? WHERE id = ?");
        $stmt->execute([$token, $ip, $user['id']]);

        // 新功能 6：登入歷史紀錄
        $stmt = $this->pdo->prepare("INSERT INTO login_logs (user_id, ip_address, user_agent) VALUES (?, ?, ?)");
        $stmt->execute([$user['id'], $ip, $ua]);

        session_regenerate_id(true); // 安全防護：重新生成 Session ID
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['session_token'] = $token;
        $_SESSION['discord_id'] = $user['discord_id'] ?? null;
        $_SESSION['roblox_id'] = $user['roblox_id'] ?? null;
        $_SESSION['avatar_url'] = $user['avatar_url'] ?? null;
        $_SESSION['email_verified'] = (bool)($user['email_verified'] ?? 0);
    }

    /**
     * 檢查 Session 狀態 (封鎖或強制登出)
     */
    public static function checkSessionStatus($pdo)
    {
        if (!isset($_SESSION['user_id']))
            return;

        $stmt = $pdo->prepare("SELECT is_banned, session_token FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        if (!$user || $user['is_banned'] || $user['session_token'] !== ($_SESSION['session_token'] ?? '')) {
            session_unset();
            session_destroy();
            header("Location: ../login.php?error=invalid_session");
            exit;
        }
    }

    /**
     * 登出
     */
    public function logout()
    {
        session_unset();
        session_destroy();
    }

    /**
     * 檢查是否已登入
     */
    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * 檢查是否為管理員
     */
    public static function isAdmin()
    {
        return isset($_SESSION['role_id']) && $_SESSION['role_id'] == ROLE_ADMIN;
    }

    /**
     * Google 登入或註冊
     */
    public function loginWithGoogle($googleId, $email, $username)
    {
        // 1. 檢查 Google ID 是否已存在
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE google_id = ?");
        $stmt->execute([$googleId]);
        $user = $stmt->fetch();

        if ($user) {
            if ($user['is_banned']) return "BANNED";
            $this->setSession($user);
            return true;
        }

        // 2. 檢查 Email 是否已存在 (關聯現有帳號)
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // 綁定 Google ID 到現有帳號
            $stmt = $this->pdo->prepare("UPDATE users SET google_id = ? WHERE id = ?");
            $stmt->execute([$googleId, $user['id']]);
            $user['google_id'] = $googleId;
            $this->setSession($user);
            return true;
        }

        // 3. 創建新帳號
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, google_id, password) VALUES (?, ?, ?, ?)");
        $randomPass = bin2hex(random_bytes(16)); // 隨機密碼
        $hashedPass = password_hash($randomPass, PASSWORD_DEFAULT);
        
        try {
            $stmt->execute([$username, $email, $googleId, $hashedPass]);
            $newId = $this->pdo->lastInsertId();
            
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$newId]);
            $this->setSession($stmt->fetch());
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * 綁定 Discord ID
     */
    public function linkDiscord($userId, $discordId)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET discord_id = ? WHERE id = ?");
        $res = $stmt->execute([$discordId, $userId]);
        if ($res) {
            $_SESSION['discord_id'] = $discordId;
        }
        return $res;
    }

    /**
     * 綁定 Roblox ID
     */
    public function bindRoblox($userId, $robloxId)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET roblox_id = ? WHERE id = ?");
        $res = $stmt->execute([$robloxId, $userId]);
        if ($res) {
            $_SESSION['roblox_id'] = $robloxId;
        }
        return $res;
    }

    /**
     * CSRF 安全防護 - 產生 Token
     */
    public static function generateCsrfToken()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * CSRF 安全防護 - 驗證 Token
     */
    public static function verifyCsrfToken($token)
    {
        return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}

$auth = new Auth($pdo);

// 全域 Session 狀態監控 (封鎖、強制登出檢測)
if (Auth::isLoggedIn()) {
    Auth::checkSessionStatus($pdo);
}
?>