<?php
include("include/config.php");
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET"  && isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];
    $query = "UPDATE employees SET status = 'f' WHERE employee_id = $employee_id;";
    $result = pg_query($con, $query);

    if ($result) {
        $_SESSION['message'] = "Employee status changed successfully!";
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Error status change employee.";
    }
}
?>