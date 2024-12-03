<?php
// logout.php
session_start();
require_once(__DIR__ . "/../../core/Database.php");

if (isset($_SESSION['userid'])) {
   $DB = new Database();
   // Update user state to 0 (logged out)
   $updateStateQuery = "UPDATE user_profile SET state = 0 WHERE id = :userid";
   $DB->write($updateStateQuery, ['userid' => $_SESSION['userid']]);
}

// Clear session data and destroy session
session_unset();
session_destroy();

header("Location: login/");
exit();
