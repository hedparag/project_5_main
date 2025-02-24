<?php
include "./include/config.php";

$ids = json_decode($_POST['ids']);
$idList = implode(",", $ids);

$query = "UPDATE employees SET status = 'f' WHERE employee_id IN ($idList)";
pg_query($con, $query);
pg_close($con);