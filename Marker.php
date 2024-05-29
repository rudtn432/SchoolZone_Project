<?php
$servername = "localhost";
$username = "root";
$password = "Adminadmin1234!!";
$dbname = "place";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("
        SELECT p.latitude, p.longitude, b.name 
        FROM Location p 
        JOIN Business b 
        ON p.business_id = b.business_id
    ");
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);

} catch(PDOException $e) {
    echo json_encode(array('error' => $e->getMessage()));
}
?>
