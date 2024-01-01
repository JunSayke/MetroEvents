<?php
include("helper.php");

if ($userData !== null && isset($_POST["review"]) && isset($_POST["eventId"])) {
    $eventManager->create_review(sanitize_inputs($_POST["review"]), $userData["id"], $_POST["eventId"]);
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

header("Location: unauthorized.php");
exit();
