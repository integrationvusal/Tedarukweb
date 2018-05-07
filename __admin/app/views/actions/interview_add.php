<?php

use jewish\backend\CMS;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;
use app\views\widgets\HTML;

if (!defined("_VALID_PHP")) die('Direct access to this location is not allowed.');

?>


<?php

// load resourses

// load jQuery UI (for autocomplete)
view::appendCsS(SITE_DIR . CMS_DIR . JS_DIR . 'jquery-ui-1.12.1/jquery-ui.css');
view::prependJS(SITE_DIR . CMS_DIR . JS_DIR . 'jquery-ui-1.12.1/jquery-ui.min.js');

// load Bootstrap Datepicker
view::appendCsS(SITE_DIR . CMS_DIR . JS_DIR . 'bootstrap-datepicker/css/bootstrap-datepicker3.css');
view::appendJS(SITE_DIR . CMS_DIR . JS_DIR . 'bootstrap-datepicker/js/bootstrap-datepicker.min.js');
view::appendJS(SITE_DIR . CMS_DIR . JS_DIR . 'bootstrap-datepicker/locales/bootstrap-datepicker.' . $_SESSION[CMS::$sess_hash]['ses_adm_lang'] . '.min.js');


//MagicSuggest
view::appendCsS(SITE_DIR . CMS_DIR . CSS_DIR . 'magicsuggest-min.css');
view::appendJS(SITE_DIR . CMS_DIR . JS_DIR . 'magicsuggest-min.js');


// load CK Editor
view::appendJS(SITE_DIR . CMS_DIR . JS_DIR . 'ckeditor/ckeditor.js');

?>
<script type="text/javascript">
    // <![CDATA[

    $(document).ready(function () {
        /*$('[name="gallery_name"]').autocomplete({
         source: function(request, response_callback) {
         $.ajax({
         url: '?controller=gallery&action=ajax_get_autocomplete&q='+request.term,
         async: true,
         cache: false,
         dataType: 'json',
         success: function(response, status, xhr) {
         if (response.success) {
         response_callback(response.data);
         } else {
         response_callback([]);
         }
         },
         error: function(xhr, err, descr) {
         response_callback([]);
         }
         });
         },
         minLength: 2,
         change: function(event, ui) {
         $('[name="gallery_id"]').val(ui.item? ui.item.id: '');
         },
         select: function(event, ui) {
         $('[name="gallery_id"]').val(ui.item? ui.item.id: '');
         }
         });
         $('[name="gallery_name"]').focus(function() {
         this.value = '';
         });

        function checkIn(array, value) {
            for (x in array) {
                if (array[x].name == value) return false;
            }
            return true;
        }

        var ms = $('#magicsuggest').magicSuggest({
            data: JSON.parse('<?=$all_tags?>'),
            name: 'tags',
            placeholder: '<?=CMS::t('interview_keywords_select')?>'
        });

        $(ms).on('blur', function (e, m) {

            if (this.getSelection().length) {
                _this = this;
                _value = this.getSelection()[this.getSelection().length - 1].name;

                if (checkIn(this.getData(), _value)) {
                    $.get('<?=view::create_url('keywords', 'add_by_value')?>', {value: _value}, function (response) {
                        if (response.success) {
                            _datas = _this.getData();
                            _new_data = {id: response.data, name: _value};
                            _datas.unshift(_new_data);
                            _this.setData(_datas);
                            ms.setValue([response.data]);
                            $('.ms-sel-item:contains("' + _value + '"):first').find('.ms-close-btn').trigger('click');
                        }
                    });
                }
            }
        });
		
		*/

    });
    // ]]>
</script>


<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?= CMS::t('menu_item_interview_add'); ?>
        <!-- <small>Subtitile</small> -->
    </h1>

    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
    </ol> -->
</section>

<!-- Content Header (Page header) -->
<section class="contextual-navigation">
    <nav>
        <a href="<?= utils::safeEcho($link_back, 1); ?>" class="btn btn-default"><i class="fa fa-arrow-left"
                                                                                    aria-hidden="true"></i> <?= CMS::t('back'); ?>
        </a>
    </nav>
</section>


