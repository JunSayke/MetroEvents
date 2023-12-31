<?php

/**
 * Define constants for file paths, user types, and JSON files.
 */
define("USERS_JSON_FOLDER", "./jsons/users/");
define("REQUESTS_JSON_FOLDER", "./jsons/requests/");
define("EVENTS_JSON_FOLDER", "./jsons/events/");
define("ADMIN", "admin");
define("ORGANIZER", "organizer");
define("NORMAL", "user");
define("PENDING_ORGANIZER_REQ_JSON", "./jsons/requests/pending_organizer.json");
define("PENDING_EVENT_PARTICIPATION_JSON", "./jsons/requests/pending_event_participation.json");
define("NOTIFICATIONS_JSON", "./jsons/notifications.json");
define("REVIEWS_JSON", "./jsons/reviews.json");

// Set session configuration.
ini_set('session.gc_maxlifetime', 3600);
session_start();

$userData = null;

// Check if user data is stored in the session.
if (isset($_SESSION["user"])) {
    $userData = json_decode($_SESSION["user"], true);
}

// ------------------------------------------------------------------------------------------------ AUXILIARY

/**
 * Get JSON data from a file.
 *
 * @param string $filepath Path to the JSON file.
 *
 * @return array|null Parsed JSON data if successful; otherwise, null.
 */
function get_json_data($filepath)
{
    if (!file_exists($filepath)) {
        echo "JSON cannot be found. <br/>";
        return null;
    }

    $data = file_get_contents($filepath);
    return json_decode($data, true);
}

/**
 * Generate a unique identifier with a prefix.
 *
 * @param string $prefix Prefix for the unique identifier.
 *
 * @return string Generated unique identifier.
 */
function generate_uniqueid($prefix)
{
    return uniqid($prefix);
}

/**
 * Generate a unique identifier for a user by hashing the username and prefix its user type.
 *
 * @param string $username Username of a user.
 * @param string $type     Type of a user.
 *
 * @return string Generated unique identifier.
 */
function generate_userid($username, $type)
{
    return $type . hash_username($username);
}

/**
 * Remove the type identifier from the specified user id.
 *
 * @param string $userId Unique id of a user.
 *
 * @return string User id without the type identifier.
 */
function extract_userid($userId)
{
    return str_replace([ADMIN, ORGANIZER, NORMAL], "", $userId);
}

/**
 * Hash the specified username using crc32 algorithm.
 *
 * @param string $username Username of a user.
 *
 * @return int Integer representation of the specified username.
 */
function hash_username($username)
{
    return crc32($username);
}

/**
 * Hash the specified password using md5 algorithm.
 *
 * @param string $password Password of a user.
 *
 * @return string Hashed password.
 */
function hash_password($password)
{
    return md5($password);
}

/**
 * Check if the username specified already exists.
 *
 * @param string $username Username of a user.
 *
 * @return string|null Pathname of the user JSON file if found; otherwise, null.
 */
function username_exist($username)
{
    return userid_exist(hash_username($username));
}

/**
 * Check if the user id specified already exists.
 *
 * @param string $userId Unique id of a user.
 *
 * @return string|null Pathname of the user JSON file if found; otherwise, null.
 */
function userid_exist($userId)
{
    $match = glob(USERS_JSON_FOLDER . "*" . extract_userid($userId) . ".json");
    return empty($match) ? null : $match[0];
}

/**
 * Check if a user is of the specified user type.
 *
 * @param string $userId Unique id of a user.
 * @param string $type   Type of a user.
 *
 * @return bool True if true; otherwise, false.
 */
function is_user_type($userId, $type)
{
    return str_contains($userId, $type);
}

/**
 * Get or create the user JSON path of the specified user id.
 *
 * @param string $userId Unique id of a user.
 *
 * @return string Pathname of the user JSON file if successful; otherwise, null.
 */
function get_user_json_path($userId)
{
    $result = userid_exist($userId);
    if ($result === null) {
        echo "User JSON cannot be found. <br/>";
        return USERS_JSON_FOLDER . $userId . ".json";
    }
    return $result;
}

