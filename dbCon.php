<?php
include('config.inc.php');
require_once 'idiorm.php';
ORM::configure('mysql:host=dbhost.cs.man.ac.uk;dbname=2014_comp10120_x4');
ORM::configure('username', $database_user);
ORM::configure('password', $database_pass);
ORM::configure('id_column', 'primary_key');
ORM::configure('return_result_sets', true);
$db = ORM::get_db();
?>
