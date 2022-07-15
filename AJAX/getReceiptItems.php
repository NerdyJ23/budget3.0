<?php
require('connect.php');
$data = json_decode(file_get_contents("php://input"));
// $type = mysqli_real_escape_string($con,$data->type);
$month = mysqli_real_escape_string($con,$data->month);
$year =  mysqli_real_escape_string($con,$data->year);
if(is_null($data->type)) {
	exit();
} else {
	$func = mysqli_real_escape_string($con,$data->type);

	echo json_encode(getItems($month,$year));
}
exit();

function getItems($month,$year) {
	global $con;
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
	$result = mysqli_query($con,$sql);
	if ($result->num_rows > 0) {
		$out = array();
		while ($row = mysqli_fetch_array($result)) {
			$temp = array(
				'receiptId' => $row["Receipt"]
				,'name' => $row["Name"]
				,'count' => $row["Count"]
				,'cost' => $row["Cost"]
				,'category' => $row["Category"]
			);
			array_push($out,$temp);
		}
		return $out;
	}
	return null;
}
?>