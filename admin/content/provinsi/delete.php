<?php
$action = getFrom('action');
$id_provinsi = getFrom('id');

if (isset($_POST['delete'], $_POST['id_provinsi']) && $id_provinsi == $_POST['id_provinsi']) {
	$provinsi = select("*", "provinsi", "idprovinsi = '$id_provinsi'");
	
	if(cekRow($provinsi) > 0) {
		$provinsi = result($provinsi);
		$delete = delete('provinsi', ['idprovinsi' => $provinsi->idprovinsi]);

		if($delete) {
			setMessage('success', "Data provinsi berhasil dihapus!", linkTo("provinsi", [], false));
		} else {
			setMessage('error', "Gagal menghapus data provinsi!", linkTo("provinsi", [], false));
		}
	} else {
		setMessage('error', "Provinsi tidak ditemukan!", linkTo("provinsi", [], false));
	}
} else {
	redirect(404);
}
?>