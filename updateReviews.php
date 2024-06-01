<?php
header('Content-Type: application/json');

// 데이터베이스 연결 설정
$host = 'localhost';
$dbname = 'place';
$username = 'root';
$password = 'Adminadmin1234!!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// POST 요청 데이터 가져오기
$input = json_decode(file_get_contents('php://input'), true);
$companyName = $input['name'];

try {
    // Business 테이블에서 companyName으로 business_id 찾기
    $stmt = $pdo->prepare('SELECT business_id FROM Business WHERE name = :name');
    $stmt->execute(['name' => $companyName]);
    $business = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($business) {
        $businessId = $business['business_id'];

        // Review 테이블에서 해당 business_id의 리뷰 가져오기
        $stmt = $pdo->prepare('SELECT rating, comment FROM Review WHERE business_id = :business_id');
        $stmt->execute(['business_id' => $businessId]);
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 평균 별점 계산하기
        $stmt = $pdo->prepare('SELECT AVG(rating) as averageRating FROM Review WHERE business_id = :business_id');
        $stmt->execute(['business_id' => $businessId]);
        $averageRating = $stmt->fetch(PDO::FETCH_ASSOC)['averageRating'];

        echo json_encode([
            'success' => true,
            'reviews' => $reviews,
            'averageRating' => $averageRating,
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Company not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Query failed: ' . $e->getMessage()]);
}
?>
