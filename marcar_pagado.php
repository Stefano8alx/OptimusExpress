<?php
include 'db.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("UPDATE lavados SET pagado = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "OK";
    } else {
        echo "Error";
    }

    $stmt->close();
} else {
    echo "ID no proporcionado.";
}

$conn->close();
?>