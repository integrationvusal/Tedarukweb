<?php

if (!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

$error_count = 0;
if (isset($_POST['save'])) {

	/*$files = scandir($_SERVER['DOCUMENT_ROOT'].'/amazonmanager/uploads/products/');
	foreach($files as $file) {
		if($file === '.' || $file === '..') {continue;}

		$file_input = $_SERVER['DOCUMENT_ROOT'].'/amazonmanager/uploads/products/'.$file.'';
		$name = $file;

		$file_output_50 = $_SERVER['DOCUMENT_ROOT'].'/amazonmanager/uploads/products_50/'.$name.'';
		$file_output_140_100 = $_SERVER['DOCUMENT_ROOT'].'/amazonmanager/uploads/products_140_100/'.$name.'';
		$file_output_160_112 = $_SERVER['DOCUMENT_ROOT'].'/amazonmanager/uploads/products_160_112/'.$name.'';
		$file_output_210_160 = $_SERVER['DOCUMENT_ROOT'].'/amazonmanager/uploads/products_210_160/'.$name.'';

		$crop_save_50 = resize($file_input, $file_output_50, 50, false, $percent = false);
		$crop_save_140_100 = resize($file_input, $file_output_140_100, 140, 100, $percent = false);
		$crop_save_160_112 = resize($file_input, $file_output_160_112, 160, 112, $percent = false);
		$crop_save_210_160 = resize($file_input, $file_output_210_160, 210, 160, $percent = false);
	}

	die();*/
    /*    if(isset($_POST['name_az']) && $_POST['name_az'] == ''){
      $error_count++;
     }
     if(isset($_POST['name_ru']) && $_POST['name_ru'] == ''){
      $error_count++;
     }
     if(isset($_POST['name_en']) && $_POST['name_en'] == ''){
      $error_count++;
     }
    if(empty($_POST['cat_id'])){
      $error_count++;
     }

    if(empty($_POST['accessories'])){
      $error_count++;
     }

    if(empty($_POST['brend_id'])){
      $error_count++;
     }
     if(isset($_POST['description_az']) && $_POST['description_az'] == ''){
      $error_count++;
     }
     if(isset($_POST['description_en']) && $_POST['description_en'] == ''){
      $error_count++;
     }
     if(isset($_POST['description_ru']) && $_POST['description_ru'] == ''){
      $error_count++;
     }
     if(isset($_POST['full_text_az']) && $_POST['full_text_az'] == ''){
      $error_count++;
     }
     if(isset($_POST['full_text_en']) && $_POST['full_text_en'] == ''){
      $error_count++;
     }
     if(isset($_POST['full_text_ru']) && $_POST['full_text_ru'] == ''){
      $error_count++;
     } */

    if ($error_count == 0) {
        $accessories = array();
        try {
            $name_az = $_POST['name_az'];
            $name_ru = $_POST['name_ru'];
            $name_en = $_POST['name_en'];
            $video = $_POST['video'];

            $brend_id = $_POST['brend_id'];

            $description_az = @$_POST['description_az'];
            $description_en = @$_POST['description_en'];
            $description_ru = @$_POST['description_ru'];

            $full_text_az = $_POST['full_text_az'];
            $full_text_en = $_POST['full_text_en'];
            $full_text_ru = $_POST['full_text_ru'];

        //    $product_cat = $_POST['product_cat'];
            $product_day = $_POST['product_day'];
            $enable = $_POST['enable'];
            $sale = $_POST['sale'];
            $price = $_POST['price'];
            $old_price = !empty($_POST['old_price']) ? $_POST['old_price'] : 0;
            $cats = $_POST['cat_id'];
            $color_id = $_POST['color_id'];
            $as_accessory = !empty($_POST['as_accessory']) ? 1 : 0;
            $free_delivery = !empty($_POST['free_delivery']) ? 1 : 0;

            if (!empty($_POST['accessories'])) {
                $accessories = $_POST['accessories'];
            }
            $id = $_GET['id'];

            $update = $pdo->exec("UPDATE `cs_product` SET
                `name_az`=".$pdo->quote($name_az).",
                `name_ru`=".$pdo->quote($name_ru).",
                `name_en`=".$pdo->quote($name_en).",
                `video`=".$pdo->quote($video).",
                `brend_id`=".$pdo->quote($brend_id).",
                `description_az`=".$pdo->quote($description_az).",
                `description_ru`=".$pdo->quote($description_ru).",
                `description_en`=".$pdo->quote($description_en).",
                `full_text_az`=".$pdo->quote($full_text_az).",
                `full_text_ru`=".$pdo->quote($full_text_ru).",
                `full_text_en`=".$pdo->quote($full_text_en).",
                `as_accessory`=".$pdo->quote($as_accessory).",
                `product_day`=".$pdo->quote($product_day).",
                `enable`=".$pdo->quote($enable).",
                `sale`= ".$pdo->quote($sale).",
                `color_id`= ".$pdo->quote($color_id).",
                `price`= ".$pdo->quote($price).",
                `old_price`= ".$pdo->quote($old_price).",
                `free_delivery`= ".$pdo->quote($free_delivery)."
                WHERE `id`= ".$pdo->quote($id)."
            ");


            $pdo->query('DELETE FROM `cs_accessories` WHERE `product_id` = '.$id.'')->execute();
            if (!empty($accessories)) {
                foreach ($accessories as $item) {
                        $sql = "INSERT INTO `cs_accessories` (`product_id`, `accessories_id`) VALUES (:product_id, :accessories_id)";
                        $query = $pdo->prepare($sql);
                        $query->execute(array(
                            ':product_id'=>$id,
                            ':accessories_id' => $item
                        ));
                }
            }

            if (!empty($cats)) {
                $pdo->query('DELETE FROM `cs_products_cat` WHERE `product_id` = '.$pdo->quote($id).'')->execute();
                foreach ($cats as $item) {
                        $sql = "INSERT INTO `cs_products_cat` (`product_id`, `cat_id`) VALUES (:product_id, :cat_id)";
                        $query = $pdo->prepare($sql);
                        $query->execute(array(
                            ':product_id'=>$id,
                            ':cat_id' => $item
                        ));
                }
            }

            // add product images
            $product_id = (int)$_GET['id'];

            if (!empty($_FILES)) {
                foreach ($_FILES['images_urls']['tmp_name'] as $k => $item) {
                    $file_input = $item;
                    $name = time().'_'.$_FILES['images_urls']['name'][$k];

                    $file_output_orig = $_SERVER['DOCUMENT_ROOT'].'/amazonmanager/uploads/products_original/'.$name.'';
                    $file_output = $_SERVER['DOCUMENT_ROOT'].'/amazonmanager/uploads/products/'.$name.'';
                    $file_output_50 = $_SERVER['DOCUMENT_ROOT'].'/amazonmanager/uploads/products_50/'.$name.'';
                    $file_output_140_100 = $_SERVER['DOCUMENT_ROOT'].'/amazonmanager/uploads/products_140_100/'.$name.'';
                    $file_output_160_112 = $_SERVER['DOCUMENT_ROOT'].'/amazonmanager/uploads/products_160_112/'.$name.'';
                    $file_output_210_160 = $_SERVER['DOCUMENT_ROOT'].'/amazonmanager/uploads/products_210_160/'.$name.'';

                    $crop_save_orig = resize($file_input, $file_output_orig, 800, false, $percent = false);
                    $crop_save = resize($file_input, $file_output, 500, 351, $percent = false);
                    $crop_save_50 = resize($file_input, $file_output_50, 50, 50, $percent = false);
                    $crop_save_140_100 = resize($file_input, $file_output_140_100, 140, 100, $percent = false);
                    $crop_save_160_112 = resize($file_input, $file_output_160_112, 160, 112, $percent = false);
                    $crop_save_210_160 = resize($file_input, $file_output_210_160, 210, 160, $percent = false);


                    if ($crop_save && $crop_save_orig && $crop_save_50 && $crop_save_140_100 && $crop_save_160_112 && $crop_save_210_160) {
                        $sql = "INSERT INTO `cs_products_image` (`product_id`, `name`, `type`) VALUES (:product_id, :name, :type)";
                        $query = $pdo->prepare($sql);
                        $result = $query->execute(array(
                            ':product_id' => $product_id,
                            ':name' => $name,
                            ':type' => 0,
                        ));
                    }

                }
            }

            if (!empty($_POST['images_urls'])) {
                foreach ($_POST['images_urls'] as $item) {
                    $file_input = $item;
                    $url_array = explode('/', $item);
                    $name = time().'_'.end($url_array);

                    $file_output_orig = $_SERVER['DOCUMENT_ROOT'].'/amazonmanager/uploads/products_original/'.$name.'';
                    $file_output = $_SERVER['DOCUMENT_ROOT'].'/amazonmanager/uploads/products/'.$name.'';
					$file_output_50 = $_SERVER['DOCUMENT_ROOT'].'/amazonmanager/uploads/products_50/'.$name.'';
					$file_output_140_100 = $_SERVER['DOCUMENT_ROOT'].'/amazonmanager/uploads/products_140_100/'.$name.'';
					$file_output_160_112 = $_SERVER['DOCUMENT_ROOT'].'/amazonmanager/uploads/products_160_112/'.$name.'';
					$file_output_210_160 = $_SERVER['DOCUMENT_ROOT'].'/amazonmanager/uploads/products_210_160/'.$name.'';

                    $crop_save_orig = resize($file_input, $file_output_orig, 800, false, $percent = false);
                    $crop_save = resize($file_input, $file_output, 500, 351, $percent = false);
					$crop_save_50 = resize($file_input, $file_output_50, 50, 50, $percent = false);
					$crop_save_140_100 = resize($file_input, $file_output_140_100, 140, 100, $percent = false);
					$crop_save_160_112 = resize($file_input, $file_output_160_112, 160, 112, $percent = false);
					$crop_save_210_160 = resize($file_input, $file_output_210_160, 210, 160, $percent = false);

					if ($crop_save && $crop_save_orig && $crop_save_50 && $crop_save_140_100 && $crop_save_160_112 && $crop_save_210_160) {
						$sql = "INSERT INTO `cs_products_image` (`product_id`, `name`, `type`) VALUES (:product_id, :name, :type)";
                        $query = $pdo->prepare($sql);
                        $result = $query->execute(array(
                            ':product_id' => $product_id,
                            ':name' => $name,
                            ':type' => 0,
                        ));
                    }

                }
            }

            echo "<script language='javascript'>
		        alert('Redirecting you to global category list');
		      </script>";
        } catch (PDOException $e) {
            echo 'Error : ' . $e->getMessage();
            exit();
        }
        // try end
    } // if have not any errors
} // Button pressed end

$prod_id = (int)$_GET['id'];
$row = $pdo->query('
    SELECT `cs_product`.*, `cs_colors`.`alias`
    FROM `cs_product`
    LEFT JOIN `cs_colors` ON `cs_colors`.`id` = `cs_product`.`color_id`
    WHERE `cs_product`.`id` = '.$prod_id.'
');
$res = $row->fetch(PDO::FETCH_ASSOC);


$cur_accessories = $pdo->query('SELECT `accessories_id` FROM `cs_accessories` WHERE `product_id` = '.$pdo->quote($prod_id).'')->fetchAll(PDO::FETCH_COLUMN);
$images = $pdo->query('SELECT `id`, `name`, `type` FROM `cs_products_image` WHERE `product_id` = '.$pdo->quote($prod_id).'')->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-box">
    <div class="content-box-header">
        <h3>Редактировать продукт</h3>

        <div class="clear"></div>
    </div>
    <div class="content-box-content">

        <?php
        if ($error_count > 0) {
            echo '<div class="fill_all_fields">Заполните все поля</div>';
        }
        ?>

        <form name="add_product" action="" method="POST" enctype="multipart/form-data">

            <div id="name">
                <!-------НАЗВАНИЕ ПРОДУКТОВ-------->
                <label>* Название продукта (AZ)</label>
                <input type="text" value="<?php echo $res['name_az']; ?>" name="name_az" class="text-input small-input"/>
                <br/> <br/>
                <label>* Название продукта (RU)</label>
                <input type="text" value="<?php echo $res['name_ru']; ?>" name="name_ru" class="text-input small-input"/>
                <br/> <br/>
                <label>* Название продукта (EN)</label>
                <input type="text" value="<?php echo $res['name_en']; ?>" name="name_en" class="text-input small-input"/>
                <br/> <br/>
                <label>* Название бренда</label>
                <?php
                    $brend_list = $pdo->query("SELECT * FROM `cs_brend_list`")->fetchAll(PDO::FETCH_ASSOC);

                ?>
                <select name="brend_id">
                        <option value="0">---</option>
                        <?php
                          foreach( $brend_list as $current_brend )
                          {?>
                        <option <?php echo  ( $res['brend_id'] == $current_brend['brend_id'] ) ? "selected='selected'" : '' ; ?> value="<?=$current_brend['brend_id']?>"><?=$current_brend['brend_name']?></option>
                          <?}
                        ?>
                </select>

                <br /><br />


                <label>* Цвет</label>
                <?php
                $colors = $pdo->query("SELECT * FROM `cs_colors`")->fetchAll(PDO::FETCH_ASSOC);

                ?>
                <select name="color_id">
                    <option value="0">-</option>
                    <?php
                    foreach( $colors as $item )
                    {?>
                        <option <?php echo  ( $item['id'] == $res['color_id'] ) ? "selected='selected'" : '' ; ?> value="<?=$item['id']?>"><?=$item['name_ru']?></option>
                    <?}
                    ?>
                </select>

                <br /><br />

				<p><strong>Simpla id</strong> - <?php echo $res['simpla_id'] ?></p>

				<br /><br />






                <div class="pice_wrapper">

                    <div>

                        <label>* Распродажа</label>
                        <label><input type="radio" name="sale" <?php if ($res['sale'] == 1) {
                                echo 'checked';
                            } ?> value="1"/>Доступно</label>
                        <label><input type="radio" name="sale" <?php if ($res['sale'] == 0) {
                                echo 'checked';
                            } ?> value="0"/>Отключено</label>
                    </div>


                    <div>

                        <label>* Товар дня</label>
                        <label><input type="radio" name="product_day" <?php if ($res['product_day'] == 1) {
                                echo 'checked';
                            } ?> value="1"/>Доступно</label>
                        <label><input type="radio" name="product_day" <?php if ($res['product_day'] == 0) {
                                echo 'checked';
                            } ?> value="0"/>Отключено</label>

                    </div>


                    <div>

                        <label>* Доступность</label>
                        <label><input type="radio" name="enable" <?php if ($res['enable'] == 1) {
                                echo 'checked';
                            } ?> value="1"/>Доступно</label>
                        <label><input type="radio" name="enable" <?php if ($res['enable'] == 0) {
                                echo 'checked';
                            } ?> value="0"/>Отключено</label>

                    </div>

                    <div>


                        <label>* Цена</label>
                        <input type="text" value="<?php echo $res['price']; ?>" name="price" class="text-input medium-input"/>
                    </div>

                    <div>


                        <label>* Старая цена</label>
                        <input type="text" value="<?php echo $res['old_price']; ?>" name="old_price" class="text-input medium-input"/>
                    </div>

                    <div>
                        <label>* Код товара</label>
                        <p><?php echo str_pad($res['id'], 7, '0', STR_PAD_LEFT).$res['alias'] ?></p>
                    </div>

                    <div>
                        <label>
                        <?php if ($res['as_accessory'] == 1): ?>
                            <input checked="checked" type="checkbox" value="1" name="as_accessory" class="text-input medium-input"/>
                        <?php else: ?>
                            <input type="checkbox" value="1" name="as_accessory" class="text-input"/>
                        <?php endif ?>Использовать как аксессуар</label>
                    </div>


                    <!--FREE SHIPPING START-->
                    <div>
                        <label>
                            <input type="checkbox" <?php echo ($res['free_delivery']) ? 'checked="checked"' :'' ; ?> name="free_delivery" valie="1" />&nbsp; Бесплатная доставка
                        </label>
                    </div>
                    <!--FREE SHIPPING   END-->



                </div>



                <div class="clear"></div>


            </div>

            <div class="clear"></div>




    <div class="move_to_right">
            <!-- Изображения товара -->
            <div id="list_images" class="block layer images_block">
                <h2>Изображения товара</h2>

                <ul class="ui-sortable">
                    <?php
                        foreach ($images as $item):
                        $border = ($item['type'] == 1) ? 'border' : '';
                    ?>
                        <li class="wizard list_images <?php echo $border ?>">
                            <a href='#' data-id="<?php echo $item['id'] ?>" data-product_id="<?php echo $prod_id ?>" data-name="<?php echo $item['name'] ?>" class='delete_image'><img src='templates/default/images/cross-circle-frame.png'></a>
                            <a href="#" data-id="<?php echo $item['id'] ?>" data-product_id="<?php echo $prod_id ?>" class="main_image" ><img width="100" src="uploads/products/<?php echo $item['name'] ?>" /></a>
                        </li>
                    <?php endforeach ?>
                </ul>


            </div>
            <!-- Добавить изображения -->
            <div class="block layer images images_block">
                <h2>Добавить изображения
                    <a href="#" data-product_id="<?php echo $prod_id ?>" id=images_wizard><img src="<?=IMAGE_DIR;?>wand.png" alt="Подобрать автоматически" title="Подобрать автоматически"/></a>
                </h2>

                <ul class="ui-sortable">
                </ul>

                <a href="#" id="upload_image"><span>+ Добавить изображение</span></a><br />
                <div id=add_image></div><br />
                <input type="submit" name="save" class="button" value="Сохранить"/>

            </div>
    </div>



            <div class="clear"></div>
            <!----ПОЛНЫЙ ТЕКСТ---->
            <label>* Полное описание (AZ)</label>
            <textarea class="text_area" name="full_text_az"
                      class="text-input small-input"><?php echo $res['full_text_az']; ?></textarea> <br/> <br/>
            <label>* Полное описание (EN)</label>
            <textarea type="text" class="text_area" name="full_text_en"
                      class="text-input small-input"><?php echo $res['full_text_en']; ?></textarea> <br/> <br/>
            <label>* Полное описание (RU)</label>
            <textarea type="text" class="text_area" name="full_text_ru"
                      class="text-input small-input"><?php echo $res['full_text_ru']; ?></textarea> <br/> <br/>

            <label>* Видео</label>
            <textarea class="text_area" name="video"
                      class="text-input small-input"><?php echo $res['video']; ?></textarea> <br/> <br/>

            <script>
                CKEDITOR.replace("full_text_az",
                    {
                        uiColor: '#f9f9f9',
                        filebrowserBrowseUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/ckfinder.html",
                        filebrowserImageBrowseUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/ckfinder.html?type=Images",
                        filebrowserFlashBrowseUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/ckfinder.html?type=Flash",
                        filebrowserUploadUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files",
                        filebrowserImageUploadUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images",
                        filebrowserFlashUploadUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash"
                    });

                CKEDITOR.replace("full_text_en",
                    {
                        uiColor: '#f9f9f9',
                        filebrowserBrowseUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/ckfinder.html",
                        filebrowserImageBrowseUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/ckfinder.html?type=Images",
                        filebrowserFlashBrowseUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/ckfinder.html?type=Flash",
                        filebrowserUploadUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files",
                        filebrowserImageUploadUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images",
                        filebrowserFlashUploadUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash"
                    });

                CKEDITOR.replace("full_text_ru",
                    {
                        uiColor: '#f9f9f9',
                        filebrowserBrowseUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/ckfinder.html",
                        filebrowserImageBrowseUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/ckfinder.html?type=Images",
                        filebrowserFlashBrowseUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/ckfinder.html?type=Flash",
                        filebrowserUploadUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files",
                        filebrowserImageUploadUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images",
                        filebrowserFlashUploadUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash"
                    });

                CKEDITOR.replace("video",
                    {
                        uiColor: '#f9f9f9',
                        filebrowserBrowseUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/ckfinder.html",
                        filebrowserImageBrowseUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/ckfinder.html?type=Images",
                        filebrowserFlashBrowseUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/ckfinder.html?type=Flash",
                        filebrowserUploadUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files",
                        filebrowserImageUploadUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images",
                        filebrowserFlashUploadUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash"
                    });
            </script>

            <!--Категория-->

            <p id="show_cat" class="garmowka">* Категория продукта<span>+</span></p>
<div class="show_hide_div1">
                <div>
                    <?php

                        $cur_cats = $pdo->query('SELECT `cat_id` FROM `cs_products_cat` WHERE `product_id` = '.$pdo->quote($_GET['id']).'')->fetchAll(PDO::FETCH_COLUMN);
						$main_cat = $pdo->query('SELECT `cat_id` FROM `cs_products_cat` WHERE `product_id` = '.$pdo->quote($_GET['id']).' AND `main_cat` = 1')->fetch(PDO::FETCH_COLUMN);
					$categories = $pdo->query("
                              SELECT `content_id`, `content_delete`, `content_hide_page`, `content_show_on_menu`, `content_under_menu`, `content_pagetitle_" . DEFAULT_LANG_DIR . "`
                              FROM `cs_content_list` WHERE `content_page_type` = 'category' AND `content_delete`='no' AND `content_hide_page`='no' AND `content_show_on_menu`='yes' AND `content_under_menu`=0 ORDER BY `content_id` ASC")->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($categories as $item):
                    ?>
                            <div class="cat_subcat_wrapper">
                            <?php if (in_array($item['content_id'], $cur_cats)): ?>
                                <div>
									<?php if ($item['content_id'] == $main_cat): ?>
										<a style="float: left; margin-right: 10px" class="main_cat" data-id="<?php echo $item['content_id']; ?>"  data-product_id="<?php echo $prod_id; ?>"><img class="main_cat_img" src="<?=IMAGE_DIR;?>day_of_on.png" /></a>
									<?php else: ?>
										<a style="float: left; margin-right: 10px" class="main_cat" data-id="<?php echo $item['content_id']; ?>"  data-product_id="<?php echo $prod_id; ?>"><img class="main_cat_img" src="<?=IMAGE_DIR;?>day_of_off.png" /></a>
									<?php endif ?>
									<label class="list"><input checked="checked" name="cat_id[]" class="cat_list" type="checkbox" value="<?php echo $item['content_id']; ?>"/><strong><?php echo $item['content_pagetitle_' . DEFAULT_LANG_DIR]; ?></strong></label>
								</div>
                            <?php else: ?><br />
                                <div>
									<a style="float: left; margin-right: 10px" class="main_cat" data-id="<?php echo $item['content_id']; ?>"  data-product_id="<?php echo $prod_id; ?>"><img class="main_cat_img" src="<?=IMAGE_DIR;?>day_of_off.png" /></a>
									<label class="list"><input class="cat_list" name="cat_id[]" type="checkbox" value="<?php echo $item['content_id']; ?>"/><strong><?php echo $item['content_pagetitle_' . DEFAULT_LANG_DIR]; ?></strong></label></div><br />
                            <?php endif ?>

                                <?php
                                    $sub_categories = $pdo->query("
                                            SELECT
                                                `content_id`, `content_delete`, `content_hide_page`, `content_show_on_menu`, `content_under_menu`, `content_pagetitle_" . DEFAULT_LANG_DIR . "`
                                            FROM
                                                `cs_content_list`
                                            WHERE
                                                `content_page_type` = 'category' AND `content_delete`='no' AND `content_hide_page`='no' AND `content_show_on_menu`='yes' AND `content_under_menu`=" . (int)$item['content_id'] . "
                                            ORDER BY
                                                `content_id` DESC
                                        ")->fetchAll(PDO::FETCH_ASSOC);

                                if (!empty($sub_categories)):
                                    foreach ($sub_categories as $item):
                                        ?>
                                        <?php if (in_array($item['content_id'], $cur_cats)): ?>
                                             <div>
												 <?php if ($item['content_id'] == $main_cat): ?>
													 <a style="float: left; margin-right: 10px" class="main_cat" data-id="<?php echo $item['content_id']; ?>"  data-product_id="<?php echo $prod_id; ?>"><img class="main_cat_img" src="<?=IMAGE_DIR;?>day_of_on.png" /></a>
												 <?php else: ?>
													 <a style="float: left; margin-right: 10px" class="main_cat" data-id="<?php echo $item['content_id']; ?>"  data-product_id="<?php echo $prod_id; ?>"><img class="main_cat_img" src="<?=IMAGE_DIR;?>day_of_off.png" /></a>
												 <?php endif ?>
												 <label class="list"><input checked="checked" name="cat_id[]" class="cat_list" type="checkbox" value="<?php echo $item['content_id']; ?>"/> ---- <?php echo $item['content_pagetitle_' . DEFAULT_LANG_DIR]; ?></label></div>
                                    <?php else: ?>
                                             <div>
												 <a style="float: left; margin-right: 10px" class="main_cat" data-id="<?php echo $item['content_id']; ?>"  data-product_id="<?php echo $prod_id; ?>"><img class="main_cat_img" src="<?=IMAGE_DIR;?>day_of_off.png" /></a>
												 <label class="list"><input class="cat_list" name="cat_id[]" type="checkbox" value="<?php echo $item['content_id']; ?>"/> ---- <?php echo $item['content_pagetitle_' . DEFAULT_LANG_DIR]; ?></label></div>
                                    <?php endif ?>
                                        <?php
                                            $sub_categories_child = $pdo->query("
                                                SELECT
                                                    `content_id`, `content_delete`, `content_hide_page`, `content_show_on_menu`, `content_under_menu`, `content_pagetitle_" . DEFAULT_LANG_DIR . "`
                                                FROM
                                                    `cs_content_list`
                                                WHERE
                                                    `content_page_type` = 'category' AND `content_delete`='no' AND `content_hide_page`='no' AND `content_show_on_menu`='yes' AND `content_under_menu`=" . (int)$item['content_id'] . "
                                                ORDER BY
                                                    `content_id` DESC
                                            ")->fetchAll(PDO::FETCH_ASSOC);

                                        if (!empty($sub_categories_child)):
                                            foreach ($sub_categories_child as $item):
                                                ?>
                                                <?php if (in_array($item['content_id'], $cur_cats)): ?>
                                                    <div>

														<?php if ($item['content_id'] == $main_cat): ?>
															<a style="float: left; margin-right: 10px" class="main_cat" data-id="<?php echo $item['content_id']; ?>"  data-product_id="<?php echo $prod_id; ?>"><img class="main_cat_img" src="<?=IMAGE_DIR;?>day_of_on.png" /></a>
														<?php else: ?>
															<a style="float: left; margin-right: 10px" class="main_cat" data-id="<?php echo $item['content_id']; ?>"  data-product_id="<?php echo $prod_id; ?>"><img class="main_cat_img" src="<?=IMAGE_DIR;?>day_of_off.png" /></a>
														<?php endif ?>
														<label class="list"><input checked="checked" name="cat_id[]" class="cat_list" type="checkbox" value="<?php echo $item['content_id']; ?>"/> ------- <?php echo $item['content_pagetitle_' . DEFAULT_LANG_DIR]; ?></label></div>
                                            <?php else: ?>
                                                    <div>
														<a style="float: left; margin-right: 10px" class="main_cat" data-id="<?php echo $item['content_id']; ?>"  data-product_id="<?php echo $prod_id; ?>"><img class="main_cat_img" src="<?=IMAGE_DIR;?>day_of_off.png" /></a>
														<label class="list"><input class="cat_list" name="cat_id[]" type="checkbox" value="<?php echo $item['content_id']; ?>"/> -------- <?php echo $item['content_pagetitle_' . DEFAULT_LANG_DIR]; ?></label></div>
                                            <?php endif ?>
                                            <?php endforeach ?>
                                        <?php endif ?>

                                    <?php endforeach ?>
                                <?php endif ?>
                            </div>

                    <?php endforeach ?>



</div>

    <div style="clear:both;"></div>
    </div>


            <p id="show_accessories"  class="garmowka">Аксессуары<span>+</span></p>
                <div class="show_hide_div">
                    <?php
                        $accessories = $pdo->query('SELECT * FROM `cs_product` WHERE `as_accessory` = 1')->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($accessories as $item):
                            ?>
                            <label>
                                <?php if (in_array($item['id'], $cur_accessories)): ?>
                                    <input checked="checked" type="checkbox" value="<?php echo $item['id']; ?>" name="accessories[]" />
                                <?php else: ?>
                                    <input type="checkbox" value="<?php echo $item['id']; ?>" name="accessories[]" class=""/>
                                <?php endif ?>
                                <?php echo $item['name_ru']; ?>
                            </label>
                        <?php endforeach ?>
                </div>

            <br/> <br/>


            <input type="submit" name="save" class="button" value="Сохранить"/>
        </form>

        <div class="clear"></div>

        <script type="text/javascript" src="/media/js/popup.js"></script>
    <script language="javascript" type="text/javascript">
        $(document).ready(function(){

			$('.main_cat').live('click', function() {
				$('.main_cat_img').attr('src', 'templates/default/images/day_of_off.png');
				cur_el = $(this);
				product_id = $(this).attr('data-product_id');
				cat_id = $(this).attr('data-id');
				$.post('actions/pages/image.php',
					{
						action: 'main_cat',
						product_id: product_id,
						cat_id: cat_id
					},
					function(data) {
						if (data == 'ok') {
							console.log('yes');
							$('.main_cat_img', cur_el).attr('src', 'templates/default/images/day_of_on.png');
						} else {
							alert('error delete');
						}
					}
				)
				return false;
			});


            var show_accessories = $('#show_accessories');
            var show_hide_div = $('.show_hide_div');

            var show_cat = $('#show_cat');
            var show_hide_div1 = $('.show_hide_div1');

            show_hide_div.hide();
            show_hide_div1.hide();



            $('#show_accessories').click(function () {
                show_hide_div.toggle(600);
                if( $(this).find('span').text() == '+' )
                {
                 $(this).find('span').text('-');
                }
                else
                {
                  $(this).find('span').text('+');
                }
            });


            $('#show_cat').click(function () {
                show_hide_div1.toggle(600);
                if( $(this).find('span').text() == '+' )
                {
                    $(this).find('span').text('-');
                }
                else
                {
                    $(this).find('span').text('+');
                }
            });







        });// ready end

    </script>

    </div>

</div>