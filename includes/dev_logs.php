<?php
/**
 * dev_logs.php - 統一管理開發日誌資料
 */

function getDevLogs()
{
    return [
        [
            'date' => '2026.03.10',
            'title' => 'Footer 視覺與功能大更新',
            'content' => '新增了隱私權政策頁面、管理員快速入口，並優化了全站 Footer 的網格佈局與霓虹發光效果。',
            'tag' => 'UI/UX',
            'status' => 'new'
        ],
        [
            'date' => '2026.03.05',
            'title' => '專案「抗重力」核心開發揭秘',
            'content' => '我們正在為下一個版本導入全新的物理引擎，這將徹底改變 PVP 的格檔機制，提供更真實的打擊感。',
            'tag' => 'CORE',
            'status' => 'stable'
        ],
        [
            'date' => '2026.02.20',
            'title' => '關於 UI 視覺風格的再進化',
            'content' => '為了追求極限的沈浸感，我們決定將所有的導覽介面改為半透明磨砂質感（Glassmorphism），並優化手機端顯示。',
            'tag' => 'UI/UX',
            'status' => 'stable'
        ],
        [
            'date' => '2026.02.10',
            'title' => '伺服器架構優化完成',
            'content' => '針對 Roblox 低延遲需求，我們優化了 API 請求流程，現在數據同步效率提升了 40%。',
            'tag' => 'SYSTEM',
            'status' => 'stable'
        ]
    ];
}
?>