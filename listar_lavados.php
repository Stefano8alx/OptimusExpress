<?php
include 'db.php'; // Incluye la conexión a la base de datos

if (isset($_GET['idEmpleado'])) {
    $idEmpleado = $_GET['idEmpleado'];
    $fechaSeleccionada = isset($_GET['fechaSeleccionada']) ? $_GET['fechaSeleccionada'] : null;

    if (empty($fechaSeleccionada)) {
        // Mostrar un mensaje de error si no se selecciona una fecha
        echo "<p class='text-center'>❌ Por favor, selecciona una fecha.</p>";
    } else {
        // Convertir la fecha seleccionada al formato de la base de datos (YYYY-MM-DD)
        $fechaFormateada = date("Y-m-d", strtotime($fechaSeleccionada));

        // Consulta para obtener los lavados del empleado en la fecha seleccionada
        $sql = "SELECT * FROM lavados WHERE id_empleado = ? AND DATE(fecha) = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $idEmpleado, $fechaFormateada);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<table class='table table-striped'>";
            echo "<thead><tr><th>ID Lavado</th><th>Placa del Vehículo</th><th>Precio</th><th>Fecha</th></tr></thead>";
            echo "<tbody>";
            while ($fila = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($fila["id"]) . "</td>";
                echo "<td>" . htmlspecialchars($fila["placa_vehiculo"]) . "</td>";
                echo "<td>$" . htmlspecialchars($fila["precio_lavado"]) . "</td>";
                echo "<td>" . htmlspecialchars($fila["fecha"]) . "</td>"; // Mostrar fecha y hora
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p class='text-center'>No se encontraron lavados para este empleado en la fecha seleccionada.</p>";
        }

        $stmt->close();
    }
} else {
    echo "<p class='text-center'>ID del empleado no proporcionado.</p>";
}

$conn->close(); // Cierra la conexión a la base de datos
?>