<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');

?>
<script type="text/javascript">
	$(document).ready(function(){
		$("#page_link").live('click', function () {
			$parent=$(this).parent().parent();
			val=parseInt($parent.find("#id").text());
			val='http://<?php echo @$_SERVER['SERVER_NAME']; ?>/page/'+val;
			$("#page_link_div").find('input[name="name"]').val(val);
			$("#page_link_div_bg").fadeIn(150, function(){
				$("#page_link_div").fadeIn(150);
			});
		});
		$('#page_link_close').live('click', function () {
			$("#page_link_div").fadeOut(150, function(){
				$("#page_link_div_bg").fadeOut(150);
			});
		});
	});
</script>
<div id="page_link_div_bg"style="border: 0px solid red; position: fixed; float: left; width: 100%; left: 0px; top: 0px; background: #000; height: 100%; display: none; -moz-opacity: 0.7; -khtml-opacity: 0.7; opacity: 0.7;"></div>
	<div id="page_link_div" style="border: 0px solid #000; text-align: right; z-index: 999; margin-left: 13%; margin-top: 15%; position: absolute; width: 500px; display: none;">
		<a href="javascript:;" id="page_link_close" title="Закрыть"><img src="<?=IMAGE_DIR;?>close.png" width="34" height="34" /></a>
		<div style="border: 2px solid #000; float: left; text-align: center; width: 500px; height: 70px; background-color: #fff;">
			<input type="text" name="name" style="border: 1px solid #ccc; width: 400px; margin-top: 22px; padding: 4px 7px; color: #666;" value="" />
		</div>
	</div>
<div class="content-box">
	<div class="content-box-header">
		<h3>Список разделов</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content">

		<?php

			if(@$_GET['st']==''){$_GET['st']=1;}

			$name_ord_sel=( @$_GET['order']=='ASC' )?'DESC':'ASC';
			$name_sort_sel=( @$_GET['sort']=='name' )?'content_pagetitle_'.DEFAULT_LANG_DIR:'content_under_menu';

			$query=$pdo->query("SELECT * FROM `cs_content_list` WHERE `content_delete`='no' AND `content_on_page`=0 AND `content_show_on_menu`='no' ORDER BY ".$name_sort_sel." ".$name_ord_sel);
				$setir=count($query->fetchAll(PDO::FETCH_ASSOC));

				if($setir>0){

					?>
					<table width="100%" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th>ID</th>
								<th>
									<a href="index.php?page=page&st=<?php echo htmlspecialchars($_GET['st']); ?>&order=<?php echo $name_ord_sel; ?>&sort=name">Название</a>
								</th>
								<th>Доступно</th>
								<th>Добавлено</th>
								<th>Управление</th>
							</tr>
						</thead>
						<tbody>
						<?php

							$count=30;
							$cnt=100;
							$rpp=30;
							$rad=1;

							$link_sc="index.php?page=page";

							if (isset($_GET['st'])){$page=$_GET['st']-1;}else{$page=0;}

								$links=$rad*2+1;
								$pages=ceil($setir/$rpp);
								$start=$page-$rad;

								if($start>$pages-$links){$start=$pages-$links;}
								if($start<0){$start=0;}
									$end=$start+$links;

								if ($end>$pages){ $end=$pages; }
								for ($j=$start; $j<$end; $j++){
									if($j==$page){

										$sql=$pdo->query("SELECT * FROM `cs_content_list` WHERE `content_delete`='no' AND `content_on_page`=0 AND `content_show_on_menu`='no' ORDER BY ".$name_sort_sel." ".$name_ord_sel." LIMIT ".($count*$j)." ,".($count)."");
											while($row=$sql->fetch(PDO::FETCH_ASSOC)){

												$block=($row['content_hide_page']=='no')?'<span style="color: green;">Доступно</span>':'<span style="color: red;">Скрытый</span>';



											?>
												<tr>
													<td  id="id" width="5%"><?php echo $row['content_id']; ?></td>
													<td><?php echo $row['content_pagetitle_'.DEFAULT_LANG_DIR.'']; ?></td>
													<td><?php echo $block; ?></td>
													<td><?php echo date("d.m.Y - H:i", strtotime($row['content_ins_date'])); ?></td>
													<td width="12%">
														<a href="index.php?page=edit_resource&id=<?php echo $row['content_id']; ?>&rsp=<?php echo htmlspecialchars($_GET['st']); ?>"><img src="<?=IMAGE_DIR;?>edit.png" class="list-ico" title="Редакотровать" /></a>
														<a onclick="return confirm('Вы уверены что хотите удалить ?')" href="index.php?page=page&st=<?php echo htmlspecialchars($_GET['st']); ?>&delete=<?php echo $row['content_id']; ?>" title="Удалить"><img src="<?=IMAGE_DIR;?>icons/cross.png" alt="Delete" /></a>
														<a href="javascript:;" id="page_link" title="Ссылка"><img src="<?=IMAGE_DIR;?>icons/information.png" /></a>
													</td>
												</tr>
											<?php

												if($row['content_id'] == @$_GET['delete']){

													@$pdo->query("UPDATE `cs_content_list` SET `content_delete`='yes' WHERE `content_id`=".(int)$_GET['delete']." LIMIT 1");
													?>
													<script>
														alert('Удалено.');
														window.location="index.php?page=page&st=<?php echo htmlspecialchars($_GET['st']); ?>";
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

						if ($page>0){echo "<a href=\"$link_sc&st=1&order=".htmlspecialchars($_GET['order'])."&sort=".htmlspecialchars($_GET['sort'])."\">« First</a><a href=\"$link_sc&st=".($page)."&order=".htmlspecialchars($_GET['order'])."&sort=".htmlspecialchars($_GET['sort'])."\">« Previous</a>";}
							for ($i=$start; $i<$end; $i++){
								if($i==$page){echo '<a href="#" class="number current">';}
									else {echo "<a class='number' href=\"$link_sc&st=".($i+1)."&order=".htmlspecialchars($_GET['order'])."&sort=".htmlspecialchars($_GET['sort'])."\">";}
										echo ($i+1);

								if($i==$page){echo "</b>";}else{echo "</a>";}
								if ($i!=($end-1)) { echo ""; }
							}

							if($pages>$links&&$page<($pages-$rad-1)){echo " ... <a href=\"$link_sc&st=".($pages)."&order=".$_GET['order']."&sort=".$_GET['sort']."\">".($pages)."</a>"; }
							if ($page<$pages-1){ echo " <a  href=\"$link_sc&st=".($page+2)."&order=".htmlspecialchars($_GET['order'])."&sort=".htmlspecialchars($_GET['sort'])."\">Next »</a><a href=\"$link_sc&st=".($pages)."&order=".htmlspecialchars($_GET['order'])."&sort=".htmlspecialchars($_GET['sort'])."\">Last »</a>";}

					?>
					</div>

					<?php

				}
				else {

					echo'<div class="no_table_info">Информация отсутствует</div>';

				}

		?>

		<div class="clear"></div>
	</div>
</div>