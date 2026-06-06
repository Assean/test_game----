<?php
/**
 * security.php - 安全防護工具
 */

class Security
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * 簡單的速率限制 (IP-based)
     * @param string $action 行動名稱 (如 'login', 'register')
     * @param int $limit 次數限制
     * @param int $seconds 時間區間 (秒)
     * @return bool 是否允許
     */
    public function checkRateLimit($action, $limit = 5, $seconds = 60)
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $currentTime = time();
        $windowStart = $currentTime - $seconds;

        // 清理過期的紀錄
        $stmt = $this->pdo->prepare("DELETE FROM rate_limits WHERE attempted_at < ?");
        $stmt->execute([$windowStart]);

        // 計算目前區間內的次數
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM rate_limits WHERE ip_address = ? AND action = ? AND attempted_at >= ?");
        $stmt->execute([$ip, $action, $windowStart]);
        $count = $stmt->fetchColumn();

        if ($count >= $limit) {
            return false;
        }

        // 紀錄本次嘗試
        $stmt = $this->pdo->prepare("INSERT INTO rate_limits (ip_address, action, attempted_at) VALUES (?, ?, ?)");
        $stmt->execute([$ip, $action, $currentTime]);

        return true;
    }
}

$security = new Security($pdo);
?>
