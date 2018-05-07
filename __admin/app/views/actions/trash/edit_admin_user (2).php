<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');


if( isset($_GET['id']) && (!empty($_GET['id'])) )
{
  $id = (int)$_GET['id'];
}
else
{
    die('Неверные параметры скрипта');
}



if( isset($_GET['action']) && ($_GET['action'] == 'delete') )
{
    $del_res = $pdo->exec("DELETE FROM `cs_admin_list` WHERE `admin_id`=".$pdo->quote($id)."");
    echo "<script language='javascript' type='text/javascript'>window.location = 'index.php?page=list_admin_user'</script>";
}




	$sfal2=$pdo->query("SELECT * FROM `cs_admin_list` WHERE `admin_id`=".(int)$_GET['id']." AND `admin_id`!=1 LIMIT 1");
		$rfal2=$sfal2->fetch(PDO::FETCH_ASSOC);

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
	$sel_admin_block=($rfal2['admin_block']=='yes')?'CHECKED="CHECKED"':'';
	
	if(isset($add_admin)){
		
		if(!$admin_name || !$admin_surname || !$admin_type){
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

		if($err==0){
			
			$edit_pass_filter=(strlen($admin_password)>0 AND strlen($admin_password_repeat)>0)?"`admin_password`=".$pdo->quote(htmlspecialchars(md5(md5($admin_password.'13').md5($admin_password.'30').'17'))).",":"";
		
		    
			
			
			
			/*
			
			$admin_block = htmlspecialchars($admin_block);
			$admin_type = htmlspecialchars($admin_type);
			$admin_name = htmlspecialchars($admin_name);
			$admin_priviliges = serialize($_POST['privileges']);
			$admin_surname = htmlspecialchars($admin_surname);
			$admin_id = (int)$_GET['id'];
			
			
			$sql = "UPDATE `cs_admin_list` 
					SET 
					`admin_block`=?,
					`admin_type`=?,
					`admin_name`=?,
					`admin_priviliges`=?,
					`admin_surname`=?,
					WHERE `admin_id`=?";
					
			$q = $pdo->prepare($sql);
			$q->execute(array( $admin_block, $admin_type, $admin_name, $admin_priviliges, $admin_surname, $admin_id ));
*/
			
			
			
			@$pdo->query("UPDATE `cs_admin_list` SET
			`admin_block`=".$pdo->quote(htmlspecialchars($admin_block)).",
			".$edit_pass_filter."
			`admin_type`=".$pdo->quote(htmlspecialchars($admin_type)).",
			`admin_name`=".$pdo->quote(htmlspecialchars($admin_name)).",
			`admin_priviliges`=".$pdo->quote(addslashes(serialize($_POST['privileges']))).",
			`admin_surname`=".$pdo->quote(htmlspecialchars($admin_surname))." WHERE `admin_id`=".(int)$_GET['id']." AND `admin_id`!=1 LIMIT 1");
			
				?>
				<script>
					alert('Сохранено успешно.');
					window.location="index.php?page=list_admin_user";
				</script>
				<?php
		
		}
		
	}

?>


<div class="content-box">	
	<div class="content-box-header">
		<h3>Редактировать админ пользователя</h3>
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
			
			<?php

            if( $rfal2['admin_type'] == 'editor' )
            {


                $priv_array = @unserialize($rfal2['admin_priviliges']);


                if(  @in_array( 'resource' , $priv_array )  )
                {
                    $has_resource = TRUE;
                }
                else
                {
                    $has_resource = FALSE;
                }



                if(  @in_array( 'brands' , $priv_array )  )
                {
                    $has_brands = TRUE;
                }
                else
                {
                    $has_brands = FALSE;
                }



                if(  @in_array( 'products' , $priv_array )  )
                {
                    $has_products = TRUE;
                }
                else
                {
                    $has_products = FALSE;
                }



                if(  @in_array( 'banners' , $priv_array )  )
                {
                    $has_banners = TRUE;
                }
                else
                {
                    $has_banners = FALSE;
                }



                if(  @in_array( 'statistics' , $priv_array )  )
                {
                    $has_statistics = TRUE;
                }
                else
                {
                    $has_statistics = FALSE;
                }



                if(  @in_array( 'users' , $priv_array )  )
                {
                    $has_users = TRUE;
                }
                else
                {
                    $has_users = FALSE;
                }



                if(  @in_array( 'orders' , $priv_array )  )
                {
                    $has_orders = TRUE;
                }
                else
                {
                    $has_orders = FALSE;
                }



                if(  @in_array( 'administration' , $priv_array )  )
                {
                    $has_administration = TRUE;
                }
                else
                {
                    $has_administration = FALSE;
                }


                if(  @in_array( 'settings' , $priv_array )  )
                {
                    $has_settings = TRUE;
                }
                else
                {
                    $has_settings = FALSE;
                }

                $ch_str = ' checked="checked"';

                ?>
                <div class="user_priv">

                    <span>Привилегии пользователя</span>
                    <div>
                        <p>
                            <label><input type="checkbox"  <?php echo ($has_resource) ? $ch_str : '' ; ?> class="ckbx" name="privileges[resource]" value="resource">&nbsp;&nbsp;Ресурсы</label>
                        </p>
                        <p>
                            <label><input type="checkbox" <?php echo ($has_brands) ? $ch_str : '' ; ?> class="ckbx" name="privileges[brands]" value="brands">&nbsp;&nbsp;Бренды</label>
                        </p>
                        <p>
                            <label><input type="checkbox"  <?php echo ($has_products) ? $ch_str : '' ; ?> class="ckbx" name="privileges[products]" value="products">&nbsp;&nbsp;Продукты</label>
                        </p>
                        <p>
                            <label><input type="checkbox"  <?php echo ($has_banners) ? $ch_str : '' ; ?>  class="ckbx" name="privileges[banners]" value="banners">&nbsp;&nbsp;Баннеры</label>
                        </p>
                        <p>
                            <label><input type="checkbox"  <?php echo ($has_statistics) ? $ch_str : '' ; ?>  class="ckbx" name="privileges[statistics]" value="statistics">&nbsp;&nbsp;Статистика</label>
                        </p>
                        <p>
                            <label><input type="checkbox"  <?php echo ($has_users) ? $ch_str : '' ; ?>   class="ckbx" name="privileges[users]" value="users">&nbsp;&nbsp;Пользователи</label>
                        </p>
                        <p>
                            <label><input type="checkbox"  <?php echo ($has_orders) ? $ch_str : '' ; ?>  class="ckbx" name="privileges[orders]" value="orders">&nbsp;&nbsp;Заказы</label>
                        </p>
                        <p>
                            <label><input type="checkbox"  <?php echo ($has_administration) ? $ch_str : '' ; ?>  class="ckbx" name="privileges[administration]" value="administration">&nbsp;&nbsp;Администрация</label>
                        </p>
                        <p>
                            <label><input type="checkbox"   <?php echo ($has_settings) ? $ch_str : '' ; ?>  class="ckbx" name="privileges[settings]" value="settings">&nbsp;&nbsp;Настройки</label>
                        </p>


                        <div class="clear"></div>

                    </div>

                </div>
          <?  }?>


			


			
			
			
			
	
			<p>
				<label>Логин</label>
				<input class="text-input small-input" value="<?php echo $rfal2['admin_login']; ?>" type="text" name="admin_login" disabled="disabled" />
			</p>
			
			<p>
				<label>Новый пароль</label>
				<input class="text-input small-input" type="password" name="admin_password" />
			</p>
			
			<p>
				<label>Новый пароль повторно</label>
				<input class="text-input small-input" type="password" name="admin_password_repeat" />
			</p>

			<p>
				<label>Имя</label>
				<input class="text-input small-input" value="<?php echo $rfal2['admin_name']; ?>" type="text" name="admin_name" />
			</p>
			
			<p>
				<label>Фамилия</label>
				<input class="text-input small-input" value="<?php echo $rfal2['admin_surname']; ?>" type="text" name="admin_surname" />
			</p>
			
			<p>
				<label>Тип</label>
				<select name="admin_type" class="small-input">
				<?php
				
					$sfat=$pdo->query("SELECT * FROM `cs_admin_type` ORDER BY `admin_type_id` DESC");
						while($rfat=$sfat->fetch(PDO::FETCH_ASSOC)){
						
							$sel_admin_type=($rfat['admin_type']==$rfal2['admin_type'])?'SELECTED="SELECTED"':'';
						
						?>
						<option <?php echo $sel_admin_type; ?> value="<?php echo $rfat['admin_type']; ?>"><?php echo $rfat['admin_type_name']; ?></option>
						<?php
						}

				?>
				</select>
			</p>
			
			<p>
				<label>Доступ</label>
				<input <?php echo $sel_admin_block; ?> type="checkbox" name="admin_block" /> Закрыть доступ для этого пользователя.
			</p>

			<p>
				<input class="button" type="submit" name="add_admin" value="Сохранить" />
			</p>
		
		</form>
			
		<div class="clear"></div>
	</div>
</div>