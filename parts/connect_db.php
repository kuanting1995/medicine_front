<?php

define('PROJ_ROOT', '/');

$db_host = 'localhost'; //連哪台主機
$db_user = 'medicine'; //db使用者帳號
$db_pass = 'medicine'; //db使用者密碼 //XAMPP預設沒有密碼
$db_name = 'medicine'; //database名稱


$dsn = "mysql:host={$db_host};dbname={$db_name};charset=utf8";
$pdo_opptions = [
  PDO::ATTR_ERRMODE => PDO::ERR_NONE, //除錯用
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]; //將PDO的FETCH()預設為關聯式陣列

try {
  $pdo = new PDO($dsn, $db_user, $db_pass, $pdo_opptions); //可能發生錯誤
} catch (PDOException $ex) {
  echo 'Connection failed:' . $ex->getMessage(); //例外發生後的處理
} //使用try-catch,會提示更多連線時的出錯問題


//啟動SESSION(登入用的)
if (!isset($_SESSION)) {
  session_start();
}
