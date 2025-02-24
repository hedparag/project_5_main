<?php
$con = pg_connect("host=localhost port=5432 dbname=EmployeeDB user=postgres password=admin");
    if (!$con) {
        die("Database connection failed: " . pg_last_error());
    }
?>