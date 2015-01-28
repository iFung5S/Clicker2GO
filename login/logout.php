<?php

// Inialize session
session_start();

// Include database connection settings
include('sql_connect.php');

// Delete session
session_unset();

// Jump to login page
header('Location: login.php?logout=1');

?>
