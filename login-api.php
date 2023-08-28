<?php require __DIR__ . '/parts/connect_db.php';

/*設置 HTTP 響應標頭，內容格式為 JSON*/
header('Content-Type: application/json');
//告訴客戶端（通常是瀏覽器）該響應的內容是 JSON 格式的資料
//這對於與客戶端進行 API 通信非常重要，因為它確保客戶端能夠正確解析和處理返回的 JSON 資料。


/*php語法，設定輸出資料，宣告output陣列，內容可自行修改改*/
$output = [
  'success' => false, //行為是否成功
  'errors' => '', //表示錯誤訊息
  'postData' => $_POST, //存處從$_POST獲取的資料
];

if (empty($_POST['email'] or $_POST['password'])) {
  $output['error'] = '資料不足';
  echo json_encode($output, JSON_UNESCAPED_UNICODE);
  exit;
}

/*取得資料表資料*/

//宣告sql指令 //找資料表admins中有無用戶輸入的account
$sql = "SELECT * FROM `members` WHERE `email`=?";
/*prepare() 方法準備一個SQL語句*/ //stmt要準備執行sql語法
$stmt = $pdo->prepare($sql);

/*執行 => execute() 是 PDO（PHP Data Objects）類的一个方法，用於執行準備好的 SQL 语句並傳遞參數值。*/
$stmt->execute([
  $_POST['email']
  //$_POST['email']是用戶端輸入的email資料
]);

//驗證帳號 使否有抓到資料表中有相同account(也就是email)
$r = $stmt->fetch();
//若$r為空，表sql處理後，資料表中沒有找尋到與用戶端輸入相同的account
if (empty($r)) {
  $output['error'] = '帳號或密碼錯誤';
  echo json_encode($output, JSON_UNESCAPED_UNICODE);
  exit;
}

//驗證密碼與資料表裡的密碼是否相同
$hash = $r['password'];
$output['success'] = password_verify($_POST['password'], $hash);
//password_verify()函數會使用相應的hash算法（通常是BCrypt）將用戶提交的密碼與資料表password 中的hash進行比較，並返回一個布林值。
if ($output['success']) {
  $_SESSION['admin'] = true;
} else {
  $output['error'] = '帳號或密碼錯誤';
  echo json_encode($output, JSON_UNESCAPED_UNICODE);
  exit;
}
echo json_encode($output, JSON_UNESCAPED_UNICODE);
