<?php
$action = getFrom('action');
$id_kabupaten = getFrom('id');

if (isset($_POST['delete'], $_POST['id_kabupaten']) && $id_kabupaten == $_POST['id_kabupaten']) {
	$kabupaten = select("*", "kabupaten", "idkabupaten = '$id_kabupaten'");
	
	if(cekRow($kabupaten) > 0) {
		$kabupaten = result($kabupaten);
		$delete = delete('kabupaten', ['idkabupaten' => $kabupaten->idkabupaten]);

		if($delete) {
			setMessage('success', "Data kabupaten berhasil dihapus!", linkTo("kabupaten", [], false));
		} else {
			setMessage('error', "Gagal menghapus data kabupaten!", linkTo("kabupaten", [], false));
		}
	} else {
		setMessage('error', "kabupaten tidak ditemukan!", linkTo("kabupaten", [], false));
	}
} else {
	redirect(404);
}
?>