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

// 로그인한 사용자 ID 받아오기
$loggedInUserId = isset($_GET['loggedInUserId']) ? $_GET['loggedInUserId'] : null;

if ($loggedInUserId === null) {
    // loggedInUserId가 제공되지 않았을 경우 에러 메시지 반환
    echo json_encode(['error' => 'No loggedInUserId provided']);
    exit; // 스크립트 종료
}

// SQL 쿼리 준비: user_id가 로그인한 사용자의 ID와 같고, is_sold가 0인 상품 조회
$sql = "SELECT product_id, image_url, title, price, updated_at FROM productInfo WHERE user_id = ? AND is_sold = 0";

// SQL 쿼리 실행 준비
$stmt = $pdo->prepare($sql);
// 쿼리 실행
$stmt->execute([$loggedInUserId]);

// 결과 페치
$posts = $stmt->fetchAll();

// 결과를 JSON 형식으로 변환하여 반환
echo json_encode(['posts' => $posts]);
?>