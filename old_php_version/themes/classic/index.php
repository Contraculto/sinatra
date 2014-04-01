<?php
// Sinatra.
// Theme file.
// Included by includes/themes.php

/*	Display header html.	*/
/*	Called by: sinatra_display();	*/
function sinatra_header($seccion) {
	global $user;
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<title>Sinatra</title>
	<link rel="stylesheet" href="themes/classic/sinatra.css" media="all" />
	<link rel="shortcut icon" href="themes/classic/img/favicon.ico">
	<?php
	if ($seccion == 'dashboard'):
		?>
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
		<script type="text/javascript">
			// Consultas de estado.
			var auto_refresh = setInterval(
			function (){
				$.getJSON('includes/info.php', function(data) {
					$('#current_info').html(data.cancion);
					$('#volume_info').html("<strong>"+data.vol_level+"%</strong>. Cambiado por "+data.vol_user+".");
					$('#votes_info').html(data.votos_act+"/"+data.votos_max);
				});
			}, 3000);
			// Indicador de estado de las consultas ajax.
			$(document).ajaxStart(function(){
				$('#status').html(".");
			});
			$(document).ajaxStop(function(){
				$('#status').html("");
			});
			// Controles: Consulta.
			function control ( control, action, value ) {
				$.ajax({
					type: "POST",
					url: "includes/controls.php",
					data: "control=" + control + "&action=" + action + "&value=" + value,
					success: function(msg) {
						// alert( "Data Saved: " + msg );
					}
				});
			}
			// Controles: Botones.
			$(document).ready(function(){
				$('.control').click(function() {
					var action = $(this).attr("rel");
					var value = $(this).attr("name");
					control("control", action, value);
				});
			});
		</script>
		<?php
	endif;
	?>
</head>
<body class="<?php echo $seccion; ?>">
	<div id="wrapper">
		<div id="header">
		<?php if($seccion == 'dashboard'):?>
		<div id="logout">
			<form method="post">
				<input name="valido" type="hidden" value="si" />
				<input name="sinatra_logout" type="hidden" value="logout" />
				<input name="submit" type="submit" id="logout_submit" value="Salir" />
			</form>
		</div>
		<?php endif; ?>
			<img src="themes/classic/img/logo.png" alt="Sinatra" />
			<?php if ($seccion == 'dashboard'): ?>
				<p>Hola, <?php echo $user['firstname']; ?>. 
				<p>
			<?php endif; ?>
		</div>
		<div id="content">
<?php
}

/*	Display footer html.	*/
/*	Called by: sinatra_display();	*/
function sinatra_footer($seccion) {
?>
		</div>
	</div>
<?php
/*
if ($seccion == 'dashboard'):
	?>
	<div id="playertoggler"><button>♫</button></div>
	<?php
endif;
?>
<div id="player" style="display:none"></div>
<script type="text/javascript" src="flash/swfobject.js"></script>
<script type="text/javascript">
var s1 = new SWFObject("flash/player.swf","ply","200","20","9","#FFFFFF");
s1.addParam("allowfullscreen","true");
s1.addParam("allowscriptaccess","always");
s1.addParam("flashvars","file=http://cain.bylcom.net:8000/;stream.nsv&type=mp3&volume=50&autostart=false");
s1.write("player");
</script></div>
<script>
$("button").click(function() {
$("#player").fadeToggle("slow", "linear");
});
</script>
*/
?>
</body>
</html>
<?php
}

