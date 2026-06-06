<?php
/**
 * MailHelper.php - 郵件發送助手
 */

class MailHelper {
    /**
     * 發送 6 位數驗證碼
     * @param string $to 收件人信箱
     * @param string $code 6 位數驗證碼
     * @return bool 是否發送成功
     */
    public static function sendCode($to, $code) {
        $subject = "=?UTF-8?B?" . base64_encode(SMTP_FROM . " - 帳戶驗證碼") . "?=";
        
        // HTML 郵件內容
        $message = "
        <div style='font-family: sans-serif; max-width: 600px; margin: auto; padding: 40px; border: 1px solid #eee; border-radius: 20px; text-align: center; background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%); color: white;'>
            <h2 style='color: #8A2BE2;'>帳戶安全驗證</h2>
            <p style='font-size: 14px; color: #ccc;'>您好，這是您的 6 位數身份驗證碼：</p>
            <div style='font-size: 48px; font-weight: 900; letter-spacing: 10px; margin: 30px 0; color: #00f2ff; text-shadow: 0 0 10px rgba(0,242,255,0.3);'>
                $code
            </div>
            <p style='font-size: 12px; color: #666;'>此驗證碼將在 10 分鐘內失效。請勿將代碼提供給他人。</p>
            <hr style='border: none; border-top: 1px solid #333; margin: 30px 0;'>
            <p style='font-size: 10px; color: #444;'>這是系統自動發送的信件，請勿直接回覆。</p>
        </div>
        ";

        // 設定郵件標頭 (HTML & UTF-8)
        $headers  = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: <" . SMTP_USER . ">" . "\r\n";
        $headers .= "Reply-To: <" . SMTP_USER . ">" . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        // 嘗試使用 PHP 原生 mail() 發送
        // 注意：在本地 XAMPP 環境下，通常需要配置 php.ini 中的 [mail function] 或使用 sendmail
        try {
            return mail($to, $subject, $message, $headers);
        } catch (Exception $e) {
            error_log("[Mail Error] " . $e->getMessage());
            return false;
        }
    }
}
?>
