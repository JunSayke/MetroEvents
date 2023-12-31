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
    <?php include("header.php") ?>
    <div class="container my-5">
        <h1 class="display-6 text-center mb-4">Explore Events</h1>
        <hr>
        <div class="pb-2">
            <a href="new_event.php" class="btn btn-success">Create New Event</a>
        </div>
        <div class="container-fluid d-grid gap-4">
            <div class="row">
                <div class="col-12 py-3 bg-light shadow">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4>Event Title</h4>
                        <span class="badge bg-secondary rounded-pill">Participants: 15</span>
                    </div>
                    <p class="text-muted text-truncate">Event description</p>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="btn-group">
                            <button class="btn btn-outline-primary">Upvote <i class="fas fa-thumbs-up"></i> <span class="badge bg-primary rounded-pill">50</span></button>
                            <button class="btn btn-outline-success">Join</button>
                            <button class="btn btn-outline-danger">Cancel</button>
                            <button class="btn btn-outline-danger">Leave</button>
                            <button class="btn btn-outline-danger">Delete</button>
                        </div>
                        <a href="event_thread.php" class="btn btn-primary">View</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include("footer.php") ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

</html>