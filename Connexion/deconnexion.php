<?php
session_register_shutdown();
session_start();
$_SESSION=array();
echo "Vous avez été déconnecté(e).";
session_register_shutdown();
echo'<meta http-equiv="Refresh" content="1.5; URL=/"/>';
?>