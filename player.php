<?php
$id = 1;
$pdo = new PDO(
    'mysql:host=localhost;dbname=BubbleWind;charset=utf8mb4',
    'ggj',
    'password'
    );

// $json_data = [
//     "id" => -1,
//     "data" => "エラー"
// ];

$json_data = [];

// $stmt = $pdo->prepare("SELECT * FROM test_data WHERE id = :id");
// $stmt->bindParam(':id', $id);
$stmt = $pdo->prepare("SELECT user_id, user_name, x, y, z FROM player_data");
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
        ];

    }
}
$pdo = null;

// 全体の構造を作成
$json_data = [
    "player_data" => $json_data
];

sleep(5);

echo json_encode($json_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
