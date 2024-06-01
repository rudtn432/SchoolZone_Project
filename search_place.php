<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "Adminadmin1234!!";
$dbname = "place";

// MySQL 연결 생성
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// JSON 형식의 POST 데이터를 읽음
$data = json_decode(file_get_contents('php://input'), true);
$query = $data['query'];

// SQL 쿼리 실행
$sql = "SELECT * FROM Business WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $query);
$stmt->execute();
$result = $stmt->get_result();

// 결과 확인
if ($result->num_rows > 0) {
  $business = $result->fetch_assoc();
  $business_id = $business['business_id'];

  // Location 데이터 가져오기
  $location_sql = "SELECT latitude, longitude FROM Location WHERE business_id = ?";
  $location_stmt = $conn->prepare($location_sql);
  $location_stmt->bind_param("i", $business_id);
  $location_stmt->execute();
  $location_result = $location_stmt->get_result();
  if ($location = $location_result->fetch_assoc()) {
      $latitude = $location['latitude'];
      $longitude = $location['longitude'];
  } else {
      $latitude = 0; // 기본값
      $longitude = 0; // 기본값
  }

  // 리뷰 및 평점 데이터 가져오기
  $review_sql = "SELECT rating, comment FROM Review WHERE business_id = ?";
  $review_stmt = $conn->prepare($review_sql);
  $review_stmt->bind_param("i", $business_id);
  $review_stmt->execute();
  $review_result = $review_stmt->get_result();

  $reviews = [];
  $total_rating = 0;
  $review_count = 0;

  while ($review = $review_result->fetch_assoc()) {
    $reviews[] = $review;
    $total_rating += $review['rating'];
    $review_count++;
  }

  $average_rating = $review_count > 0 ? $total_rating / $review_count : 0;

  $response = [
    'name' => $business['name'],
    'address' => $business['address'],
    'photo' => $business['photo'],
    'reviews' => $reviews,
    'averageRating' => $average_rating,
    'latitude' => $latitude,
    'longitude' => $longitude
];

  echo json_encode($response);
} else {
  echo json_encode(['error' => 'No business found']);
}

// 연결 종료
$conn->close();
?>
