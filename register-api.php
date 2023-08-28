<?php
require __DIR__ . '/parts/connect_db.php';

use Google\Service\Directory\VerificationCode;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './vendor/phpmailer/phpmailer/src/Exception.php';
require './vendor/phpmailer/phpmailer/src/PHPMailer.php';
require './vendor/phpmailer/phpmailer/src/SMTP.php';

$output = [
    'success' => false,
    'error' => [],
    'postDara' => $_POST,
];



//表單格式驗證
if (!empty($_POST['name'])) {
    $isPass = true;

    //POST抓表單填入的值
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $gender = $_POST['gender'] ?? "";
    $birthday = $_POST['birthday'] ?? "";
    $mobile = $_POST['mobile'] ?? "";
    $city = $_POST['city'] ?? "";
    $dist = $_POST['dist'] ?? "";
    $rd = $_POST['rd'] ?? "";
    $level = $_POST['level'] ?? "";

    //檢查姓名
    if (mb_strlen($name, 'utf8') < 2) {
        $output['errors']['name'] = '請輸入正確姓名';
        $isPass = false;
    }
    //檢查email
    if (!empty($email) and !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $output['errors']['email'] = 'Email格式錯誤';
        $isPass = false;
    }
    if (empty($password)) {
        $output['errors']['password'] = '無設定密碼';
        $isPass = false;
    }

    /*檢查表單資料格式都正確後($isPass = TRUE)
GMAIL發驗證碼，會員回傳驗證碼通過才正式註冊會員 */

    if ($isPass) {

        //POST抓表單填入的值
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $gender = $_POST['gender'] ?? "";
        $birthday = $_POST['birthday'] ?? "";
        $mobile = $_POST['mobile'] ?? "";
        $city = $_POST['city'] ?? "";
        $dist = $_POST['dist'] ?? "";
        $rd = $_POST['rd'] ?? "";
        $level = $_POST['level'] ?? "";
        $mail_vaild = 'N';

        $sql_member = "INSERT INTO `members`(
            `member_name`, 
            `email`, 
            `password`, 
            `mobile`, 
            `gender`, 
            `birthday`, 
            `address_city`, 
            `address_dist`, 
            `address_rd`, 
            `member_level_id`,`mail_vaild`,`last_edit_date`)  VALUES(
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                NOW())";

        $stmt_member = $pdo->prepare($sql_member);
        $stmt_member->execute(
            [
                $name,
                $email,
                $password,
                $mobile,
                $gender,
                $birthday,
                $city,
                $dist,
                $rd,
                $level,
                $mail_vaild,
            ]
        );

        //1.創立亂數 email 驗證碼 使用mt_rand(min,max)
        $verificationCode = mt_rand(100000, 999999);
        //驗證碼加入資料表
        $sql_code = "INSERT INTO `mail_verification`(
            `verificationCode`,
            `create_at`) VALUES(
            ?,
            NOW())";
        $stmt_code = $pdo->prepare($sql_code);
        $stmt_code->execute(
            [
                $verificationCode,
            ]
        );

        /*2.gmail寄送驗證碼 */
        $mail = new PHPMailer(true);
        try {
            // 配置SMTP服务器和身份验证
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // 替换为您的SMTP服务器地址
            $mail->SMTPAuth = true;
            $mail->Username = 'a90011147@gmail.com'; // 替换为您的邮箱地址
            $mail->Password = 'hvatmsjpssgleljn'; // 替换为您的邮箱密码
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            // 设置发件人和收件人
            $mail->setFrom('a90011147@gmail.com', 'Zheng Yune'); // 替换为您的邮箱地址和姓名
            $mail->addAddress($email); // 替换为用户提供的邮箱地址

            // 设置邮件内容
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification';
            $mail->Body = '正元蔘藥行，驗證碼: ' . $verificationCode;

            // 发送邮件
            $mail->send();
            echo 'Verification email has been sent.';
        } catch (Exception $e) {
            echo 'Email could not be sent. Error: ' . $mail->ErrorInfo;
        }
    }
}


echo json_encode($output, JSON_UNESCAPED_UNICODE);
