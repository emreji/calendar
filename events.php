<?php
require_once 'libs/GoogleService.php';
session_start();
if (!isset($_COOKIE['user']) || !isset($_COOKIE['token'])) {
    echo 'Please login';
    return;
}

$token = json_decode($_COOKIE['token'], true)['access_token'];
$user = json_decode($_COOKIE['user'], true);

$google = new GoogleService();

if(!isset($_SESSION['events'])) {
    $events = $google->getCalendarEvents($token);
    $_SESSION['events'] = json_encode($events);
} else {
    $events = json_decode($_SESSION['events'], true);
}

if($events == null) {

}

if (isset($events['error'])) {
    header('location:' . $google->getRedirectURL());
    return;
}

function findEventById($events, $id) {
    foreach ($events as $event) {
        if ($event['id'] == $id) {
            return $event;
        }
    }
    return null;
}

if(isset($_GET['clicked'])) {
    $selectedEvent = findEventById($events['items'], $_GET['clicked']);

} else {
    $selectedEvent = $events['items'][0];
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="styles/style.css">
    <title>Calendar</title>
</head>
<body>
    <div class="navbar">
        <div class="name"><?=$user['name'] ?> | Calendar Events</div>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <?php if(!empty($events['items'])) { ?>
        <ul class="left">
            <?php foreach ($events['items'] as $event) { ?>
                <li id="<?=$event['id']?>" onclick="window.location.href='events.php?clicked=<?=$event['id']?>'" class="<?php echo ($event['id'] == $selectedEvent[id]) ? 'selected' : '' ?>">
                    <h3><?php echo $event['summary']; ?></h3>
                    <?php if (isset($event['start']['dateTime'])) { ?>
                        <small>
                            <?php echo date_format(date_create($event['start']['dateTime']), 'Y/m/d D h:i A') . ' - ' . date_format(date_create($event['end']['dateTime']), 'h:i A'); ?>
                        </small>
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>
        <ul class="right">
            <h1><?php echo $selectedEvent['summary'] ?></h1>
            <div>
                <?php if (isset($selectedEvent['creator']['displayName'])) { ?>
                    <li>
                        <span class="event-title">Creator : </span>
                        <span class="event"><?php echo $selectedEvent['creator']['displayName']; ?></span>
                    </li>
                <?php } else if (isset($selectedEvent['organizer']['displayName'])) { ?>
                    <li>
                        <span class="event-title">Organiser : </span>
                        <span><?php echo $selectedEvent['organizer']['displayName']; ?></span>
                    </li>
                <?php } ?>
            </div>
            <div>
                <li>
                    <span class="event-title">Status: </span>
                    <span><?php echo $selectedEvent['status']; ?></span>
                </li>
            </div>
            <div>
                <?php if (isset($selectedEvent['description'])) { ?>
                    <li>
                        <span class="event-title">Description : </span>
                        <span><?php echo $selectedEvent['description']; ?></span>
                    </li>
                <?php }?>
            </div>
            <?php if(isset($selectedEvent['location'])) {?>
            <div class="map">
                <iframe
                        width="700"
                        height="300"
                        frameborder="0" style="border:0"
                        src="<?php echo $google->getMapURL($selectedEvent['location'])?>" allowfullscreen>
                </iframe>
            </div>
            <?php }?>
        </ul>
        <?php } else {?>
        <h3 class="no-events">You have no events set up!</h3>
        <?php }?>
    </div>



</body>
</html>