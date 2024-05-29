<?php
$servername = "localhost";
$username = "root"; // 데이터베이스 사용자 이름
$password = "Adminadmin1234!!"; // 데이터베이스 비밀번호
$dbname = "place"; // 데이터베이스 이름

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "<p>DB 연결 성공<p>";
}




$business_id = $_POST['business_id'];
$rating = $_POST['rating'];
$comment = $_POST['comment'];
$created_at = date("Y-m-d H:i:s");

$sql = "INSERT INTO Review (business_id, rating, comment, created_at) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $business_id, $rating, $comment, $created_at);
$stmt->execute();
if ($stmt->execute()) {
    echo "리뷰가 성공적으로 등록되었습니다.";
} else {
    echo "리뷰 등록 중 오류가 발생했습니다: " . $stmt->error;
}









// SQL 쿼리 준비
// 변수를 쿼리의 매개변수로 바인딩
// 연결 종료
$stmt->close();
$conn->close();
?>