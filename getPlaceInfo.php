<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'place';
$username = 'root';
$password = 'Adminadmin1234!!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    $data = json_decode(file_get_contents('php://input'), true);
    $latitude = $data['latitude'];
    $longitude = $data['longitude'];

    $stmt = $pdo->prepare("SELECT * FROM Search WHERE latitude=:latitude AND longitude=:longitude");
    $stmt->execute(['latitude' => $latitude, 'longitude' => $longitude]);
    $place = $stmt->fetch(PDO::FETCH_ASSOC);

    $reviews = [];
    $totalRating = 0;
    if ($place) {
        // Business 테이블에서 사진 URL 가져오기
        $stmt = $pdo->prepare("SELECT photo FROM Business WHERE business_id=:business_id");
        $stmt->execute(['business_id' => $place['business_id']]);
        $photo = $stmt->fetch(PDO::FETCH_ASSOC);

        // Review 테이블에서 리뷰 검색
        $stmt = $pdo->prepare("SELECT * FROM Review WHERE business_id=:business_id");
        $stmt->execute(['business_id' => $place['business_id']]);
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 평점 계산
        foreach ($reviews as $review) {
            $totalRating += $review['rating'];
        }
        $averageRating = count($reviews) ? $totalRating / count($reviews) : 0;
    }

    echo json_encode([
        'name' => $place['name'] ?? '',
        'address' => $place['address'] ?? '',
        'photo' => $photo['photo'] ?? '', // 사진 URL 추가
        'reviews' => $reviews,
        'averageRating' => round($averageRating, 1) // 평균 평점 추가
    ]);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}
?>
