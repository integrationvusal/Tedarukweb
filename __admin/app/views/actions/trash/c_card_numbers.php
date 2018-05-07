<?php

if (!defined("_VALID_PHP")) {die();}

include 'actions/langs/card_numbers_ru.lang.php';
require_once 'actions/plugins/card_numbers.class.php';
card_numbers::$db = $pdo;

if (isset($_POST['delete'])) {
	card_numbers::delCardNumber($_POST['del_id']);
}
if (isset($_POST['add'])) {
	card_numbers::addCardNumber();
}

$card_numbers = card_numbers::getCardNumbers();

?>

<div class="content-box">
	<div class="content-box-header">
		<h3>Регистрация номеров карт</h3>
        <div class="clear"></div>
    </div>

	<div class="content-box-content">
        <form action="" method="post">
            <input type="text" name="card_number" value="" size="64" style="padding: 4px; border: 1px solid #d5d5d5; border-radius: 4px;" /> <input type="submit" name="add" value="+ Добавить" />
        </form>
        <div class="clear"></div>

		<?php
			if (is_array($card_numbers) && count($card_numbers)) {
				foreach ($card_numbers as $cn) {
					print '<div style="margin-top: 10px;">
			<form action="" method="post">
				<input type="hidden" name="del_id" value="'.$cn['id'].'" />
				<div style="padding: 4px; border: 1px solid #d5d5d5; border-radius: 4px; display: inline-block; width: 400px;">
					'.$cn['card_number'].'
				</div>
				<input type="submit" name="delete" value="x Удалить" onclick="return confirm(\'Вы уверены что хотите удалить этот номер карты?\');" />
			</form>
		</div>';
				}
			}
		?>
	</div>
</div>