<?php
include 'conexion.php';
header('Content-Type: application/json');

if (!isset($_POST['id'])) {
    echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
    exit;
}

$id_marca = $_POST['id'];

try {
    $sql = "DELETE FROM marca WHERE id_marca = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_marca);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception("Error al eliminar la marca");
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conexion)) $conexion->close();
}
?>