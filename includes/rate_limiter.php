<?php
/**
 * rate_limiter.php — 頻率限制類別（新功能 9 / 優化版）
 * 使用資料庫 rate_limits 資料表持久化追蹤
 */

class RateLimiter
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * 檢查頻率限制
     * @param string $ip         來源 IP
     * @param string $action     動作識別碼 (login / register / redeem / api)
     * @param int    $maxAttempts 最大嘗試次數
     * @param int    $windowSecs  時間窗口（秒）
     * @return bool true = 允許通過, false = 超過限制
     */
    public function checkLimit(string $ip, string $action, int $maxAttempts, int $windowSecs): bool
    {
        $now  = time();
        $from = $now - $windowSecs;

        // 清理過期紀錄
        $this->pdo->prepare("DELETE FROM rate_limits WHERE first_attempt < FROM_UNIXTIME(?)")
                  ->execute([$from]);

        // 查詢現有紀錄
        $stmt = $this->pdo->prepare(
            "SELECT id, attempts FROM rate_limits WHERE ip_address = ? AND action = ? AND first_attempt >= FROM_UNIXTIME(?)"
        );
        $stmt->execute([$ip, $action, $from]);
        $row = $stmt->fetch();

        if (!$row) {
            // 首次嘗試 — 建立紀錄
            $this->pdo->prepare("INSERT INTO rate_limits (ip_address, action, attempts) VALUES (?, ?, 1)")
                      ->execute([$ip, $action]);
            return true;
        }

        if ($row['attempts'] >= $maxAttempts) {
            return false; // 超過限制
        }

        // 更新嘗試次數
        $this->pdo->prepare("UPDATE rate_limits SET attempts = attempts + 1 WHERE id = ?")
                  ->execute([$row['id']]);
        return true;
    }

    /**
     * 重置某 IP 的特定動作限制（登入成功後使用）
     */
    public function resetLimit(string $ip, string $action): void
    {
        $this->pdo->prepare("DELETE FROM rate_limits WHERE ip_address = ? AND action = ?")
                  ->execute([$ip, $action]);
    }
}

// 全域實例
$rateLimiter = new RateLimiter($pdo);
?>
