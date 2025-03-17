<?php
include 'db.php';

$sql = "SELECT * FROM empleados";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<ul class='list-group'>";
    while ($fila = $result->fetch_assoc()) {
        echo "<li class='list-group-item'>ID: " . htmlspecialchars($fila["id"]) . " - Nombre: " . htmlspecialchars($fila["nombre"]) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p class='text-center'>No se encontraron empleados.</p>";
}

$conn->close();
?>