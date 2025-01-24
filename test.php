<?php
// Content-Type ヘッダーを設定
header('Content-Type: application/json; charset=utf-8');

// レスポンスデータを配列として定義
$response = [
    'status' => 'success',
    'message' => 'データの取得に成功しました。',
    'data' => [
        'id' => 1,
        'name' => '山田太郎',
        'email' => 'taro.yamada@example.com',
        'age' => 25
    ]
];

// 配列をJSON形式に変換して出力
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

// 終了コード（必要に応じて使用）
exit;