/**
 * Check if an array contains all specified keys.
 *
 * @param array $array Array to check.
 * @param array $keys  Keys to check for.
 *
 * @return bool True if all keys are present; otherwise, false.
 */
function contain_keys($array, $keys)
{
    if (!is_array($array) || !is_array($keys)) {
        return false;
    }
    return empty(array_diff($keys, array_keys($array)));
}

/**
 * Validate a user type. A type is valid if it is one of the following: ADMIN, ORGANIZER, NORMAL.
 *
 * @param string $type Type of a user.
 *
 * @return bool True if valid; otherwise, false.
 */
function validate_user_type($type)
{
    return in_array($type, [ADMIN, ORGANIZER, NORMAL]);
}

/**
 * Validate a user id. A user id must contain one of the following type or role identifiers: ADMIN, ORGANIZER, NORMAL.
 *
 * @param string $userId Unique id of a user.
 *
 * @return bool True if valid; otherwise, false.
 */
function validate_userid($userId)
{
    return is_user_type($userId, ADMIN) || is_user_type($userId, ORGANIZER) || is_user_type($userId, NORMAL);
}

/**
 * Validate user data. User data must be of type array which contains the following keys: name, type, username, password.
 *
 * @param array $userData Data of a user.
 *
 * @return bool True if valid; otherwise, false.
 */
function validate_user_data($userData)
{
    $requiredKeys = ["name", "type", "username", "password"];
    return contain_keys($userData, $requiredKeys);
}

/**
 * Validate event data. Event data must be of type array which contains the following keys: title, description, category, organizer, participants, upvotes.
 *
 * @param array $eventData Data of an event.
 *
 * @return bool True if valid; otherwise, false.
 */
function validate_event_data($eventData)
{
    $requiredKeys = ["title", "description", "category", "organizer", "participants", "upvotes"];
    return contain_keys($eventData, $requiredKeys) && is_array($eventData["participants"]) && is_array($eventData["upvotes"]);
}

/**
 * Validate notification data. Notification data must be of type array which contains the following keys: title, body, subscribers.
 *
 * @param array $notifData Data of notifications.
 *
 * @return bool True if valid; otherwise, false.
 */
function validate_notif_data($notifData)
{
    $requiredKeys = ["title", "body", "subscribers"];
    return contain_keys($notifData, $requiredKeys);
}

/**
 * Validate review data. Review data must be of type array which contains the following keys: review, userId, eventId.
 *
 * @param array $reviewData Data of a review.
 *
 * @return bool True if valid; otherwise, false.
 */
function validate_review_data($reviewData)
{
    $requiredKeys = ["review", "userId", "eventId"];
    return contain_keys($reviewData, $requiredKeys);
}

/**
 * Sanitize user inputs to prevent code injection.
 *
 * @param mixed $input Input data to sanitize.
 *
 * @return mixed Sanitized input.
 */
function sanitize_inputs($input)
{
    return htmlspecialchars($input);
}

// ------------------------------------------------------------------------------------------------ USERS

/**
 * Create or replace a user JSON file for the specified user data.
 *
 * @param array $userData Data of a user.
 */
function create_user_json($userData)
{
    if (!validate_user_data($userData)) {
        echo "Invalid user data. <br/>";
        return;
    }

    $userData["id"] = generate_userid($userData["username"], $userData["type"]);
    $json = json_encode($userData, JSON_PRETTY_PRINT);
    $filepath = get_user_json_path($userData["id"]);
    $filename = pathinfo($filepath, PATHINFO_FILENAME);

    // Rename the file if the user id has changed.
    if ($filename !== $userData["id"]) {
        unlink($filepath);
        $filepath = str_replace($filename, $userData["id"], $filepath);
    }

    file_put_contents($filepath, $json);
}

/**
 * Find, get, and parse the user JSON file for the specified user id.
 *
 * @param string $userId Unique id of a user.
 *
 * @return array|null User data if successful; otherwise, null.
 */
function get_user($userId)
{
    $userPath = userid_exist($userId);
    if ($userPath === null) {
        echo "User not found. <br/>";
        return null;
    }

    $data = get_json_data($userPath);
    if ($data === null) {
        echo "Error decoding JSON file. <br/>";
        return null;
    }
    return $data;
}

