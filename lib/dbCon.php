<?php

require_once(dirname(__FILE__).'/../config.inc.php');
require_once('idiorm.php');
ORM::configure("mysql:host=$database_host;dbname=2014_comp10120_x4");
ORM::configure('username', $database_user);
ORM::configure('password', $database_pass);
ORM::configure('return_result_sets', true);
ORM::configure('id_column_overrides', array(
    'user' => 'username'
));
$db = ORM::get_db();

?>
