<?php
session_register_shutdown();
$_SESSION=array();
echo'<meta http-equiv="Refresh" content="1; URL=/"/>';
?>