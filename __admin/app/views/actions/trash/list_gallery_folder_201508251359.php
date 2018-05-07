<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');

?>
<div class="content-box">	
	<div class="content-box-header">
		<h3>Редактирования галереи</h3>
	</div>
	<div class="content-box-content">

        <?
     			$query="SELECT * FROM `cs_gallery_list` WHERE `folder_id`=".(int)$_GET['id']."";
					$res=$pdo->query($query);
				
											
					$setir=count($res->fetchAll(PDO::FETCH_ASSOC));
                    if($setir >0){
        
        ?>		
        <div class="tab-content default-tab" id="tab1">
			<table>
				<thead>
					<tr>
						<th>ID</th>
						<th>Названия</th>
						<th>Добавлен</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php
								
				$delete=@$_GET['delete'];
				$count=10;

					$cnt=100; 
					$rpp=10; 
					$id=@$_GET['id'];
					$rad=1;

					$link_sc="index.php?page=list_gallery_folder&id=".(int)$_GET['id']."";
									 
					if (isset($_GET['st'])){$page=$_GET['st']-1;}else{$page=0;}
												 
					$links=$rad*2+1;
					$pages=ceil($setir/$rpp);
					$start=$page-$rad;

					if ($start>$pages-$links){$start=$pages-$links;}
					if ($start<0) { $start=0; }
						$end=$start+$links;

					if ($end>$pages){ $end=$pages; }
						for ($j=$start; $j<$end; $j++){
							if ($j==$page){
													
								$sql="SELECT * FROM `cs_gallery_list` WHERE `folder_id`=".(int)$_GET['id']." ORDER BY `gallery_id` DESC LIMIT ".($count*$j)." ,".($count)." ";
									$res1=$pdo->query($sql);
									while($row=$res1->fetch(PDO::FETCH_ASSOC)){
									?>
									<tr>
										<td><?php echo $row['gallery_id']; ?></td>
										<td><a href="../uploads/gallery/big/<?php echo $row['folder_id']; ?>/<?php echo $row['gallery_photo_url']; ?>" class="thickbox" ><?php echo $row['gallery_photo_url']; ?></a></td>
										<td><?php echo $row['gallery_ins_date']; ?></td>
										<td>
										<?php 
											
											if(@$_GET['st']==''){$_GET['st']=1;}
														
										?>
											 <a onclick="return confirm('Вы уверены что хотите удалить картинку ?')" href="index.php?page=list_gallery_folder&id=<?php echo htmlspecialchars((int)$_GET['id']); ?>&st=<?php echo $_GET['st']; ?>&delete=<?php echo $row['gallery_id']; ?>" title="Delete"><img src="<?=IMAGE_DIR;?>icons/cross.png" alt="Delete" /></a> 
										</td>
									</tr>
									<?php
										
										if($row['gallery_id']==$delete){
										
										@mysql_query("DELETE FROM `cs_gallery_list` WHERE `gallery_id`=".(int)@$_GET['delete']." LIMIT 1") or die (mysql_error());
										
										@unlink('../uploads/gallery/big/'.(int)$row['folder_id'].'/'.$row['gallery_photo_url']);
										@unlink('../uploads/gallery/small/'.(int)$row['folder_id'].'/'.$row['gallery_photo_url']);
										
										?>
										
										<script>
											alert('Картинка удалена успешно.');
											window.location="index.php?page=list_gallery_folder&id=<?php echo htmlspecialchars($_GET['id']); ?>&st=<?php echo htmlspecialchars($_GET['st']); ?>";
										</script>
										
										<?php
										}
		
									}
							}
						}
						?>
				</tbody>
			</table>
						
			<div class="pagination">
			<?php
										
				if ($page>0){echo "<a href=\"$link_sc&st=1\">« First</a><a href=\"$link_sc&st=".($page)."\">« Previous</a>";}
					for ($i=$start; $i<$end; $i++){				 
						if($i==$page){echo '<a href="#" class="number current">';}
							else {echo "<a class='number' href=\"$link_sc&st=".($i+1)."\">";}

								echo ($i+1);
								if($i==$page){echo "</b>";}
									else{echo "</a>";}
								if ($i!=($end-1)) { echo ""; }
						}
											
						if($pages>$links&&$page<($pages-$rad-1)) { echo " ... <a href=\"$link_sc&st=".($pages)."\">".($pages)."</a>"; }
						if ($page<$pages-1){ echo " <a  href=\"$link_sc&st=".($page+2)."\">Next »</a><a href=\"$link_sc&st=".($pages)."\">Last »</a>";}	
									
						?>
			</div>
						
		</div>
        <?}else{
            
            
            	echo'<div class="no_table_info">Информация отсутствует</div>';
        }?>
	</div>
</div>