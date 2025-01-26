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
$sql = "SELECT user_id FROM player_data WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $_GET['user_id'] );
$res = $stmt->execute();
if( $res ) {
    $rowCount = $stmt->rowCount();
    if ($rowCount === 0) {
        // 新規
        $sql = "insert into player_data (user_id, user_name, update_dt) values ( :user_id, :user_name, now())";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $_GET['user_id'] );
        $stmt->bindParam(':user_name', $_GET['user_name'] );
        $stmt->execute();
    }
    else {
        // 上書き
        $sql = "UPDATE player_data SET user_name = :user_name, update_dt = now() where user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $_GET['user_id'] );
        $stmt->bindParam(':user_name', $_GET['user_name'] );
        $stmt->execute();
    }
}

$pdo = null;

//sleep(1);
usleep(100000);

//echo json_encode($json_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
