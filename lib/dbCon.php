<?php
include('../config.inc.php');
require_once('idiorm.php');
ORM::configure('mysql:host=dbhost.cs.man.ac.uk;dbname=2014_comp10120_x4');
ORM::configure('username', $database_user);
ORM::configure('password', $database_pass);
ORM::configure('return_result_sets', true);
ORM::configure('id_column_overrides', array(
    'user' => 'username'
));
$db = ORM::get_db();
?>