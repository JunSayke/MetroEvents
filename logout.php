<?php
include("helper.php");
if ($userData) {
    logout();
    header("Location: index.php");
    exit();
}
header("Location: unauthorized.php");
exit();
