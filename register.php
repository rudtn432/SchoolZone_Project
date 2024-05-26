<?php
// 데이터베이스 연결 설정
$servername = "localhost";
$username = "root"; // 데이터베이스 사용자 이름
$userpassword = "Adminadmin1234!!"; // 데이터베이스 비밀번호
$dbname = "userInfo"; // 데이터베이스 이름

// POST로 받은 데이터
$id = $_POST['id'];
$password = $_POST['password']; // 실제 애플리케이션에서는 비밀번호를 해싱하여 저장해야 합니다.
$name = $_POST['name'];
$birthdate = $_POST['birthdate'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$school = $_POST['school']; // select의 name 속성 값

// MySQL 연결
$conn = new mysqli($servername, $username, $userpassword, $dbname);

if($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "<p>DB 연결 성공<p>";
}

// SQL 쿼리 준비
$sql = "INSERT INTO userInfo (id, password, name, birthdate, phone, email, school)
VALUES ('$id', '$password', '$name', '$birthdate', '$phone', '$email', '$school')";

// 쿼리 실행
if ($conn->query($sql) === TRUE) {
  // 새로운 레코드 생성 성공 시 Main.html로 리다이렉션
  header('Location: Main.html');
  exit();
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

// 연결 종료
$conn->close();
?>
