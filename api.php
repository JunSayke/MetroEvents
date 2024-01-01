<?php
header('Content-Type: application/json');
include("helper.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"])) {
        switch ($_POST["action"]) {
            case "join_event_request":
                if ($userData !== null && isset($_POST["eventId"])) {
                    $data = array("success" => 1, "userId" => $userData["id"], "eventId" => $_POST["eventId"]);
                    $eventManager->request_user_event_participation($userData["id"], $_POST["eventId"]);
                    echo json_encode($data);
                    exit();
                }
                break;
            case "cancel_join_event_request":
                if ($userData !== null && isset($_POST["eventId"])) {
                    $data = array("success" => 1, "userId" => $userData["id"], "eventId" => $_POST["eventId"]);
                    $eventManager->cancel_user_event_participation($userData["id"], $_POST["eventId"]);
                    echo json_encode($data);
                    exit();
                }
                break;
            case "cancel_event":
                if ($userData !== null && isset($_POST["eventId"]) && isset($_POST["reason"])) {
                    $data = array("success" => 1, "userId" => $userData["id"], "eventId" => $_POST["eventId"], "reason" => $_POST["reason"]);
                    $eventManager->cancel_event($_POST["eventId"], $_POST["reason"]);
                    echo json_encode($data);
                    exit();
                }
                break;
            case "upvote_event":
                if ($userData !== null && isset($_POST["eventId"])) {
                    $data = array("success" => 1, "userId" => $userData["id"], "eventId" => $_POST["eventId"]);
                    $eventManager->upvote_event($_POST["eventId"], $userData["id"]);
                    echo json_encode($data);
                    exit();
                }
                break;
            case "remove_upvote":
                if ($userData !== null && isset($_POST["eventId"])) {
                    $data = array("success" => 1, "userId" => $userData["id"], "eventId" => $_POST["eventId"]);
                    $eventManager->remove_upvote($_POST["eventId"], $userData["id"]);
                    echo json_encode($data);
                    exit();
                }
                break;
            case "organizer_request":
                if ($userData !== null) {
                    $data = array("success" => 1, "userId" => $userData["id"]);
                    $userManager->request_organizer($userData["id"]);
                    echo json_encode($data);
                    exit();
                }
                break;
            case "cancel_organizer_request":
                if ($userData !== null) {
                    $data = array("success" => 1, "userId" => $userData["id"]);
                    $userManager->cancel_organizer_request($userData["id"]);
                    echo json_encode($data);
                    exit();
                }
                break;
            case "accept_organizer_request":
                if ($userData !== null && isset($_POST["userId"])) {
                    $data = array("success" => 1, "userId" => $_POST["userId"]);
                    $userManager->accept_organizer($_POST["userId"]);
                    echo json_encode($data);
                    exit();
                }
                break;
            case "reject_organizer_request":
                if ($userData !== null && isset($_POST["userId"])) {
                    $data = array("success" => 1, "userId" => $_POST["userId"]);
                    $userManager->cancel_organizer_request($_POST["userId"]);
                    echo json_encode($data);
                    exit();
                }
                break;
            case "delete_review":
                if ($userData !== null && isset($_POST["reviewId"])) {
                    $data = array("success" => 1, "userId" => $userData["id"], "reviewId" => $_POST["reviewId"]);
                    $eventManager->delete_review($_POST["reviewId"]);
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
