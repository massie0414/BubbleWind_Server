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

$sql = "UPDATE room_data SET status = 1 where room_id = :room_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':room_id', $_GET['room_id'] );
$stmt->execute();

// TODO タイミングが早いですが、ここでスタートタイムを代入
$sql = "UPDATE player_data SET start_time = now(), clear_time = null where room_id = :room_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':room_id', $_GET['room_id'] );
$stmt->execute();



$pdo = null;
