<section class="contacto-wrapper">
    <div class="info-page-header">
        <h1>Contáctanos</h1>
        <p>¿Dudas con tu pedido, alérgenos o colaboraciones? Escríbenos y te respondemos al momento.</p>
    </div>

    <div class="contacto-grid">
        <!-- Columna izquierda: Información directa -->
        <aside class="contacto-info-col">
            <article class="info-page-card">
                <h2>Atención al Cliente</h2>
                <p><strong>Teléfono:</strong> 611 41 14 88</p>
                <p><strong>Email:</strong> soporte@rioteats.es</p>
                <p><strong>Horario:</strong> Lunes a Domingo de 12:00 a 00:00 h</p>
            </article>

            <article class="info-page-card">
                <h2>Cocina Central & Local</h2>
                <p><strong>Dirección:</strong> Carrer de Pedro i Pons, 9, Les Corts</p>
                <p><strong>Ciudad:</strong> 08034 Barcelona, España</p>
            </article>

            <article class="info-page-card">
                <h2>Prensa y Eventos</h2>
                <p>Para patrocinios de torneos, creadores de contenido o medios: <strong>prensa@rioteats.es</strong></p>
            </article>
        </aside>

        <!-- Columna derecha: Formulario -->
        <div class="contacto-form-card">
            <h2>Envíanos un mensaje</h2>

            <?php if ($enviado): ?>
                <div class="contacto-alert exito">
                    <strong>¡Mensaje enviado!</strong> Hemos recibido tu consulta correctamente. Nuestro equipo te responderá en breve a tu correo.
                </div>
            <?php endif; ?>

            <?php if (!empty($errores)): ?>
                <div class="contacto-alert error">
                    <ul>
                        <?php foreach ($errores as $err): ?>
                            <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= url ?>index.php?controller=contacto&action=index" method="POST" class="contacto-form">
                <div class="form-grupo">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" required
                           placeholder="Tu nombre o invocador"
                           value="<?= htmlspecialchars($nombre) ?>">
                </div>

                <div class="form-grupo">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" required
                           placeholder="tu@email.com"
                           value="<?= htmlspecialchars($email) ?>">
                </div>

                <div class="form-grupo">
                    <label for="asunto">Motivo de contacto</label>
                    <select id="asunto" name="asunto" required>
                        <option value="" disabled <?= $asunto === '' ? 'selected' : '' ?>>Selecciona una opción...</option>
                        <option value="pedido" <?= $asunto === 'pedido' ? 'selected' : '' ?>>Incidencia con un pedido</option>
                        <option value="alergenos" <?= $asunto === 'alergenos' ? 'selected' : '' ?>>Consulta sobre ingredientes / alérgenos</option>
                        <option value="sugerencia" <?= $asunto === 'sugerencia' ? 'selected' : '' ?>>Sugerencia o valoración</option>
                        <option value="otro" <?= $asunto === 'otro' ? 'selected' : '' ?>>Otro motivo</option>
                    </select>
                </div>

                <div class="form-grupo">
                    <label for="mensaje">Mensaje</label>
                    <textarea id="mensaje" name="mensaje" rows="5" required
                              placeholder="Cuéntanos en qué podemos ayudarte..."><?= htmlspecialchars($mensaje) ?></textarea>
                </div>

                <button type="submit" class="btn xl contacto-btn-enviar">/ Enviar Mensaje</button>
            </form>
        </div>
    </div>
</section>