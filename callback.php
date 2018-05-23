<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'libs/GoogleService.php';

if (isset($_GET['code'])) {
    $google = new GoogleService();
    $authToken = $google->getTokenFromAuthorizationCode($_GET['code']);
    $userInfo = $google->getLoggedInUserInfo($authToken['access_token']);

    setcookie('token', json_encode($authToken));
    setcookie('user', json_encode($userInfo));

    header('location: welcome.php');
}