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

// $json_data = [
//     "id" => -1,
//     "data" => "エラー"
// ];

// 登録処理
$sql = "SELECT user_id FROM player_data WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $_GET['user_id'] );
$res = $stmt->execute();
if( $res ) {
    $rowCount = $stmt->rowCount();
    if ($rowCount === 0) {
        // 新規
        //$sql = "insert into player_data (user_id, x, y, z, score, update_dt, room_id) values ( :user_id, :x, :y, :z, :score, now(), :room_id )";
        //$sql = "insert into player_data (user_id, x, y, z, score, update_dt, weather) values ( :user_id, :x, :y, :z, :score, now(), :weather)";
        $sql = "insert into player_data (user_id, x, y, z, score, update_dt) values ( :user_id, :x, :y, :z, :score, now())";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $_GET['user_id'] );
        $stmt->bindParam(':x', $_GET['x'] );
        $stmt->bindParam(':y', $_GET['y'] );
        $stmt->bindParam(':z', $_GET['z'] );
        $stmt->bindParam(':score', $_GET['score'] );
        //$stmt->bindParam(':weather', $_GET['weather'] );
        // $stmt->bindParam(':room_id', $_GET['room_id'] );
        $stmt->execute();
    }
    else {
        // 上書き
        //$sql = "UPDATE player_data SET x = :x, y = :y, z = :z, score = :score, update_dt = now(), room_id = :room_id where user_id = :user_id";
        //$sql = "UPDATE player_data SET x = :x, y = :y, z = :z, score = :score, update_dt = now(), weather = :weather where user_id = :user_id";
        $sql = "UPDATE player_data SET x = :x, y = :y, z = :z, score = :score, update_dt = now() where user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $_GET['user_id'] );
        $stmt->bindParam(':x', $_GET['x'] );
        $stmt->bindParam(':y', $_GET['y'] );
        $stmt->bindParam(':z', $_GET['z'] );
        $stmt->bindParam(':score', $_GET['score'] );
        //$stmt->bindParam(':weather', $_GET['weather'] );
        //$stmt->bindParam(':room_id', $_GET['room_id'] );
        $stmt->execute();
    }
}

// 天気を反映する
if($_GET['weather'] != -1){
    // 天気が変わった
    $sql = "UPDATE player_data SET weather = :weather where room_id = :room_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':room_id', $_GET['room_id'] );
    $stmt->bindParam(':weather', $_GET['weather'] );
    $stmt->execute();
}


// 他プレイヤー（自分以外）の読み込み

$json_data = [];

// $stmt = $pdo->prepare("SELECT * FROM test_data WHERE id = :id");
// $stmt->bindParam(':id', $id);
$stmt = $pdo->prepare("SELECT user_id, user_name, x, y, z, score, weather FROM player_data WHERE user_id <> :user_id and room_id = :room_id");
$stmt->bindParam(':user_id', $_GET['user_id'] );
$stmt->bindParam(':room_id', $_GET['room_id'] );
$res = $stmt->execute();
if( $res ) {
//	$data = $stmt->fetch();
//	var_dump($data);
    $array = $stmt->fetchAll();
    foreach($array as $data){

        // $json_data["id"] = (int)$data[0];
        // $json_data["data"] = $data[1];

        $json_data[] = [
            "user_id" => (int)$data[0],
            "user_name" => $data[1],
            "x" => (float)$data[2],
            "y" => (float)$data[3],
            "z" => (float)$data[4],
            "score" => (float)$data[5],
            "weather" => (float)$data[6],
        ];

    }
}
$pdo = null;

// 全体の構造を作成
$json_data = [
    "player_data" => $json_data
];

//sleep(1);
usleep(100000);

echo json_encode($json_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
