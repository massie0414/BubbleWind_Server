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


$json_data = [];

$stmt = $pdo->prepare("SELECT room_id FROM room_data");
$res = $stmt->execute();
if( $res ) {
    $array = $stmt->fetchAll();
    foreach($array as $data){
        // $json_data[] = [
        //     "room_id" => (int)$data[0],
        // ];
        $json_data["room_id"] = (int)$data[0];
    }
}
$pdo = null;

// 全体の構造を作成
// $json_data = [
//     "RoomData" => $json_data
// ];

//sleep(1);
usleep(100000);

echo json_encode($json_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
