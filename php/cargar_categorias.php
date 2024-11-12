<?php
include 'db_connection.php';

$conn = openConnection();

$sql = "SELECT * FROM categorias";
$result = $conn->query($sql);

$categorias = array();
while ($row = $result->fetch_assoc()) {
    $categorias[] = $row;
}

echo json_encode(['categorias' => $categorias]);

closeConnection($conn);
?>
