<?php
$id = 1;
$pdo = new PDO(
    'mysql:host=localhost;dbname=test;charset=utf8mb4',
    'testuser',
    'password'
    );

// $json_data = [
//     "id" => -1,
//     "data" => "エラー"
// ];

$json_data = [];

// $stmt = $pdo->prepare("SELECT * FROM test_data WHERE id = :id");
// $stmt->bindParam(':id', $id);
$stmt = $pdo->prepare("SELECT * FROM test_data");
$res = $stmt->execute();
if( $res ) {
//	$data = $stmt->fetch();
//	var_dump($data);
    $array = $stmt->fetchAll();
    foreach($array as $data){

        // $json_data["id"] = (int)$data[0];
        // $json_data["data"] = $data[1];

        $json_data[] = [
            "id" => (int)$data[0],
            "data" => $data[1]
        ];

    }
}
$pdo = null;

// 全体の構造を作成
$json_data = [
    "mysqldata" => $json_data
];

sleep(5);

echo json_encode($json_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
