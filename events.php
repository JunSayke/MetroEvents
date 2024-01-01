<?php
include("helper.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <title>Events - Metro Events</title>
</head>

<body>
    <?php
    include("header.php");
    $html = "";
    if (isset($_GET["id"])) {
        $eventHtml = get_event_html($_GET["id"]);
        $html = <<<HTMLBODY
                <div class="container p-4">
                    {$eventHtml}
                </div>
            HTMLBODY;
    } else {
        $button = "";
        if ($userData !== null) {
            if ($userData["type"] === "organizer" || $userData["type"] === "admin") {
                $button = '<a href="new_event.php" class="action-btn btn btn-success" id="create-event-btn">Create New Event</a>';
            } else if (in_array($userData["id"], $userManager->get_request_organizer_list())) {
                $button = '<button class="action-btn btn btn-outline-danger" id="cancel-organizer-request-btn">Cancel Request</button>';
            } else {
                $button = '<button class="action-btn btn btn-outline-success" id="organizer-request-btn">Become Organizer</button>';
            }
        }

        $eventListHtml = get_events_html();
        $html = <<<HTMLBODY
                <div class="container my-5">
                <h1 class="display-6 text-center mb-4">Explore Events</h1>
                <hr>
                <div class="pb-2">
                    {$button}
                </div>
                <div id="events-list" class="container-fluid d-grid gap-4">
                    {$eventListHtml}
                </div>
            </div>
        HTMLBODY;
    }
    echo $html;
    ?>
    <?php include("footer.php") ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

</html>