<?php
$servername = "localhost";
$username = "root";
$password = "Adminadmin1234!!";
$dbname = "product";

$user_id = $_POST['user_id'];
$product_id = $_POST['product_id'];

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 찜 수 증가 쿼리 실행
$updateLikeCountSql = "UPDATE productInfo SET like_count = like_count + 1 WHERE product_id = ?";
$stmt = $conn->prepare($updateLikeCountSql);
$stmt->bind_param("i", $product_id);
$stmt->execute();

$sql = "INSERT INTO productWishlist (user_id, product_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $user_id, $product_id);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}

$stmt->close();
$conn->close();
?>
