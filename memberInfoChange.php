<?php
error_reporting(E_ALL); // 에러 메시지 활성화
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$userpassword = "Adminadmin1234!!";
$dbname = "userInfo";

$conn = new mysqli($servername, $username, $userpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// POST 데이터 확인
var_dump($_POST);

$id = $_POST['id'];
$password = $_POST['password'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$school = $_POST['school'];
$accountnum = $_POST['accountnum'];

$sql = $conn->prepare("UPDATE userInfo SET password=?, email=?, phone=?, school=?, accountnum=? WHERE id=?");

$sql->bind_param("ssssss", $password, $email, $phone, $school, $accountnum, $id);

if ($sql->execute()) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $sql->error;
}

$conn->close();
?>
