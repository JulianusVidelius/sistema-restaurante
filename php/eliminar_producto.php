<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    echo "No tienes permisos para realizar esta acción.";
    exit();
}
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = $_POST['id_producto'];

    $conn = openConnection();

    $sql = "DELETE FROM productos WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id_producto);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "Producto eliminado correctamente.";
            } else {
                echo "No se encontró un producto con ese ID.";
            }
        } else {
            echo "Error al eliminar el producto: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error al preparar la declaración: " . $conn->error;
    }

    closeConnection($conn);
}
?>