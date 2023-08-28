<?php

//啟動session
if(! isset($_SESSION)){
  session_start();
};

if(! isset($_SESSION['admin'])){
  header('Content-Type: application/json');
  echo json_encode([
    'success' => 'false',
    'error' => '請先登入管理者帳號'
  ], JSON_UNESCAPED_UNICODE);
  exit;
};

?>