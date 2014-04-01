<?php
// Sinatra.
// Ajax controls, Ejecuta comandos via ajax.

include('config.php');
if (!empty($_POST['control'])):

	$cookie_user = $_COOKIE['Sinatra'];
	$user_query = mysql_query("SELECT id, username, firstname, lastname, type FROM users WHERE username='$cookie_user'", $sin_db) or die(mysql_error());
	$user = mysql_fetch_array($user_query, MYSQL_ASSOC);

/*
	Revisar referrers desde sinatra con
	$_SERVER["HTTP_REFERER"]
*/

	// Controles de Admin.
	if ($_POST['action'] === 'admin'):
		if ($user['type'] == 'admin'):
			switch ($_POST['value']):
				case 'pause':
					exec('mpc pause');
					break;
				case 'play':
					exec('mpc play');
					break;
				case 'prev':
					exec('mpc prev');
					break;
				case 'next':
					exec('mpc next');
					break;
				case 'restart':
					exec('service mpd restart');
					break;
			endswitch;
		endif;

	// Control: Volumen
	elseif ($_POST['action'] === 'volume'):

		$control_volumen = $_POST['value'];
		$userid = $user['username'];
		switch ($_POST['value']):
			case '0':
				exec('amixer -c 0 set Master playback 0');
				$query = "INSERT INTO volume (userid, volume) VALUES ('$userid', '$control_volumen')";
				mysql_query($query, $sin_db) or die(mysql_error());
				break;
			case '10':
				exec('amixer -c 0 set Master playback 10%');
				$query = "INSERT INTO volume (userid, volume) VALUES ('$userid', '$control_volumen')";
				mysql_query($query, $sin_db) or die(mysql_error());
				break;
			case '20':
				exec('amixer -c 0 set Master playback 20%');
				$query = "INSERT INTO volume (userid, volume) VALUES ('$userid', '$control_volumen')";
				mysql_query($query, $sin_db) or die(mysql_error());
				break;
			case '30':
				exec('amixer -c 0 set Master playback 30%');
				$query = "INSERT INTO volume (userid, volume) VALUES ('$userid', '$control_volumen')";
				mysql_query($query, $sin_db) or die(mysql_error());
				break;
			case '40':
				exec('amixer -c 0 set Master playback 40%',$arr);
				$query = "INSERT INTO volume (userid, volume) VALUES ('$userid', '$control_volumen')";
				mysql_query($query, $sin_db) or die(mysql_error());
				break;
			case '50':
				exec('amixer -c 0 set Master playback 50%');
				$query = "INSERT INTO volume (userid, volume) VALUES ('$userid', '$control_volumen')";
				mysql_query($query, $sin_db) or die(mysql_error());
				break;
			case '60':
				exec('amixer -c 0 set Master playback 60%');
				$query = "INSERT INTO volume (userid, volume) VALUES ('$userid', '$control_volumen')";
				mysql_query($query, $sin_db) or die(mysql_error());
				break;
			case '70':
				exec('amixer -c 0 set Master playback 70%');
				$query = "INSERT INTO volume (userid, volume) VALUES ('$userid', '$control_volumen')";
				mysql_query($query, $sin_db) or die(mysql_error());
				break; 
			case '80':
				exec('amixer -c 0 set Master playback 80%');
				$query = "INSERT INTO volume (userid, volume) VALUES ('$userid', '$control_volumen')";
				mysql_query($query, $sin_db) or die(mysql_error());
				break; 
			case '90':
				exec('amixer -c 0 set Master playback 90%');
				$query = "INSERT INTO volume (userid, volume) VALUES ('$userid', '$control_volumen')";
				mysql_query($query, $sin_db) or die(mysql_error());
				break; 
			case '100':
				exec('amixer -c 0 set Master playback 100%');
				$query = "INSERT INTO volume (userid, volume) VALUES ('$userid', '$control_volumen')";
				mysql_query($query, $sin_db) or die(mysql_error());
				break;
		endswitch;
	// Controles.
	elseif ($_POST['action'] === 'skip'):
		$nombretrack = exec('/usr/bin/mpc current');
		$nombretrack = str_replace('\'','',$nombretrack);
		$ahora = date('Y-m-d H:i:s');
		$antes = date("Y-m-d H:i:s", strtotime("-20 minutes"));
		switch ($_POST['value']):

			//Control: Skip.
			case 'skip':
				$userid = $user['username'];
				// Valida Cuantos skip tiene la cancion con un rango de 10 minutos hacia atras.
				$skip_votos_db = "SELECT * FROM controls WHERE title='$nombretrack' AND control='skip' AND date BETWEEN '$antes' AND '$ahora'";
				$skip_votos_data = mysql_query($skip_votos_db, $sin_db) or die(mysql_error());
				$skip_votos = mysql_num_rows($skip_votos_data);
				// Valida cantidad de votos del usuario para esta cancion en un rango de 20 minutos.
				if ($skip_votos!=0):
					$skip_votosusuario_db = "SELECT * FROM controls WHERE title='$nombretrack' AND control='skip' AND userid='$userid' AND date BETWEEN '$antes' AND '$ahora' ";
					$skip_votosusuario_data = mysql_query($skip_votosusuario_db, $sin_db) or die(mysql_error());
					$skip_votosusuarios = mysql_num_rows($skip_votosusuario_data);
				endif;
				// Si el usuario no ha votado por esta cancion, permite votar.
				if ($skip_votosusuarios == 0):
					$skip_addvote_db = "INSERT INTO controls (title, date, control, userid) VALUES ('$nombretrack', '$ahora', 'skip', '$userid')";
					mysql_query($skip_addvote_db, $sin_db) or die(mysql_error());
				endif;
				break;

			//Control: Like.
			case 'like':
				$userid = $user['username'];
				// Valida Cuantos like tiene la cancion con un rango de una hora hacia atras.
				$like_votos_db = "SELECT * FROM controls WHERE title='$nombretrack' AND control='like' AND date BETWEEN '$antes' AND '$ahora'";
				$like_votos_data = mysql_query($like_votos_db, $sin_db) or die(mysql_error());
				$like_votos = mysql_num_rows($like_votos_data);
				// Valida cantidad de votos del usuario para esta cancion en un rango de 20 minutos.
				if ($like_votos!=0):
					$like_votosusuario_db = "SELECT * FROM controls WHERE title='$nombretrack' AND control='like' AND userid='$userid' AND date BETWEEN '$antes' AND '$ahora' ";
					$like_votosusuario_data = mysql_query($like_votosusuario_db, $sin_db) or die(mysql_error());
					$like_votosusuarios = mysql_num_rows($like_votosusuario_data);
				endif;
				// Si el usuario no ha votado por esta cancion, permite votar.
				if ($like_votosusuarios == 0):
					$like_addvote_db = "INSERT INTO controls (title, date, control, userid) VALUES ('$nombretrack', '$ahora', 'like', '$userid')";
					mysql_query($like_addvote_db, $sin_db) or die(mysql_error());
				endif;
				break;
		endswitch;
	endif;
endif;
