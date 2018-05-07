<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');
	?>	
	
	
	<div class="content-box">	
	<div class="content-box-header">
		<h3>Курс валют</h3>
		<div class="clear"></div>
	</div>


	<div class="content-box-content">
	
		<?php
			if(isset($ok_settings) AND $err>0){
			?>
			<div class="error_div">
			<?php echo $errors; ?>
			</div>
			<?php
			}
		?>
	<div>	
		<div class="exchange_wrapper">
			<div class="exchange">

           <?php
// READING DATA FROM CBAR.AZ START
           $fpr = fopen("cashe/counter.txt", "r");
                if( $fpr )
                {
                    $contet_from_cbar = (array) json_decode(file_get_contents("cashe/counter.txt") , true);
                }
                else
                {
                    echo 'file read failed';
                }
// READING DATA FROM CBAR.AZ END

if(isset($_POST['ok_exchangeq']))
{
	array_pop($_POST); // obrezaem posledniy element massiva (eto knopka 'Soxranit')
	$serial_post = serialize($_POST);  // Serializuem postupivwie dannie
	
	$fpser = fopen("cashe/real_site_data.txt", "w+");  // sozdaem(perezapisivaem) fayl
	$fp_wrt = fwrite($fpser, $serial_post); // Zapisivaem v fayl
	if( !$fp_wrt )
	{
	  echo 'File write error';
	}
	
}



// reading data from file


$fpread = fopen("cashe/real_site_data.txt", "r");
if(!$fpread){echo 'Cannot read file';}
else
{
$real_data = file_get_contents("cashe/real_site_data.txt");
$real_arry = unserialize($real_data);
}
?>



			<form name="" action="" method="POST">
				<table>
					<tr>
						 <th>Валюта</th>
						 <th>Данные с cbar.az</th>
						 <th>% ставка</th>
						 <th>На сайте</th>
					</tr>
					
					<tr>
						<td>RUB</td>
						<td><span class="cbar_data"><?=$contet_from_cbar['RUB']?></span></td>
						<td><input type="text" name="rub_per" value="<?=$real_arry['rub_per'];?>" class="text-input small-input" >&nbsp;%</td>
						<td><span class="on_site"><?=$real_arry['hidden_rub'];?></span> <input type="hidden" name="hidden_rub" value="<?=$real_arry['hidden_rub'];?>" /></td>
					<tr>
					<tr class="alt-row">
						<td>USD</td>
						<td><span class="cbar_data"><?=$contet_from_cbar['USD']?></span></td>
						<td><input type="text" name="usd_per" value="<?=$real_arry['usd_per'];?>" class="text-input small-input">&nbsp;%</td>
						<td><span class="on_site"><?=$real_arry['hidden_usd'];?></span> <input type="hidden" name="hidden_usd" value="<?=$real_arry['hidden_usd'];?>" /></td>
					<tr>

					<tr>
						<td>EUR</td>
						<td><span class="cbar_data"><?=$contet_from_cbar['EUR']?></span></td>
						<td><input type="text" name="eur_per" value="<?=$real_arry['eur_per'];?>" class="text-input small-input">&nbsp;%</td>
						<td><span class="on_site"><?=$real_arry['hidden_eur'];?></span> <input type="hidden" name="hidden_eur" value="<?=$real_arry['hidden_eur'];?>" /></td>
					<tr>

					<tr class="alt-row">
						<td>AZN</td>
						<td>1</td>
						<td></td>
						<td></td>
					<tr>
				</table>
				
				<br />
				<p><input type="submit" name="ok_exchangeq" value="Сохранить" class="button"></p>
			  </form>

                <script type="text/javascript" language="javascript">

                    /**
                     * Функция округления числа
                     *
                     * @param float value — число, которое округляем
                     * @param int precision — количество знаков после запятой
                     *
                     * @return float — возвращает округленное число
                     */
                    function round_mod(value, precision)
                    {
                        // спецчисло для округления
                        var precision_number = Math.pow(10, precision);

                        // округляем
                        return Math.round(value * precision_number) / precision_number;
                    }



                var percentage = $('.small-input');
                percentage.blur(function(){
                    if( $(this).val() == '' )
                    {
                        $(this).val('0');
                    }

                    var input_value = ($(this).attr('value'))?$(this).attr('value') : 0;  // pure value
                    var koeff = input_value * 0.01;           // value devided by 100 (koefficient)
                    var data_from_cbar = $(this).parent().parent().find('.cbar_data').text(); // data from cbar.az


                    var data_for_site = (data_from_cbar * koeff) + parseFloat(data_from_cbar);

                    var site_value_place_holder = $(this).parent().parent().find('span.on_site');
                    var site_value_place_holder_hidden_inp = $(this).parent().parent().find('input[type=hidden]');

                    site_value_place_holder.text(round_mod(data_for_site, 4));
                    site_value_place_holder_hidden_inp.attr('value' , round_mod(data_for_site, 4));




                    console.log(data_for_site);


                }); // blur end



                </script>


			</div>
		</div>
		
	</div>
			
		<div class="clear"></div>
	</div>
</div>
	
	
	
	
	
	
