<?php
// 에러 리포팅 설정
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 데이터베이스 연결 설정
$host = 'localhost'; // 데이터베이스 호스트
$username = 'root'; // 데이터베이스 사용자 이름
$password = 'Adminadmin1234!!'; // 데이터베이스 비밀번호
$dbname = 'product'; // 데이터베이스 이름
$charset = 'utf8mb4'; // 문자 인코딩 설정

// DSN(Data Source Name) 구성
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

// PDO 옵션 설정
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // PDO 인스턴스 생성을 통한 데이터베이스 연결 시도
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    // 연결 실패 시 예외 처리
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$loggedInUserId = $_GET['loggedInUserId'];

// 결과를 저장할 배열 초기화
$posts = array();

// productSale 테이블에서 사용자가 판매한 상품 조회
$sql = "SELECT ps.product_id, ps.sale_time, pi.image_url, pi.title, pi.price
        FROM productSale ps
        JOIN productInfo pi ON ps.product_id = pi.product_id
        WHERE ps.user_id = ?";

// SQL 쿼리 준비 및 실행
if($stmt = $pdo->prepare($sql)){ // $conn을 $pdo로 수정
    $stmt->bindParam(1, $loggedInUserId, PDO::PARAM_INT); // bind_param을 bindParam으로 수정하고 PDO 방식에 맞게 인자 조정
    $stmt->execute();
    $result = $stmt->fetchAll(); // get_result() 대신 fetchAll() 사용

    foreach($result as $row){
        array_push($posts, $row);
    }

    $stmt = null; // $stmt->close(); 대신 null 할당
}

$pdo = null; // $conn->close(); 대신 null 할당

// 결과 반환
echo json_encode(array("posts" => $posts));
?>
