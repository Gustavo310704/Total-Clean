
<!DOCTYPE html>
<html lang="es">


<head>
    <meta charset="UTF-8">
    <title>TotalClean</title>

    <link rel="stylesheet" href="barralateral.css">
</head>


<body>




    <div class="sidebar">
        <h2>TotalClean</h2>
        <a href="index.html" class="active">Inicio</a>
        <a href="formulariousuario.html">Usuarios</a>
        <a href="formularioproducto.html">Productos</a>
        <a href="formularioinventario.html">Inventario</a>
        <hr>
        <a href="historialEntradas.html">Historial entradas</a>
        <a href="historialSalidas.html">Historial salidas</a>
        <a href="salir.html" class="logout">Salir</a>
    </div>




    <div class="content">


        <div class="grid numero-columnas-3">
            <div class="cuadro padding-1">
                <form action="agregar_producto.php" method="POST">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" maxlength="100" required><br><br>
                 
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" required></textarea><br><br>
                 
                    <label for="idCategoria">ID Categoría:</label>
                    <input type="number" id="idCategoria" name="idCategoria" required><br><br>
                 
                    <label for="idMarca">ID Marca:</label>
                    <input type="number" id="idMarca" name="idMarca" required><br><br>
                 
                    <label for="idFabricante">ID Fabricante:</label>
                    <input type="number" id="idFabricante" name="idFabricante" required><br><br>
                 
                    <label for="stock">Stock:</label>
                    <input type="number" id="stock" name="stock" required><br><br>
                 
                    <label for="precio_unitario">Precio Unitario:</label>
                    <input type="number" id="precio_unitario" name="precio_unitario" step="0.01" required><br><br>
                 
                    <label for="fecha_vencimiento">Fecha de Vencimiento:</label>
                    <input type="date" id="fecha_vencimiento" name="fecha_vencimiento" required><br><br>
                 
                    <input type="submit" value="Agregar Producto">
                  </form>
               
            </div>
        </div>






    </div>




</body>


</html>



