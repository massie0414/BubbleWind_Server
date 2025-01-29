<?php
$id = 1;
$pdo = new PDO(
    'mysql:host=localhost;dbname=BubbleWind;charset=utf8mb4',
    'ggj',
    'password'
    );

// タイムゾーンをJSTに設定
$pdo->exec("SET time_zone = '+09:00'");

date_default_timezone_set('Asia/Tokyo');



// 登録処理
$sql = "UPDATE player_data SET clear_time = now() where user_id = :user_id and clear_time IS NULL";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $_GET['user_id'] );
$stmt->execute();


// 他プレイヤー（自分以外）の読み込み

$json_data = [];

$stmt = $pdo->prepare("SELECT user_id, user_name, TIMESTAMPDIFF(MICROSECOND, start_time, clear_time) AS time_difference FROM player_data WHERE room_id = :room_id and clear_time is not null order by clear_time asc");
$stmt->bindParam(':room_id', $_GET['room_id'] );
$res = $stmt->execute();
if( $res ) {
    $array = $stmt->fetchAll();
    foreach($array as $data){

        $json_data[] = [
            "user_id" => (int)$data[0],
            "user_name" => $data[1],
            "time" => (float)$data[2],
        ];
    }
}
$pdo = null;

// 全体の構造を作成
$json_data = [
    "result_data" => $json_data
];

//sleep(1);
usleep(100000);

echo json_encode($json_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
