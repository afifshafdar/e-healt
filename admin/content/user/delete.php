<?php
$action = getFrom('action');
$id_user = getFrom('id');

if (isset($_POST['delete'], $_POST['id_user']) && $id_user == $_POST['id_user']) {
	$user = select("*", "users", "iduser = '$id_user'");
	
	if(cekRow($user) > 0) {
		$user = result($user);
		$delete = delete('users', ['iduser' => $user->iduser]);

		if($delete) {
			setMessage('success', "Data user berhasil dihapus!", linkTo("user", [], false));
		} else {
			setMessage('error', "Gagal menghapus data user!", linkTo("user", [], false));
		}
	} else {
		setMessage('error', "User tidak ditemukan!", linkTo("user", [], false));
	}
} else {
	redirect(404);
}
?>