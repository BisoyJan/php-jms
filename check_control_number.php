<?php
include('db_connection.php'); // Adjust based on your connection script

$contestant_ctr = $_GET['contestant_ctr'];
$sub_event_id = $_GET['sub_event_id'];

$query = $conn->query("SELECT * FROM contestants WHERE contestant_ctr='$contestant_ctr' AND subevent_id='$sub_event_id'");
echo json_encode(['taken' => $query->rowCount() > 0]);
?>

