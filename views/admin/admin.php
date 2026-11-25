<div class="admin-container">
    <div class="admin-header">
        <h1>Panel de Administración</h1>
        <p>Gestión de productos y catálogo de Riot Eats</p>
    </div>

    <section id="productos" class="admin-card">
        <div class="admin-toolbar">
            <h2>Listado de Productos</h2>

            <form action="" id="formEliminarProducto" class="admin-form-delete">
                <input type="text" name="idProducto" id="inputEliminarId" placeholder="ID a eliminar...">
                <button type="submit" class="btn-admin-danger">Eliminar por ID</button>
            </form>
        </div>

        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Imagen</th>
                        <th>Precio</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaProductos">
                    <!-- Aquí se cargan los productos desde JS -->
                </tbody>
            </table>
        </div>
    </section>
</div>

<script src="views/admin/vistasAdministrador.js?v=3"></script>