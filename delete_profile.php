<?php
include("include/config.php");
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];
    // Delete the employee record
    $psql = "DELETE FROM users WHERE employee_id = $employee_id;";
    $result1 = pg_query($con, $psql);
    $query = "DELETE FROM employees WHERE employee_id = $employee_id;";
    $result2 = pg_query($con, $query);

    if ($result2) {
        $_SESSION['message'] = "Employee deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting employee.";
    }
    
    header("Location: profile.php");
    exit();
}
?>
