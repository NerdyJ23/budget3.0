<?php
	require('connect.php');
	$data = json_decode(file_get_contents("php://input"));
	$month = $data->month;
	$year = $data->year;
	//TODO: Check authentication via hash/cookie check

	$sql = "SELECT i.ID
		,i.Receipt
		,i.Name
		,i.Count
		,i.Cost
		,i.Category
		,i.Owner FROM Items AS i
		INNER JOIN Receipts AS r ON i.Receipt = r.`Receipt ID`
		WHERE MONTH(r.Date) = $month 
		AND YEAR(r.Date) = $year";
	$out->sql = $sql;
	echo json_encode($out);
	exit();
?>