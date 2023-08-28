<?php
require __DIR__ . '/parts/connect_db.php';

$title = "會員註冊";
$nowpage = "member-add";
?>

<?php
//地址
$sql_city = sprintf("SELECT `ct_name`FROM `address_list` WHERE `parent_sid`=0 ");

$stmt_city = $pdo->prepare($sql_city);
$stmt_city->execute();
$rows_city = $stmt_city->fetchAll();

$city = array_column($rows_city, 'ct_name', 'ct_sid');
$city_num = count($city);



$sql_dist = sprintf("SELECT `ct_name`,`parent_sid`FROM `address_list` WHERE `parent_sid`!=0 ");

$stmt_dist = $pdo->prepare($sql_dist);
$stmt_dist->execute();
$dist = $stmt_dist->fetchAll(PDO::FETCH_ASSOC);


?>





<?php include __DIR__ . '/parts/html-head.php'; ?>
<?php include __DIR__ . '/parts/navbar.php'; ?>
<?php include __DIR__ . '/parts/1.css.php'; ?>
<?php include __DIR__ . '/parts/2.css.php'; ?>
<?php include __DIR__ . '/parts/3.css.php'; ?>
<?php include __DIR__ . '/parts/4.css.php'; ?>
<?php include __DIR__ . '/parts/5.css.php'; ?>
<div class="container w-75">
    <div style="height: 5vh"></div>
    <div class="buttoncont my-3">
        <div class="d-flex flex-row justify-content-center mb-2">
            <h1>會員註冊</h1>



        </div>

    </div>


</div>




<div class="container">
    <div class="memberList mb-3">
        <form name="form1" class="form1" onsubmit="checkForm(event)">


            <div class="row w-100">
                <div class="theader col-3">姓名
                    <span style="color: lightcoral;font-size: 10px;;">&nbsp;&nbsp;*必填</span>
                </div>
                <div class=" tbb col-9 ">
                    <input class="form-control me-2" type="text" name="name" id="name">
                    <div class="form-text"></div>
                </div>
            </div>

            <div class="row w-100">
                <div class="theader col-3">email
                    <span style="color: lightcoral;font-size: 10px;;">&nbsp;&nbsp;*必填</span>
                </div>
                <div class=" tbb col-9 ">
                    <input class="form-control me-2" type="email" name="email" id="email">
                    <div class="form-text"></div>
                </div>
            </div>

            <div class="row w-100">
                <div class="theader col-3">會員密碼
                    <span style="color: lightcoral;font-size: 10px;;">&nbsp;&nbsp;*必填</span>
                </div>
                <div class=" tbb col-9 position-relative">
                    <input class="form-control me-2" type="password" name="password" id="password">
                    <div id="seepass">
                        <i id="seepass1" style="display: none;color:gray" class="fa-solid fa-eye"></i>
                        <i id="seepass2" style="display: inline;color:gray" class="fa-solid fa-eye-slash"></i>
                    </div>
                    <div class="form-text"></div>

                </div>
                <div class="theader col-3">再次輸入密碼
                </div>
                <div class=" tbb col-9 ">
                    <input class="form-control me-2" type="password" name="password2" id="password2">
                    <div class="form-text"></div>
                </div>
            </div>

            <div class="row w-100">
                <div class="theader col-3">電話
                    <span style="color: lightcoral;font-size: 10px;;">&nbsp;&nbsp;*必填</span>
                </div>
                <div class=" tbb col-9 ">
                    <input class="form-control me-2" type="text" name="mobile" id="mobile">
                    <div class="form-text"></div>
                </div>
            </div>


            <div class="row w-100">
                <div class="theader col-3">會員性別</div>
                <div class=" tbb col-9 ">
                    <div class="input-group w-100">
                        <div class="input-group-text w-50">
                            <input class="form-check-input mt-0" type="radio" value="male" name="gender" id="male" />
                            <label for="male">&nbsp;男</label>
                        </div>
                        <div class="input-group-text w-50">
                            <input class="form-check-input mt-0" type="radio" value="female" name="gender" id="female" /><label for="female">&nbsp;女</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row w-100">
                <div class="theader col-3">生日
                    <span style="color: lightcoral;font-size: 10px;;">&nbsp;&nbsp;</span>
                </div>
                <div class=" tbb col-9 ">
                    <input class="form-control me-2" type="date" name="birthday" id="birthday" min="1950-01-01" max="2006-12-31">
                </div>
            </div>


            <div class="row w-100">
                <div class="theader col-3">地址</div>
                <div class=" tbb col-9 ">
                    <select class="form-select" name="city" id="city-list" onchange="citychange(this.selectedIndex)">
                    </select>
                    <select class="form-select" name="dist" id="dist-list"></select>


                    <input class="form-control me-2" type="text" name="rd" placeholder="詳細路名 ex:中山路1號5樓">
                </div>
            </div>










            <div id="formAlert" class="alert alert-info" style="display: none"></div>
            <div class="mt-4 box d-flex justify-content-evenly">
                <button type="submit" class="btn btn-outline-secondary btn-che">儲存</button>
                <a href="indexx.php" class="btn btn-outline-secondary btn-canc me-4 " role="button"> 取消</a>
            </div>
        </form>

    </div>


