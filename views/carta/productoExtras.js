document.addEventListener("DOMContentLoaded", () => {
    const MAX_EXTRAS = 2;
    const precioTotalEl = document.getElementById("precio-total");
    if (!precioTotalEl) return;

    const precioBase = parseFloat(precioTotalEl.dataset.base) || 0;
    const tarjetasExtras = document.querySelectorAll(".extra-card");
    const botonesCantidad = document.querySelectorAll(".btn-qty");

    function actualizarTotal() {
        let sumaExtras = 0;

        tarjetasExtras.forEach(tarjeta => {
            const precioExtra = parseFloat(tarjeta.dataset.precio) || 0;
            const inputCantidad = tarjeta.querySelector("input[type='hidden']");
            const cantidad = parseInt(inputCantidad.value) || 0;

            sumaExtras += precioExtra * cantidad;
        });

        const total = precioBase + sumaExtras;
        precioTotalEl.textContent = `/ ${total.toFixed(2)} €`;
    }

    botonesCantidad.forEach(boton => {
        boton.addEventListener("click", () => {
            const id = boton.dataset.id;
            const delta = parseInt(boton.dataset.delta);

            const display = document.getElementById(`qty-display-${id}`);
            const input = document.getElementById(`input-extra-${id}`);

            let cantidadActual = parseInt(input.value) || 0;
            let nuevaCantidad = cantidadActual + delta;

            if (nuevaCantidad < 0) nuevaCantidad = 0;
            if (nuevaCantidad > MAX_EXTRAS) nuevaCantidad = MAX_EXTRAS;

            input.value = nuevaCantidad;
            display.textContent = nuevaCantidad;

            // Deshabilita visualmente los botones al llegar a los límites (0 o 3)
            const controles = boton.closest(".extra-controls");
            controles.querySelector(".minus").disabled = (nuevaCantidad === 0);
            controles.querySelector(".plus").disabled = (nuevaCantidad === MAX_EXTRAS);

            actualizarTotal();
        });
    });
});