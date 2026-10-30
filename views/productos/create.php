<h1>Crear producto</h1>
<p><a href="?controller=producto">Volver al listado</a></p>

<form action="?controller=producto&action=store" method="POST">
    <label>
        Nombre
        <input type="text" name="nombre" id="nombre" required maxlength="100">
    </label>

    <label>
        Talla
        <select name="talla" required>
            <option value="XXL">XXL</option>
            <option value="XL">XL</option>
            <option value="L">L</option>
            <option value="M" selected>M</option>
            <option value="S">S</option>
        </select>
    </label>

    <label>
        Precio
        <input type="number" name="precio" id="precio" step="0.01" min="0" required>
    </label>

    <button type="submit">Guardar</button>
</form>
