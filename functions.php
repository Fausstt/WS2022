<?php
var_dump($_POST);
function ajax_form()
{
	$fname = $_POST['FName'];
	$lname = $_POST['LName'];
	$fullphone = $_POST['Phone']; // \D [^0-9]
	$email = $_POST['Email'];
	extract(array_map("htmlspecialchars", $_POST), EXTR_OVERWRITE);

	$url = "https://marketing.affboat.com/api/v3/integration?api_token=SHby4oKeyyZeW6iuzTggnotl6Jvi2dogCNlssroHvr267kMKr6Fwm7rgsqQh";
	// ?api_token=qTBkzadtPj0Ax3iQcyPwzbDZlmo3DRJmrFmu5JFVxG5PSq3LWyybNrCxVoBw

	if (isset($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} else {
		$ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
	}


	$apiData = [
		'api_token' => "SHby4oKeyyZeW6iuzTggnotl6Jvi2dogCNlssroHvr267kMKr6Fwm7rgsqQh",
		'fname' => $fname,
		'lname' => isset($lname) ? $lname : 'None',
		'fullphone' => $fullphone,
		'ip' => $ip,
		'email' => $email,
		'source' => "quanta.trade",
		'link_id' => 3234,

	];

	header('Content-Type: application/json');

	try {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt(
			$ch,
			CURLOPT_POSTFIELDS,
			http_build_query($apiData)
		);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec($ch);
		$response = json_decode($server_output);
	} catch (Exception $e) {
		echo json_encode(['success' => false, 'message' => $e->getMessage()]);
		die;
	}

	if ($response->success) {
		echo json_encode([
			'success' => true,
			'autologin' => $response->autologin,
			'password' => ($response->password),
			'login' => ($apiData['email'])
		]);
		die;
	} else {
		echo json_encode([
			'success' => false,
			'message' => 'Server error!',
			'debug' => json_encode($response)

		]);
		die;
	}
}
