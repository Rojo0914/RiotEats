<h2>Crear categoría</h2>

<form action="<?= url ?>index.php?controller=Categoria&action=guardar" method="POST">

    <!-- Nombre -->
    <label for="nombre">
        Nombre:
    </label>

    <input
        type="text"
        id="nombre"
        name="nombre"
        required
    >


    <!-- Slug -->
    <label for="slug">
        Slug:
    </label>

    <input
        type="text"
        id="slug"
        name="slug"
        required
    >


    <!-- Descripción -->
    <label for="descripcion">
        Descripción:
    </label>

    <textarea
        id="descripcion"
        name="descripcion"
    ></textarea>


    <!-- Estado -->
    <label for="activa">
        Categoría activa:
    </label>

    <input
        type="checkbox"
        id="activa"
        name="activa"
        checked
    >


    <!-- Enviar -->
    <button type="submit">
        Guardar categoría
    </button>

</form>