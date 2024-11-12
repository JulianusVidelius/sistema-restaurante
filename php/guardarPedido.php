<?php
session_start();
require 'db_connection.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que el usuario haya iniciado sesión
    if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
        echo json_encode(["error" => "No se ha iniciado sesión o el ID de usuario es inválido."]);
        exit();
    }

    $userId = (int)$_SESSION['user_id'];  // Convertir a entero por seguridad

    // Verificar que el campo 'productos' existe y contiene un JSON válido
    if (isset($_POST['productos'])) {
        $productos = json_decode($_POST['productos'], true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($productos)) {
            echo json_encode(["error" => "Formato de productos inválido."]);
            exit();
        }
    } else {
        echo json_encode(["error" => "No se recibieron productos."]);
        exit();
    }

    // Convertir productos a JSON y establecer estado inicial
    $productosJSON = json_encode($productos);
    $estado = 'pendiente';

    try {
        // Abrir conexión a la base de datos
        $conn = openConnection();
        if (!$conn) {
            throw new Exception("Error de conexión a la base de datos.");
        }

        // Preparar y ejecutar la consulta de inserción
        $sql = "INSERT INTO pedidos (user_id, productos, estado) VALUES (?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("iss", $userId, $productosJSON, $estado);

            if ($stmt->execute()) {
                echo json_encode(["success" => "Pedido guardado con exito."]);
            } else {
                throw new Exception("Error al guardar el pedido: " . $stmt->error);
            }

            $stmt->close();
        } else {
            throw new Exception("Error al preparar la declaración: " . $conn->error);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    } finally {
        // Cerrar conexión
        if (isset($conn)) {
            closeConnection($conn);
        }
    }
}
?>
