<?php
include 'db.php';

// Obtener lavados pendientes (pagado = 0)
$sql = "SELECT * FROM lavados WHERE pagado = 0 ORDER BY fecha DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table class='table table-striped'>";
    echo "<thead><tr><th>ID Lavado</th><th>Placa</th><th>Precio</th><th>Fecha</th><th>Acción</th></tr></thead>";
    echo "<tbody>";
    while ($fila = $result->fetch_assoc()) {
        echo "<tr id='lavado-{$fila['id']}'>";
        echo "<td>{$fila['id']}</td>";
        echo "<td>{$fila['placa_vehiculo']}</td>";
        echo "<td>$" . number_format($fila['precio_lavado'], 2) . "</td>";
        echo "<td>{$fila['fecha']}</td>";
        echo "<td><button class='btn btn-success btn-sm' onclick='marcarComoPagado({$fila['id']})'>Marcar como Pagado</button></td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p class='text-center'>No hay lavados pendientes.</p>";
}

$conn->close();
?>