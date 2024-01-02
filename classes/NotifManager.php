<?php
class NotifManager
{
    private $notifsJsonFile;

    public function __construct($notifsJsonFile)
    {
        $this->notifsJsonFile = $notifsJsonFile;
    }

    function get_json_data()
    {
        if (!file_exists($this->notifsJsonFile)) {
            throw new Exception("Filepath cannot be found.");
        }

        $data = file_get_contents($this->notifsJsonFile);
        if (!$data) {
            return [];
        }
        return json_decode($data, true);
    }

    function create_notifications($title, $body, $subscribers = [], $timestamp = null)
    {
        if ($timestamp === null) {
            $timestamp = date('Y-m-d\TH:i');
        }
        $data = $this->get_json_data();
        $notifData = [
            "id" => uniqid("notif"),
            "title" => $title,
            "body" => $body,
            "timestamp" => $timestamp,
            "subscribers" => $subscribers,
        ];
        $data[$notifData["id"]] = $notifData;
        file_put_contents($this->notifsJsonFile, json_encode($data, JSON_PRETTY_PRINT));
        return $notifData["id"];
    }

    function remove_notifications($notifId)
    {
        $data = $this->get_json_data($this->notifsJsonFile);

        unset($data[$notifId]);
        file_put_contents($this->notifsJsonFile, json_encode($data, JSON_PRETTY_PRINT));
    }

    function add_user_notification($userId, $notifId)
    {
        $data = $this->get_json_data($this->notifsJsonFile);

        $data[$notifId]["subscribers"][] = $userId;
        file_put_contents($this->notifsJsonFile, json_encode($data, JSON_PRETTY_PRINT));
    }

    function remove_user_notification($userId, $notifId)
    {
        $data = $this->get_json_data($this->notifsJsonFile);

        $data[$notifId]["subscribers"] = array_diff($data[$notifId]["subscribers"], [$userId]);
        file_put_contents($this->notifsJsonFile, json_encode($data, JSON_PRETTY_PRINT));
    }

    function get_user_notification($userId)
    {
        $data = $this->get_json_data($this->notifsJsonFile);
        $userNotifs = [];
        foreach ($data as $notifData) {
            if (in_array($userId, $notifData["subscribers"])) {
                $userNotifs[] = $notifData;
            }
        }
        return $userNotifs;
    }

    function clean_notifications()
    {
        $data = $this->get_json_data($this->notifsJsonFile);
        foreach ($data as $notifId => $notifData) {
            if (empty($notifData["subscribers"])) {
                unset($data[$notifId]);
            }
        }
        file_put_contents($this->notifsJsonFile, json_encode($data, JSON_PRETTY_PRINT));
    }
}
