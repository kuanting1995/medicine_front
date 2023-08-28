<?php
session_start();
if (!isset($_SESSION['token'])) {
    header('Location: login.php');
    exit;
}

require('./config.php');
$client->setAccessToken($_SESSION['token']);

if ($client->isAccessTokenExpired()) {
    header('Location: logout.php');
    exit;
}
$google_oauth = new Google_Service_Oauth2($client);
$user_info = $google_oauth->userinfo->get();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        body {
            padding: 50px;
        }

        ul {
            list-style: none;
        }

        li {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <ul>
        <h1>成功登入!</h1>
        <li><img src="<?= $user_info['picture']; ?>"></li>
        <li><strong>ID:</strong> <?= $user_info['id']; ?></li>
        <li><strong>Full Name:</strong> <?= $user_info['givenName']; ?> <?= $user_info['familyName']; ?></li>
        <li><strong>Email:</strong> <?= $user_info['email']; ?></li>
        <li><a href="./logout-gmail.php">取消登入</a></li>
        <h5><span id="count">3</span>秒後自動跳轉...</h5>
    </ul>
</body>
<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
    let iid = setInterval(() => {
        count.innerHTML = parseInt(count, innerHTML) - 1;
        if (parseInt(count.innerHTML) < 1) {
            clearInterval(iid)
        }
    }, 1000)
    setTimeout(() => {
        location.href = 'indexx.php';
    }, 3000)
</script>

</html>