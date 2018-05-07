<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');

?>
<div class="content-box">	
	<div class="content-box-header">
		<h3>Список страниц</h3>

 


		<div class="clear"></div>
	</div>
	<div class="content-box-content">
			
		<?php
		
			if(@$_GET['st']==''){$_GET['st']=1;}
			
			$name_ord_sel=( @$_GET['order']=='ASC' )?'DESC':'ASC';
			$name_sort_sel=( @$_GET['sort']=='name' )?'content_pagetitle_'.DEFAULT_LANG_DIR:'content_under_menu';
		
			$query=$pdo->query("SELECT * FROM `cs_content_list` WHERE `content_delete`='no' AND `content_on_page`!=0 ORDER BY ".$name_sort_sel." ".$name_ord_sel);
				$setir=count($query->fetchAll(PDO::FETCH_ASSOC));
	
				
				if($setir>0){
				
					?>
					<table width="100%" cellpadding="0" cellspacing="0"  id="myTable" class="tablesorter">
						<thead>
							<tr>
								<th id="page_list_id">ID</th>
								<th id="page_list_name">Название</th>
								<th id="page_list_cat">Раздел</th>
								<th id="page_list_enable">Доступно</th>
								<th id="page_list_added">Добавлено</th>
								<th>Управление</th>
							</tr>
						</thead>
						<tbody>
						<?php
				
							$count=10;
							$cnt=100; 
							$rpp=10;
							$rad=1;
							
							$link_sc="index.php?page=news";
																 
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

                                        $sql = $pdo->query("SELECT * FROM `cs_content_list` WHERE `content_delete`='no' AND `content_on_page`!=0 ORDER BY ".$name_sort_sel." ".$name_ord_sel." LIMIT ".($count*$j)." ,".($count)."");

                                        $row = $sql->fetchAll(PDO::FETCH_ASSOC);


											//while($row=$sql->fetch(PDO::FETCH_ASSOC)){

                                        foreach( $row as $row ){
											
												$sfcom=$pdo->query("SELECT `content_pagetitle_".DEFAULT_LANG_DIR."` FROM `cs_content_list` WHERE `content_id`=".(int)$row['content_on_page']." LIMIT 1");
													$rfcom=$sfcom->fetch(PDO::FETCH_ASSOC);

												$block=($row['content_hide_page']=='no')?'<span style="color: green;">Доступно</span>':'<span style="color: red;">Скрытый</span>';
												
												
												
												
											?>
												<tr>
													<td width="5%"><?php echo $row['content_id']; ?></td>
													<td><?php echo $row['content_pagetitle_'.DEFAULT_LANG_DIR.'']; ?></td>
													<td><?php echo $rfcom['content_pagetitle_'.DEFAULT_LANG_DIR.'']; ?></td>
													<td><?php echo $block; ?></td>
													<td><?php echo date("d.m.Y - H:i", strtotime($row['content_ins_date'])); ?></td>
													<td width="12%">
														<a href="index.php?page=edit_resource&id=<?php echo $row['content_id']; ?>&rtn=news&rsp=<?php echo htmlspecialchars($_GET['st']); ?>"><img src="<?=IMAGE_DIR;?>edit.png" class="list-ico" title="Редакотровать" /></a>
														<a onclick="return confirm('Вы уверены что хотите удалить ?')" href="index.php?page=news&st=<?php echo htmlspecialchars($_GET['st']); ?>&order=<?php echo htmlspecialchars($_GET['order']); ?>&sort=<?php echo htmlspecialchars($_GET['sort']); ?>&delete=<?php echo $row['content_id']; ?>" title="Удалить"><img src="<?=IMAGE_DIR;?>icons/cross.png" alt="Delete" /></a>
													</td>
												</tr>
											<?php

										
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
																	
							if($pages>$links&&$page<($pages-$rad-1)){echo " ... <a href=\"$link_sc&st=".($pages)."&order=".htmlspecialchars($_GET['order'])."&sort=".htmlspecialchars($_GET['sort'])."\">".($pages)."</a>"; }
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


        <script type="text/javascript">
            $(document).ready(function()
            {

                var page_id        = $('#page_list_id');          // set cookoe value equal to 0
                var page_name      = $('#page_list_name');        // set cookoe value equal to 1
                var page_category  = $('#page_list_cat');         // set cookoe value equal to 2
                var page_enable    = $('#page_list_enable');      // set cookoe value equal to 3
                var page_added     = $('#page_list_added');       // set cookoe value equal to 4

                page_id.click(function(){
                    $.cookie('cookie_page_sort_row', '0' , { expires: 7 });
                });

                page_name.click(function(){
                    $.cookie('cookie_page_sort_row', '1' , { expires: 7 });
                });

                page_category.click(function(){
                    $.cookie('cookie_page_sort_row', '2' , { expires: 7 });
                });

                page_enable.click(function(){
                    $.cookie('cookie_page_sort_row', '3' , { expires: 7 });
                });

                page_added.click(function(){
                    $.cookie('cookie_page_sort_row', '4' , { expires: 7 });
                });




                $("#myTable").tablesorter({
                    widgets: ["zebra", "filter"],
                    sortList: [[$.cookie('cookie_page_sort_row'),0]],
                    headers:{
                        5: {sorter: false }
                    }
                }); // sorter end

            }); // ready end
        </script>
	</div>
</div>