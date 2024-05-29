<?php
header('Content-Type: application/json');

// 데이터베이스 연결
$host = 'localhost'; // 데이터베이스 주소
$dbname = 'place'; // 데이터베이스 이름
$username = 'root'; // 데이터베이스 사용자 이름
$password = 'Adminadmin1234!!'; // 데이터베이스 비밀번호

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // JSON으로 받은 데이터를 PHP 배열로 변환
    $data = json_decode(file_get_contents('php://input'), true);
    $latitude = $data['latitude'];
    $longitude = $data['longitude'];

    // Search 테이블에서 위치 정보 검색
    $stmt = $pdo->prepare("SELECT * FROM Search WHERE latitude=:latitude AND longitude=:longitude");
    $stmt->execute(['latitude' => $latitude, 'longitude' => $longitude]);
    $place = $stmt->fetch(PDO::FETCH_ASSOC);

    // Review 테이블에서 리뷰 검색
    $reviews = [];
    if ($place) {
        $stmt = $pdo->prepare("SELECT * FROM Review WHERE business_id=:business_id");
        $stmt->execute(['business_id' => $place['business_id']]);
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 결과 반환
    echo json_encode([
        'name' => $place['name'] ?? '',
        'address' => $place['address'] ?? '',
        'reviews' => $reviews
    ]);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}
?>
