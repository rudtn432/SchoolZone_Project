<?php
header('Content-Type: application/json');

$host = 'localhost'; 
$user = 'root'; 
$pass = 'Adminadmin1234!!'; 
$db_name = 'chat'; 

$conn = new mysqli($host, $user, $pass, $db_name);

if ($conn->connect_error) {
    die("연결 실패: " . $conn->connect_error);
}

$sql = "SELECT send_user_id, receive_user_id, message FROM messages";
$result = $conn->query($sql);

$messages = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
} else {
    echo "0 결과";
}

echo json_encode($messages);

$conn->close();
?>
