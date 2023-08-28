<?php require __DIR__ . '/parts/connect_db.php';

header('Content-Type: application/json');
$output = [
    'success' => false,
    'error' => '',
    'postData' => $_POST,
];

if (empty($_POST['verificationCode'])) {
    $output['error'] = '資料不足';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
}
$sql = "SELECT * FROM `mail_verification` WHERE `verificationCode`=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['verificationCode']
]);
$r = $stmt->fetch();

if (empty($r)) {
    $output['error'] = '驗證碼錯誤!註冊失敗';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
} else {
    $output['success'] = true;
    echo json_encode('註冊成功');
}
exit;
echo json_encode($output, JSON_UNESCAPED_UNICODE);
