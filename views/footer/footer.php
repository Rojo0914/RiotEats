<footer class="foot" id="">
    <img class="logo" src="<?php echo url ?>/img/Logo/Logo-blanco.svg" alt="Riot Eats">
    <nav>
        <p>
            <a href="<?php echo url ?>index.php?controller=info&action=prensa">PRENSA</a>
            <a href="<?php echo url ?>index.php?controller=info&action=seguridad">SEGURIDAD</a>
            <a href="<?php echo url ?>index.php?controller=info&action=legal">LEGAL</a>
            <a href="<?php echo url ?>index.php?controller=info&action=privacidad">AVISO DE PRIVACIDAD</a>
            <a href="<?php echo url ?>index.php?controller=info&action=terminos">TERMINOS DE SERVICIO</a>
            <a href="<?php echo url ?>index.php?controller=info&action=asistencia">ASISTENCIA AL CLIENTE</a>
            <a href="<?php echo url ?>index.php?controller=info&action=verificar">VERIFICAR EN LÍNEA</a>
            <a href="<?php echo url ?>index.php?controller=info&action=accesibilidad">ACCESIBILIDAD</a>
            <a href="<?php echo url ?>index.php?controller=info&action=informes">INFORMES ANUALES</a>
            <a href="<?php echo url ?>index.php?controller=info&action=pacto">PACTO DE LA CLIENTELA</a>
        </p>
    </nav>
    <div class="soc">
        <a href="https://x.com" target="_blank" rel="noopener noreferrer">
            <img src="<?php echo url ?>/img/svg/X.svg" alt="X">
        </a>
        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer">
            <img src="<?php echo url ?>/img/svg/Instagram.svg" alt="Instagram">
        </a>
        <a href="https://tiktok.com" target="_blank" rel="noopener noreferrer">
            <img src="<?php echo url ?>/img/svg/TikTok.svg" alt="Tik Tok">
        </a>
        <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer">
            <img src="<?php echo url ?>/img/svg/Linkedin.svg" alt="LinkedIn">
        </a>
        <a href="https://facebook.com" target="_blank" rel="noopener noreferrer">
            <img src="<?php echo url ?>/img/svg/Facebook.svg" alt="Facebook">
        </a>
        <a href="https://youtube.com" target="_blank" rel="noopener noreferrer">
            <img src="<?php echo url ?>/img/svg/YouTube.svg" alt="YouTube">
        </a>
    </div>
    <hr>
    
    <small>
        <a href="#" id="btn-cookies" style="color: inherit; text-decoration: underline;">Preferencias de cookies</a> | 
        <span>© 2026 Riot Eats, Inc. Todos los derechos reservados.</span>
    </small>
</footer>

<script>
document.getElementById("btn-cookies")?.addEventListener("click", (e) => {
    e.preventDefault();
    alert("Preferencias de cookies: Riot Eats únicamente utiliza cookies técnicas de sesión necesarias para mantener tu carrito y tu inicio de sesión activos.");
});
</script>