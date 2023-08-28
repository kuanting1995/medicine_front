<?php

require __DIR__ . '/parts/connect_db.php';

$pageName = 'verify';
$title = 'email驗證';

require __DIR__ . '/parts/html-head.php';
require __DIR__ . '/parts/1.css.php';
require __DIR__ . '/parts/2.css.php';
require __DIR__ . '/parts/3.css.php';
require __DIR__ . '/parts/4.css.php';
require __DIR__ . '/parts/5.css.php';
require __DIR__ . '/parts/navbar.php';
?>


<!-- html -->
<div class="container">
    <div class="row justify-content-center align-items-center" style="height:35vh">
        <form name="form1" onsubmit="checkform(event)" novalidate>
            <div class="col-6">
                <label for="" class="form-label">
                    <h5>請回傳email驗證碼:</h5>
                </label>
                <input type="text" class="form-control" id="" name="verificationCode" placeholder="提示:6位數字" style="border: 1px solid black;">
                <div class="text-end mt-4"> <button type="submit" class="btn btn-rgst">確定</button></div>
                <div id="formAlert" class="alert alert-info" style="display:none;"></div>
            </div>
        </form>
    </div>
</div>
<?php require __DIR__ . '/parts/scripts.php'; ?>
<script>
    //alert
    const formAlert = document.querySelector('#formAlert');
    const showAlert = function(msg = '驗證碼', type = 'INFO') {
        formAlert.innerHTML = msg;
        formAlert.className = `alert alert-${type}`;
        formAlert.style.display = 'block';
    };
    //驗證 驗證碼
    function checkform(event) {
        event.preventDefault();
        const fd = new FormData(document.form1);
        fetch('mail-ver-api.php', {
            method: 'POST',
            body: fd,
        }).then(r => r.json()).then(obj => {
            console.log(obj);

            if (obj.success) {
                showAlert('註冊成功!', 'SUCC');
            } else {
                showAlert(obj.msg);
            }

        });
    }
</script>
<?php require __DIR__ . '/parts/html-foot.php'; ?>