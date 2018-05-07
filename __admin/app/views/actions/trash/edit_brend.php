<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');


    if( isset($_GET['id']) && !empty($_GET['id']) )
    {
        $id = (int)$_GET['id'];
    }

    if( isset($_GET['action']) && ($_GET['action'] == 'delete') )
    {
        $pdo->exec("DELETE FROM `cs_brend_list` WHERE `brend_id`=".$pdo->quote($id)."");

        echo '<script type="text/javascript" language="javascript">window.location = "index.php?page=list_brend"</script>';
    }


    $query3 = $pdo->query("SELECT * FROM `cs_brend_list` WHERE `brend_id`=".$pdo->quote($id)."")->fetch(PDO::FETCH_ASSOC);


$brend_name = @$_POST['brend_name'];
$ok_brend = @$_POST['ok_brend'];
$datetime = date('Y-m-d H:i:s');
$icon = @$_POST['icon'];


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

        //$stmt = $pdo->prepare("UPDATE `cs_brend_list` SET `brend_name` = :brend_name , `brend_url` = :brend_url WHERE `brend_id`=:brend_id");
        $stmt = $pdo->prepare("UPDATE `cs_brend_list` SET `brend_name` = :brend_name  WHERE `brend_id`=:brend_id");

        $stmt->bindParam(':brend_name', $brend_name);
        $stmt->bindParam(':brend_id', $brend_id);


/*
        if( $_FILES['icon']['size'] != 0)
        {
            // Загружено новое фото
            $stmt->bindParam(':brend_url', $brend_url);

        }
        else
        {
            // Новое фото не загружено
            $stmt->bindParam(':brend_url', $query3['brend_url']);
        }
*/
        $brend_name = $brend_name;
        $brend_id = $id;
        //@$brend_url = $fot;

        $stmt->execute();


		?>
		<script>;
			window.location="index.php?page=list_brend";
		</script>
		<?php
	
	}

?>
<div class="content-box">	
	<div class="content-box-header">
		<h3>Редактировать бренд</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content">


       <!-- <div class="brend_preview">
            <img src="<?='/uploads/icons/'.$query3['brend_url']?>" />
        </div>
		-->
		<form method="POST"  enctype="multipart/form-data">
		
			<p>
				<label>Название</label>
				<input class="text-input small-input" type="text" value="<?php echo $query3['brend_name']; ?>" name="brend_name" />
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