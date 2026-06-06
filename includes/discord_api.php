<?php
/**
 * discord_api.php - Discord OAuth2 與 Webhook 串接
 */

class DiscordAPI
{
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $webhookUrl;

    public function __construct($clientId, $clientSecret, $redirectUri, $webhookUrl)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
        $this->webhookUrl = $webhookUrl;
    }

    /**
     * 獲取 Discord 登入 URL
     */
    public function getLoginUrl($state = '')
    {
        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => 'identify email guilds'
        ];
        if ($state) {
            $params['state'] = $state;
        }
        return "https://discord.com/api/oauth2/authorize?" . http_build_query($params);
    }

    /**
     * 使用 Code 交換 Access Token
     */
    public function getAccessToken($code)
    {
        $data = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->redirectUri
        ];

        return $this->sendRequest("https://discord.com/api/oauth2/token", 'POST', http_build_query($data), [
            'Content-Type: application/x-www-form-urlencoded'
        ]);
    }

    /**
     * 獲取使用者資訊
     */
    public function getUserDetails($accessToken)
    {
        return $this->sendRequest("https://discord.com/api/users/@me", 'GET', null, [
            "Authorization: Bearer $accessToken"
        ]);
    }

    /**
     * 發送 Webhook 訊息
     */
    public function sendWebhook($content, $username = 'Website Bot')
    {
        $data = [
            'content' => $content,
            'username' => $username
        ];

        return $this->sendRequest($this->webhookUrl, 'POST', json_encode($data), [
            'Content-Type: application/json'
        ]);
    }

    private function sendRequest($url, $method = 'GET', $payload = null, $headers = [])
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($payload) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
?>