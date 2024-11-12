<?php
if (isset($_SERVER['DOCUMENT_ROOT']) && $_SERVER['DOCUMENT_ROOT'] != '') {
	if(strpos($_SERVER['DOCUMENT_ROOT'], 'e-bengkel') === true) {
		$__ROOT__ = $_SERVER['DOCUMENT_ROOT'];
	} else {
		$__ROOT__ = $_SERVER['DOCUMENT_ROOT'] . '/e-bengkel/';
	}
} else {
	$__ROOT__ = '/var/www/html/e-bengkel/';
}

require_once $__ROOT__ . 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable($__ROOT__);
$dotenv->load();

if(strtoupper(getenv('APP_DEBUG')) === TRUE || strtoupper(getenv('APP_DEBUG')) === 'TRUE') {
	error_reporting(E_ALL | E_STRICT);
	ini_set('display_errors', 'On');
} else {
	error_reporting(0);
	ini_set('display_errors', 'Off');
}

session_start();
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');
ob_start();

require_once 'session.php';
require_once 'other.php';
require_once 'connect.php';
require_once 'crud.php';
require_once 'method.php';
require_once 'auth.php';
$no = 1;
?>