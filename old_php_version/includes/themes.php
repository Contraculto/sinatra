<?php
// Sinatra.
// Themes.
// Included by includes/themes.php

// Set theme to use.
/*
if (isset($_COOKIE['Sinatra_theme'])):
	$sin_theme = $_COOKIE['Sinatra_theme'];
	if (file_exists("themes/$sin_theme/index.php")):
		include ("themes/$sin_theme/index.php");
	else:
		include ("themes/classic/index.php");
	endif;
else:
	include ("themes/classic/index.php");
endif;
*/
include ('themes/enamorados/index.php');
function sinatra_display($seccion,$status='') {
	// Show header.
	sinatra_header($seccion);
	// Show corresponding section.
	switch ($seccion):
		case 'dashboard':
			sinatra_dashboard();
			break;
		case 'login':
			sinatra_login($status);
			break;
	endswitch;
	// Show footer.
	sinatra_footer($seccion);
}
