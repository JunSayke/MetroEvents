<?php include_once("helper.php"); ?>
<header>
    <nav class="navbar bg-primary" data-bs-theme="dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                METRO EVENTS
            </a>
            <ul class="navbar-nav me-auto d-flex flex-row gap-2">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="events.php">
                        Events
                    </a>
                </li>
                <?php
                if ($userData) {
                    echo '<li class="nav-item">
                            <a class="nav-link" href="notifications.php">
                                Notifications
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="settings.php">
                                Settings
                            </a>
                        </li>';
                }
                ?>
            </ul>
            <?php
            if ($userData) {
                echo '<span class="text-light me-2">Welcome ' . $userData["name"] . ' </span>
                        <a type="button" class="btn btn-danger" href="logout.php">Logout</a>';
            } else {
                echo '<a type="button" class="btn btn-outline-light me-2" href="login.php">Login</a>
                    <a type="button" class="btn btn-light" href="register.php">Sign-up</a>';
            }
            ?>
        </div>
    </nav>
</header>