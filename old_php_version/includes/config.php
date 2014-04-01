<?php
// Sinatra.
// Configuration.
// Included by index.php

// Entorno.
$sin_usuario = 'cardumen';
$sin_path = '/var/www/sinatra/';

// Base de datos.
$sin_db_hostname = 'localhost';
$sin_db_database = 'sinatra';
$sin_db_username = 'root';
$sin_db_password = 'trinity'; 

// Inicialización de base de datos.
$sin_db = mysql_connect($sin_db_hostname, $sin_db_username, $sin_db_password);
mysql_select_db($sin_db_database ,$sin_db) or die('Error de conexion con la base de datos. Por favor h&iacute;nchele las pelotas a Ignacio.');
