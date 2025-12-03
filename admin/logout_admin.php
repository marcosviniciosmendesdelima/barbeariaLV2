<?php
session_start();

if (!empty($_SESSION["user"]) && ($_SESSION["user"]["tipo"] ?? '') === "admin") {
    unset($_SESSION["user"]);
}

$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

header("Location: login_admin.php");
exit;
