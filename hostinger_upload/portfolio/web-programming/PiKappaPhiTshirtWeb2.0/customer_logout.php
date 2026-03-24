<?php
require_once 'customer_auth.php';
logoutCustomer();
header('Location: index.php');
exit;
