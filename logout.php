<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'libs/GoogleService.php';

if (!isset($_COOKIE['user']) || !isset($_COOKIE['token'])) {
    echo 'Please login';
    return;
}

$token = json_decode($_COOKIE['token'], true)['access_token'];

$google = new GoogleService();
$google->logout($token);

setcookie("user", "", time()-3600);
setcookie("token", "", time()-3600);
session_start();
session_destroy();
header('location: index.php');