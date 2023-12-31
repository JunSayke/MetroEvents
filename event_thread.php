<?php
include("helper.php");
if (!$userData) {
    header("Location: unauthorized.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <title>New Event - Metro Events</title>
</head>

<body>
    <?php include("header.php") ?>
    <div class="container p-4">
        <h1 class="display-4">Sample Event Title</h1>
        <p class="lead">Event description goes here.</p>

        <hr class="my-4">

        <h2>Reviews</h2>
        <div class="list-group">
            <div class="list-group-item d-flex justify-content-between align-items-start">
                <div>
                    <h5 class="mb-1">Reviewer 1</h5>
                    <p class="mb-1">This was a great event! I really enjoyed it.</p>
                </div>
                <button type="button" class="btn btn-danger btn-sm">X</button>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-start">
                <div>
                    <h5 class="mb-1">Reviewer 2</h5>
                    <p class="mb-1">The event was well-organized and informative.</p>
                </div>
                <button type="button" class="btn btn-danger btn-sm">X</button>
            </div>
        </div>


        <hr class="my-4">

        <h2>Leave a Review</h2>
        <form>
            <div class="mb-3">
                <textarea class="form-control" rows="3" placeholder="Write your review here"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Review</button>
        </form>
    </div>
    <?php include("footer.php") ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

</html>