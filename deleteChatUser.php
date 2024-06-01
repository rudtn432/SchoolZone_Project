<?php
// 데이터베이스 연결 설정
$host = 'localhost';
$dbname = 'chat';
$username = 'root';
$password = 'Adminadmin1234!!';

// json으로부터 데이터 받기
$data = json_decode(file_get_contents("php://input"), true);

$user_id1 = $data['user_id1'];
$user_id2 = $data['user_id2'];

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // PDO 오류 모드를 예외로 설정
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 쿼리 실행: user_id1과 user_id2에 해당하는 행 삭제
    $stmt = $conn->prepare("DELETE FROM chatUser WHERE user_id1 = :user_id1 AND user_id2 = :user_id2");
    $stmt->bindParam(':user_id1', $user_id1);
    $stmt->bindParam(':user_id2', $user_id2);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // 삭제 성공
        echo json_encode(['message' => 'Chat user successfully deleted']);
    } else {
        // 삭제 실패 (해당하는 행이 없음)
        echo json_encode(['message' => 'No chat user found to delete']);
    }

} catch(PDOException $e) {
    echo "연결 실패: " . $e->getMessage();
}
?>
