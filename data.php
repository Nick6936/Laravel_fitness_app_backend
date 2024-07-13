<?php
header('Content-Type: application/json');
$data = array("message" => "Hello from PHP server!");
echo json_encode($data);
?>
