<?php

/**
 * Define constants for file paths, user types, and JSON files.
 */
define("USERS_JSON_FOLDER", "./jsons/users/");
define("REQUESTS_JSON_FOLDER", "./jsons/requests/");
define("EVENTS_JSON_FOLDER", "./jsons/events/");
define("PENDING_ORGANIZER_REQ_JSON", "./jsons/requests/pending_organizer.json");
define("PENDING_EVENT_PARTICIPATION_JSON", "./jsons/requests/pending_event_participation.json");
define("NOTIFICATIONS_JSON", "./jsons/notifications.json");
define("REVIEWS_JSON", "./jsons/reviews.json");

include("./classes/EventManager.php");
include("./classes/NotifManager.php");
include("./classes/UserManager.php");

// Set session configuration.
ini_set('session.gc_maxlifetime', 3600);
session_start();

$userData = null;

// Check if user data is stored in the session.
if (isset($_SESSION["user"])) {
    $userData = json_decode($_SESSION["user"], true);
}

$eventManager = new EventManager(EVENTS_JSON_FOLDER, REVIEWS_JSON, PENDING_EVENT_PARTICIPATION_JSON);
$notifManager = new NotifManager(NOTIFICATIONS_JSON);
$userManager = new UserManager(USERS_JSON_FOLDER, PENDING_ORGANIZER_REQ_JSON);

function sanitize_inputs($input)
{
    return htmlspecialchars($input);
}

function login($username, $hashPassword)
{
    global $userManager;
    $userJson = $userManager->username_exist($username);

    if ($userJson === null) {
        echo "Login Failed! <br/>";
        return null;
    }

    $user = $userManager->get_json_data($userJson);

    if ($user["password"] !== $hashPassword) {
        echo "Login Failed! Mismatch password! <br/>";
        return null;
    }

    $_SESSION["user"] = json_encode($user, true);
    echo "Login Successfully! <br/>";
    return $user;
}

function register($name, $type, $username, $password)
{
    global $userManager;
    if ($userManager->username_exist($username) !== null) {
        echo "Username is already taken. <br/>";
        return false;
    }

    $userManager->create_user_json($name, $type, $username, $userManager->hash_password($password));
    return true;
}

function logout()
{
    unset($_SESSION["user"]);
    session_destroy();
}

function display_events()
{
}
