<?php
include 'db.php'; // Incluye la conexión a la base de datos

// Establecer la zona horaria de Colombia
date_default_timezone_set('America/Bogota');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idEmpleado = $_POST['idEmpleadoLavado'];
    $placa = $_POST['placaVehiculo'];
    $precio = $_POST['precioLavado'];
    $fechaLavado = $_POST['fechaLavado']; // Obtener la fecha seleccionada

    // Convertir la fecha seleccionada al formato de la base de datos (YYYY-MM-DD HH:MM:SS)
    $fechaCompleta = date("Y-m-d H:i:s", strtotime($fechaLavado . " " . date("H:i:s")));

    // Consulta para insertar el lavado con la fecha y hora completas
    $sql = "INSERT INTO lavados (id_empleado, placa_vehiculo, precio_lavado, fecha) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isds", $idEmpleado, $placa, $precio, $fechaCompleta);

    if ($stmt->execute()) {
        echo "✅ Lavado registrado exitosamente.";
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>