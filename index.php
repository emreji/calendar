<?php

require_once 'libs/GoogleService.php';

if (isset($_GET['action'])) {
    $google = new GoogleService();
    header('location:' . $google->getRedirectURL());
}

?>

<html>
    <head>
        <title>Calendar</title>
        <link rel="stylesheet" type="text/css" href="styles/style.css">
    </head>
    <body>

        <div class="navbar">
            <div class="name">Calendar</div>
        </div>
        <h1 class="welcome-header">Welcome to Calendar</h1>
        <div class="container centerpage">
            <a href="index.php?action=login">
                <img src="images/googleLogin.png" width="250" height="60">
            </a>
        </div>
    </body>
</html>