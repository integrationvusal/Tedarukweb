<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');

?>
<div class="content-box">	
	<div class="content-box-header">
		<h3>Редактирования галереи</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content">
			  <?
     			$query="SELECT * FROM `cs_gallery_folder`";
					$res=$pdo->query($query);
				
											
					$setir=count($res->fetchAll(PDO::FETCH_ASSOC));
                    if($setir >0){
        
            ?>		
			<table width="100%" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th>ID</th>
						<th>Название</th>
						<th>Раздел</th>
						<th>Управление</th>
					</tr>
				</thead>
				<tbody>
				<?php
				
					$sfcatl=$pdo->query("SELECT * FROM `cs_gallery_folder` ORDER BY `gallery_folder_id` DESC");
						while($rfcatl=$sfcatl->fetch(PDO::FETCH_ASSOC)){
						
							$sfcl=$pdo->query("SELECT * FROM `cs_content_list` WHERE `content_id`=".(int)$rfcatl['content_id']." AND `content_delete`='no'");
								$rfcl=$sfcl->fetch(PDO::FETCH_ASSOC);
								
								$cat_name=(strlen($rfcl['content_pagetitle_'.DEFAULT_LANG_DIR])>0)?$rfcl['content_pagetitle_'.DEFAULT_LANG_DIR]:' ----- ';
								
						?>
						<tr>
							<td width="7%"><?php echo $rfcatl['gallery_folder_id']; ?></td>
							<td><a href="index.php?page=list_gallery_folder&id=<?php echo $rfcatl['gallery_folder_id']; ?>"><?php echo $rfcatl['gallery_folder_name_'.DEFAULT_LANG_DIR.'']; ?></a></td>
							<td><?php echo $cat_name; ?></td>
							<td width="18%">
								<a href="index.php?page=edit_gallery_folder&id=<?php echo $rfcatl['gallery_folder_id']; ?>"><img src="<?=IMAGE_DIR;?>edit.png" class="list-ico" title="Редакотровать" /></a>
								<a onclick="return confirm('Вы уверены что хотите удалить ?')" href="index.php?page=list_gallery&delete=<?php echo $rfcatl['gallery_folder_id']; ?>" title="Удалить"><img src="<?=IMAGE_DIR;?>icons/cross.png" alt="Delete" /></a>
							</td>
						</tr>
						<?php
						
							if($rfcatl['gallery_folder_id']==@$_GET['delete']){

								$pdo->query("DELETE FROM `cs_gallery_folder` WHERE `gallery_folder_id`=".(int)$_GET['delete']." LIMIT 1");
								
								/*
									@unlink(@$_SERVER['DOCUMENT_ROOT'].'/uploads/gallery/big/'.(int)$rfcatl['gallery_folder_id']);
									@unlink(@$_SERVER['DOCUMENT_ROOT'].'/uploads/gallery/small/'.(int)$rfcatl['gallery_folder_id']);
								*/
								
								?>
								<script>
									alert('Удалено.');
									window.location="index.php?page=list_gallery";
								</script>
								<?php
							}
						
						}
					
				?>
				</tbody>
			</table>
			
		<div class="clear"></div>
        <?}else{
            
            	echo'<div class="no_table_info">Информация отсутствует</div>';
        }?>
	</div>
    
</div>