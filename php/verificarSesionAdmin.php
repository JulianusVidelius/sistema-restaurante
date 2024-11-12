<?php
session_start();
$response = ["sesionIniciada" => false];

if (isset($_SESSION['admin_id'])) {
    $response["sesionIniciada"] = true;
}

echo json_encode($response);
?>