<?php

if(!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

$admin_login=strip_tags(trim(@$_POST['admin_login']));
$admin_password=@$_POST['admin_password'];
$admin_password_repeat=@$_POST['admin_password_repeat'];
$admin_name=@$_POST['admin_name'];
$admin_surname=@$_POST['admin_surname'];
$admin_type=@$_POST['admin_type'];
$admin_block=@$_POST['admin_block'];
$add_admin=@$_POST['add_admin'];
$datetime=date('Y-m-d H:i:s');
$err=0;

global $errors;

$admin_block=(strlen($admin_block)>0)?'yes':'no';

$sfal=$pdo->query("SELECT `admin_login` FROM `cs_admin_list` WHERE `admin_login`=".$pdo->quote(mb_strtolower($admin_login))." LIMIT 1") or die;
$rfal=$sfal->fetch(PDO::FETCH_ASSOC);

if(isset($add_admin)) {


    $serialize_priv =  stripslashes(serialize(@$_POST['privileges']));




    @$_SESSION[CMS::$sess_hash]['add_admin_login']=$admin_login;
    @$_SESSION[CMS::$sess_hash]['add_admin_name']=$admin_name;
    @$_SESSION[CMS::$sess_hash]['add_admin_surname']=$admin_surname;
    @$_SESSION[CMS::$sess_hash]['add_admin_type']=$admin_type;
    @$_SESSION[CMS::$sess_hash]['add_admin_block']=$admin_block;

    if(!$admin_login || !$admin_password || !$admin_password_repeat || !$admin_name || !$admin_surname || !$admin_type){
        $errors.='Заполните все поля.<br />';
        $err++;
    }

    if(strlen($admin_password)>0){
        if($admin_password!=$admin_password_repeat){
            $errors.='Введенные вами пароли не совпадают.<br />';
            $err++;
        }
    }

    if(strlen($admin_password)>0 AND strlen($admin_password)<6){
        $errors.='Пароль должен быть не менее 6 символов.<br />';
        $err++;
    }

    if(strlen($admin_login)>0){
        if($rfal['admin_login']==$admin_login){
            $errors.='Логин с таким именем уже существует.<br />';
            $err++;
        }
    }


    if($err==0){


        $stmt = $pdo->prepare("INSERT INTO `cs_admin_list`
		 (
		 `admin_block`,
		 `admin_type` ,
		 `admin_login`,
		 `admin_password`,
		 `admin_name`,
		 `admin_surname`,
		 `admin_reg_date`,
		 `admin_priviliges`

		 ) value

		 (
		 :admin_block,
		 :admin_type,
		 :admin_login,
		 :admin_password,
		 :admin_name,
		 :admin_surname,
		 :admin_reg_date,
		 :admin_priviliges

		 )");

        $stmt->bindParam(':admin_block', $admin_block);
        $stmt->bindParam(':admin_type', $admin_type);
        $stmt->bindParam(':admin_login', $admin_login);
        $stmt->bindParam(':admin_password', $admin_password);
        $stmt->bindParam(':admin_name', $admin_name);
        $stmt->bindParam(':admin_surname', $admin_surname);
        $stmt->bindParam(':admin_reg_date', $admin_reg_date);
        $stmt->bindParam(':admin_priviliges', $admin_priviliges);


        $admin_block = htmlspecialchars($admin_block);
        $admin_type = htmlspecialchars($admin_type);
        $admin_login = htmlspecialchars($admin_login);
        $admin_password = htmlspecialchars(md5(md5($admin_password.'13').md5($admin_password.'30').'17'));
        $admin_name = htmlspecialchars($admin_name);
        $admin_surname = htmlspecialchars($admin_surname);
        $admin_reg_date = strip_tags($datetime);
        $admin_priviliges = $serialize_priv;

        $stmt->execute();



        unset($_SESSION[CMS::$sess_hash]['add_admin_login']);
        unset($_SESSION[CMS::$sess_hash]['add_admin_name']);
        unset($_SESSION[CMS::$sess_hash]['add_admin_surname']);
        unset($_SESSION[CMS::$sess_hash]['add_admin_type']);
        unset($_SESSION[CMS::$sess_hash]['add_admin_block']);


        ?>
        <script>
            alert('Добавлено успешно.');
            window.location="index.php?page=list_admin_user";
        </script>
    <?php

    }

}

?>
<div class="content-box">
    <div class="content-box-header">
        <h3>Добавить админ пользователя</h3>
        <div class="clear"></div>
    </div>
    <div class="content-box-content">

        <?php
        if(isset($add_admin) AND $err>0){
            ?>
            <div class="error_div">
                <?php echo $errors; ?>
            </div>
        <?php
        }
        ?>




        <form method="POST">


            <div class="user_priv">

                <span>Привилегии пользователя</span>
                <div>
                    <p>
                        <label><input type="checkbox" class="ckbx" name="privileges[resource]" value="resource">&nbsp;&nbsp;Ресурсы</label>
                    </p>
                    <p>
                        <label><input type="checkbox" class="ckbx" name="privileges[brands]" value="brands">&nbsp;&nbsp;Бренды</label>
                    </p>
                    <p>
                        <label><input type="checkbox" class="ckbx" name="privileges[products]" value="products">&nbsp;&nbsp;Продукты</label>
                    </p>
                    <p>
                        <label><input type="checkbox" class="ckbx" name="privileges[banners]" value="banners">&nbsp;&nbsp;Баннеры</label>
                    </p>
                    <p>
                        <label><input type="checkbox" class="ckbx" name="privileges[statistics]" value="statistics">&nbsp;&nbsp;Статистика</label>
                    </p>
                    <p>
                        <label><input type="checkbox" class="ckbx" name="privileges[users]" value="users">&nbsp;&nbsp;Пользователи</label>
                    </p>
                    <p>
                        <label><input type="checkbox" class="ckbx" name="privileges[orders]" value="orders">&nbsp;&nbsp;Заказы</label>
                    </p>
                    <p>
                        <label><input type="checkbox" class="ckbx" name="privileges[administration]" value="administration">&nbsp;&nbsp;Администрация</label>
                    </p>
                    <p>
                        <label><input type="checkbox" class="ckbx" name="privileges[settings]" value="settings">&nbsp;&nbsp;Настройки</label>
                    </p>


                    <div class="clear"></div>

                </div>

            </div>

            <p>
                <label>Логин</label>
                <input class="text-input small-input" value="<?php echo @$_SESSION[CMS::$sess_hash]['add_admin_login']; ?>" type="text" name="admin_login" />
            </p>

            <p>
                <label>Пароль</label>
                <input class="text-input small-input" type="password" name="admin_password" />
            </p>

            <p>
                <label>Пароль повторно</label>
                <input class="text-input small-input" type="password" name="admin_password_repeat" />
            </p>

            <p>
                <label>Имя</label>
                <input class="text-input small-input" value="<?php echo @$_SESSION[CMS::$sess_hash]['add_admin_name']; ?>" type="text" name="admin_name" />
            </p>

            <p>
                <label>Фамилия</label>
                <input class="text-input small-input" value="<?php echo @$_SESSION[CMS::$sess_hash]['add_admin_surname']; ?>" type="text" name="admin_surname" />
            </p>

            <p>
                <label>Тип</label>
                <select name="admin_type" class="small-input" id="admin_editor">
                    <?php

                    $sfat=$pdo->query("SELECT * FROM `cs_admin_type` ORDER BY `admin_type_id` DESC");
                    while($rfat=$sfat->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <option value="<?php echo $rfat['admin_type']; ?>"><?php echo $rfat['admin_type_name']; ?></option>
                    <?php
                    }

                    ?>
                </select>

                <script language="javascript" type="text/javascript">

                    var privileges_block = $('.user_priv');
                    var admin_type = $("select#admin_editor option:selected").attr('value');

                    if( admin_type == 'admin' )
                    {
                        privileges_block.css('display','none');
                    }
                    else
                    {
                        privileges_block.css('display','block');
                    }


                    $('#admin_editor').change(function(){

                        var privileges_block = $('.user_priv');
                        var admin_type = $("select#admin_editor option:selected").attr('value');


                        if( admin_type == 'admin' )
                        {
                            privileges_block.css('display','none');
                        }
                        else
                        {
                            privileges_block.css('display','block');
                        }
                    });



                </script>


            </p>

            <p>
                <label>Доступ</label>
                <input type="checkbox" name="admin_block" /> Закрыть доступ для этого пользователя.
            </p>

            <p>
                <input class="button" type="submit" name="add_admin" value="Сохранить" class="submit_btn"/>
            </p>

        </form>


        <div class="clear"></div>
    </div>
</div>