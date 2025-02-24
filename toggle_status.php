<?php
include "./include/config.php";

$employee_id = $_POST['id'];
$new_status = $_POST['status'];

$query = "UPDATE employees SET status = '$new_status' WHERE employee_id = $employee_id";
pg_query($con, $query);
