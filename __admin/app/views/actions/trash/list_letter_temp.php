<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');
  
?>
<div class="content-box">	
	<div class="content-box-header">
		<h3>Список публикации	</h3>


        <!--searchbox start-->
        <div class="search_box">
            <form name="search_form" action="" method="POST">
                <input type="text" name="search_query" placeholder="Поиск шаблонов писемь" value="<?=@$_POST['search_query'];?>" />
                <input type="submit" name="search_user" value="Поиск" />
            </form>
        </div>
        <!--searchbox end-->




		
		<div class="clear"></div>
	</div>
	<div class="content-box-content">
			
		<?php
		
			if(@$_GET['st']==''){$_GET['st']=1;}
			
			$name_ord_sel=( @$_GET['order']=='ASC' )?'DESC':'ASC';
			//$name_sort_sel=( @$_GET['sort']=='name' )?'content_pagetitle_'.DEFAULT_LANG_DIR:'content_under_menu';
		
		
			$query=$pdo->query("SELECT * FROM `cs_letter_temp` WHERE `id`!=0");
				$setir = count($query->fetchAll(PDO::FETCH_ASSOC));
				
				
				if($setir>0){
				
					?>
					<table width="100%" cellpadding="0" cellspacing="0" id="myTable" class="tablesorter">
						<thead>
							<tr>
								<th id="letter_id">ID</th>
								<th id="letter_name">
									<!--<a href="index.php?page=news&st=<?php echo htmlspecialchars($_GET['st']); ?>&order=<?php echo $name_ord_sel; ?>&sort=name">-->
									Название
								</th>
								
								<!--<th>
									<a href="index.php?page=news&st=<?php echo htmlspecialchars($_GET['st']); ?>&order=<?php echo $name_ord_sel; ?>&sort=category">Категория</a>
								</th>-->
								
								<!--<th>Доступно</th>-->
								<th id="letter_date">Добавлено</th>
								<th>Управление</th>
							</tr>
						</thead>
						
						
						<tbody>
						<?php
				
							$count=30;
							$cnt=100; 
							$rpp=30; 
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
																				
										$letter_query = $pdo->query( "SELECT * FROM `cs_letter_temp`" );



                                        // Search code start
                                        if( isset($_POST['search_user']) && !empty($_POST['search_query']) )
                                        {

                                            $str = htmlspecialchars($_POST['search_query']);      // Query string
                                            $search_key_arr = array();                            // Empty array
                                            $table_row_arr = array();                             // array for table rows
                                            $search_key_arr_explode = explode(' ' ,trim($str));   // Exploding search query by spaces



                                            // table rows
                                            $table_row_arr[] = 'name_ru';
                                            $table_row_arr[] = 'text_ru';

                                            $sql_part1 = " WHERE `".$table_row_arr[0]."` ";
                                            $sql_part2 = "`".$table_row_arr[1]."` ";

                                            foreach( $search_key_arr_explode as $key=>$value )
                                            {
                                                if( $key == 0 )
                                                {
                                                    $sql_part1 .= "LIKE ".$pdo->quote("%". $value."%")."";
                                                    $sql_part2 .= "LIKE ".$pdo->quote("%". $value."%")."";
                                                }
                                                else
                                                {
                                                    $sql_part1 .= " OR `".$table_row_arr[0]."` LIKE ".$pdo->quote("%". $value."%")."";
                                                    $sql_part2 .= " OR `".$table_row_arr[1]."` LIKE ".$pdo->quote("%". $value."%")."";
                                                }

                                            }

                                            $letter_query = $pdo->query("SELECT * FROM `cs_letter_temp` ".$sql_part1 ." OR ". $sql_part2);

                                        }

                                        else
                                        {

                                        }
                                        // search code end







                                        $row = $letter_query->fetchAll(PDO::FETCH_ASSOC);

										
											foreach( $row as $letter_res ){
															
																							
											?>
												<tr>
													<td width="5%"><?php echo $letter_res['id']; ?></td>
													<td><?php echo $letter_res['name_'.DEFAULT_LANG_DIR.'']; ?></td>						
													
													<td>
                                                        <?=date("d.m.Y - H:i", strtotime($letter_res['ins_date'])); ?>
                                                    </td>
													<td width="12%" style="text-align:center;">
													
														<a href="index.php?page=edit_temp_list&id=<?php echo $letter_res['id']; ?>">
														    <img src="<?=IMAGE_DIR;?>edit.png" class="list-ico" title="Редакотровать" />
														</a>														
													</td>
												</tr>
											    <?php
											
										      
											}
									}
								}
						?>
						</tbody>
					</table>
					
					
					
					<!--
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
					-->
					
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


                var row_id         = $('#letter_id');           // set cookoe value equal to 0
                var row_name       = $('#letter_name');         // set cookoe value equal to 1
                var row_date       = $('#letter_date');         // set cookoe value equal to 2

                row_id.click(function(){
                    $.cookie('cookie_letter_sort_row', '0', { expires: 7 });
                });

                row_name.click(function(){
                    $.cookie('cookie_letter_sort_row', '1', { expires: 7 });
                });

                row_date.click(function(){
                    $.cookie('cookie_letter_sort_row', '3', { expires: 7 });
                });





                $("#myTable").tablesorter({
                    widgets: ["zebra", "filter"],
                    sortList: [[$.cookie('cookie_letter_sort_row'),0]],
                    headers:{
                        3: {sorter: false }
                    }
                }); // sorter end

            }); //ready end
        </script>

	</div>
</div>