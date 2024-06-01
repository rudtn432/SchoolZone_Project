<?php
// 데이터베이스 연결 설정
$servername = "localhost";
$username = "root";
$password = "Adminadmin1234!!";
$dbname = "userInfo";

// JSON POST 데이터 받기
$input = json_decode(file_get_contents('php://input'), true);
$userId = $input['id'];

// MySQL 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 오류 확인
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// 사용자의 학교 정보 검색
$sql = "SELECT school FROM userInfo WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// 결과 반환
if ($row) {
    echo json_encode($row);
} else {
    echo json_encode(array('school' => ''));
}

// 연결 종료
$stmt->close();
$conn->close();
?>
