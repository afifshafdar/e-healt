<?php
function select($columns, $table, $conditions = NULL) {
	if ($conditions != NULL) {
		$sql = "SELECT $columns FROM $table WHERE $conditions ";
	} else {
		$sql = "SELECT $columns FROM $table";
	}
	
	return query($sql);
}

function result($query) {
	return mysqli_fetch_object($query);
}
function query($sql){
	global $link;
	
	if ($data = mysqli_query($link, $sql) or die(mysqli_error($link))) {
		return $data;
	}
}

function execute($sql){
	global $link;
	if (mysqli_query($link, $sql) or die(mysqli_error($link))) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function insert($table, $cols, $values){
	$sql = "INSERT INTO $table ($cols) VALUES ($values) ";
	return execute($sql);
}

function update($table, $data, $id){
	$sql = "UPDATE $table SET $data WHERE id = $id ";
	//die($sql);
	return execute($sql);
}

function delete($table, $where = []){
	$sql = "DELETE FROM $table WHERE ".array_keys($where)[0]." = '".array_values($where)[0]."'";
	return execute($sql);
}

function cekRow($sql) {
	return mysqli_num_rows($sql);
}

function joinTable($table1, $table2, $conditions, $columns = '*', $where = NULL) {
    // Check for required parameters
    if (empty($table1) || empty($table2) || empty($conditions)) {
        throw new InvalidArgumentException("Table names and conditions cannot be empty.");
    }

    // Build the SQL query
    $sql = "SELECT $columns FROM $table1 JOIN $table2 ON $conditions";
    
    // Append WHERE clause if provided
    if ($where != NULL) {
        $sql .= " WHERE $where";
    }

    return $sql;
}


function escape($str) {
	global $link;
	$string = mysqli_real_escape_string($link, $str);
	return $string;
}


function generateCode($table, $str) {
	$sql_kode = select("MAX(kode) as kode", $table);
	$kode_db  = result($sql_kode);
	$kode_db  = str_replace($str . "-", "", $kode_db->kode);
	$kode_db  = (int) $kode_db + 1;

	if (strlen($kode_db) == 1) {
	    $addZero = "000";
	} elseif (strlen($kode_db) == 2) {
	    $addZero = "00";
	} elseif (strlen($kode_db) == 3) {
	    $addZero = "0";
	} else {
	    $addZero = "";
	}

	$new_kode = $str . "-" . $addZero . $kode_db;
	return $new_kode;
}

function insertArray($table = '', $data = []){
    $fields  = implode(", ", array_keys($data));
    $values  = implode("', '", array_values($data));
    $insertQuery = "INSERT INTO $table ($fields) VALUES ('$values')";
    //return $insertQuery;
    return execute($insertQuery);
}

function updateArray($table = '', $data = [], $where = []){

	array_walk($data, function(&$val, $key) {
		$val = "{$key} = '{$val}'";
	});

	$key_value = implode(", ", $data);

    $updateQuery = "UPDATE $table SET $key_value WHERE ".array_keys($where)[0]." = '".array_values($where)[0]."'";
    // return $updateQuery;
    return execute($updateQuery);
}

?>