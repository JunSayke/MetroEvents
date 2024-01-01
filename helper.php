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

function get_events_html()
{
    global $eventManager, $userManager, $userData;
    $eventsId = $eventManager->get_all_events();
    $html = "";
    if (empty($eventsId)) {
        echo '<p class="lead">No events yet.</p>';
    }

    foreach ($eventsId as $eventId) {
        $event = $eventManager->get_event($eventId);
        $formattedDate = (new DateTime($event['date']))->format('m/d/Y h:i a');
        $participantCount = count($event['participants']);
        $upvoteCount = count($event['upvotes']);

        $actionButton = "";
        $upvoteButton = '<span class="badge bg-primary rounded-pill">Upvote: ' . $upvoteCount . '</span>';
        if ($userData) {
            if (($userData['id'] == $event['organizer'] && $userData["type"] === "organizer") || $userData["type"] === "admin") {
                $actionButton = '<button class="action-btn btn btn-outline-danger" id="cancel-event-btn" data-event-id="' . $event['id'] . '">Cancel Event</button>';
            } else if (in_array($userData['id'], $event['participants'])) {
                $actionButton = '<button class="action-btn btn btn-outline-danger" id="leave-event-btn" data-event-id="' . $event['id'] . '">Leave</button>';
            } else if (in_array($userData['id'], $eventManager->get_event_participation_request($event["id"]))) {
                $actionButton = '<button class="action-btn btn btn-outline-danger" id="cancel-join-event-btn" data-event-id="' . $event['id'] . '">Cancel Request</button>';
            } else {
                $actionButton = '<button class="action-btn btn btn-outline-success" id="join-event-btn" data-event-id="' . $event['id'] . '">Join</button>';
            }

            $upvoteButton = in_array($userData["id"], $event["upvotes"])
                ? '<button class="action-btn btn btn-primary" id="remove-upvote-btn" data-event-id="' . $event['id'] . '">Upvote <i class="fas fa-thumbs-up"></i> <span class="badge bg-primary rounded-pill">' . $upvoteCount . '</span></button>'
                : '<button class="action-btn btn btn-outline-primary" id="upvote-event-btn" data-event-id="' . $event['id'] . '">Upvote <i class="fas fa-thumbs-up"></i> <span class="badge bg-primary rounded-pill">' . $upvoteCount . '</span></button>';
        }

        $organizer = $userManager->get_user($event["organizer"]);
        $html .= <<<EVENTHTML
        <div class="row">
            <div class="col-12 py-3 bg-light shadow">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>{$event['title']}</h4>
                    <span class="badge bg-secondary rounded-pill">Participants: {$participantCount}</span>
                </div>
                <p class="text-muted text-truncate">{$event['description']}</p>
                <small class="text-muted">Organizer: {$organizer['name']} - Event Date: <span class="event-date">{$formattedDate}</span></small>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="btn-group">
                        {$upvoteButton}
                        {$actionButton}
                    </div>
                    <a href="events.php?id={$event['id']}" class="btn btn-primary">View</a>
                </div>
            </div>
        </div>
        EVENTHTML;
    }
    return $html;
}

function get_event_html($eventId)
{
    global $eventManager, $userManager, $userData;
    $event = $eventManager->get_event($eventId);
    $formattedDate = (new DateTime($event['date']))->format('m/d/Y h:i a');
    $organizer = $userManager->get_user($event["organizer"]);
    $reviews = $eventManager->get_reviews($eventId);
    $reviewForm = "";
    $reviewsHtml = "";

    if ($userData) {
        $reviewForm =
            '<h2>Leave a Review</h2>
            <form action="create_review.php" method="POST">
                <div class="mb-3">
                    <input type="hidden" name="eventId" value="' . $eventId . '">
                    <textarea class="form-control" rows="3" placeholder="Write your review here" name="review"></textarea>
                </div>
                <button type="submit" class="action-btn btn btn-primary">Submit Review</button>
            </form>';
    }

    if (empty($reviews)) {
        $reviewsHtml = '<p class="lead">No reviews yet.</p>';
    }

    foreach ($reviews as $review) {
        $user = $userManager->get_user($review["userId"]);

        $reviewsHtml .=
            '<div class="list-group-item d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="mb-1">' . $user["name"] . ' <small class="text-muted">@' . $user["username"] . '</small></h6>
                    <p class="mb-1">' . $review["review"] . '</p>
                </div>
                ' . ($review["userId"] === $userData["id"] ? '<button class="action-btn btn btn-danger btn-sm" id="delete-review-btn" data-review-id="' . $review["id"] . '">X</button>' : '') . '
            </div>';
    }

    $participants = $event["participants"];
    $participantsHtml = "";
    if (empty($participants)) {
        $participantsHtml = '<p class="lead">No participants yet.</p>';
    }

    foreach ($participants as $participant) {
        $user = $userManager->get_user($participant);
        $participantsHtml .= '<li class="list-group-item">' . $user["name"] . ' <small class="text-muted">@' . $user["username"] . '</small></li>';
    }

    $html = <<<EVENTHTML
    <div class="row">
        <div class="col-md-8">
            <h1 class="display-4">{$event['title']}</h1>
            <p class="lead">{$event['description']}</p>

            <hr class="my-4">

            <h2>Reviews</h2>
            <div class="list-group">
                {$reviewsHtml}
            </div>

            <hr class="my-4">

            {$reviewForm}
        </div>
        <div class="col-md-4 text-muted">
            <h4>Event Details</h4>
            <p><strong>Category:</strong> {$event['category']}</p>
            <p><strong>Date:</strong> {$formattedDate}</p>
            <p><strong>Organizer:</strong> {$organizer['name']} <small class="text-muted">@{$organizer['username']}</small></p>
            <hr class="my-4">

            <h4>Participants</h4>
            <ul class="list-group">
                {$participantsHtml}
            </ul>
        </div>
    </div>
    EVENTHTML;

    return $html;
}

