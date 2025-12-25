<?php
require_once 'auth.php';
logoutAdmin();
header('Location: login.php');
exit;
?>
