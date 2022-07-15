<?php
header("Content-Type: application/json; charset=UTF-8");
$con = mysqli_connect("localhost",getenv("DB_USER"), getenv("DB_PASS"),getenv("BUDGET_DB"));
?>