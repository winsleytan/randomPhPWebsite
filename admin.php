<?php
session_start();

// // Check if the user is logged in and is an admin
// if (!isset($_SESSION['loggedin']) || $_SESSION['is_admin'] !== true) {
//     header("Location: login.php"); // Redirect unauthorized users to login page
//     exit();
// }

// Admin page content
echo "Welcome, Admin!";
?>