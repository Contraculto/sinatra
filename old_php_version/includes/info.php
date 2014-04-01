<?php
//	Sinatra.
//	Ajax info. Devuelve estados de los controles.

include('config.php');

//	Cancion actual.
$cancion = exec('/usr/bin/mpc current');
$cancion = str_replace('\'','',$cancion);

//	Cantidad de votos.
$actual = exec('/usr/bin/mpc current');
$actual = str_replace('\'','',$actual);
$ahora = date('Y-m-d H:i:s');
$antes = date("Y-m-d H:i:s", strtotime("-10 minutes"));

$query = "SELECT * FROM controls WHERE title='$actual' AND control='skip' AND date BETWEEN '$antes' AND '$ahora'";
$proc = mysql_query($query, $sin_db) or die(mysql_error());
$cantidad = mysql_num_rows($proc);

$query_like = "SELECT * FROM controls WHERE title='$actual' AND control='like' AND date BETWEEN '$antes' AND '$ahora'";
$proc_like = mysql_query($query_like, $sin_db) or die(mysql_error());
$cantidad_like = mysql_num_rows($proc_like);
$cantidad_max = 3 + ($cantidad_like/2);

$hora = date('G');
if ($hora >= 19 || $hora <= 8):
	if ($cantidad >= 1):
		$query_skip = "UPDATE controls SET control='skipped' WHERE title='$actual' AND control='skip' AND date BETWEEN '$antes' AND '$ahora' ";
		exec('/usr/bin/mpc next');
		mysql_query($query_skip, $sin_db) or die(mysql_error());
		$skip = 'si';
	else:
		$skip = 'no';
	endif;
else:
	if ($cantidad >= $cantidad_max):
		$query_skip = "UPDATE controls SET control='skipped' WHERE title='$actual' AND control='skip' AND date BETWEEN '$antes' AND '$ahora' ";
		exec('/usr/bin/mpc next');
		mysql_query($query_skip, $sin_db) or die(mysql_error());
		$skip = 'si';
	else:
		$skip = 'no';
	endif;
endif;

//	Volumen.
$queryvlm = "SELECT * FROM volume ORDER BY id DESC LIMIT 1";
$validacionvlm = mysql_query($queryvlm, $sin_db) or die(mysql_error());
$articleDbavlm = mysql_fetch_object($validacionvlm);
$vlmvlm = $articleDbavlm->volume;
$vlmuser = $articleDbavlm->userid;
$user_query = "SELECT * FROM users WHERE username='$vlmuser'";
$user_db = mysql_query($user_query, $sin_db) or die(mysql_error());
$volume_user = mysql_fetch_array($user_db, MYSQL_ASSOC);
$vol_user = utf8_encode($volume_user['firstname'].' '.$volume_user['lastname']);

//	Resultados en json.
echo '{', "\n";
echo '"cancion" : ', json_encode($cancion), "\n,";
echo '"skip" : ', json_encode($skip), "\n,";
echo '"votos_act" : ', json_encode($cantidad), "\n,";
echo '"votos_max" : ', json_encode($cantidad_max), "\n,";
echo '"vol_level" : ', json_encode($vlmvlm), "\n,";
echo '"vol_user" : ', json_encode($vol_user), "\n";
echo '}';
