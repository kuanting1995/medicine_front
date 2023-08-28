<?php
// 依據所在頁面的$title變數顯示網站名稱
if (!isset($title)) {
  $title = '正元蔘藥行';
} else {
  $title = $title;
}
?>

<!DOCTYPE html>
<html lang="zh-Hant-TW">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Fjalla+One&family=Noto+Serif+TC:wght@300&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fjalla+One&family=Playfair+Display&display=swap" rel="stylesheet">
  <title><?= $title ?></title>
</head>

<body>