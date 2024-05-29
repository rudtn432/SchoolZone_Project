<?php
// db.php
$host = 'localhost';
$dbname = 'product';
$username = 'root';
$password = 'Adminadmin1234!!';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// productInfo.php
header('Content-Type: application/json');

$sql = "SELECT product_id, image_url, title, price, view_count, like_count FROM productInfo WHERE is_sold = 0";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll();

echo json_encode($products);
?>
