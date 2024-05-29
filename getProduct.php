<?php
// 데이터베이스 연결 설정
$servername = "localhost";
$username = "root";
$password = "Adminadmin1234!!";
$dbname = "product";

// 데이터베이스 연결 생성
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 요청된 상품 ID 가져오기 (예: ?id=123)
$product_id = $_GET['id'];

// 조회수 증가 쿼리 실행
$updateViewCountSql = "UPDATE productInfo SET view_count = view_count + 1 WHERE product_id = ?";
$stmt = $conn->prepare($updateViewCountSql);
$stmt->bind_param("i", $product_id);
$stmt->execute();

// SQL 쿼리 작성
$sql = "SELECT title, image_url, price, product_condition, shipping_fee, stock_quantity, view_count, like_count, description FROM productInfo WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id); // 'i'는 변수 타입이 정수임을 의미합니다.
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // 결과를 배열로 변환
    $product = $result->fetch_assoc();
    // JSON 형식으로 변환하여 출력
    echo json_encode($product);
} else {
    echo json_encode(["error" => "Product not found"]);
}

$stmt->close();
$conn->close();
?>
