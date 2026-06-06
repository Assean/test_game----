<?php
/**
 * error_handler.php — 全域錯誤處理（優化 1）
 * 統一記錄錯誤日誌，對使用者只顯示友善訊息
 */

// 從 config.php 取得 DEBUG_MODE (若尚未定義則預設 false)
if (!defined('DEBUG_MODE')) {
    define('DEBUG_MODE', false);
}

/**
 * 記錄錯誤並視情況顯示
 */
function logError(Throwable $e, string $context = ''): void
{
    $msg = sprintf(
        "[%s] %s | File: %s | Line: %d | Context: %s",
        date('Y-m-d H:i:s'),
        $e->getMessage(),
        $e->getFile(),
        $e->getLine(),
        $context ?: 'general'
    );
    error_log($msg);
}

/**
 * 輸出 JSON 錯誤 (for AJAX handlers)
 */
function jsonError(string $userMessage = '系統忙碌中，請稍後再試', \Throwable $e = null, string $context = ''): void
{
    if ($e) {
        logError($e, $context);
        if (DEBUG_MODE) {
            $userMessage = $e->getMessage();
        }
    }
    echo json_encode(['success' => false, 'message' => $userMessage]);
    exit;
}

/**
 * 重導並顯示錯誤 (for form handlers)
 */
function redirectError(string $location, string $errorCode = 'system', \Throwable $e = null, string $context = ''): void
{
    if ($e) {
        logError($e, $context);
    }
    header("Location: $location?error=$errorCode");
    exit;
}

/**
 * 設定 PHP 全域錯誤處理
 */
set_error_handler(function (int $errno, string $errstr, string $errfile, int $errline): bool {
    if (!(error_reporting() & $errno)) {
        return false;
    }
    $msg = "PHP Error [$errno]: $errstr in $errfile on line $errline";
    error_log($msg);
    return true;
});

set_exception_handler(function (\Throwable $e): void {
    logError($e, 'uncaught_exception');
    if (DEBUG_MODE) {
        echo "<pre style='color:red'>" . htmlspecialchars($e->getMessage()) . "</pre>";
    } else {
        http_response_code(500);
        echo "伺服器發生錯誤，請稍後再試。";
    }
});
?>
