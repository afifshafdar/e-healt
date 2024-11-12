<?php
$action = getFrom('action');
$id_bengkel = getFrom('id');

if (isset($_POST['delete'], $_POST['id_bengkel']) && $id_bengkel == $_POST['id_bengkel']) {
	$bengkel = select("*", "bengkel", "idbengkel = '$id_bengkel'");
	
	if(cekRow($bengkel) > 0) {
		$bengkel = result($bengkel);
		$path_foto = "../uploads/bengkel/".$bengkel->foto;
		if(file_exists($path_foto)) {
			unlink($path_foto);
		}
		
		$delete = delete('bengkel', ['idbengkel' => $bengkel->idbengkel]);

		if($delete) {
			setMessage('success', "Data bengkel berhasil dihapus!", linkTo("bengkel", [], false));
		} else {
			setMessage('error', "Gagal menghapus data bengkel!", linkTo("bengkel", [], false));
		}
	} else {
		setMessage('error', "Bengkel tidak ditemukan!", linkTo("bengkel", [], false));
	}
} else {
	redirect(404);
}
?>