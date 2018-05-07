<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');

	$sfset = $pdo->query("SELECT * FROM `cs_site_settings` ORDER BY `settings_id` ASC LIMIT 1");
		$rfset=$sfset->fetch(PDO::FETCH_ASSOC);
 
	$default_name=@$_POST['default_name'];
	$default_page=@$_POST['default_page'];
	$default_email=@$_POST['default_email'];
	$default_name=@$_POST['default_name'];
	$default_template=@$_POST['default_template'];
	$default_lang=@$_POST['default_lang'];
	$default_pay=@$_POST['default_pay'];
	$page_null_az=@$_POST['page_null_az'];
	$page_null_ru=@$_POST['page_null_ru'];
	$page_null_en=@$_POST['page_null_en'];
    $site_hot_line=@$_POST['hot_line'];

    $default_site_lang = @$_POST['default_site_lang'];
    $default_site_exchange = @$_POST['default_site_exchange'];

	$ok_settings=@$_POST['ok_settings'];
	$err=0;
	
	global $errors;
	
	$f_mail=preg_match('|([a-z0-9_\.\-]{1,20})@([a-z0-9\.\-]{1,20})\.([a-z]{2,4})|is', $default_email);
	
	if(isset($ok_settings)){
		
		if(!$default_name || !$default_email  ||  !$site_hot_line){
			$errors.='Заполните все поля';
			$err++;
		}
		
		if(strlen($default_email)>0){
			if($f_mail==''){
				$errors.='Введите правильный почтовый адрес.<br />';
				$err++;
			}
		}
		
		if($err==0){
			
			$pdo->query("UPDATE `cs_site_settings` SET
			`site_default_name`=".$pdo->quote(htmlspecialchars($default_name)).",
			`site_hot_line`=".$pdo->quote(htmlspecialchars($site_hot_line)).",

			`default_lang`=".$pdo->quote(htmlspecialchars($default_site_lang)).",
			`default_exchange`=".$pdo->quote(htmlspecialchars($default_site_exchange)).",


			`site_default_email`='".htmlspecialchars($default_email)."' WHERE `settings_id`=1 LIMIT 1");

            // Adding logo start
            if ((($_FILES["logo"]["type"] == "image/gif")
                || ($_FILES["logo"]["type"] == "image/jpg")
                || ($_FILES["logo"]["type"] == "image/jpeg")
                || ($_FILES["logo"]["type"] == "image/png")))
            {
                if ($_FILES["logo"]["error"] > 0)
                {
                    echo "Error Code: " . $_FILES["logo"]["error"] . "<br>";
                }
                else
                {
                    $logo = date('Y_m_d_h_i_s').'_'.$_FILES["logo"]["name"];
                    if(move_uploaded_file( $_FILES["logo"]["tmp_name"], $_SERVER['DOCUMENT_ROOT']."/application/media/img/".$logo ))
                    {
                         $pdo->query("UPDATE `cs_site_settings` SET `logo`=".$pdo->quote($logo)."  WHERE `settings_id` = 1 LIMIT 1");

                    } // if end
                }
            }
            else
            {
                echo "Invalid logo";
            }
            // Adding logo start


			?>
			<script>
				alert('Сохранено успешно.');
				window.location="index.php?page=site_settings";
			</script>
			<?php
			
		}


		
	} // ok end
	
?>
<div class="content-box">
	<div class="content-box-header">
		<h3>Параметры сайта</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content">
        <?php
         $query_for_logo = $pdo->query("SELECT `logo` FROM `cs_site_settings` WHERE `settings_id`=1 LIMIT 1")->fetch(PDO::FETCH_ASSOC);

        ?>

        <div style="outline: 1px #e0e0e0 solid; width: 230px; height: 70px; position: absolute; top: 10px; right: 10px;">
            <img src="<?php echo "http://www.".$_SERVER['SERVER_NAME']."/img/".$query_for_logo['logo']; ?>" alt="Логотип" title="Логотип"/>
        </div>

	
		<?php
			if(isset($ok_settings) AND $err>0){
			?>
			<div class="error_div">
			<?php echo $errors; ?>
			</div>
			<?php
			}
		?>
			
		<form method="POST" enctype="multipart/form-data">
	
			<p>
				<label>Название сайта</label>
				<input class="text-input small-input" type="text" value="<?php echo $rfset['site_default_name']; ?>" name="default_name" />
			</p>
			<p>
				<label>Горячая линия</label>
				<input class="text-input small-input" type="text" value="<?php echo $rfset['site_hot_line']; ?>" name="hot_line" />
			</p>

			<p>
				<label>Язык по умолчанию</label>
				<!--<input class="text-input small-input" type="text" value="" name="default_site_lang" />-->
                <select name="default_site_lang">
                    <option value="az" <?php echo ($rfset['default_lang'] == 'az') ? 'selected="selected"' : ' ' ; ?> >AZE</option>
                    <option value="ru" <?php echo ($rfset['default_lang'] == 'ru') ? 'selected="selected"' : ' ' ; ?> >RUS</option>
                    <option value="en" <?php echo ($rfset['default_lang'] == 'en') ? 'selected="selected"' : ' ' ; ?> >ENG</option>
                </select>
			</p>
			<p>
				<label>Валюта по умолчанию</label>
				<!--<input class="text-input small-input" type="text" value="" name="default_site_exchange" />-->
                <select name="default_site_exchange">
                    <option value="azn" <?php echo ($rfset['default_exchange'] == 'azn') ? 'selected="selected"' : ' ' ; ?> >AZN</option>
                    <option value="usd" <?php echo ($rfset['default_exchange'] == 'usd') ? 'selected="selected"' : ' ' ; ?> >USD</option>
                    <option value="eur" <?php echo ($rfset['default_exchange'] == 'eur') ? 'selected="selected"' : ' ' ; ?> >EUR</option>
                </select>
			</p>

			
			<p>
				<label>E-mail</label>
				<input class="text-input small-input" type="text" value="<?php echo $rfset['site_default_email']; ?>" name="default_email" />
			</p>

			<p>
				<label>Логотип (размер <span class="rezolution">225x66</span>)</label>
				<input type="file" name="logo" />
			</p>


			
			<!--<p>
				<label>Если нет информации выходит (AZ)</label>
				<input class="text-input small-input" type="text" value="<?php echo $rfset['page_null_az']; ?>" name="page_null_az" />
			</p>
			
			<p>
				<label>Если нет информации выходит (RU)</label>
				<input class="text-input small-input" type="text" value="<?php echo $rfset['page_null_ru']; ?>" name="page_null_ru" />
			</p>
			
			<p>
				<label>Если нет информации выходит (EN)</label>
				<input class="text-input small-input" type="text" value="<?php echo $rfset['page_null_en']; ?>" name="page_null_en" />
			</p>-->
			
			<!--<p>
				<label>Язык по умолчанию</label>
				<select name="default_lang" class="small-input">
				<?php
							
					$sflang=$pdo->query("SELECT * FROM `cs_language_list` ORDER BY `language_id` ASC");
						while($rflang=$sflang->fetch(PDO::FETCH_ASSOC)){
							
							$select_lang=($rfset['site_default_lang_dir']==$rflang['language_dir'])?'SELECTED="SELECTED"':'';
								
						?>
						<option <?php echo $select_lang; ?> value="<?php echo $rflang['language_dir']; ?>"><?php echo $rflang['language_name']; ?></option>
						<?php
						}
				?>
				</select> 
			</p>-->

			<p>
				<input class="button" type="submit" name="ok_settings" value="Сохранить" />
			</p>
		
		</form>
			
		<div class="clear"></div>
	</div>
</div>