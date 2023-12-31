<?php
include("helper.php");
if ($userData === null) {
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
    <title>Settings - Metro Events</title>
</head>

<body>
    <?php include("header.php") ?>
    <div class="container py-2">
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
                        <tr>
                            <td>Concert in the Park</td>
                            <td>musiclover123</td>
                            <td>
                                <button type="button" class="btn btn-success">Accept</button>
                                <button type="button" class="btn btn-danger">Reject</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="container py-2">
        <h2 class="mb-4">Admin Settings</h2>
        <div class="card mb-4">
            <div class="card-header">
                <h4>Make Admin</h4>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label for="userid" class="form-label">Userid</label>
                        <input type="text" class="form-control" id="userid">
                    </div>
                    <button type="submit" class="btn btn-primary">Make Admin</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4>Approve Organizer Requests</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>johndoe</td>
                            <td>johndoe@example.com</td>
                            <td>
                                <button type="button" class="btn btn-success">Approve</button>
                                <button type="button" class="btn btn-danger">Reject</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include("footer.php") ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

</html>