/*	Display dashboard html.	*/
/*	Called by: sinatra_display();	*/
function sinatra_dashboard() {
	global $sin_db;
	global $sin_usuario;
	global $user;
	$nombretrack = exec('sudo -u bylcom mpc current');
	$nombretrack = str_replace('\'','',$nombretrack);
	$ahora = date('Y-m-d H:i:s');
	$antes = date("Y-m-d H:i:s", strtotime("-10 minutes"));

	if ($user['type'] == 'admin'):
	?>
		<!--
		<div id="status">
		</div>
		-->
		<div id="admin_controls">
			<p>Buena poh, admin.</p>
			<a class="control" name="pause" rel="admin">pause</a>
			<a class="control" name="play" rel="admin">play</a>
			<a class="control" name="prev" rel="admin">prev</a>
			<a class="control" name="next" rel="admin">next</a>
			<a class="control" name="restart" rel="admin">restart</a>
		</div>
	<?php  
	endif;
?>
			<div id="current">
				<p>Estamos escuchando:</p>
				<div id="current_info"><?php echo $nombretrack ?></div>
				
				<a class="control" name="skip" rel="skip">Skip</a>
				<a class="control" name="like" rel="skip">Like</a>
				
			</div>
			<div id="votes">
				<p>Votos: <span id="votes_info">Cargando...</span></p>
			</div>
			<div id="volume">
				<p>Volumen: <span id="volume_info">Cargando...</span></p>
				<a class="control" name="0" rel="volume">0%</a>
				<a class="control" name="10" rel="volume">10%</a>
				<a class="control" name="20" rel="volume">20%</a>
				<a class="control" name="30" rel="volume">30%</a>
				<a class="control" name="40" rel="volume">40%</a>
				<a class="control" name="50" rel="volume">50%</a>
				<a class="control" name="60" rel="volume">60%</a>
				<a class="control" name="70" rel="volume">70%</a>
				<a class="control" name="80" rel="volume">80%</a>
				<a class="control" name="90" rel="volume">90%</a>
				<a class="control" name="100" rel="volume">100%</a>
			</div>
<?php
}

/*	Display login html.	*/
/*	Called by: sinatra_display();	*/
function sinatra_login($status='') {
	global $user;
	if ($status == 'newpass'):
		?>
		<form id="login" method="post" action="">
			<div class="notice"><p>Hora de elegir una contrase&ntilde;a nueva.</p></div>
			<p>Nombre de usuario:<br>
			<input name="user" type="text" value="" /></p>
			<p>Contrase&ntilde;a:<br>
			<input name="passwd1" type="password" value="" /></p>
			<p>Repetir contrase&ntilde;a:<br>
			<input name="passwd2" type="password" value="" /></p>
			<input type="hidden" name="sinatra_create" value="nancy" />
			<input name="submit" type="submit" id="login_entrar" value="Cambiar" />
		</form>
		<?php
		elseif ($status == 'logout'):
		?>
		<form id="login" method="post" action="">
			<div class="notice"><p>Hasta pronto.</p></div>
			<p>Nombre de usuario:<br>
			<input name="user" type="text" value="" /></p>
			<p>Contrase&ntilde;a:<br>
			<input name="passwd" type="password" value="" /></p>
			<input type="hidden" name="sinatra_login" value="frank" />
			<input name="submit" type="submit" id="login_entrar" value="Entrar" />
		</form>
		<?php
	elseif ($status == 'error'):
		?>
		<form id="login" method="post" action="">
			<div class="error"><p>Nombre de usuario o contrase&ntilde;a incorrectos.</p></div>
			<p>Nombre de usuario:<br>
			<input name="user" type="text" value="" /></p>
			<p>Contrase&ntilde;a:<br>
			<input name="passwd" type="password" value="" /></p>
			<input type="hidden" name="sinatra_login" value="frank" />
			<input name="submit" type="submit" id="login_entrar" value="Entrar" />
		</form>
		<?php
	elseif ($status == 'passchange'):
		?>
		<form id="login" method="post" action="">
			<div class="notice"><p>Contrase&ntilde;a cambiada correctamente.</p></div>
			<p>Nombre de usuario:<br>
			<input name="user" type="text" value="" /></p>
			<p>Contrase&ntilde;a:<br>
			<input name="passwd" type="password" value="" /></p>
			<input type="hidden" name="sinatra_login" value="frank" />
			<input name="submit" type="submit" id="login_entrar" value="Entrar" />
		</form>
		<?php
	else:
		?>
		<form id="login" method="post" action="">
			<p>Nombre de usuario:<br>
			<input name="user" type="text" value="" /></p>
			<p>Contrase&ntilde;a:<br>
			<input name="passwd" type="password" value="" /></p>
			<input type="hidden" name="sinatra_login" value="frank" />
			<input name="submit" type="submit" id="login_entrar" value="Entrar" />
		</form>
		<?php
	endif;
}
