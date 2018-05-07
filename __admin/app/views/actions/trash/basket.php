<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');

?>
<div class="content-box">	
	<div class="content-box-header">
		<h3>Корзина</h3>




        <!--searchbox start-->
        <div class="search_box">
            <form name="search_form" action="" method="POST">
                <input type="text" name="search_query" placeholder="Поиск в корзине" value="<?=@$_POST['search_query'];?>" />
                <input type="submit" name="search_user" value="Поиск" />
            </form>
        </div>
        <!--searchbox end-->



        <div class="clear"></div>
	</div>
	<div class="content-box-content">
			
		<?php
		
			$query=$pdo->query("SELECT * FROM `cs_product` WHERE `delete`= '1' ORDER BY `id` DESC");
		           $setir = count($query->fetchAll(PDO::FETCH_ASSOC));




				if($setir>0){
				
					?>
					<table width="100%" cellpadding="0" cellspacing="0"  id="myTable" class="tablesorter">
						<thead>
							<tr>
								<th>ID</th>
								<th>Название</th>
								<th>Добавлено</th>
								<th>Управление</th>
							</tr>
						</thead>
						<tbody>
						<?php
				
							$count=10;
							$cnt=100; 
							$rpp=10; 
							$rad=1;
							
							$link_sc="index.php?page=basket";
																 
							if (isset($_GET['st']) and $_GET['st']>1){$page=$_GET['st']-1;}else{$page=0;}
																			 
								$links=$rad*2+1;
								$pages=ceil($setir/$rpp);
								$start=$page-$rad;
								
								if($start>$pages-$links){$start=$pages-$links;}
								if($start<0){$start=0;}
									$end=$start+$links;
										
								if ($end>$pages){ $end=$pages; }
								for ($j=$start; $j<$end; $j++){
									if($j==$page){
																				
										$sql=$pdo->query("SELECT * FROM `cs_product` WHERE `delete`=1 ORDER BY `id` DESC LIMIT ".($count*$j)." ,".($count)."");



                                        // Search code start
                                        if( isset($_POST['search_user']) && !empty($_POST['search_query']) )
                                        {

                                            $str = htmlspecialchars($_POST['search_query']);      // Query string
                                            $search_key_arr = array();                            // Empty array
                                            $table_row_arr = array();                             // array for table rows
                                            $search_key_arr_explode = explode(' ' ,trim($str));   // Exploding search query by spaces



                                            // table rows
                                            $table_row_arr[] = 'name_ru';
                                            $table_row_arr[] = 'description_ru';

                                            $sql_part1 = " WHERE  `".$table_row_arr[0]."` ";
                                            $sql_part2 = "`".$table_row_arr[1]."` ";

                                            foreach( $search_key_arr_explode as $key=>$value )
                                            {
                                                if( $key == 0 )
                                                {
                                                    $sql_part1 .= "LIKE ".$pdo->quote(."%". $value."%".)."";
                                                    $sql_part2 .= "LIKE ".$pdo->quote(."%". $value."%".)."";
                                                }
                                                else
                                                {
                                                    $sql_part1 .= " OR `".$table_row_arr[0]."` LIKE ".$pdo->quote(."%". $value."%".)."";
                                                    $sql_part2 .= " OR `".$table_row_arr[1]."` LIKE ".$pdo->quote(."%". $value."%".)."";
                                                }

                                            }

                                            $sql = $pdo->query("SELECT * FROM `cs_product` ".$sql_part1 ." OR ". $sql_part2 ." AND `delete`=1");


                                        }

                                        else
                                        {

                                        }
                                        // search code end




                                        $row = $sql->fetchAll(PDO::FETCH_ASSOC);

											foreach($row as $row){
											?>
												<tr>
													<td width="5%"><?php echo $row['id']; ?></td>
													<td><?php echo $row['name_'.DEFAULT_LANG_DIR.'']; ?></td>
													<td width="18%"><?=date("d.m.Y - H:i", strtotime($row['added_date'])); ?></td>
													<td width="12%">
														<a onclick="return confirm('Вы действительно хотите вернуть удаленный файл?')" href="index.php?page=basket&st=<?php echo htmlspecialchars($_GET['st']); ?>&return=<?php echo $row['id']; ?>" title="Восстановить"><img src="<?=IMAGE_DIR;?>icons/return.png" alt="Восстановить" /></a>
														<a onclick="return confirm('Вы действительно хотите удалить файл без восстановления?')" href="index.php?page=basket&st=<?php echo htmlspecialchars($_GET['st']); ?>&delete=<?php echo $row['id']; ?>" title="Удалить"><img src="<?=IMAGE_DIR;?>icons/cross.png" alt="Удалить" /></a>
													</td>
												</tr>
											<?php
											
												if(empty($_GET['st'])){$_GET['st']=1;}
											
												if($row['id']==@$_GET['return']){
													
													$pdo->query("UPDATE `cs_product` SET `delete`='0' WHERE `id`=".(int)$_GET['return']." ");
													
													?>
													<script>
														alert('Перемещено обратно.');
														window.location="index.php?page=basket&st=<?php echo htmlspecialchars($_GET['st']); ?>";
													</script>
													<?php
												}
													
												if($row['id']==@$_GET['delete']){
												
													@$pdo->query("DELETE FROM `cs_product` WHERE `id`=".(int)$_GET['delete']." LIMIT 1");
													
													?>
													<script>
														alert('Удалено.');
														window.location="index.php?page=basket&st=<?php echo $_GET['st']; ?>";
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
																
								if($i==$page){echo "</b>";}else{echo "</a>";}
								if ($i!=($end-1)) { echo ""; }
							}
																	
							if($pages>$links&&$page<($pages-$rad-1)){echo " ... <a href=\"$link_sc&st=".($pages)."\">".($pages)."</a>"; }
							if ($page<$pages-1){ echo " <a  href=\"$link_sc&st=".($page+2)."\">Next »</a><a href=\"$link_sc&st=".($pages)."\">Last »</a>";}	
															
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
                $("#myTable").tablesorter({
                    widgets: ["zebra", "filter"],
                    sortList: [[1,0]],
                    headers:{
                        3: {sorter: false }
                    }
                }); // sorter end

            });
        </script>


	</div>
</div>