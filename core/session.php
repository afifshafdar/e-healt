<?php
function setSession($index, $value) {
	return $_SESSION[$index] = $value;
}

function getSession($index) {
	return $_SESSION[$index];
}

function cekSessionUser() {
	if(isset($_SESSION['user']['username']) && isset($_SESSION['user']['fullname'])) {
		return true;
	} else {
		return false;
	}
}

function getSessionUser($index) {
	$data = $_SESSION['user'][$index];
	return $data;
}

function setSessionUser($data = []) {
	$data = (array) $data;
	foreach ($data as $key => $value) {
		$_SESSION['user'][$key] = $value;
	}
}

function resetSessionUser() {
	unset($_SESSION['user']);
	session_destroy();
}

function setMessage($type, $text, $pathRedirect) {
	@$_SESSION['message'] = [
		'type' => $type,
		'text' => $text,
		'path_redirect' => $pathRedirect
	];
}

function getMessage($key) {
	return @$_SESSION['message'][$key];
}

function checkMessage() {
	if(isset($_SESSION['message']['type'], $_SESSION['message']['text'], $_SESSION['message']['path_redirect']) && ($_SESSION['message']['type'] != '' && $_SESSION['message']['text'] != '' && $_SESSION['message']['path_redirect'] != '')) {
		return true;
	} else {
		return false;
	}
}

function setUserLocation($lat, $lng) {
	@$_SESSION['user_latitude'] = $lat;
	@$_SESSION['user_longitude'] = $lng;
}

function getUserLocation($key) {
	return @$_SESSION[$key];
}

function checkUserLocation() {
	return (isset($_SESSION['user_latitude'], $_SESSION['user_longitude'])) ? true : false;
}
