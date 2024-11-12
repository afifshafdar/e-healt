<?php

function getPost($key) {
	if(is_array($_POST[$key])) {
		return $_POST[$key];
	} else {
		return escape($_POST[$key]);
	}
}

function getFrom($index) {
	$get = isset($_GET[$index]) ? $_GET[$index] : "";
	return $get;
}

function getFile($main, $index) {
	return $_FILES[$main][$index];
}

function isAjaxRequest() {
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
}

function getImage($type, $filename, $is_admin = false) {
	$path_of_file = ($is_admin != false) ? "../uploads/".$type."/".$filename : "uploads/".$type."/".$filename;
	if(file_exists($path_of_file)) {
		$path = base_url("uploads/".$type."/".$filename);
	} else {
		$path = base_url("assets/img/no_image.jpg");
	}
	return $path;
}

function linkTo($page, $params = [], $isPublicLink = true) {
	$link = "";
	if($isPublicLink == false) {
		$link .= "admin/";
	}

	$link .= "index.php?page=". $page;
	$other_params = "";
	if(count($params) > 0) {
		foreach ($params as $key => $val) {
			$other_params .= "&$key=$val";
		}
	}

	$link .= $other_params;
	return base_url($link);
}