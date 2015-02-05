<?php

// Inialize session
session_start();

// Include database connection settings
include('../lib/sqlconnect.php');

// Delete session
session_unset();

include('logout.html');

// Jump to login page
header('Location: login.php?logout=1');

?>
