<?php
if(file_exists("core/init.php")) {
    require_once("core/init.php");
} else {
    die("Main configuration file is empty!");
}

if(isAjaxRequest()){
	$id_data = getFrom('id');
	$is_single = getFrom('single');

	if(empty(trim($id_data))) {
		$response = [
			'status' => 'error',
			'message' => 'Silahkan pilih provinsi/kabupaten terlebih dahulu!'
		];
	} else {

		//Hanya untuk menampilkan detail data kabupaten saja
		if(!empty(trim($is_single)) && $is_single == true) {
			$id_kabupaten = $id_data;
			$sql_kabupaten = select("*", "kabupaten", "idkabupaten = '$id_kabupaten'");
			if(cekRow($sql_kabupaten) > 0) {
				$kabupaten = result($sql_kabupaten);
				$response = [
					'status' => 'success',
					'data' => $kabupaten
				];
			} else {
				$response = [
					'status' => 'error',
					'message' => 'Silahkan pilih kabupaten terlebih dahulu!'
				];
			}

		//Menampilan semua data kabupaten berdasarkan provinsi yang dipilih
		} else {
			$id_provinsi = $id_data;
			$sql_kabupaten = select("*", "kabupaten", "idprovinsi = '$id_provinsi'");
			if(cekRow($sql_kabupaten) > 0) {
				$kabupaten_data = [];
				while($data = result($sql_kabupaten)){
					$kabupaten_data[] = [
						'id_kabupaten' => $data->idkabupaten,
						'nama_kabupaten' => $data->nama,
						'latitude' => $data->lat,
						'longitude' => $data->lng,
					];
				}

				$response = [
					'status' => 'success',
					'data' => $kabupaten_data
				];
			} else {
				$response = [
					'status' => 'error',
					'message' => 'Silahkan pilih provinsi terlebih dahulu!'
				];
			}
		}
	}

	echo json_encode($response);
}