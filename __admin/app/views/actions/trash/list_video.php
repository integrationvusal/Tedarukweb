<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');

?>
<div class="content-box">	
	<div class="content-box-header">
		<h3>Список видео</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content">
	<?
        	$query=$pdo->query("SELECT * FROM `cs_video_list` ORDER BY `video_id` DESC");
           $setir=count($query->fetchAll(PDO::FETCH_ASSOC));
           if($setir){
    ?>
			<table width="100%" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th>ID</th>
						<th>Название видео</th>
						<th>Раздел</th>
						<th>Управление</th>
					</tr>
				</thead>
				<tbody>
				<?php
				
					$sfcatl=$pdo->query("SELECT * FROM `cs_video_list` ORDER BY `video_id` DESC");
						while($rfcatl=$sfcatl->fetch(PDO::FETCH_ASSOC)){
						
							$sfcl=$pdo->query("SELECT * FROM `cs_content_list` WHERE `content_id`=".(int)$rfcatl['content_id']." AND `content_delete`='no'");
								$rfcl=$sfcl->fetch(PDO::FETCH_ASSOC);
								
								$cat_name=(strlen($rfcl['content_pagetitle_'.DEFAULT_LANG_DIR])>0)?$rfcl['content_pagetitle_'.DEFAULT_LANG_DIR]:' ----- ';
								
						?>
						<tr>
							<td width="7%"><?php echo $rfcatl['video_id']; ?></td>
							<td><?php echo $rfcatl['video_name_'.DEFAULT_LANG_DIR.'']; ?></td>
							<td><?php echo $cat_name; ?></td>
							<td width="18%">
								<a href="index.php?page=edit_video&id=<?php echo $rfcatl['video_id']; ?>"><img src="<?=IMAGE_DIR;?>edit.png" class="list-ico" title="Редакотровать" /></a>
								<a onclick="return confirm('Вы уверены что хотите удалить ?')" href="index.php?page=list_video&delete=<?php echo $rfcatl['video_id']; ?>" title="Удалить"><img src="<?=IMAGE_DIR;?>icons/cross.png" alt="Delete" /></a>
							</td>
						</tr>
						<?php
						
							if($rfcatl['video_id']==@$_GET['delete']){

								@$pdo->query("DELETE FROM `cs_video_list` WHERE `video_id`=".(int)$_GET['delete']." LIMIT 1");
								?>
								<script>
									alert('Удалено.');
									window.location="index.php?page=list_video";
								</script>
								<?php
							}
						
						}
					
				?>
				</tbody>
			</table>
			<?}else{?>
            <div class="no_table_info">Информация отсутствует</div>
            <?}?>
		<div class="clear"></div>
	</div>
</div>