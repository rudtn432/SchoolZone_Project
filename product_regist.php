<?php
// 에러 리포팅 설정
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$dbname = 'product';
$user = 'root';
$password = 'Adminadmin1234!!';
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 이미지 파일 처리
    $targetDir = "Images/";
    $fileName = basename($_FILES["image"]["name"]);
    // 현재 시간을 파일 이름에 추가
    $fileNameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME);
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    $newFileName = $fileNameWithoutExt . "_" . time() . "." . $fileExt;
    $targetFilePath = $targetDir . $newFileName;
    move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath);

    // 상품 정보 삽입
    $stmt = $pdo->prepare("INSERT INTO productInfo (user_id, title, description, image_url, price, stock_quantity, category, shipping_fee, product_condition, created_at, updated_at, is_sold, view_count, like_count) VALUES (:userId, :title, :description, :imageUrl, :price, :stockQuantity, :category, :shippingFee, :productCondition, NOW(), NOW(), 0, 0, 0)");

    $stmt->execute([
        ':userId' => $_POST['userId'],
        ':title' => $_POST['title'],
        ':description' => $_POST['description'],
        ':imageUrl' => $targetFilePath,
        ':price' => $_POST['price'],
        ':stockQuantity' => $_POST['stockQuantity'],
        ':category' => $_POST['category'],
        ':shippingFee' => $_POST['shippingFee'],
        ':productCondition' => $_POST['productCondition']
    ]);

    // 상품 정보 삽입 후
    $lastInsertId = $pdo->lastInsertId();

    echo json_encode(array('success' => true, 'productId' => $lastInsertId));

} catch (PDOException $e) {
    // 오류 발생 시 JSON 형태로 응답
    echo json_encode(array('success' => false, 'message' => $e->getMessage()));
    exit;
}

?>
