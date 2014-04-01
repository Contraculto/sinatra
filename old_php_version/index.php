<?php
// Sinatra.
// Version 0.3
// Main File.

/*
	TO DO:
	* Core.
		* Tags para canciones (en db).
		* Sanitizar datos de posteos y consultas a db.
		* Sistema de votos de verdad, según cantidad de usuarios conectados.
		* Mejor validación de usuario. Sessions?
	* Admin.
		* Página de creación de usuarios/contraseñas nuevas.
		* Página de administración. Usuarios (ver modificar, agregar, eliminar), listas, etc.
	* Theme.
		* Botónes de like/skip dinámicos, que sólo permitan votar una vez y cambien de estado. // Ajax Rod.
		* Unficar interfaz de likes y skips. // Ajax Rod.
		* Detector de tags que cambia fondo.
*/

//	Maintenance.
//	Show PHP errors.
//	error_reporting(E_ALL);
//	ini_set('display_errors', '1');
//	Maintenance.

//	Includes.
include('includes/config.php');
include('includes/themes.php');
//	Includes.

if (!empty($_POST['sinatra_logout'])):
	setcookie ('Sinatra', '', time() - 3600);
	sinatra_display('login','logout');

elseif (isset($_COOKIE['Sinatra'])): // Usuario conectado.
	$cookie_user = $_COOKIE['Sinatra'];
	$user_query = mysql_query("SELECT username, firstname, lastname, type FROM users WHERE username='$cookie_user'", $sin_db) or die(mysql_error());
	$user = mysql_fetch_array($user_query, MYSQL_ASSOC);
	sinatra_display('dashboard');

elseif (!empty($_POST['sinatra_login'])): // Tratando de entrar.
	// Datos del formulario.
	// TODO: Limpiar, validar, asegurar.
	$login_user = $_POST['user'];
	$login_pass = $_POST['passwd'];

	if (empty($login_user)): // Usuario vacío.
		sinatra_display('login');
	elseif (empty($login_pass)): // Contraseña vacia.
		$validacion = mysql_query("SELECT username, hash FROM users WHERE username='$login_user'", $sin_db) or die(mysql_error());
		$userdata = mysql_fetch_object($validacion);
		if (empty($userdata->hash)): // No tiene contraseña, debe crearla.
			sinatra_display('login', 'newpass');
		else: // Tiene contraseña pero no la puso. Fail.
			sinatra_display('login', 'error');
		endif;
	else:
		$validacion = mysql_query("SELECT username, type, hash, salt FROM users WHERE username='$login_user'", $sin_db) or die(mysql_error());
		$userdata = mysql_fetch_object($validacion);
		$pass_temp = sha1($login_pass).$userdata->salt;
		$pass_final = sha1($pass_temp);
		if ($pass_final == $userdata->hash):
			setcookie('Sinatra', $login_user, time()+60*60*24*30);
			$user_query = mysql_query("SELECT username, firstname, lastname, type FROM users WHERE username='$login_user'", $sin_db) or die(mysql_error());
			$user = mysql_fetch_array($user_query, MYSQL_ASSOC);
			sinatra_display('dashboard');
		else:
			sinatra_display('login', 'error');
		endif;
	endif;

elseif (!empty($_POST['sinatra_create'])): // Creando password.
	// Datos del formulario.
	// TODO: Limpiar, validar, asegurar.
	$username = $_POST['user'];
	$password1 = $_POST['passwd1'];
	$password2 = $_POST['passwd2'];
	if ($password1 == $password2):
		$pass = sha1($password1);
		$salt = substr( str_pad( dechex( mt_rand() ), 8, '0', STR_PAD_LEFT ), -8 );
		$mix = $pass.$salt;
		$hash = sha1($mix);
		if (mysql_query("Update users SET hash='$hash', salt='$salt' WHERE username='$username'", $sin_db)):
			sinatra_display('login', 'passchanged');
		else:
			sinatra_display('login', 'error');
		endif;
	else:
		sinatra_display('login', 'error');
	endif;

else: // Login
	sinatra_display('login');

endif;
