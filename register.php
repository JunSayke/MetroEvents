<?php
include("helper.php");

if ($userData) {
    header("Location: unauthorized.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize_inputs($_POST["name"]);
    $username = strtolower(sanitize_inputs($_POST["username"]));
    $password = sanitize_inputs($_POST["password"]);
    $confirmPassword = sanitize_inputs($_POST["confirm_password"]);

    if ($password != $confirmPassword) {
        header("Location: ?register_error1");
        exit();
    } else {
        $result = register($name, "user", $username, $password);

        if ($result) {
            header("Location: ?register_success");
        } else {
            header("Location: ?register_error2");
        }
        exit();
    }
}
?>
<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <title>Register - Metro Events</title>
</head>

<body>
    <?php
    include("header.php");
    ?>

    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-lg-4 col-md-6 col-sm-8">
                <div class="card shadow-sm rounded-3 border-0">
                    <div class="card-body">
                        <h3 class="text-center mb-4">Register for Metro Events</h3>
                        <?php
                        // Display appropriate error or success messages
                        if (isset($_GET["register_error1"])) {
                            echo '<div class="alert alert-danger" role="alert">Registration failed! Passwords do not match.</div>';
                        } elseif (isset($_GET["register_error2"])) {
                            echo '<div class="alert alert-danger" role="alert">Registration failed! Username is already taken.</div>';
                        } elseif (isset($_GET["register_success"])) {
                            echo '<div class="alert alert-success" role="alert">Registration successful!</div>';
                        }
                        ?>
                        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="name" name="name" placeholder="name" required autofocus>
                                <label for="name">Full Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="username" name="username" placeholder="username" required>
                                <label for="username">Username</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="password" name="password" placeholder="password" required>
                                <label for="password">Password</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="confirm password" required>
                                <label for="confirm_password">Confirm Password</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>
                        <p class="text-center mt-3">Already have an account? <a href="login.php">Login here</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include("footer.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>