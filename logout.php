<?php
session_start();
session_destroy(); // Destroy the session
header("Location: index.php"); // Redirect to homepage after logout
exit;
?>
