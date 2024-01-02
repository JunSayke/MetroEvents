<?php
include("helper.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && $userData && isset($_POST["currentPassword"]) && isset($_POST["newPassword"]) && isset($_POST["confirmPassword"])) {
    $currentPassword = $userManager->hash_password(sanitize_inputs($_POST["currentPassword"]));
    $newPassword = $userManager->hash_password(sanitize_inputs($_POST["newPassword"]));
    $confirmPassword = $userManager->hash_password(sanitize_inputs($_POST["confirmPassword"]));

    if ($currentPassword !== $userData["password"]) {
        header("Location: ?change_password_error1");
        exit();
    }

    if ($newPassword !== $confirmPassword) {
        header("Location: ?change_password_error2");
        exit();
    }

    $userManager->change_user_password($userData["id"], $newPassword);
    header("Location: ?change_password_success");
    exit();
}

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
    <?php include("header.php"); ?>
    <div class="container py-5">
        <h2 class="mt-4 mb-4">User Settings</h2>
        <div class="card">
            <div class="card-header">
                Change Password
            </div>
            <div class="card-body">
                <?php
                if (isset($_GET["change_password_error1"])) {
                    echo '<div class="alert alert-danger" role="alert">Incorrect password.</div>';
                } elseif (isset($_GET["change_password_error2"])) {
                    echo '<div class="alert alert-danger" role="alert">Mismatch new password and confirm password.</div>';
                } elseif (isset($_GET["change_password_success"])) {
                    echo '<div class="alert alert-success" role="alert">Password has been changed successfully!</div>';
                }
                ?>
                <form action="settings.php" method="POST">
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">Current Password:</label>
                        <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password:</label>
                        <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm New Password:</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </form>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-header">
                Delete Account
            </div>
            <div class="card-body">
                <p class="text-danger">Warning: This action cannot be undone.</p>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-account-modal">
                    Delete Account
                </button>
            </div>
        </div>
        <div class="modal fade" id="delete-account-modal" tabindex="-1" aria-labelledby="delete-account-modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="delete-account-modalLabel">Confirm Account Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to permanently delete your account? This action cannot be reversed.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="action-btn btn btn-danger" id="delete-account-btn">Delete Account</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php

    if ($userData["type"] === "organizer" || $userData["type"] === "admin") {
        echo get_organizer_settings_html();
    }

    if ($userData["type"] === "admin") {
        echo get_admin_settings_html();
    }
    ?>

    <?php include("footer.php") ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

</html>