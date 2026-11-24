<section class="auth-section">
    <div class="auth-card auth-card-wide">
        <h2>Crear Cuenta</h2>
        <p class="auth-subtitle">Únete a Riot Eats y haz tu pedido</p>

        <?php if (!empty($error)): ?>
            <div class="auth-alert"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="?controller=usuario&action=doRegister" method="POST" class="auth-form">
            <div class="auth-row">
                <div class="auth-group">
                    <label for="nombre">Nombre *</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required>
                </div>

                <div class="auth-group">
                    <label for="email">Correo electrónico *</label>
                    <input type="email" id="email" name="email" placeholder="tu@email.com" required>
                </div>
            </div>

            <div class="auth-row">
                <div class="auth-group">
                    <label for="password">Contraseña *</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>

                <div class="auth-group">
                    <label for="password2">Repetir contraseña *</label>
                    <input type="password" id="password2" name="password2" placeholder="••••••••" required>
                </div>
            </div>

            <div class="auth-row">
                <div class="auth-group">
                    <label for="telefono">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" placeholder="600 000 000">
                </div>

                <div class="auth-group">
                    <label for="fecha_nacimiento">Fecha de nacimiento</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento">
                </div>
            </div>

            <div class="auth-group">
                <label for="direccion">Dirección de entrega</label>
                <input type="text" id="direccion" name="direccion" placeholder="Calle, número, ciudad">
            </div>

            <button type="submit" class="auth-btn">Registrarse</button>
        </form>

        <p class="auth-switch">
            ¿Ya tienes cuenta? <a href="?controller=usuario&action=login">Inicia sesión</a>
        </p>
    </div>
</section>