<?php
/**
 * validator.php — 統一輸入驗證器（優化 2）
 */

class Validator
{
    private array $errors = [];

    // Email 格式驗證
    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // 用戶名驗證：3-30 字元，英數字底線橫線
    public static function validateUsername(string $username): bool
    {
        return preg_match('/^[a-zA-Z0-9_\-\x{4e00}-\x{9fff}]{3,30}$/u', $username) === 1;
    }

    // 密碼強度驗證：至少 8 字元，含大小寫和數字
    public static function validatePassword(string $password): bool
    {
        return strlen($password) >= 8
            && preg_match('/[A-Z]/', $password)
            && preg_match('/[a-z]/', $password)
            && preg_match('/[0-9]/', $password);
    }

    // 統一輸入清理
    public static function sanitize(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    // 整數驗證
    public static function validateInt(mixed $value, int $min = PHP_INT_MIN, int $max = PHP_INT_MAX): bool
    {
        $v = filter_var($value, FILTER_VALIDATE_INT);
        return $v !== false && $v >= $min && $v <= $max;
    }

    // 取得密碼強度訊息
    public static function passwordStrengthMessage(string $password): string
    {
        if (strlen($password) < 8)    return '密碼至少需要 8 個字元';
        if (!preg_match('/[A-Z]/', $password)) return '密碼必須包含至少一個大寫字母';
        if (!preg_match('/[a-z]/', $password)) return '密碼必須包含至少一個小寫字母';
        if (!preg_match('/[0-9]/', $password)) return '密碼必須包含至少一個數字';
        return '';
    }

    // 批次驗證 (fields => rules)
    public function validate(array $data, array $rules): bool
    {
        $this->errors = [];
        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? '';
            foreach ($fieldRules as $rule) {
                switch ($rule) {
                    case 'required':
                        if (empty($value)) $this->errors[$field] = "「$field」為必填欄位";
                        break;
                    case 'email':
                        if (!self::validateEmail($value)) $this->errors[$field] = 'Email 格式不正確';
                        break;
                    case 'username':
                        if (!self::validateUsername($value)) $this->errors[$field] = '用戶名格式不正確（3-30 字元）';
                        break;
                    case 'password':
                        $msg = self::passwordStrengthMessage($value);
                        if ($msg) $this->errors[$field] = $msg;
                        break;
                }
            }
        }
        return empty($this->errors);
    }

    public function getErrors(): array { return $this->errors; }
    public function firstError(): string { return array_values($this->errors)[0] ?? ''; }
}
?>
