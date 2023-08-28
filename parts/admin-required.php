<?php

//啟動session
//檢查是否已經啟動了一個會話（session），如果尚未啟動，則使用 session_start() 函式啟動一個新的會話
if (!isset($_SESSION)) {
  session_start();
};

//檢查是否已經設置了一個名為 'admin' 的變數
// 如果還沒登入，轉向登入頁 
if (!isset($_SESSION['admin'])) {
  header('Location: login.php');
  exit;
};
