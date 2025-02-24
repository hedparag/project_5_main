<?php
include "include/config.php";
session_start();

if (!isset($_SESSION["employee_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['employee_id'])) {
    $_SESSION['employee_id'] = $_POST['employee_id'];
}
$employee_id = $_GET['id'] ?? null;

if (!$employee_id) {
    header("Location: dashboard.php"); // Redirect if no ID found
    exit();
}

$invalid = 0;
if(md5($_GET['id'].'abcd') != $_GET['hash']){
    $invalid = 1;
}

if (isset($employee_id) && $invalid == 0) {
    // Delete the employee record
    $psql = "DELETE FROM users WHERE employee_id = '$employee_id';";
    $result1 = pg_query($con, $psql);
    $query = "DELETE FROM employees WHERE employee_id = '$employee_id';";
    $result2 = pg_query($con, $query);

    if ($result2) {
        $_SESSION['message'] = "Employee deleted successfully!";
        $previous_page = $_SERVER['HTTP_REFERER'] ?? '';
        if (strpos($previous_page, 'dashboard.php') !== false) {
            header("Location: dashboard.php");
        } else {
            header("Location: logout.php");
        }
        exit();
        }
} else {
    $_SESSION['error'] = "Error deleting employee.";
    echo "<div class='alert alert-danger' role='alert'>
    INVALID USER ID.
    </div>".$invalid;
}

?>
