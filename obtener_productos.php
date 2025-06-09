<?php
// Configurar cabeceras JSON limpias
header('Content-Type: application/json');

// Conexión segura usando el archivo centralizado
include 'conexion.php';

// Consulta a la tabla producto con relaciones
$sql = "SELECT 
            p.idProducto, 
            p.nombre, 
            p.descripcion, 
            p.stock, 
            p.precio_unitario, 
            p.fecha_vencimiento,
            c.nombre AS categoria,
            m.nombre AS marca,
            f.nombre AS fabricante
        FROM 
            producto p
        LEFT JOIN 
            categoria c ON p.idCategoria = c.idCategoria
        LEFT JOIN 
            marca m ON p.idMarca = m.idMarca
        LEFT JOIN 
            fabricante f ON p.idFabricante = f.idFabricante";

$resultado = $conexion->query($sql);

$productos = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $productos[] = $fila;
    }
}

echo json_encode($productos);

$conexion->close();
?>
