<?php

if (!isset($_COOKIE['user']) || !isset($_COOKIE['token'])) {
    echo 'Please login';
    return;
}

$user = json_decode($_COOKIE['user'], true);

?>
<html>
<head>
    <title>Calendar</title>
    <link rel="stylesheet" type="text/css" href="styles/style.css">
</head>
<body>

<div class="navbar">
    <div class="name">Calendar</div>
    <a href="logout.php">Logout</a>
</div>

<div class="container welcome-content">
    <img src="<?php echo $user['picture']; ?>" width="200", height="200" />
    <div>Welcome, <?php echo $user['name']; ?></div>
    <div><a href="events.php">See Events</a> </div>
</div>
</body>
</html>