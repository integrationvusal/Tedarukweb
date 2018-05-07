<?php

header('Content-type: application/json; charset=utf-8');

$response = [
	'success' => false, // request status
	'message' => 'unknown error', // human-readable request status description
	//['code' => string], // HTTP-like response status for programm processing
	//['data' => [],] // data
	//['errors' => [],] // multiple errors or notifications
];

if (!defined("_VALID_PHP")) {
	$response['message'] = 'Direct access to this location is not allowed.';
} else if (empty($_GET['action'])) {
	$response['message'] = 'Action is not defined by request.';
} else {
	switch ($_GET['action']) {
		case 'trigger_status':
			require_once MODEL_DIR.'debates.php';
			$id = @$_GET['id'];
			$status = @$_GET['set'];
			$updated = debates::setPollStatus($id, $status);
			if ($updated) {
				$response['success'] = true;
				$response['message'] = 'Performed successfully';
				$response['data']['action'] = $_GET['set'];
			}
		break;
		default:
			$response['message'] = 'Action is not registered.';
		break;
	}
}

print json_encode($response);
die();

?>