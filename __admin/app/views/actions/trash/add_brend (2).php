<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');

	$brend_name=@$_POST['brend_name'];
	$ok_brend=@$_POST['ok_brend'];
	$datetime=date('Y-m-d H:i:s');
	$icon=@$_POST['icon'];
	$err=0;
	
	global $errors;

	if(isset($ok_brend)){

/*
		if(is_file($_FILES["icon"]["tmp_name"])){
			
			$file=@$_FILES["icon"]["tmp_name"];
			$fot='brend_'.date('Ymdhis').'.png';
			$upload_icon="`brend_icon_url`='".$fot."',";
				
			@mkdir($_SERVER['DOCUMENT_ROOT']."/uploads/icons/", 0777, true);
			@resize($file, $_SERVER['DOCUMENT_ROOT']."/uploads/icons/".$fot, 128);
				
		}
		if(!is_file($_FILES["icon"]["tmp_name"])){
			$upload_icon='';
		}

*/
        //$add_q = $pdo->prepare("INSERT INTO `cs_brend_list` (`brend_name` , `brend_ins_date` , `brend_url`) VALUES (:brend_name , :brend_ins_date , :brend_url)");
        $add_q = $pdo->prepare("INSERT INTO `cs_brend_list` (`brend_name` , `brend_ins_date`) VALUES (:brend_name , :brend_ins_date)");

        $add_q->bindParam(':brend_name', $brend_name);
        $add_q->bindParam(':brend_ins_date' , $brend_ins_date);
        //$add_q->bindParam(':brend_url' , $brend_url);

        $brend_name = htmlspecialchars($brend_name);
        $brend_ins_date = htmlspecialchars($datetime);
        //$brend_url = $fot;

        $add_q->execute();

 
        ?>
        <script>
            window.location="index.php?page=list_brend";
        </script>
        <?php

	}

?>
<div class="content-box">	
	<div class="content-box-header">
		<h3>Добавить бренд</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content">
		
		<form method="POST"  enctype="multipart/form-data">
		
			<p>
				<label>Название</label>
				<input class="text-input small-input" type="text" name="brend_name" />
			</p>
			
			<!--<p>
				<label>Иконка</label>
				<input type="file" name="icon" />
			</p>-->

			<p>
				<input class="button" type="submit" name="ok_brend" value="Сохранить" />
			</p>
		
		</form>
			
		<div class="clear"></div>
	</div>
</div>