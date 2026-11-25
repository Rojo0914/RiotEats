class vistaAdministrador {
    constructor() {
        this.productos = [];
    }

    iniciar() {
        fetch("http://localhost/RiotEats/?controller=producto&action=api")
            .then(response => response.json())
            .then(r => {
                this.productos = r;
                const tablaProductos = document.getElementById("tablaProductos");
                tablaProductos.innerHTML = "";

                this.productos.forEach(producto => {
                    const hamburguesa = new Hamburguesas(
                        producto.id,
                        producto.nombre,
                        producto.slug,
                        producto.descripcion,
                        producto.imagen,
                        producto.precio,
                        producto.activo
                    );

                    const tr = document.createElement("tr");

                    const td1 = document.createElement("td");
                    td1.textContent = hamburguesa.id;
                    tr.append(td1);

                    const td2 = document.createElement("td");
                    td2.textContent = hamburguesa.nombre;
                    tr.append(td2);

                    const td3 = document.createElement("td");
                    td3.textContent = hamburguesa.descripcion;
                    tr.append(td3);

                    const td4 = document.createElement("td");
                    const img = document.createElement("img");
                    img.src = hamburguesa.imagen;
                    img.alt = hamburguesa.nombre;
                    td4.append(img);
                    tr.append(td4);

                    const td5 = document.createElement("td");
                    td5.textContent = hamburguesa.precio + " €";
                    tr.append(td5);

                    const td6 = document.createElement("td");
                    td6.textContent = hamburguesa.activo == 1 ? "Sí" : "No";
                    tr.append(td6);

                    const td7 = document.createElement("td");
                    const botonEliminar = document.createElement("button");
                    botonEliminar.textContent = "Eliminar";
                    botonEliminar.classList.add("btn-admin-danger");
                    td7.append(botonEliminar);
                    tr.append(td7);

                    botonEliminar.addEventListener("click", () => {
                        hamburguesa.eliminar();
                    });

                    tablaProductos.append(tr);
                });
            });
    }
}

class Hamburguesas {
    constructor(id, nombre, slug, descripcion, imagen, precio, activo) {
        this.id = id;
        this.nombre = nombre;
        this.slug = slug;
        this.descripcion = descripcion;
        this.imagen = imagen;
        this.precio = precio;
        this.activo = activo;
    }

    eliminar() {
        if (!confirm("¿Seguro que quieres eliminar " + this.nombre + "?")) {
            return;
        }

        fetch("http://localhost/RiotEats/?controller=producto&action=api", {
            method: "DELETE",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id: this.id })
        })
        .then(r => r.json())
        .then(respuesta => {
            if (respuesta.ok) {
                location.reload();
            } else {
                alert("Error al eliminar: " + respuesta.mensaje);
            }
        });
    }
}

const formularioEliminarProducto = document.getElementById("formEliminarProducto");
formularioEliminarProducto.addEventListener("submit", (e) => {
    e.preventDefault();
    const id = document.getElementById("inputEliminarId").value.trim();

    if (id === "") {
        alert("Escribe un ID primero.");
        return;
    }

    fetch("http://localhost/RiotEats/?controller=producto&action=api", {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: id })
    })
    .then(r => r.json())
    .then(respuesta => {
        if (respuesta.ok) {
            location.reload();
        } else {
            alert("Error: " + respuesta.mensaje);
        }
    });
});

const admin = new vistaAdministrador();
admin.iniciar();