<?php 

error_reporting(E_ALL);
ini_set('display_errors', '1');

// Creacion de usuarios

if (!empty($_POST)):

	$pass = $_POST['pass'];
	$pass1 = sha1($pass);
	$salt = substr( str_pad( dechex( mt_rand() ), 8, '0', STR_PAD_LEFT ), -8 );
	$mix = $pass1.$salt;
	$final = sha1($mix);
	?>
	<pre>
	<?php
		echo '<p>hash: '.$final.'</p>';
		echo '<p>salt: '.$salt.'</p>';
	?>
	</pre>
<?php
else:

?>

<form method="post" action="">
	<input type="password" name="pass" />
</form>

<?php

endif;
