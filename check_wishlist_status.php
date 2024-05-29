<?php
$servername = "localhost";
$username = "root";
$password = "Adminadmin1234!!";
$dbname = "product";

$user_id = $_GET['user_id'];
$product_id = $_GET['product_id'];

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM productWishlist WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "unwish"; // 찜해제 상태
} else {
    echo "wish"; // 찜하기 상태
}

$stmt->close();
$conn->close();
?>
