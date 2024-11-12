<?php
function env($key, $default_value) {
	return (getenv($key) ? getenv($key) : $default_value);
}

function base_url($file = NULL) {
	if(isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] != '') {
		$path = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/e-bengkel/";
	} else if(getenv('APP_URL')) {
		$path = getenv('APP_URL');
	}

	$path .= $file;
	return $path;
}

function redirect($loc = "back")
{
	if($loc != "back") {
		$loc = ($loc == 404) ? "../404.php" : $loc;
		echo "<script type='text/javascript'>window.location='".$loc."';</script>";
	} else {
		echo "<script type='text/javascript'>window.history.back();</script>";
	}
}

function alert($text, $location = NULL){
	$alert = "<script type='text/javascript'>alert('$text');";
	if($location != NULL) {
		if($location == "back") {
			$alert .= "window.history.back();";
		} else {
			$alert .= "window.location='$location';";
		}
	}
	$alert .= "</script>";
	echo $alert;
}

function getLevel($index = NULL) {
	$levels = [
		'Administrator', 'Operator', 'Warga / Masyarakat'
	];

	if($index != NULL) {
		return $levels[$index-1];
	} else {
		return $levels;
	}
}

function getTemplate($page, $action = '') {
	if($action == '') {
		$action = 'index';
	}

	$path_file = "content/".$page."/".$action.".php";
	if (file_exists($path_file)) {
		$file = require_once($path_file);
		return $file;
	} else {
		die("Template tidak ditemukan!");
	}
}

function generateUid() {
	$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz'; 
    $randomString = '';
    $randomString2 = '';
    $randomString3 = '';
    $randomString4 = '';
  
    for ($i = 0; $i < 4; $i++) { 
        $index = rand(0, strlen($characters) - 1); 
        $index2 = rand(2, strlen($characters) - 2); 
        $index3 = rand(3, strlen($characters) - 3); 
        $index4 = rand(4, strlen($characters) - 4); 
        $randomString .= $characters[$index]; 
        $randomString2 .= $characters[$index2]; 
        $randomString3 .= $characters[$index3]; 
        $randomString4 .= $characters[$index4]; 
    } 
  
    $result = $randomString . "-" . $randomString2 . "-" . $randomString3 . "-" . $randomString4; 
    return ($result);
}

function encrypt_decrypt($action, $string) {
    $output = false;

    $encrypt_method = "AES-256-CBC";
    $secret_key = 'This is my secret key';
    $secret_iv = 'This is my secret iv';

    // hash
    $key = hash('sha256', $secret_key);
    
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}


function toRupiah($number) {
	$format = "Rp. " . str_replace(",", ".", number_format($number)) . ",-";
	return $format;
}

function slug($string) {
	$result = str_replace("'", "", str_replace(" ", "-", $string));
	return $result;
}

function getHariKerja($index = NULL) {
	$list_hari_kerja = [
		'setiap_hari' => 'Setiap Hari',
		'6_hari' => 'Senin - Sabtu',
		'5_hari' => "Senin - Jum'at",
		'4_hari' => 'Senin - Kamis'
	];

	if($index != NULL) {
		return $list_hari_kerja[$index];
	} else {
		return $list_hari_kerja;
	}
}

?>