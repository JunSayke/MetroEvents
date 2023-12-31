<?php
class EventManager
{
    private $eventsJsonFolder;
    private $reviewsJsonFile;
    private $pendingParticipationJsonFile;

    public function __construct($eventsJsonFolder, $reviewsJsonFile, $pendingParticipationJsonFile)
    {
        $this->eventsJsonFolder = $eventsJsonFolder;
        $this->reviewsJsonFile = $reviewsJsonFile;
        $this->pendingParticipationJsonFile = $pendingParticipationJsonFile;
    }

    function get_json_data($filepath)
    {
        if (!file_exists($filepath)) {
            throw new Exception("Filepath cannot be found.");
        }

        $data = file_get_contents($filepath);
        return json_decode($data, true);
    }

    function get_event_json_path($eventId)
    {
        return $this->eventsJsonFolder . $eventId . ".json";
    }

    function create_event_json($title, $description, $category, $organizer, $participants = [], $upvotes = [])
    {
        $eventData = [
            "id" => uniqid("event"),
            "title" => $title,
            "description" => $description,
            "category" => $category,
            "organizer" => $organizer,
            "participants" => $participants,
            "upvotes" => $upvotes,
        ];
        $json = json_encode($eventData, JSON_PRETTY_PRINT);
        $filepath = $this->get_event_json_path($eventData["id"]);
        file_put_contents($filepath, $json);
    }

    function get_all_events()
    {
        $eventsJsons = glob($this->eventsJsonFolder . "event*.json");
        $getEventsId = fn ($eventJson) => pathinfo($eventJson, PATHINFO_FILENAME);
        return array_map($getEventsId, $eventsJsons);
    }

    function get_event($eventId)
    {
        $eventPath = $this->get_event_json_path($eventId);
        $eventData = $this->get_json_data($eventPath);
        return $eventData;
    }

    function cancel_event($eventId, $reason)
    {
        // $eventData = $this->get_event($eventId);
        // $notifId = create_notifications([
        //     "title" => $eventData["title"] . " event has been cancelled.",
        //     "body" => $reason,
        //     "subscribers" => [],
        // ]);

        // foreach ($eventData["participants"] as $participant) {
        //     add_user_notification($participant, $notifId);
        // }

        unlink($this->get_event_json_path($eventId));

        $data = $this->get_json_data($this->pendingParticipationJsonFile);
        unset($data[$eventId]);
        file_put_contents($this->pendingParticipationJsonFile, json_encode($data, JSON_PRETTY_PRINT));

        $reviewData = $this->get_json_data($this->reviewsJsonFile);
        unset($reviewData[$eventId]);
        file_put_contents($this->reviewsJsonFile, json_encode($reviewData, JSON_PRETTY_PRINT));
    }

    function create_review($reviewData)
    {
        $data = $this->get_json_data($this->reviewsJsonFile);
        $reviewData["id"] = uniqid("review");
        $data[$reviewData["id"]] = $reviewData;
        file_put_contents($this->reviewsJsonFile, json_encode($data, JSON_PRETTY_PRINT));
        return $reviewData["id"];
    }

    function delete_review($reviewId)
    {
        $reviewData = $this->get_json_data($this->reviewsJsonFile);

        unset($reviewData[$reviewId]);
        file_put_contents($this->reviewsJsonFile, json_encode($reviewData, JSON_PRETTY_PRINT));
    }

    function upvote_event($eventId, $userId)
    {
        $eventData = $this->get_event($eventId);

        $eventData["upvotes"][] = $userId;
        file_put_contents($this->get_event_json_path($eventId), json_encode($eventData, JSON_PRETTY_PRINT));
    }

    function remove_upvote($eventId, $userId)
    {
        $eventData = $this->get_event($eventId);

        $eventData["upvotes"] = array_diff($eventData["upvotes"], [$userId]);
        file_put_contents($this->get_event_json_path($eventId), json_encode($eventData, JSON_PRETTY_PRINT));
    }

    function request_user_event_participation($userId, $eventId)
    {
        $data = $this->get_json_data($this->pendingParticipationJsonFile);

        $data[$eventId][] = $userId;
        file_put_contents($this->pendingParticipationJsonFile, json_encode($data, JSON_PRETTY_PRINT));
    }

    function accept_user_event_participation($userId, $eventId)
    {
        $data = $this->get_json_data($this->pendingParticipationJsonFile);

        $data[$eventId] = array_diff($data[$eventId], [$userId]);
        file_put_contents($this->pendingParticipationJsonFile, json_encode($data, JSON_PRETTY_PRINT));
        $data = $this->get_event($eventId);
        $data["participants"][] = $userId;
        file_put_contents($this->get_event_json_path($eventId), json_encode($data, JSON_PRETTY_PRINT));

        // $notifId = create_notifications([
        //     "title" => "Request Approved.",
        //     "body" => "Your event participation request for event \"" . $data["title"] . "\" has been approved.",
        //     "subscribers" => [$userId],
        // ]);
        // add_user_notification($userId, $notifId);
    }
}
