<?php
// 데이터베이스 연결 설정
$servername = "localhost";
$username = "root";
$userpassword = "Adminadmin1234!!";
$dbname = "userInfo";

// 데이터베이스에 연결
$conn = new mysqli($servername, $username, $userpassword, $dbname);

if($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "<p>DB 연결 성공<p>";
}

// POST로 전송된 데이터 받기
$id = $_POST['id'];
$password = $_POST['password'];

// SQL 쿼리 준비
$stmt = $conn->prepare("SELECT password FROM userInfo WHERE id = ?");
$stmt->bind_param("s", $id);

// SQL 쿼리 실행
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // 사용자 정보가 존재할 경우
    $row = $result->fetch_assoc();
    $db_password = $row['password'];

    // 비밀번호 직접 비교
    if ($password === $db_password) {
        // 로그인 성공
        $response['status'] = 'success';
        $response['message'] = '로그인 성공!';
    } else {
        // 비밀번호 불일치
        $response['status'] = 'error';
        $response['message'] = 'ID 또는 비밀번호가 잘못되었습니다.';
    }
} else {
    // 사용자 정보가 존재하지 않음
    $response['status'] = 'error';
    $response['message'] = 'ID 또는 비밀번호가 잘못되었습니다.';
}

echo json_encode($response);
$conn->close();
?>
