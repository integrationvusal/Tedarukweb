<?php

if (!defined("_VALID_PHP")) {die();}

$empty_filter = (!isset($_GET['invitors']) && !isset($_GET['status']) && !isset($_GET['since']) && !isset($_GET['till']));
if ($empty_filter) {
	$default_date_since = date('d.m.Y', mktime(0, 0, 0, date('n'), date('j')-7, date('Y')));
}

include 'actions/langs/print_report_ru.lang.php';
require_once 'actions/plugins/print_report.class.php';
print_report::$db = $pdo;

$list = print_report::getRecords();
// print "<pre>\n".var_export($list, true)."\n</pre>";


$tr_statuses = array(
	'planned' => 'Назначен',
	'cancelled' => 'Отменён',
	'started' => 'Протекает',
	'finished' => 'Завершён',
	'expired' => 'Истёк'
);

?>

<div class="content-box">
    <div class="content-box-header">
        <h3>Отчёт по визиторам</h3>

        <div class="clear"></div>
    </div>

	<div class="content-box-filter">
		<form action="" method="get" id="fltForm">
			<input type="hidden" name="page" value="<?php print @$_GET['page']; ?>" />
			<input type="hidden" name="<?php print time(); ?>" value="" />

			<div class="pickUsers" style="margin: 10px; display: inline-block; vertical-align: top;">
				<p>Фильтр по редакторам:</p>

				<div style="border: 1px solid #d5d5d5; border-radius: 5px; width: 260px; height: 120px; padding: 10px 0 10px 10px;">
					<div style="background: #fff; height: 120px; overflow: auto;">
						<?php
							$redaktors = print_report::getUsersList();
							if (is_array(@$redaktors) && count(@$redaktors)) {
								foreach ($redaktors as $usr) {
									print '<input type="checkbox" name="invitors[]" value="'.$usr['id'].'"'.(@in_array($usr['id'], @$_GET['invitors'])? ' checked="checked"': '').' onclick="this.form.submit();" id="invitor_'.$usr['id'].'" /> <label for="invitor_'.$usr['id'].'" style="display: inline; font-weight: normal;" title="'.$usr['username'].'">'.(empty($usr['displayname'])? $usr['email']: $usr['displayname']).'</label><br />';
								}
							}
						?>
					</div>
				</div>
			</div>

			<div class="fltStatus" style="margin: 10px; display: inline-block; vertical-align: top;">
				<p>Фильтр по статусу:</p>

				<select name="status" onchange="this.form.submit();">
					<option value="">Все</option>
					<?php
						foreach ($tr_statuses as $st_name=>$st_tr) {
							print '<option value="'.$st_name.'"'.(($st_name==@$_GET['status'])? ' selected="selected"': '').'>'.$st_tr.'</option>';
						}
					?>
				</select>
			</div>

			<div class="fltDateInterval" style="margin: 10px; display: inline-block; vertical-align: top;">
				<p>Фильтр по дате:</p>

				<div>
					<input type="text" name="since" value="<?php print (empty($default_date_since)? @$_GET['since']: $default_date_since); ?>" class="datepicker" id="fltDateInterval_since" style="padding: 4px; border: 1px solid #d5d5d5; border-radius: 4px;" /> <button onclick="document.getElementById('fltDateInterval_since').value = ''; document.getElementById('fltForm').submit(); return false;">X</button> - <input type="text" name="till" value="<?php print @$_GET['till']; ?>" class="datepicker" id="fltDateInterval_till" style="padding: 4px; border: 1px solid #d5d5d5; border-radius: 4px;" /> <button onclick="document.getElementById('fltDateInterval_till').value = ''; document.getElementById('fltForm').submit(); return false;">X</button>
					<script type="text/javascript">
$(document).ready(function() {
	$('#fltDateInterval_since').datepicker({
		dateFormat: 'dd.mm.yy',
		onSelect: function(dateText, datepickerInst) {
			this.form.submit();
		}
	});
	$('#fltDateInterval_till').datepicker({
		dateFormat: 'dd.mm.yy',
		onSelect: function(dateText, datepickerInst) {
			this.form.submit();
		}
	});
});
					</script>
				</div>
			</div>

			<!-- datepickers interval -->
		</form>
	</div>

	<div class="content-box-content">
		<form name="menu_list" action="" method="post">
			<table width="100%" cellpadding="0" cellspacing="0" id="myTable" class="tablesorter">
				<!-- // author, guest, purpose, status, invitation time, visit start time, visit end time, card No, add date -->
                <thead>
					<tr>
						<th id="d_invitor">Редактор</th>
						<th id="d_guest">Гость</th>
						<th id="d_purpose">Цель визита</th>
						<th id="d_invitation_time">Назначен на</th>
						<th id="d_status">Статус</th>
						<th id="d_visit_start">Зарегистрирован</th>
						<th id="d_visit_end">Время выхода</th>
						<th id="d_card_id">Номер карты пропуска</th>
					</tr>
                </thead>
                <tbody>
					<?php

						if (is_array(@$list) && count(@$list)) {
							foreach ($list as $rec) {
								$status = 'planned';
								if (!empty($rec['is_cancelled'])) {
									$status = 'cancelled';
								} else if (!empty($rec['card_id'])) {
									if (empty($rec['visit_end'])) {
										$status = 'started';
									} else {
										$status = 'finished';
									}
								} else if (date('d.m.Y', $rec['invitation_time'])!=date('d.m.Y')) {
									$status = 'expired';
								}
					?>
					<tr>
						<td><?php print (empty($rec['invitor_title'])? ($rec['invitor'].' '.$rec['invitor_mail']): $rec['invitor_title']).' ('.date('H:i d.m.Y', $rec['add_date']).')'; ?></td>
						<td><?php print $rec['guest_surname'].' '.$rec['guest_name'].(empty($rec['company_name'])? '': (' ('.$rec['company_name'].')')); ?></td>
						<td><?php print $rec['purpose']; ?></td>
						<td><?php print date('H:i d.m.Y', $rec['invitation_time']); ?></td>
						<td><?php print $tr_statuses[$status]; ?></td>
						<td><?php print (empty($rec['visit_start'])? ' - ': date('H:i d.m.Y', $rec['visit_start'])); ?></td>
						<td><?php print (empty($rec['visit_end'])? ' - ': date('H:i d.m.Y', $rec['visit_end'])); ?></td>
						<td><?php print (empty($rec['card_id'])? '': $rec['card_id']); ?></td>
					</tr>
					<?php
							}
						} else {
							print '<td colspan="8">'.$admin_lang['no_data_found'].'</td>';
						}
					?>
				</tbody>
			</table>
		</form>

		<div class="bottom_controls">
			<a href="#" title="Print" onclick="window.print(); return false;"><img src="<?php print SITE.TPL_DIR; ?>images/printer.png" alt="Print" style="height: 128px;" /></a>
			<a href="<?php
				$page_params = $_GET;
				$page_params['page'] = 'c_print_report_xl';
				$page_params['direct_output'] = '1';
				$page_params['via_tmp_file'] = '1';
				print '?'.$utils->array2url($page_params);
			?>" title="Export to Excel"><img src="<?php print SITE.TPL_DIR; ?>images/excel.png" alt="Export to Excel" style="height: 100px;" /></a>
		</div>
	</div>
</div>