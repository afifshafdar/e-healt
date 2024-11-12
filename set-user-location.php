<?php
if(file_exists("core/init.php")) {
    require_once("core/init.php");
} else {
    die("Main configuration file is empty!");
}

if(isAjaxRequest()){
	$user_latitude = getPost('user_latitude');
	$user_longitude = getPost('user_longitude');
	
	//When user lat and lng not empty
	if(!empty(trim($user_latitude)) && !empty(trim($user_longitude))) {

		//When the user location is not defined
		if(!checkUserLocation()) {
			setUserLocation($user_latitude, $user_longitude);
			$response = [
				'status' => 'success',
				'data'	=> [
					'user_latitude' => $user_latitude,
					'user_longitude' => $user_longitude
				]
			];
		} else {
			$response = [
				'status' => 'success',
				'data'	=> [
					'user_latitude' => getUserLocation('user_latitude'),
					'user_longitude' => getUserLocation('user_longitude')
				]
			];
		}
	} else {
		$response = [
			'status' => 'error',
			'message' =>  'User location is empty!'
		];
	}

	echo json_encode($response);
}