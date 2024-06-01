<?php
// 데이터베이스 연결 설정
$host = 'localhost';
$dbname = 'chat';
$username = 'root';
$password = 'Adminadmin1234!!';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // PDO 오류 모드를 예외로 설정
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 로그인한 사용자 ID를 받는다.
    $loggedInId = $_GET['loggedInId'];

    // 쿼리 실행: user_id1이 loggedInId와 일치하는 모든 행의 user_id2를 선택
    $stmt = $conn->prepare("SELECT user_id2 FROM chatUser WHERE user_id1 = :loggedInId");
    $stmt->bindParam(':loggedInId', $loggedInId);
    $stmt->execute();

    // 결과를 가져온다
    $userIds = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 결과를 JSON 형태로 출력
    echo json_encode($userIds);

} catch(PDOException $e) {
    echo "연결 실패: " . $e->getMessage();
}
?>
