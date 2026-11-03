<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Categoría - Restaurante</title>
</head>
<body>

    <h1>Crear Nueva Categoría</h1>

    <!-- FORMULARIO: Envía los datos mediante POST a la acción 'guardar' del controlador -->
    <form action="index.php?controller=Categoria&action=guardar" method="POST">
        
        <!-- CAMPO 1: Nombre de la Categoría -->
        <div>
            <label for="nombre">Nombre de la categoría:</label><br>
            <input type="text" id="nombre" name="nombre" required placeholder="Ej: Hamburguesas Smash">
        </div>
        <br>

        <!-- CAMPO 2: Slug (Texto amigable para la URL) -->
        <div>
            <label for="slug">Slug (URL amigable):</label><br>
            <input type="text" id="slug" name="slug" required placeholder="Ej: hamburguesas-smash">
        </div>
        <br>

        <!-- CAMPO 3: Descripción (Opcional) -->
        <div>
            <label for="descripcion">Descripción:</label><br>
            <textarea id="descripcion" name="descripcion" rows="4" cols="50" placeholder="Escribe una breve descripción (opcional)..."></textarea>
        </div>
        <br>

        <!-- CAMPO 4: Estado Activa (Booleano) -->
        <div>
            <label for="activa">
                <!-- Se envía valor '1' si la casilla está marcada -->
                <input type="checkbox" id="activa" name="activa" value="1" checked>
                Categoría activa (visible en la carta)
            </label>
        </div>
        <br>

        <!-- BOTÓN DE ENVÍO -->
        <button type="submit">Guardar Categoría</button>

    </form>

</body>
</html>