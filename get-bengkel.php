<?php
if(file_exists("core/init.php")) {
    require_once("core/init.php");
} else {
    die("Main configuration file is empty!");
}

if(isAjaxRequest()){
	$id_bengkel = getFrom('id');

	if(empty(trim($id_bengkel))) {
		$response = [
			'status' => 'error',
			'message' => 'Silahkan pilih bengkel terlebih dahulu!'
		];
	} else {
		$sql_bengkel = select("*", "bengkel");
		
		if(!empty(trim($id_bengkel))){
			$sql_bengkel = select("*", "bengkel", "idbengkel = '$id_bengkel'");
		}

		if(cekRow($sql_bengkel) > 0) {
			if(!empty(trim($id_bengkel))){
				$bengkel = result($sql_bengkel);
			} else {
				$bengkel = [];
				while($bkl = result($sql_bengkel)){
					$bengkel[] = [
						'id_bengkel' => $bkl->idbengkel,
						'nama_bengkel' => $bkl->nama,
						'latitude' => $bkl->lat,
						'longitude' => $bkl->lng,
						'foto' => $bkl->foto,
						'alamat' => $bkl->alamat
					];
				}
			}

			$response = [
				'status' => 'success',
				'data' => $bengkel
			];
		} else {
			$response = [
				'status' => 'error',
				'message' => 'Silahkan pilih bengkel terlebih dahulu!'
			];
		}

	}

	echo json_encode($response);
}