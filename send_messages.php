<?php
// 데이터베이스 연결 설정
$host = 'localhost'; // 데이터베이스 호스트
$user = 'root'; // 데이터베이스 사용자 이름
$pass = 'Adminadmin1234!!'; // 데이터베이스 비밀번호
$db_name = 'chat'; // 데이터베이스 이름

// POST로 받은 데이터
$data = json_decode(file_get_contents('php://input'), true);
$sendUserId = $data['sendUserId'];
$receiveUserId = $data['receiveUserId'];
$message = $data['message'];

// 데이터베이스 연결
$conn = new mysqli($host, $user, $pass, $db_name);

// 연결 오류 확인
if ($conn->connect_error) {
    die("연결 실패: " . $conn->connect_error);
}

// 메시지 저장 쿼리 실행
$sql = "INSERT INTO messages (send_user_id, receive_user_id, message) VALUES ('$sendUserId', '$receiveUserId', '$message')";

if ($conn->query($sql) === TRUE) {
    echo "메시지가 성공적으로 전송되었습니다.";
} else {
    echo "오류: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
