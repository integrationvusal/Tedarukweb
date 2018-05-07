<?php

if (!defined("_VALID_PHP")) {die();}

$empty_filter = (!isset($_GET['invitors']) && !isset($_GET['status']) && !isset($_GET['since']) && !isset($_GET['till']));
if ($empty_filter) {
	$default_date_since = date('d.m.Y', mktime(0, 0, 0, date('n'), date('j')-7, date('Y')));
}

require_once 'actions/plugins/print_report.class.php';
print_report::$db = $pdo;

$list = print_report::getRecords();

if (is_array($list) && count($list)) {
	// open *.xlsx template file
	require_once 'plugins/php_excel/PHPExcel.php';
	//$xl = new PHPExcel();
	$xl = PHPExcel_IOFactory::load('templates/default/report_sample.xlsx');
	$sheet = $xl->getActiveSheet();
	//print '-'.$xl->getActiveSheet()->getCellByColumnAndRow(0, 2)->getValue().'-';

	$tr_statuses = array(
		'planned' => 'Назначен',
		'cancelled' => 'Отменён',
		'started' => 'Протекает',
		'finished' => 'Завершён',
		'expired' => 'Истёк'
	);

	foreach ($list as $i=>$rec) {
		$row_number = $i+3;
		$sheet->insertNewRowBefore($row_number);

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

		$sheet->setCellValueByColumnAndRow(0, $row_number, (empty($rec['invitor_title'])? $rec['invitor_mail']: $rec['invitor_title']));
		$sheet->setCellValueByColumnAndRow(1, $row_number, $rec['guest_surname'].' '.$rec['guest_name'].(empty($rec['company_name'])? '': (', '.$rec['company_name'])));
		$sheet->setCellValueByColumnAndRow(2, $row_number, $rec['purpose']);
		$sheet->setCellValueByColumnAndRow(3, $row_number, PHPExcel_Shared_Date::PHPToExcel($rec['invitation_time']+date('Z', $time)));
		$sheet->setCellValueByColumnAndRow(4, $row_number, $tr_statuses[$status]);
		if (!empty($rec['visit_start'])) {
			$sheet->setCellValueByColumnAndRow(5, $row_number, PHPExcel_Shared_Date::PHPToExcel($rec['visit_start']+date('Z', $time)));
		}
		if (!empty($rec['visit_end'])) {
			$sheet->setCellValueByColumnAndRow(6, $row_number, PHPExcel_Shared_Date::PHPToExcel($rec['visit_end']+date('Z', $time)));
		}
		if (!empty($rec['card_id'])) {
			$sheet->setCellValueByColumnAndRow(7, $row_number, (empty($rec['card_id'])? '': $rec['card_id']));
			$sheet->getStyleByColumnAndRow(7, $row_number)->getNumberFormat()->setFormatCode(str_pad('', strlen($rec['card_id']), '0', STR_PAD_LEFT));
			//$sheet->setCellValueExplicitByColumnAndRow(7, $row_number, (empty($rec['card_id'])? '': $rec['card_id']), PHPExcel_Cell_DataType::TYPE_STRING);
			//$sheet->setCellValueByColumnAndRow(7, $row_number, (empty($rec['card_id'])? '': "'{$rec['card_id']}"));
		}
	}
	$sheet->removeRow(2, 1);

	$xl_rw = PHPExcel_IOFactory::createWriter($xl, 'Excel2007');

	$do_output_fname = 'emcards_export_'.date('Ymd_His').'.xlsx';
	if (!empty($_GET['direct_output'])) {
		if (empty($_GET['via_tmp_file'])) {
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.$do_output_fname);
			header('Content-Transfer-Encoding: binary');

			$xl_rw->save('php://output');
			die();
		} else {
			$xl_rw->save('export_tmp/'.$do_output_fname);

			$do_output_file_size = filesize('export_tmp/'.$do_output_fname);
			header('Content-Type: application/octet-stream');
			header('Content-Length: '.$do_output_file_size);
			header('Content-Disposition: attachment; filename='.$do_output_fname);
			header('Content-Transfer-Encoding: binary');

			$file = fopen('export_tmp/'.$do_output_fname, 'rb');
			if ($file) {
				fpassthru($file);
			}
			die();
		}
	} else {
		$xl_rw->save('export_tmp/'.$do_output_fname);

		$utils->showNotice('success', 'File successfully saved to '.SITE.CMS_DIR.'export_tmp/'.$do_output_fname);
	}
}

?>