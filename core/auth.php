<?php

function login($user, $pass, $bagian) {
	$login = select('*', "users", "username = '$user' AND level = '$bagian'");
	if (cekRow($login) > 0) {
		$user = mysqli_fetch_object($login);

		if (password_verify($pass, $user->password)) {
			setSessionUser($user);
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function logout() {
	resetSessionUser();
	redirect(base_url("login"));
}