<?php

class ContactoController {
    public function index() {
        $enviado = false;
        $errores = [];
        $nombre = '';
        $email = '';
        $asunto = '';
        $mensaje = '';

        // Si el usuario ya tiene sesión iniciada, autorrellenamos su nombre/email si existen
        if (isset($_SESSION['usuario'])) {
            $u = $_SESSION['usuario'];
            $nombre = is_object($u) ? ($u->nombre ?? '') : ($u['nombre'] ?? '');
            $email  = is_object($u) ? ($u->email ?? '')  : ($u['email'] ?? '');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre  = trim($_POST['nombre'] ?? '');
            $email   = trim($_POST['email'] ?? '');
            $asunto  = trim($_POST['asunto'] ?? '');
            $mensaje = trim($_POST['mensaje'] ?? '');

            if ($nombre === '') {
                $errores[] = "El nombre es obligatorio.";
            }
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errores[] = "Introduce un correo electrónico válido.";
            }
            if ($asunto === '') {
                $errores[] = "Selecciona o escribe un motivo de consulta.";
            }
            if (strlen($mensaje) < 10) {
                $errores[] = "El mensaje debe tener al menos 10 caracteres.";
            }

            if (empty($errores)) {
                $enviado = true;
                // Limpiamos el mensaje tras enviarlo con éxito
                $asunto = '';
                $mensaje = '';
            }
        }

        $view = "views/contacto/index.php";
        include_once("views/main.php");

        return $view;
    }
}