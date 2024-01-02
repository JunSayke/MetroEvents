<?php
header('Content-Type: application/json');
include("helper.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["action"])) {
        switch ($_POST["action"]) {
            case "get_upcoming_events":
                if ($userData) {
                    $upcomingEvents = $eventManager->get_upcoming_events();
                    $data = array("success" => 1, "events" => []);
                    foreach ($upcomingEvents as $event) {
                        $data["events"][] = $event;
                    }
                    echo json_encode($data);
                    exit();
                }
                break;
            case "join_event_request":
                if ($userData && isset($_POST["eventId"])) {
                    $data = array("success" => 1, "userId" => $userData["id"], "eventId" => $_POST["eventId"]);
                    $uid = $userManager->extract_userid($userData["id"]);
                    $eventManager->request_user_event_participation($uid, $_POST["eventId"]);
                    echo json_encode($data);
                    exit();
                }
                break;
            case "cancel_join_event_request":
                if ($userData && isset($_POST["eventId"])) {
                    $data = array("success" => 1, "userId" => $userData["id"], "eventId" => $_POST["eventId"]);
                    $uid = $userManager->extract_userid($userData["id"]);
                    $eventManager->cancel_user_event_participation($uid, $_POST["eventId"]);
                    echo json_encode($data);
                    exit();
                }
                break;
            case "cancel_event":
                if ($userData && isset($_POST["eventId"]) && isset($_POST["reason"])) {
                    $data = array("success" => 1, "userId" => $userData["id"], "eventId" => $_POST["eventId"], "reason" => $_POST["reason"]);
                    $event = $eventManager->get_event($_POST["eventId"]);
                    $eventManager->cancel_event($_POST["eventId"]);
                    if (!empty($event["participants"])) {
                        $notifId = $notifManager->create_notifications($event["title"] . " event has been cancelled.", sanitize_inputs($_POST["reason"]));
                        foreach ($event["participants"] as $participant) {
                            $uid = $userManager->extract_userid($participant);
                            $notifManager->add_user_notification($uid, $notifId);
                        }
                    }
                    echo json_encode($data);
                    exit();
                }
                break;
            case "leave_event":
                if ($userData && isset($_POST["eventId"])) {
                    $data = array("success" => 1, "userId" => $userData["id"], "eventId" => $_POST["eventId"]);
                    $uid = $userManager->extract_userid($userData["id"]);
                    $eventManager->remove_user_from_event($uid, $_POST["eventId"]);
                    echo json_encode($data);
                    exit();
                }
                break;
            case "upvote_event":
                if ($userData && isset($_POST["eventId"])) {
                    $data = array("success" => 1, "userId" => $userData["id"], "eventId" => $_POST["eventId"]);
                    $uid = $userManager->extract_userid($userData["id"]);
                    $eventManager->upvote_event($_POST["eventId"], $uid);
                    echo json_encode($data);
                    exit();
                }
                break;
            case "remove_upvote":
                if ($userData && isset($_POST["eventId"])) {
                    $data = array("success" => 1, "userId" => $userData["id"], "eventId" => $_POST["eventId"]);
                    $uid = $userManager->extract_userid($userData["id"]);
                    $eventManager->remove_upvote($_POST["eventId"], $uid);
                    echo json_encode($data);
                    exit();
                }
                break;
            case "organizer_request":
                if ($userData) {
                    $data = array("success" => 1, "userId" => $userData["id"]);
                    $uid = $userManager->extract_userid($userData["id"]);
                    $userManager->request_organizer($uid);
                    echo json_encode($data);
                    exit();
                }
                break;
            case "cancel_organizer_request":
                if ($userData) {
                    $data = array("success" => 1, "userId" => $userData["id"]);
                    $uid = $userManager->extract_userid($userData["id"]);
                    $userManager->cancel_organizer_request($uid);
                    echo json_encode($data);
                    exit();
                }
                break;
            case "accept_organizer_request":
                if ($userData && isset($_POST["userId"])) {
                    $data = array("success" => 1, "userId" => $_POST["userId"]);
                    $uid = $userManager->extract_userid($_POST["userId"]);
                    $userManager->accept_organizer($uid);
                    $notifManager->create_notifications("Request Approved.", "Your application to become an organizer has been accepted.", [$uid]);
                    echo json_encode($data);
                    exit();
                }
                break;
            case "reject_organizer_request":
                if ($userData && isset($_POST["userId"])) {
                    $data = array("success" => 1, "userId" => $_POST["userId"]);
                    $uid = $userManager->extract_userid($_POST["userId"]);
                    $userManager->cancel_organizer_request($uid);
                    $notifManager->create_notifications("Request Rejected.", "Your application to become an organizer has been denied.", [$uid]);
                    echo json_encode($data);
                    exit();
                }
                break;
            case "delete_review":
                if ($userData && isset($_POST["reviewId"])) {
                    $data = array("success" => 1, "userId" => $userData["id"], "reviewId" => $_POST["reviewId"]);
                    $eventManager->delete_review($_POST["reviewId"]);
                    echo json_encode($data);
                    exit();
                }
                break;
            case "accept_join_request":
                if ($userData && isset($_POST["userId"]) && isset($_POST["eventId"])) {
                    $data = array("success" => 1, "userId" => $_POST["userId"], "eventId" => $_POST["eventId"]);
                    $uid = $userManager->extract_userid($_POST["userId"]);
                    $eventManager->accept_user_event_participation($uid, $_POST["eventId"]);
                    $event = $eventManager->get_event($_POST["eventId"]);
                    $notifManager->create_notifications("Request Approved.", "Your application to join the event \"" . $event["title"] . "\" has been accepted.", [$uid]);
                    echo json_encode($data);
                    exit();
                }
                break;
            case "reject_join_request":
                if ($userData && isset($_POST["userId"]) && isset($_POST["eventId"])) {
                    $data = array("success" => 1, "userId" => $_POST["userId"], "eventId" => $_POST["eventId"]);
                    $uid = $userManager->extract_userid($_POST["userId"]);
                    $eventManager->cancel_user_event_participation($uid, $_POST["eventId"]);
                    $event = $eventManager->get_event($_POST["eventId"]);
                    $notifManager->create_notifications("Request Rejected.", "Your application to join the event \"" . $event["title"] . "\" has been denied.", [$uid]);
                    echo json_encode($data);
                    exit();
                }
                break;
            case "demote_user":
                if ($userData && isset($_POST["userId"])) {
                    $user = $userManager->get_user($_POST["userId"]);
                    $data = array("success" => 1, "userId" => $_POST["userId"]);
                    if ($user["type"] === "admin") {
                        $userManager->change_user_type($_POST["userId"], "organizer");
                    } else if ($user["type"] === "organizer") {
                        $userManager->change_user_type($_POST["userId"], "user");
                    }
                    $notifManager->create_notifications("Role Demoted.", "You have been demoted.", [$userManager->extract_userid($_POST["userId"])]);
                    echo json_encode($data);
                    exit();
                }
                break;
            case "promote_user":
                if ($userData && isset($_POST["userId"])) {
                    $user = $userManager->get_user($_POST["userId"]);
                    $data = array("success" => 1, "userId" => $_POST["userId"]);
                    if ($user["type"] === "user") {
                        $userManager->change_user_type($_POST["userId"], "organizer");
                    } else if ($user["type"] === "organizer") {
                        $userManager->change_user_type($_POST["userId"], "admin");
                    }
                    $notifManager->create_notifications("Role Promoted.", "You have been promoted.", [$userManager->extract_userid($_POST["userId"])]);
                    echo json_encode($data);
                    exit();
                }
                break;
            case "delete_user":
                if ($userData && isset($_POST["userId"])) {
                    $data = array("success" => 1, "userId" => $_POST["userId"]);
                    $userManager->delete_user_json($_POST["userId"]);
                    echo json_encode($data);
                    exit();
                }
                break;
            case "delete_account":
                if ($userData) {
                    $data = array("success" => 1, "userId" => $userData["id"]);
                    $userManager->delete_user_json($userData["id"]);
                    logout();
                    echo json_encode($data);
                    exit();
                }
                break;
            case "delete_notif":
                if ($userData && isset($_POST["notifId"])) {
                    $data = array("success" => 1, "notifId" => $_POST["notifId"]);
                    $uid = $userManager->extract_userid($userData["id"]);
                    $notifManager->remove_user_notification($uid, $_POST["notifId"]);
                    $notifManager->clean_notifications();
                    echo json_encode($data);
                    exit();
                }
                break;
            case "remove_participant":
                if ($userData && isset($_POST["userId"]) && isset($_POST["eventId"])) {
                    $data = array("success" => 1, "userId" => $_POST["userId"], "eventId" => $_POST["eventId"]);
                    $uid = $userManager->extract_userid($_POST["userId"]);
                    $eventManager->remove_user_from_event($uid, $_POST["eventId"]);
                    echo json_encode($data);
                    exit();
                }
                break;
        }
        invalid_data_parameters();
    }
    $data = array("success" => 0, "reason" => "Invalid action!");
    echo json_encode($data);
    exit();
}
$data = array("success" => 0, "reason" => "Method not allowed!");
echo json_encode($data);
exit();

function invalid_data_parameters()
{
    $data = array("success" => 0, "reason" => "Invalid data parameters!");
    echo json_encode($data);
    exit();
}
