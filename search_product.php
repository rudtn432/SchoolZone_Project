<?php
// 데이터베이스 연결 설정
$host = 'localhost';
$dbname = 'product';
$username = 'root';
$password = 'Adminadmin1234!!';
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// 사용자 입력 처리
$query = $_GET['q'] ?? ''; // PHP 7.0+ null 합체 연산자 사용
$query = "%$query%"; // SQL LIKE 검색을 위해

// 상품 검색 쿼리 실행
// is_sold가 0인 상품만 검색
$stmt = $pdo->prepare("SELECT * FROM productInfo WHERE title LIKE ? AND is_sold = 0");
$stmt->execute([$query]);
$products = $stmt->fetchAll();

// 결과를 JSON 형식으로 출력
header('Content-Type: application/json');
echo json_encode($products);
?>