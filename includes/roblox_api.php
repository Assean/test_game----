<?php
/**
 * roblox_api.php - Roblox Open Cloud API 串接邏輯
 */

class RobloxAPI
{
    private $apiKey;
    private $universeId;

    public function __construct($apiKey, $universeId)
    {
        $this->apiKey = $apiKey;
        $this->universeId = $universeId;
    }

    /**
     * 發送 Messaging Service 訊息 (全服廣播或特定事件)
     */
    public function publishMessage($topic, $data)
    {
        $url = "https://apis.roblox.com/messaging-service/v1/universes/{$this->universeId}/topics/{$topic}";

        $payload = json_encode(['message' => json_encode($data)]);

        return $this->sendRequest($url, 'POST', $payload);
    }

    /**
     * 更新 DataStore 數值 (例如發放貨幣)
     * 注意：這通常需要對接遊戲端自定義的 DataStore Key
     */
    public function updateDataStore($datastoreName, $entryKey, $newValue)
    {
        $url = "https://apis.roblox.com/datastores/v1/universes/{$this->universeId}/standard-datastores/datastore/entries/entry";
        $url .= "?datastoreName=" . urlencode($datastoreName) . "&entryKey=" . urlencode($entryKey);

        return $this->sendRequest($url, 'POST', json_encode($newValue));
    }

    /**
     * 獲取 DataStore 數值
     */
    public function getDataStoreEntry($datastoreName, $entryKey)
    {
        $url = "https://apis.roblox.com/datastores/v1/universes/{$this->universeId}/standard-datastores/datastore/entries/entry";
        $url .= "?datastoreName=" . urlencode($datastoreName) . "&entryKey=" . urlencode($entryKey);

        return $this->sendRequest($url, 'GET');
    }

    private function sendRequest($url, $method = 'GET', $payload = null)
    {
        $ch = curl_init($url);

        $headers = [
            "x-api-key: {$this->apiKey}",
            "Content-Type: application/json"
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($payload && ($method === 'POST' || $method === 'PATCH')) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return ['success' => false, 'error' => $error_msg];
        }

        curl_close($ch);

        return [
            'success' => ($httpCode >= 200 && $httpCode < 300),
            'status' => $httpCode,
            'data' => json_decode($response, true) ?: $response
        ];
    }
}
?>