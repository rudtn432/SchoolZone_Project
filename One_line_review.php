<?php
header('Content-Type: application/json');

// 데이터베이스 연결 설정
$servername = "localhost";
$username = "root";
$password = "Adminadmin1234!!";
$dbname = "place";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(array('success' => false, 'message' => "Connection failed: " . $conn->connect_error)));
}

$data = json_decode(file_get_contents('php://input'), true);

$rating = $data['rating'];
$comment = $data['comment'];
$companyName = $data['companyName'];
$userId = $data['userId'];

// companyName으로 business_id 찾기
$sql = "SELECT business_id FROM Business WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $companyName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $businessId = $row['business_id'];
    
    // 해당 business_id와 user_id로 이미 리뷰가 있는지 확인
    $sql = "SELECT * FROM Review WHERE business_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $businessId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // 이미 리뷰가 있다면 실패 메시지 반환
        echo json_encode(array('success' => false, 'message' => "You have already reviewed this business."));
    } else {
        // Review 테이블에 데이터 삽입
        $sql = "INSERT INTO Review (business_id, user_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $businessId, $userId, $rating, $comment);

        if ($stmt->execute()) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('success' => false, 'message' => "Error: " . $stmt->error));
        }
    }
} else {
    echo json_encode(array('success' => false, 'message' => "Business not found"));
}

$stmt->close();
$conn->close();
?>
