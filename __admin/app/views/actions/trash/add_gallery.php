<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');

	$upload_image[]=@$_POST['upload_image'];
	$photo[]=@$_POST['photo'];
	$datetime=date('Y-m-d H:i:s');
	$folder_id=@$_POST['folder_id'];
	$ok=@$_POST['ok'];

?>
<script type="text/javascript">
    var pc = 0;

    function addphoto()
    {
        var pd = document.getElementById("photodiv");
        var newdiv = document.createElement('div');
        newdiv.setAttribute('id',"pdc' + pc + '");
        newdiv.innerHTML =  '<a href="javascript:removephoto(' + pc + ')"></a><input style="padding: 3px 0px;" type="file" name="photo[]">';
        pd.appendChild(newdiv);
        pc++;
		
		if(pc >= 20){
			alert('Вы не можете загрузить более 20 фотографий за один раз')
		}
    }
</script> 
<div class="content-box">	
	<div class="content-box-header">
		<h3>Добавить картинку</h3>
	</div>
	<div class="content-box-content">
		<div class="tab-content default-tab" id="tab1">
		
			<div>
				<h3 style="color: #FF0000;">За один раз более чем 20 картинок не будут загружатся!</h3>
			</div>
			
			<form method="POST"  enctype="multipart/form-data">
			<?php
				
				if($ok){
				
					for($i=0; $i<=20; $i++){
						
						if(is_file(@$_FILES["photo"]["tmp_name"]["$i"])){	
							
							if(is_file(@$_FILES["photo"]["tmp_name"]["$i"])){
								$file=@$_FILES["photo"]["tmp_name"]["$i"];
								$fot[$i]='photo_'.date('Ymdhis').$i.'.png';
								$upload_image="`gallery_photo_url`=".$pdo->quote($fot[$i]).",";
							}
							if(!is_file(@$_FILES["photo"]["tmp_name"]["$i"])){
								$upload_image="";
							}

							@mkdir($_SERVER['DOCUMENT_ROOT']."/uploads/gallery/big/".(int)$folder_id, 0777, true);
							@mkdir($_SERVER['DOCUMENT_ROOT']."/uploads/gallery/small/".(int)$folder_id, 0777, true);
							@resize($file, $_SERVER['DOCUMENT_ROOT']."/uploads/gallery/big/".(int)$folder_id."/".$fot[$i], 700);
							@resize($file, $_SERVER['DOCUMENT_ROOT']."/uploads/gallery/small/".(int)$folder_id."/".$fot[$i], 150);
							
							$ap=$pdo->query("INSERT INTO `cs_gallery_list` SET
							`folder_id`=".(int)$folder_id.",
							".$upload_image."
							`gallery_ins_date`='".$datetime."'");
						}
					}
					?>
					<script>
						alert('Добавлено.')
					</script>
					<?php
				}
				?>
					<a href="javascript:addphoto()"><b>+ добавить фотографию</b></a>
						<div id="photodiv"></div>
					
					<p>
						<label>Папка картинок</label>              
						<select name="folder_id" class="small-input">
							<?php 
								$sfgp=$pdo->query("SELECT * FROM `cs_gallery_folder` ORDER BY `gallery_folder_id` DESC");
								while($mpr=$sfgp->fetch(PDO::FETCH_ASSOC)){
							?>
								<option value="<?php echo $mpr['gallery_folder_id']; ?>"><?php echo $mpr['gallery_folder_name_'.DEFAULT_LANG_DIR]; ?></option>
							<?php
								}
							?>
						</select> 
					</p>
				
					<p>
						<input class="button" type="submit" name="ok" value="Сохранить" />
					</p>
							
				<div class="clear"></div>
			</form>
		</div>  
	</div>
</div>