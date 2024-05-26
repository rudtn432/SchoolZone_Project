<?php
ini_set('display_errors', 1);
// 데이터베이스 설정
$dbHost     = 'localhost';
$dbUsername = 'root';
$dbPassword = 'Adminadmin1234!!';
$dbName     = 'test';

// 데이터베이스 연결 생성
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// 연결 확인
if($db->connect_error){
    die("연결 실패: " . $db->connect_error);
}

// 파일 업로드 경로
$targetDir = "Images/";
$fileName = basename($_FILES["imageInput"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

if(isset($_POST["submit"]) && !empty($_FILES["imageInput"]["name"])){
    // 허용되는 파일 형식
    $allowTypes = array('jpg','png','jpeg','gif','pdf');
    if(in_array($fileType, $allowTypes)){
        // 서버에 파일 업로드
        if(move_uploaded_file($_FILES["imageInput"]["tmp_name"], $targetFilePath)){
            // 데이터베이스에 이미지 파일 이름 삽입
            $insert = $db->query("INSERT into test (image_url) VALUES ('".$targetFilePath."')");
            if($insert){
                echo "파일 ".$fileName. "이(가) 성공적으로 업로드되었습니다.";
            }else{
                echo "파일 업로드에 실패하였습니다. 다시 시도해주세요.";
            } 
        }else{
            echo "죄송합니다. 파일을 업로드하는 동안 오류가 발생했습니다.";
        }
    }else{
        echo "죄송합니다. JPG, JPEG, PNG, GIF, & PDF 파일만 업로드가 가능합니다.";
    }
}else{
    echo "업로드할 파일을 선택해주세요.";
}
?>
