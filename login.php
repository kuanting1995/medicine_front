<?php
require __DIR__ . '/parts/connect_db.php';
require('../medicine_front/config.php');
$pageName = 'login';
$title = '登入';



/*----------------------------------Gmail登入-------------------------------------- */
# the createAuthUrl() method generates the login URL.
$login_url = $client->createAuthUrl();
/* 
 * After obtaining permission from the user,
 * Google will redirect to the login.php with the "code" query parameter.
*/
if (isset($_GET['code'])) :
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (isset($token['error'])) {
        header('Location: login.php');
        exit;
    }

    $_SESSION['token'] = $token;

    /* -- Inserting the user data into the database -- */

    # Fetching the user data from the google account
    $client->setAccessToken($token);
    $google_oauth = new Google_Service_Oauth2($client);
    $user_info = $google_oauth->userinfo->get();
    $google_id = trim($user_info['id']);
    $f_name = trim($user_info['given_name']);
    $l_name = trim($user_info['family_name']);
    $email = trim($user_info['email']);
    $gender = trim($user_info['gender']);
    $local = trim($user_info['local']);
    $picture = trim($user_info['picture']);
    # Database connection
    require __DIR__ . '/parts/db_connection.php';
    # Checking whether the email already exists in our database.
    $check_email = $db_connection->prepare("SELECT `email` FROM `gmail_members` WHERE `email`=?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows === 0) {
        # Inserting the new user into the database
        $query_template = "INSERT INTO `gmail_members` (`oauth_uid`, `first_name`, `last_name`,`email`,`profile_pic`,`gender`,`local`) VALUES (?,?,?,?,?,?,?)";
        $insert_stmt = $db_connection->prepare($query_template);
        $insert_stmt->bind_param("sssssss", $google_id, $f_name, $l_name, $email, $gender, $local, $picture);
        if (!$insert_stmt->execute()) {
            echo "Failed to insert user.";
            exit;
        }
    }
    $_SESSION['admin'] = true;
    header('Location: home.php');
    exit;

endif;

?>
<?php
// 如果已經登入，跳轉到首頁
if (isset($_SESSION['admin'])) {
    echo '<script>alert("您已登入會員!  (跳回首頁)");</script>';
    echo '<script>window.location.href = "indexx.php";</script>';
    exit;
} ?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/parts/1.css.php' ?>
<?php include __DIR__ . '/parts/2.css.php' ?>
<?php include __DIR__ . '/parts/3.css.php' ?>
<?php include __DIR__ . '/parts/4.css.php' ?>
<?php include __DIR__ . '/parts/5.css.php' ?>
<?php include __DIR__ . '/parts/navbar.php' ?>

<style>
    .btn-g {
        display: flex;
        justify-content: center;
        padding: 5px;
    }

    .btn-g a {
        all: unset;
        cursor: pointer;
        padding: 10px;
        display: flex;
        width: 250px;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        background-color:
            #f9f9f9;
        border: 1px solid rgba(0, 0, 0, .2);
        border-radius: 3px;
    }

    .btn-g a:hover {
        background-color:
            #ffffff;
    }

    .btn-g img {
        width: 40px;
        margin-right: 5px;

    }
</style>

<?/*html */ ?>
<div class="container w-75">
    <div class="row justify-content-center align-items-center" style="height:95vh;">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class=" card-title text-center">會員登入</h5>
                    <form name="form1" onsubmit="checkForm(event)" novalidate>

                        <div class="mb-3">
                            <label for="email" class="form-label">帳號 <span style="color: gray; font-size:smaller">(Email)</span></label>
                            <input type="text" class="form-control" id="email" name="email" required>
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">密碼<span style="color: gray; font-size:smaller"> (含大小寫英文字)</span></label>
                            <div class="position-relative">
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div id="seepass" class="col">
                                    <i id="seepass1" style="display: none;color:gray" class="fa-solid fa-eye"></i>
                                    <i id="seepass2" style="display: inline;color:gray" class="fa-solid fa-eye-slash"></i>
                                </div>
                                <div class="form-text"></div>
                            </div>
                        </div>
                        <div id="formAlert" class="alert alert-info" style="display:none;"></div>
                        <div class="text-end mt-4"> <button type="submit" class="btn btn-rgst">登入</button></div>
                        <div class="btn-g">
                            <a href="<?= $login_url ?>"><img src="https://tinyurl.com/46bvrw4s" alt="Google Logo"> Login
                                with Google</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
    //alert
    const formAlert = document.querySelector('#formAlert');
    const showAlert = function(msg = '登入失敗', type = 'INFO') {
        formAlert.innerHTML = msg;
        formAlert.className = `alert alert-${type}`;
        formAlert.style.display = 'block';
    };

    //form-text
    const form_text = document.querySelectorAll(".form-text");

    //seepass
    document.getElementById("seepass").onmousedown = function() {
        document.getElementById("password").type = "text";
        seepass1.style.display = "inline";
        seepass2.style.display = "none";
    }
    document.getElementById("seepass").onmouseup = function() {
        document.getElementById("password").type = "password";
        seepass1.style.display = "none";
        seepass2.style.display = "inline";

    }

    //驗證帳密
    const checkForm = function(event) {
        let p2 = document.form1.password2;
        event.preventDefault();
        // 欄位外觀回復原來的樣子
        document.form1.querySelectorAll(`input`).forEach(el => {
            el.style.border = '1px solid #ced4da';
            form_text[0].innerHTML = '';
            form_text[1].innerHTML = '';
        })


        // TODO: 欄位檢查

        let isPass = true;

        let field = document.form1.email;
        if (field.value.length > 3) {
            isPass = true;
        } else {
            isPass = false;
            field.style.border = '2px solid red';
            form_text[0].innerHTML = '請輸入帳號';
        }

        field = document.form1.password;
        if (field.value.length > 0) {
            isPass = true;
        } else {
            isPass = false;
            field.style.border = '2px solid red';
            form_text[1].innerHTML = '請輸入密碼';
        }



        if (isPass) {
            const fd = new FormData(document.form1);

            fetch('login-api.php', {
                method: 'POST',
                body: fd,
            }).then(r => r.json()).then(obj => {
                console.log(obj);

                if (obj.success) {
                    showAlert('登入成功!', 'SUCC');
                    setTimeout("location.href='indexx.php'", 1300);
                } else {
                    showAlert(obj.msg);
                }

            })
        }
    }
</script>
<?php include __DIR__ . '/parts/html-foot.php' ?>