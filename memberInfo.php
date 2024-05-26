<?php
$servername = "localhost";
$username = "root";
$password = "Adminadmin1234!!";
$dbname = "userInfo";

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "<p>DB 연결 성공<p>";
}

// loggedInId 가져오기
$loggedInId = $_GET['loggedInId'];

// SQL 쿼리 실행
$sql = "SELECT * FROM userInfo WHERE id='$loggedInId'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // 결과를 배열로 변환
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode([]);
}

$conn->close();
?>