function get_organizer_settings_html()
{
    global $eventManager, $userManager;
    $participationRequests = $eventManager->get_all_participation_request();
    $participationRequestsHtml = "";

    foreach ($participationRequests as $eventId => $participationRequest) {
        $event = $eventManager->get_event($eventId);
        foreach ($participationRequest as $participant) {
            $user = $userManager->get_user($participant);
            $participationRequestsHtml .=
                '<tr>
                    <td>' . $event["title"] . '</td>
                    <td>' . $user["username"] . '</td>
                    <td>
                        <button type="button" class="action-btn btn btn-success" id="accept-organizer-request-btn" data-userid="' . $user["id"] . '">Accept</button>
                        <button type="button" class="action-btn btn btn-danger" id="reject-organizer-request-btn" data-userid="' . $user["id"] . '">Reject</button>
                    </td>
                </tr>';
        }
    }

    $html = <<<HTMLBODY
    <div class="container py-5">
        <h2 class="mb-4">Organizer Settings</h2>

        <div class="card">
            <div class="card-header">
                <h4>Join Requests</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Event</th>
                            <th scope="col">Username</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {$participationRequestsHtml}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    HTMLBODY;

    return $html;
}

function get_admin_settings_html()
{
    global $userManager;
    $organizerRequests = $userManager->get_request_organizer_list();
    $organizerRequestsHtml = "";

    foreach ($organizerRequests as $organizer) {
        $user = $userManager->get_user($organizer);
        $organizerRequestsHtml .=
            '<tr>
                <td>' . $user["name"] . '</td>
                <td>' . $user["username"] . '</td>
                <td>
                    <button type="button" class="action-btn btn btn-success" id="accept-organizer-request-btn" data-userid="' . $user["id"] . '">Approve</button>
                    <button type="button" class="action-btn btn btn-danger" id="reject-organizer-request-btn" data-userid="' . $user["id"] . '">Reject</button>
                </td>
            </tr>';
    }

    $users = $userManager->get_users();
    $userListHtml = "";

    foreach ($users as $userId) {
        $user = $userManager->get_user($userId);
        $buttons = "";
        if ($user["type"] !== "user") {
            $buttons .= '<button class="action-btn btn btn-outline-warning" id="demote-user-btn" data-userid="' . $user["id"] . '">Demote</button>';
        }
        if ($user["type"] !== "admin") {
            $buttons .= '<button class="action-btn btn btn-outline-success" id="promote-user-btn" data-userid="' . $user["id"] . '">Promote</button>';
        }
        $userListHtml .=
            '<tr>
                <td>' . $user["username"] . '</td>
                <td>' . $user["type"] . '</td>
                <td class="btn-group">
                    ' . $buttons . '
                    <button class="action-btn btn btn-outline-danger" id="delete-user-btn" data-userid="' . $user["id"] . '">Delete</button>
                </td>
            </tr>';
    }

    $html = <<<HTMLBODY
        <div class="container py-5">
            <h2 class="mb-4">Admin Settings</h2>
            <div class="card">
                <div class="card-header">
                    <h4>Approve Organizer Requests</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Full Name</th>
                                <th scope="col">Username</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {$organizerRequestsHtml}
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="users-container" class="my-5">
                <h2>Users</h2>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        {$userListHtml}
                    </tbody>
                </table>
            </div>
        </div>
    HTMLBODY;

    return $html;
}
