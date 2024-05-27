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
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // 에러 모드 설정: 예외 발생
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // 기본 페치 모드 설정: 연관 배열
    PDO::ATTR_EMULATE_PREPARES   => false, // 에뮬레이트된 준비문 사용 비활성화
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

// productBuy 테이블에서 사용자가 구매한 상품 조회
$sql = "SELECT pb.product_id, pb.buy_time, pi.image_url, pi.title, pi.price
        FROM productBuy pb
        JOIN productInfo pi ON pb.product_id = pi.product_id
        WHERE pb.user_id = ?";

// SQL 쿼리 준비 및 실행
$stmt = $pdo->prepare($sql);
$stmt->execute([$loggedInUserId]);

while($row = $stmt->fetch()){
    array_push($posts, $row);
}

// Statement 객체 해제
$stmt = null;

// 데이터베이스 연결 종료
$pdo = null;

// 결과 반환
echo json_encode(array("posts" => $posts));
?>
