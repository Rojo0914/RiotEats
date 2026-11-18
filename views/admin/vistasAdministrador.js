class vistaAdministrador {
    constructor() {
        this.enlaces = null;
        this.secciones = null;
        this.vista = null;
        this.packs = null;
        this.complementos = null;
        this.bebidas = null;
        this.hamburguesas = null;
        this.postres = null;
        this.usuarios = null;
    }

    iniciar() {
        fetch("http://localhost/RiotEats/?controller=producto&action=api")
        .then(response => response.json())
        .then(r => {
            this.productos = r;
            const tablaProductos = document.getElementById("productos");
            this.productos.forEach(producto => {
                
                const tr = document.createElement("tr");
                const td1 = document.createElement("td");
                td1.textContent = producto.id;
                tr.append(td1);

                const td2 = document.createElement("td");
                td2.textContent = producto.nombre;
                tr.append(td2);

                const td3 = document.createElement("td");
                td3.textContent = producto.descripcion;
                tr.append(td3);

                const td4 = document.createElement("td");
                td4.textContent = producto.imagen;
                tr.append(td4);

                const td5 = document.createElement("td");
                td5.textContent = producto.precio;
                tr.append(td5);

                const td6 = document.createElement("td");
                td6.textContent = producto.activa;
                tr.append(td6);

                

                tablaProductos.append(tr);
            })
        });

    let admin = new vistaAdministrador();
    admin.iniciar();

    }
}

const formularioEliminarProducto = document.getElementById("eliminarProducto");
formularioEliminarProducto.addEventListener("submit", (e) => {
    e.preventDefault();
    console.log(e.target[0].value);
        fetch("http://localhost/RiotEats/?controller=producto&action=api"),
        method; "DELETE",
        body; JSON.stringify(id),
        headers; {
            "Content-Type"; "application/json"
    }

})

class hamburguesas {
    constructor(nombre, slug, descripcion, imagen, precio, activa) {
        this.nombre = nombre;
        this.slug = slug;
        this.descripcion = descripcion;
        this.imagen = imagen;
        this.precio = precio;
        this.activa = activa;
    }
}