</div>










<?php include __DIR__ . '/parts/scripts.php'; ?>

<script>
    const formAlert = document.querySelector('#formAlert');

    const fields = ['name', 'email', 'password', 'mobile'];

    const mobile_re = /^09\d{2}-?\d{3}-?\d{3}$/;
    const email_re =
        /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zAZ]{2,}))$/;
    //密碼驗證:含1個大寫字母1個小寫字母1個數字,長度6-12位
    var passw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,12}$/;

    const showAlert = function(msg = '新增失敗', type = 'INFO') {
        formAlert.innerHTML = msg;
        formAlert.className = `alert alert-${type}`;
        formAlert.style.display = 'block';
        formAlert.style.color = 'red';
    };
    const hideAlert = () => {
        formAlert.style.display = 'none';
    };

    const form_text = document.querySelectorAll(".form-texeepast");

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
    //地址選單
    const citysele = document.querySelector("#city-list");
    const distsele = document.querySelector("#dist-list");

    const city = <?= json_encode($city) ?>;
    const dist = <?= json_encode($dist) ?>
    //都市選擇
    let cityinner = "";
    for (let i = 0; i < city.length; i++) {
        cityinner = cityinner + '<option value=' + city[i] + '>' + city[i] + '</option>';
    }
    citysele.innerHTML = `<option value="none" selected disabled hidden>縣市</option>` + cityinner;

    //鄉鎮區選擇
    function citychange(ind) {
        let distinner = "";
        let dist2 = dist.filter((el) => el.parent_sid == (ind));

        for (let i = 0; i < dist2.length; i++) {
            distinner = distinner + '<option value=' + dist2[i].ct_name + '>' + dist2[i].ct_name + '</option>'
        };
        distsele.innerHTML = `<option value="none" selected disabled hidden>區鄉鎮</option>` + distinner;
    }

    citychange(document.getElementById("city-list").selectedIndex);
    // 這裡呼叫一次"typechange"這方法，讓瀏覽器在讀完XML後可以直接讓系所的資料出來






    //表單送出
    const checkForm = function(event) {
        let p2 = document.form1.password2;
        event.preventDefault();
        // 欄位外觀回復原來的樣子
        document.form1.querySelectorAll(`input`).forEach(el => {
            el.style.border = '1px solid #ced4da';
            form_text[0].innerHTML = '';
            form_text[1].innerHTML = '';
            form_text[2].innerHTML = '';
            form_text[3].innerHTML = '';
            form_text[4].innerHTML = '';
        })
        p2.style.border = '1px solid #ced4da';

        // TODO: 欄位檢查

        let isPass = true;

        let field = document.form1.name;
        if (field.value.length < 2) {
            isPass = false;
            field.style.border = '2px solid red';
            form_text[0].innerHTML = '請輸入正確姓名';
        }
        field = document.form1.email;
        if (!email_re.test(field.value)) {
            isPass = false;
            field.style.border = '2px solid red';
            form_text[1].innerHTML = '請輸入正確email';
        }
        field = document.form1.mobile;
        if (!mobile_re.test(field.value)) {
            isPass = false;
            field.style.border = '2px solid red';
            form_text[4].innerHTML = '請輸入正確手機號碼';
        }
        field = document.form1.password;
        if (field.value.match(passw)) {
            isPass = true;
            if (field.value === p2.value) {
                isPass = true;
            } else {
                isPass = false;
                p2.style.border = '2px solid red';
                form_text[3].innerHTML = '兩次密碼輸入不同';
            }
        } else {
            isPass = false;
            field.style.border = '2px solid red';
            form_text[2].innerHTML = '請設定至少含1個大寫字母及1個小寫字母1個數字,長度6-12位的密碼';
        }








        if (isPass) {
            const fd = new FormData(document.form1);

            fetch('register-api.php', {
                method: 'POST',
                body: fd,
            }).then(r => r.json()).then(obj => {
                console.log(obj);

                if (obj.success) {
                    showAlert('註冊成功!', 'succ');
                    setTimeout("location.href='email-verify.php'", 1300);
                } else {
                    for (let id in obj.errors) {
                        const field = document.querySelector(`#${id}`);
                        field.style.border = '2px solid red';
                        field.nextElementSibling.innerHTML = obj.error[id];
                    }
                    if (obj.msg) {
                        showAlert(obj.msg);
                    }

                }
            })
        }
    }
</script>
<?php include __DIR__ . '/parts/html-foot.php'; ?>