/**
 * Get all users of the specified type.
 *
 * @param string $type Type of a user.
 *
 * @return array|null A list of user IDs if successful; otherwise, null.
 */
function get_users_by_type($type)
{
    if (!validate_user_type($type)) {
        echo "Invalid user type. <br/>";
        return null;
    }

    $usersJsons = glob(USERS_JSON_FOLDER . $type . "*.json");
    $getUsersId = fn ($userJson) => pathinfo($userJson, PATHINFO_FILENAME);
    $users = array_map($getUsersId, $usersJsons);
    return $users;
}

/**
 * Get all users.
 *
 * @return array A list of user IDs.
 */
function get_users()
{
    return array_merge(get_users_by_type(ADMIN), get_users_by_type(ORGANIZER), get_users_by_type(NORMAL));
}

/**
 * Change the user type to the specified type.
 *
 * @param string $userId Unique id of a user.
 * @param string $type   Type of a user.
 */
function change_user_type($userId, $type)
{
    if (!validate_user_type($type)) {
        echo "Invalid user type. <br/>";
        return;
    }

    $userData = get_user($userId);

    if ($userData === null) {
        echo "Something went wrong with change_user_type(). <br/>";
    }

    if (is_user_type($userId, $type)) {
        echo "User is already an " . $type . "<br/>";
        return;
    }

    $userData["type"] = $type;
    create_user_json($userData);
}

/**
 * Validate login credentials of the specified user id.
 *
 * @param string $username     Username of a user.
 * @param string $hashPassword Hashed password of a user.
 *
 * @return array|null User data if successful; otherwise, null.
 */
function login($username, $hashPassword)
{
    $user = get_user(hash_username($username));

    if ($user === null) {
        echo "Login Failed! <br/>";
        return null;
    }

    if ($user["password"] !== $hashPassword) {
        echo "Login Failed! Mismatch password! <br/>";
        return null;
    }

    $_SESSION["user"] = json_encode($user, true);
    echo "Login Successfully! <br/>";
    return $user;
}

/**
 * Register a new user based on the specified user data.
 *
 * @param array $userData Data or information of a user.
 *
 * @return bool True if operation is successful; otherwise, false.
 */
function register($userData)
{
    if (!validate_user_data($userData)) {
        echo "Invalid user data. <br/>";
        return false;
    }

    if (username_exist($userData["username"]) !== null) {
        echo "Username is already taken. <br/>";
        return false;
    }

    create_user_json($userData);
    return true;
}

/**
 * Logout the user by destroying the session.
 */
function logout()
{
    unset($_SESSION["user"]);
    session_destroy();
}

// ------------------------------------------------------------------------------------------------ EVENTS

/**
 * Get the file path for an event JSON file.
 *
 * @param string $eventId Unique identifier of an event.
 *
 * @return string File path for the event JSON file.
 */
function get_event_json_path($eventId)
{
    return EVENTS_JSON_FOLDER . $eventId . ".json";
}

/**
 * Create an event JSON file with the specified event data.
 *
 * @param array $eventData Data of an event.
 */
function create_event_json($eventData)
{
    $eventData["id"] = generate_uniqueid("event");
    $json = json_encode($eventData, JSON_PRETTY_PRINT);
    $filepath = get_event_json_path($eventData["id"]);
    file_put_contents($filepath, $json);
}

/**
 * Get and parse the data of an event from its JSON file.
 *
 * @param string $eventId Unique identifier of an event.
 *
 * @return array Data of an event if successful; otherwise, null.
 */
function get_event($eventId)
{
    $eventPath = get_event_json_path($eventId);
    $eventData = get_json_data($eventPath);
    return $eventData;
}

/**
 * Cancel an event and notify participants with a reason.
 *
 * @param string $eventId Unique identifier of an event.
 * @param string $reason  Reason for cancellation.
 */
