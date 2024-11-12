<?php
$hostname = env('DB_HOST', '');
$username = env('DB_USER', '');
$password = env('DB_PASS', '');
$database = env('DB_NAME', '');

$link = mysqli_connect($hostname, $username, $password, $database) or die(mysqli_connect_error());

?>