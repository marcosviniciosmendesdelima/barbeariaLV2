<?php
session_start();

// remove somente os dados do admin sem quebrar toda a sessão
if (isset($_SESSION["user"]) && $_SESSION["user"]["tipo"] === "admin") {
    unset($_SESSION["user"]);
}

// destruir sessão completamente (mais seguro)
session_destroy();

// remover cookie se existir
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

header("Location: login_admin.php");
exit;
