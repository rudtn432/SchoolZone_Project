<?php
// 에러 리포팅 설정
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "Adminadmin1234!!";
$dbname = "product";

// JSON 입력을 받아와서 디코딩
$json = file_get_contents('php://input');
$input = json_decode($json, true);

$productBuy = $input['productBuy'];
$productSale = $input['productSale'];
$productInfo = $input['productInfo'];

// MySQL 연결
$mysqli = new mysqli($servername, $username, $password, $dbname);

// 연결 오류 확인
if ($mysqli->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . $mysqli->connect_error]);
    exit();
}

// 트랜잭션 시작
$mysqli->begin_transaction();

try {
    // productInfo 테이블에서 is_sold 값을 조회
    $stmt = $mysqli->prepare("SELECT is_sold FROM productInfo WHERE product_id = ?");
    $stmt->bind_param("i", $productInfo['product_id']);
    $stmt->execute();
    $stmt->bind_result($is_sold);
    $stmt->fetch();
    $stmt->close();

    if($is_sold){
        throw new Exception("이미 판매 완료된 상품입니다.");
    }

    // productBuy 테이블 업데이트
    $stmt = $mysqli->prepare("INSERT INTO productBuy (product_id, user_id, buy_time) VALUES (?, ?, NOW())");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $mysqli->error);
    }
    $stmt->bind_param("is", $productBuy['product_id'], $productBuy['user_id']);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    $stmt->close();

    // productSale 테이블 업데이트를 위한 seller_id 추출
    $seller_stmt = $mysqli->prepare("SELECT user_id FROM productInfo WHERE product_id = ?");
    if (!$seller_stmt) {
        throw new Exception("Prepare failed: " . $mysqli->error);
    }
    $seller_stmt->bind_param("i", $productSale['product_id']);
    if (!$seller_stmt->execute()) {
        throw new Exception("Execute failed: " . $seller_stmt->error);
    }
    $seller_stmt->bind_result($seller_id);
    if (!$seller_stmt->fetch()) {
        throw new Exception("Fetch failed: " . $seller_stmt->error);
    }
    $seller_stmt->close();

    // productSale 테이블 업데이트
    $stmt = $mysqli->prepare("INSERT INTO productSale (product_id, user_id, sale_time) VALUES (?, ?, NOW())");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $mysqli->error);
    }
    $stmt->bind_param("is", $productSale['product_id'], $seller_id);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    $stmt->close();

    // productInfo 테이블 업데이트
    $stmt = $mysqli->prepare("UPDATE productInfo SET is_sold = ? WHERE product_id = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $mysqli->error);
    }
    $stmt->bind_param("ii", $productInfo['is_sold'], $productInfo['product_id']);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    $stmt->close();

    // 트랜잭션 커밋
    $mysqli->commit();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // 트랜잭션 롤백
    $mysqli->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} finally {
    $mysqli->close();
}
?>