function cancel_event($eventId, $reason)
{
    $eventData = get_event($eventId);
    $notifId = create_notifications([
        "title" => $eventData["title"] . " event has been cancelled.",
        "body" => $reason,
        "subscribers" => [],
    ]);

    foreach ($eventData["participants"] as $participant) {
        add_user_notification($participant, $notifId);
    }

    unlink(get_event_json_path($eventId));

    $data = get_json_data(PENDING_EVENT_PARTICIPATION_JSON);
    unset($data[$eventId]);
    file_put_contents(PENDING_EVENT_PARTICIPATION_JSON, json_encode($data, JSON_PRETTY_PRINT));

    $reviewData = get_json_data(REVIEWS_JSON);
    unset($reviewData[$eventId]);
    file_put_contents(REVIEWS_JSON, json_encode($reviewData, JSON_PRETTY_PRINT));
}

/**
 * Create a review with the specified review data.
 *
 * @param array $reviewData Data of a review.
 *
 * @return string Unique identifier of the created review.
 */
function create_review($reviewData)
{
    $data = get_json_data(REVIEWS_JSON);
    $reviewData["id"] = generate_uniqueid("review");
    $data[$reviewData["id"]] = $reviewData;
    file_put_contents(REVIEWS_JSON, json_encode($data, JSON_PRETTY_PRINT));
    return $reviewData["id"];
}

/**
 * Delete a review with the specified review ID.
 *
 * @param string $reviewId Unique identifier of a review.
 */
function delete_review($reviewId)
{
    $reviewData = get_json_data(REVIEWS_JSON);

    unset($reviewData[$reviewId]);
    file_put_contents(REVIEWS_JSON, json_encode($reviewData, JSON_PRETTY_PRINT));
}

/**
 * Create notifications with the specified notification data.
 *
 * @param array $notifData Data of notifications.
 *
 * @return string Unique identifier of the created notifications.
 */
function create_notifications($notifData)
{
    $data = get_json_data(NOTIFICATIONS_JSON);
    $notifData["id"] = generate_uniqueid("notif");
    $data[$notifData["id"]] = $notifData;
    file_put_contents(NOTIFICATIONS_JSON, json_encode($data, JSON_PRETTY_PRINT));
    return $notifData["id"];
}

/**
 * Remove notifications with the specified notification ID.
 *
 * @param string $notifId Unique identifier of notifications.
 */
function remove_notifications($notifId)
{
    $data = get_json_data(NOTIFICATIONS_JSON);

    unset($data[$notifId]);
    file_put_contents(NOTIFICATIONS_JSON, json_encode($data, JSON_PRETTY_PRINT));
}

/**
 * Add a user to the subscribers of a notification.
 *
 * @param string $userId  Unique identifier of a user.
 * @param string $notifId Unique identifier of notifications.
 */
function add_user_notification($userId, $notifId)
{
    $data = get_json_data(NOTIFICATIONS_JSON);

    $data[$notifId]["subscribers"][] = $userId;
    $data[$notifId]["subscribers"] = array_unique($data[$notifId]["subscribers"]);
    file_put_contents(NOTIFICATIONS_JSON, json_encode($data, JSON_PRETTY_PRINT));
}

/**
 * Remove a user from the subscribers of a notification.
 *
 * @param string $userId  Unique identifier of a user.
 * @param string $notifId Unique identifier of notifications.
 */
function remove_user_notification($userId, $notifId)
{
    $data = get_json_data(NOTIFICATIONS_JSON);

    $data[$notifId]["subscribers"] = array_diff($data[$notifId]["subscribers"], [$userId]);
    file_put_contents(NOTIFICATIONS_JSON, json_encode($data, JSON_PRETTY_PRINT));
}

/**
 * Request to become an organizer.
 *
 * @param string $userId Unique identifier of a user.
 */
function request_organizer($userId)
{
    $data = get_json_data(PENDING_ORGANIZER_REQ_JSON);

    $data[] = $userId;
    file_put_contents(PENDING_ORGANIZER_REQ_JSON, json_encode(array_unique($data), JSON_PRETTY_PRINT));
}

/**
 * Request user event participation.
 *
 * @param string $userId  Unique identifier of a user.
 * @param string $eventId Unique identifier of an event.
 */
function request_user_event_participation($userId, $eventId)
{
    $data = get_json_data(PENDING_EVENT_PARTICIPATION_JSON);

    $data[$eventId][] = $userId;
    $data[$eventId] = array_unique($data[$eventId]);
    file_put_contents(PENDING_EVENT_PARTICIPATION_JSON, json_encode($data, JSON_PRETTY_PRINT));
}

