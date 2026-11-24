<section class="auth-section">
    <div class="auth-card">
        <h2>Iniciar Sesión</h2>
        <p class="auth-subtitle">Accede a tu cuenta de Riot Eats</p>

        <?php if (!empty($error)): ?>
            <div class="auth-alert"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="?controller=usuario&action=doLogin" method="POST" class="auth-form">
            <div class="auth-group">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" placeholder="ejemplo@rioteats.local" required>
            </div>

            <div class="auth-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="auth-btn">Entrar</button>
        </form>

        <p class="auth-switch">
            ¿Aún no tienes cuenta? <a href="?controller=usuario&action=register">Regístrate aquí</a>
        </p>
    </div>
</section>