<!-- Main content -->
<section class="content">
    <?php
    if (!empty($op)) {
        if ($op['success']) {
            print view::notice($op['message'], 'success');
        } else {
            print view::notice(empty($op['errors']) ? $op['message'] : $op['errors']);
        }
    }
    ?>

    <!-- Info boxes -->

    <div class="box" style="border-top: none; ">
        <!-- <div class="box-header with-border">
			<h3 class="box-title"><?= CMS::t('menu_item_interview_add'); ?></h3>
		</div> -->
        <!-- /.box-header -->

        <form action="" method="post" enctype="multipart/form-data" class="form-std" role="form">
            <input type="hidden" name="CSRF_token" value="<?= $CSRF_token; ?>"/>

            <div class="box-body" style="padding: 0;">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <?php if (!empty($langs) && is_array($langs)) foreach ($langs as $k=>$lng) {?>
							<li <?=$lng['language_dir']==CMS::$default_site_lang?'class="active"':null?>><a data-tab="true" href="#desc-tab-<?=$lng['language_dir']?>"><?=$lng['language_name']?></a></li>
						<?php } ?>
						<li><a data-tab="true" href="#common-tab"><?=CMS::t('common_data')?></a></li>
                    </ul>

                    <div class="tab-content">
                        <!-- Common Informational TAB -->
                        <div class="tab-pane" id="common-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><a href="https://google.com/?q=SEF+friendly+urls" title=""
                                                  target="_blank"><?= CMS::t('interview_sef'); ?> <i
                                                        class="fa fa-external-link" aria-hidden="true"
                                                        style="font-size: inherit;"></i></a></label>

                                        <input type="text" name="sef" value="<?= utils::safeEcho(@$_POST['sef'], 1); ?>"
                                               class="form-control"/>
                                        <!-- <p class="form-input-tip"><?= CMS::t('interview_sef_descr'); ?></p> -->
                                    </div>

                                    <div class="form-group">
                                        <label><?= CMS::t('image'); ?></label>

                                        <?= view::browse([
                                            'name' => 'img',
                                            'accept' => 'image/*'
                                        ]); ?>

                                        <p class="form-info-tip"><?= CMS::t('interview_image_descr', [
                                                '{types}' => implode(', ', $allowed_thumb_ext)
                                            ]); ?></p>
                                    </div>


                                    <div class="form-group">
                                        <label><?= CMS::t('interview_add_datetime'); ?></label>

                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                                    <input type="text" name="add_date"
                                                           value="<?= utils::safePostValue('add_date', date('d.m.Y')); ?>"
                                                           placeholder="<?= CMS::t('interview_publish_date_placeholder'); ?>"
                                                           class="form-control datepicker"/>
                                                </div>

                                                <script type="text/javascript">
                                                    // <![CDATA[
                                                    $(document).ready(function () {
                                                        $('[name="add_date"]').datepicker({
                                                            format: 'dd.mm.yyyy',
                                                            clearBtn: true,
                                                            language: '<?=utils::safeJsEcho($_SESSION[CMS::$sess_hash]['ses_adm_lang'], 1);?>'
                                                        });
                                                    });
                                                    // ]]>
                                                </script>
                                            </div>

                                            <div class="col-xs-6">
                                                <select name="add_hour" class="form-control"
                                                        style="display: inline-block; width: 45%;">
                                                    <option value=""><?= CMS::t('interview_add_hour_placeholder'); ?></option>
                                                    <?php
                                                    $publish_hour_selected = (empty($_POST['add_hour']) ? date('G') : $_POST['add_hour']);
                                                    for ($i = 0; $i < 24; $i++) {
                                                        ?>
                                                        <option value="<?= $i; ?>"<?= (($i == $publish_hour_selected) ? ' selected="selected"' : ''); ?>><?= str_pad($i, 2, '0', STR_PAD_LEFT); ?></option><?php
                                                    }
                                                    ?>
                                                </select>
                                                :
                                                <select name="add_minutes" class="form-control"
                                                        style="display: inline-block; width: 45%;">
                                                    <option value=""><?= CMS::t('interview_add_minutes_placeholder'); ?></option>
                                                    <?php
                                                    $publish_minutes_selected = (empty($_POST['add_minutes']) ? (floor(date('i') / 5) * 5) : $_POST['add_minutes']);
                                                    if ($publish_minutes_selected >= 60) {
                                                        $publish_minutes_selected = 0;
                                                    }
                                                    for ($i = 0; $i < 60; $i = ($i + 5)) {
                                                        ?>
                                                        <option value="<?= $i; ?>"<?= (($i == $publish_minutes_selected) ? ' selected="selected"' : ''); ?>><?= str_pad($i, 2, '0', STR_PAD_LEFT); ?></option><?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label><?= CMS::t('interview_publish_datetime'); ?></label>

                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                                    <input type="text" name="publish_date"
                                                           value="<?= utils::safePostValue('publish_date', date('d.m.Y')); ?>"
                                                           placeholder="<?= CMS::t('interview_publish_date_placeholder'); ?>"
                                                           class="form-control datepicker"/>
                                                </div>

                                                <script type="text/javascript">
                                                    // <![CDATA[
                                                    $(document).ready(function () {
                                                        $('[name="publish_date"]').datepicker({
                                                            format: 'dd.mm.yyyy',
                                                            clearBtn: true,
                                                            language: '<?=utils::safeJsEcho($_SESSION[CMS::$sess_hash]['ses_adm_lang'], 1);?>'
                                                        });
                                                    });
                                                    // ]]>
                                                </script>
                                            </div>

                                            <div class="col-xs-6">
                                                <select name="publish_hour" class="form-control"
                                                        style="display: inline-block; width: 45%;">
                                                    <option value=""><?= CMS::t('interview_publish_hour_placeholder'); ?></option>
                                                    <?php
                                                    $publish_hour_selected = (empty($_POST['publish_hour']) ? date('G') : $_POST['publish_hour']);
                                                    for ($i = 0; $i < 24; $i++) {
                                                        ?>
                                                        <option value="<?= $i; ?>"<?= (($i == $publish_hour_selected) ? ' selected="selected"' : ''); ?>><?= str_pad($i, 2, '0', STR_PAD_LEFT); ?></option><?php
                                                    }
                                                    ?>
                                                </select>
                                                :
                                                <select name="publish_minutes" class="form-control"
                                                        style="display: inline-block; width: 45%;">
                                                    <option value=""><?= CMS::t('interview_publish_minutes_placeholder'); ?></option>
                                                    <?php
                                                    $publish_minutes_selected = (empty($_POST['publish_minutes']) ? (floor(date('i') / 5) * 5) : $_POST['publish_minutes']);
                                                    if ($publish_minutes_selected >= 60) {
                                                        $publish_minutes_selected = 0;
                                                    }
                                                    for ($i = 0; $i < 60; $i = ($i + 5)) {
                                                        ?>
                                                        <option value="<?= $i; ?>"<?= (($i == $publish_minutes_selected) ? ' selected="selected"' : ''); ?>><?= str_pad($i, 2, '0', STR_PAD_LEFT); ?></option><?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="checkbox" name="is_published"
                                               value="1"<?= ((isset($_POST['is_published']) && empty($_POST['is_published'])) ? '' : ' checked="checked"'); ?>
                                               id="triggerNewsStatus"/><label for="triggerNewsStatus"
                                                                                  style="display: inline; font-weight: normal;"> <?= CMS::t('publish'); ?></label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <?php if (!empty($cats) && count($cats)) { ?>
                                        <div class="form-group">
                                            <label><?= CMS::t('news_category'); ?></label>

                                            <div class="form-div-multicheckbox"
                                                 style="width: 100%; height: auto; max-height: 214px;">
                                                 <?=HTML::renderTree($cats)?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>

                            </div>
                        </div>


                        <?php
                        if (!empty($langs) && is_array($langs)) foreach ($langs as $lng) {
                            ?>
                            <div id="desc-tab-<?=$lng['language_dir']?>" class="active tab-pane">
                                <!-- <?= $lng['language_name']; ?> tab -->

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?= CMS::t('interview_title'); ?> <?= (($lng['language_dir'] == CMS::$default_site_lang) ? '*' : ''); ?></label>

                                            <input type="text" name="title[<?= $lng['language_dir']; ?>]"
                                                   value="<?php utils::safeEcho(@$_POST['title'][$lng['language_dir']]); ?>"
                                                   class="form-control"/>
                                        </div>

                                        <div class="form-group">
                                            <label><?= CMS::t('interview_full'); ?> <?= (($lng['language_dir'] == CMS::$default_site_lang) ? '*' : ''); ?></label>

                                            <textarea name="full[<?= $lng['language_dir']; ?>]" rows="4" cols="32"
                                                      class="form-input-std"
                                                      id="wysiwyg_full_<?= $lng['language_dir']; ?>"><?php utils::safeEcho(@$_POST['full'][$lng['language_dir']]); ?></textarea>
                                            <script type="text/javascript">
                                                // <![CDATA[
                                                CKEDITOR.replace('wysiwyg_full_<?=$lng['language_dir'];?>', {
                                                    uiColor: '#f9f9f9',
                                                    language: '<?=$lng['language_dir'];?>',
                                                    filebrowserBrowseUrl: '<?=SITE . CMS_DIR . JS_DIR?>ckeditor/ckfinder/ckfinder.html?hash=<?=CMS::$sess_hash?>',
                                                    filebrowserUploadUrl: '<?=SITE . CMS_DIR . JS_DIR?>ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                                                    height: 200
                                                });
                                                // ]]>
                                            </script>
                                        </div>

                                        <?php if ($lng['language_dir'] != CMS::$default_site_lang) { ?>
                                            <div class="form-group">
                                                <input type="checkbox"
                                                       name="is_published_lang[<?= $lng['language_dir']; ?>]"
                                                       value="1"<?= ((isset($_POST['is_published_lang'][$lng['language_dir']]) ? $_POST['is_published_lang'][$lng['language_dir']] : ($lng['language_dir'] == CMS::$default_site_lang)) ? ' checked="checked"' : ''); ?>
                                                       id="triggerLangStatus_<?= $lng['language_dir']; ?>"/><label
                                                        for="triggerLangStatus_<?= $lng['language_dir']; ?>"
                                                        style="display: inline; font-weight: normal;"> <?= CMS::t('publish_lang'); ?></label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                <button type="submit" name="add" value="1" class="btn btn-primary"><i class="fa fa-plus-circle"
                                                                                      aria-hidden="true"></i> <?= CMS::t('add'); ?>
                </button>
                <button type="reset" name="reset" value="1" class="btn btn-default"><i class="fa fa-refresh"
                                                                                       aria-hidden="true"></i> <?= CMS::t('reset'); ?>
                </button>
            </div>
        </form>
    </div>
    <!-- /.box -->

    <!-- /.info boxes -->
</section>
<!-- /.content -->