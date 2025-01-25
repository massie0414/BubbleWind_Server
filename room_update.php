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


$room_id = 0;

$stmt = $pdo->prepare("SELECT room_id FROM room_data");
$res = $stmt->execute();
if( $res ) {
    $array = $stmt->fetchAll();
    foreach($array as $data){
        $room_id = (int)$data[0];
    }
}

$room_id++;

$sql = "UPDATE room_data SET room_id = :room_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':room_id', $room_id );
$stmt->execute();

$pdo = null;

//sleep(1);
usleep(100000);

