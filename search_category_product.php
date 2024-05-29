<?php
// 데이터베이스 연결 설정
$host = 'localhost'; // 데이터베이스 서버 주소
$dbname = 'product'; // 데이터베이스 이름
$username = 'root'; // 데이터베이스 사용자 이름
$password = 'Adminadmin1234!!'; // 데이터베이스 비밀번호

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // POST 요청으로부터 카테고리 받기
    $category = $_POST['category'];

    // 해당 카테고리의 상품 조회
    $stmt = $pdo->prepare("SELECT * FROM productInfo WHERE category = ? AND is_sold = 0");
    $stmt->execute([$category]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 상품 데이터 JSON 형태로 반환
    echo json_encode($products);

} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
