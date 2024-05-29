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

// 찜 수 감소 쿼리 실행
$updateLikeCountSql = "UPDATE productInfo SET like_count = like_count - 1 WHERE product_id = ?";
$stmt = $conn->prepare($updateLikeCountSql);
$stmt->bind_param("i", $product_id);
$stmt->execute();

$sql = "DELETE FROM productWishlist WHERE user_id = ? AND product_id = ?";
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