/**
 * Accept a user as an organizer.
 *
 * @param string $userId Unique identifier of a user.
 */
function accept_organizer($userId)
{
    $data = get_json_data(PENDING_ORGANIZER_REQ_JSON);

    $data = array_diff($data, [$userId]);
    file_put_contents(PENDING_ORGANIZER_REQ_JSON, json_encode($data, JSON_PRETTY_PRINT));
    change_user_type($userId, ORGANIZER);

    $notifId = create_notifications([
        "title" => "Request Approved.",
        "body" => "Your request application to become an organizer has been approved.",
        "subscribers" => [$userId],
    ]);
    add_user_notification($userId, $notifId);
}

/**
 * Accept a user's event participation request.
 *
 * @param string $userId  Unique identifier of a user.
 * @param string $eventId Unique identifier of an event.
 */
function accept_user_event_participation($userId, $eventId)
{
    $data = get_json_data(PENDING_EVENT_PARTICIPATION_JSON);

    $data[$eventId] = array_diff($data[$eventId], [$userId]);
    file_put_contents(PENDING_EVENT_PARTICIPATION_JSON, json_encode($data, JSON_PRETTY_PRINT));
    $data = get_event($eventId);
    $data["participants"][] = $userId;
    file_put_contents(get_event_json_path($eventId), json_encode($data, JSON_PRETTY_PRINT));

    $notifId = create_notifications([
        "title" => "Request Approved.",
        "body" => "Your event participation request for event \"" . $data["title"] . "\" has been approved.",
        "subscribers" => [$userId],
    ]);
    add_user_notification($userId, $notifId);
}

/**
 * Get notifications for a user.
 *
 * @param string $userId Unique identifier of a user.
 *
 * @return array Array of notifications for the user.
 */
function get_user_notification($userId)
{
    $data = get_json_data(NOTIFICATIONS_JSON);
    $userNotifs = [];
    foreach ($data as $notifData) {
        if (in_array($userId, $notifData["subscribers"])) {
            $userNotifs[] = $notifData;
        }
    }
    return $userNotifs;
}

/**
 * Upvote an event by a user.
 *
 * @param string $eventId Unique identifier of an event.
 * @param string $userId  Unique identifier of a user.
 */
function upvote_event($eventId, $userId)
{
    $eventData = get_event($eventId);

    $eventData["upvotes"][] = $userId;
    $eventData["upvotes"] = array_unique($eventData["upvotes"]);
    file_put_contents(get_event_json_path($eventId), json_encode($eventData, JSON_PRETTY_PRINT));
}

/**
 * Remove upvote of a user for an event.
 *
 * @param string $eventId Unique identifier of an event.
 * @param string $userId  Unique identifier of a user.
 */
function remove_upvote($eventId, $userId)
{
    $eventData = get_event($eventId);

    $eventData["upvotes"] = array_diff($eventData["upvotes"], [$userId]);
    file_put_contents(get_event_json_path($eventId), json_encode($eventData, JSON_PRETTY_PRINT));
}

// create_event_json([
//     "title" => "Testing Title",
//     "description" => "Testing Description",
//     "category" => "Testing type",
//     "organizer" => "Testing organizer",
//     "participants" => [],
//     "upvotes" => [],
// ]);

// request_user_event_participation("organizer1379451747", "event6590fd18494af");
// accept_user_event_participation("organizer1379451747", "event6590fd18494af");
// cancel_event("event6590fd18494af", "No reason");

// upvote_event("event6590dc5fe44db", "test3");

// remove_upvote("event6590dc5fe44db", "test3");

// create_review([
//     "review" => "Testing review",
//     "userId" => "Test user id",
//     "eventId" => "Test event id"
// ]);

// delete_review("review6590de723552d");

// register([
//     "name" => "Antonio Ubaldo",
//     "type" => NORMAL,
//     "username" => "hunyo1",
//     "password" => hash_password("ninagawA1!"),
// ]);

// request_organizer("user1463150486");

// accept_organizer("user1463150486");

// remove_user_notification("user1379451747", "notif6590e69af3e70");
