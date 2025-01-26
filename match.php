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

$json_all_data = [];
$room_id = 0;
$status = 0;

// すべて閉じられていたら作っておく（TODO これだと複数作られそう？）
$stmt = $pdo->prepare("SELECT room_id, status FROM room_data where status = 0");
$res = $stmt->execute();
if( $res ) {
    $rowCount = $stmt->rowCount();
    if ($rowCount === 0) {
        // 追加
        $sql = "insert into room_data (status) values (0)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
}

// ステータスの取得
$stmt = $pdo->prepare("SELECT room_id, status FROM room_data where room_id = :room_id");
$stmt->bindParam(':room_id', $_GET['room_id'] );
$res = $stmt->execute();
if( $res ) {
    $array = $stmt->fetchAll();
    foreach($array as $data){
        $json_all_data["room_id"] = (int)$data[0];
        $room_id = (int)$data[0];
        $status = (int)$data[1];
    }
}

$json_user_data = [];


// 自分自身のルームIDを登録
$sql = "SELECT user_id FROM player_data WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $_GET['user_id'] );
$res = $stmt->execute();
if( $res ) {
    $rowCount = $stmt->rowCount();
    if ($rowCount === 0) {
        // 新規
        $sql = "insert into player_data (user_id, update_dt, room_id) values ( :user_id, now(), :room_id )";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $_GET['user_id'] );
        $stmt->bindParam(':room_id', $_GET['room_id'] );
        $stmt->execute();
    }
    else {
        // 上書き 
        $sql = "UPDATE player_data SET update_dt = now(), room_id = :room_id where user_id = :user_id and room_id <> :room_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $_GET['user_id'] );
        $stmt->bindParam(':room_id', $_GET['room_id'] );
        $stmt->execute();
    }
}

// 現在マッチング中のユーザー一覧
$stmt = $pdo->prepare("SELECT user_name FROM player_data WHERE room_id = :room_id order by update_dt;");
$stmt->bindParam(':room_id', $room_id );
$res = $stmt->execute();
if( $res ) {
    $array = $stmt->fetchAll();
    foreach($array as $data){
        $json_user_data[] = [
            "user_name" => $data[0]
        ];

    }
}

$pdo = null;



// 全体の構造を作成
//$json_all_data["room_id"] = $json_data;
$json_all_data["UserData"] = $json_user_data;

$json_all_data["status"] = $status;

//sleep(1);
usleep(100000);

echo json_encode($json_all_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
