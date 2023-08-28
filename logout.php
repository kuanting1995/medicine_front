<?php require __DIR__ . '/parts/connect_db.php';

session_start();


unset($_SESSION['admin']);

// redirect 轉向
header('Location: login.php');
