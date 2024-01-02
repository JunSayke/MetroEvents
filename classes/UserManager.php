<?php
class UserManager
{
    private $usersJsonFolder;
    private $pendingOrganizerJsonFile;
    private const ADMIN = "admin";
    private const ORGANIZER = "organizer";
    private const NORMAL = "user";

    public function __construct($usersJsonFolder, $pendingOrganizerJsonFile)
    {
        $this->usersJsonFolder = $usersJsonFolder;
        $this->pendingOrganizerJsonFile = $pendingOrganizerJsonFile;
    }

    function get_user_json_path($userId)
    {
        $result = $this->userid_exist($userId);
        return $result !== null ? $result : $this->usersJsonFolder . $userId . ".json";
    }

    function get_json_data($filepath)
    {
        if (!file_exists($filepath)) {
            throw new Exception("Filepath cannot be found.");
        }

        $data = file_get_contents($filepath);
        return json_decode($data, true);
    }

    function generate_userid($username, $type)
    {
        return $type . $this->hash_username($username);
    }

    function extract_userid($userId)
    {
        return str_replace([self::ADMIN, self::ORGANIZER, self::NORMAL], "", $userId);
    }

    function hash_username($username)
    {
        return crc32($username);
    }

    function hash_password($password)
    {
        return md5($password);
    }

    function username_exist($username)
    {
        return $this->userid_exist($this->hash_username($username));
    }

    function userid_exist($userId)
    {
        $match = glob($this->usersJsonFolder . "*" . $this->extract_userid($userId) . ".json");
        return empty($match) ? null : $match[0];
    }

    function is_user_type($userId, $type)
    {
        return str_contains($userId, $type);
    }

    function create_user_json($name, $type, $username, $password)
    {
        $userData = [
            "id" => $this->generate_userid($username, $type),
            "name" => $name,
            "type" => $type,
            "username" => $username,
            "password" => $password,
        ];
        $filepath = $this->get_user_json_path($userData["id"]);
        $filename = pathinfo($filepath, PATHINFO_FILENAME);

        // Rename the file if the user id has changed.
        if ($filename !== $userData["id"]) {
            unlink($filepath);
            $filepath = str_replace($filename, $userData["id"], $filepath);
        }

        file_put_contents($filepath, json_encode($userData, JSON_PRETTY_PRINT));
    }

    function change_user_password($userId, $newPassword)
    {
        $userData = $this->get_user($userId);

        $userData["password"] = $newPassword;
        file_put_contents($this->get_user_json_path($userId), json_encode($userData, JSON_PRETTY_PRINT));
    }

    function delete_user_json($userId)
    {
        unlink($this->get_user_json_path($userId));
    }

    function get_user($userId)
    {
        return $this->get_json_data($this->userid_exist($userId));
    }

    function get_users_by_type($type)
    {
        $usersJsons = glob($this->usersJsonFolder . $type . "*.json");
        $getUsersId = fn ($userJson) => pathinfo($userJson, PATHINFO_FILENAME);
        return array_map($getUsersId, $usersJsons);
    }

    function get_users()
    {
        return array_merge($this->get_users_by_type(self::ADMIN), $this->get_users_by_type(self::ORGANIZER), $this->get_users_by_type(self::NORMAL));
    }

    function change_user_type($userId, $type)
    {
        $userData = $this->get_user($userId);
        $userData["type"] = $type;
        $this->create_user_json($userData["name"], $userData["type"], $userData["username"], $userData["password"]);
    }

    function request_organizer($userId)
    {
        $data = $this->get_json_data($this->pendingOrganizerJsonFile);

        $data[] = $userId;
        file_put_contents($this->pendingOrganizerJsonFile, json_encode($data, JSON_PRETTY_PRINT));
    }

    function accept_organizer($userId)
    {
        $data = $this->get_json_data($this->pendingOrganizerJsonFile);

        $data = array_diff($data, [$userId]);
        file_put_contents($this->pendingOrganizerJsonFile, json_encode($data, JSON_PRETTY_PRINT));
        $this->change_user_type($userId, self::ORGANIZER);

        // $notifId = create_notifications([
        //     "title" => "Request Approved.",
        //     "body" => "Your request application to become an organizer has been approved.",
        //     "subscribers" => [$userId],
        // ]);
        // add_user_notification($userId, $notifId);
    }

    function cancel_organizer_request($userId)
    {
        $data = $this->get_json_data($this->pendingOrganizerJsonFile);

        $data = array_diff($data, [$userId]);
        file_put_contents($this->pendingOrganizerJsonFile, json_encode($data, JSON_PRETTY_PRINT));
    }

    function get_request_organizer_list()
    {
        $data = $this->get_json_data($this->pendingOrganizerJsonFile);

        return isset($data) ? $data : [];
    }
}
