<?php
include_once(__DIR__ . "/../models/UsuarioDAO.php");

class UsuarioController {

    // 1. MOSTRAR EL FORMULARIO DE LOGIN
    public function login() {
        // Si la sesión no está iniciada, la iniciamos
        if (!isset($_SESSION)) {
            session_start();
        }

        // Si venimos de un fallo anterior, guardamos el error y lo borramos de la sesión
        $error = "";
        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']);
        }

        // Si venimos de registrarnos bien, guardamos el mensaje verde y lo borramos de la sesión
        $exito = "";
        if (isset($_SESSION['exito'])) {
            $exito = $_SESSION['exito'];
            unset($_SESSION['exito']);
        }

        $view = __DIR__ . "/../views/usuario/login.php";
        include_once 'views/main.php';
    }

    // 2. PROCESAR LOS DATOS CUANDO PULSAS "ENTRAR"
    public function doLogin() {
        if (!isset($_SESSION)) {
            session_start();
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $usuario = UsuarioDAO::getByEmail($email);

        if ($usuario != null && password_verify($password, $usuario->getPassword())) {
            
            $_SESSION['usuario'] = [
                'id'     => $usuario->getId(),
                'nombre' => $usuario->getNombre(),
                'email'  => $usuario->getEmail(),
                'rol'    => $usuario->getRol()
            ];

            if ($usuario->esAdmin()) {
                header("Location: ?controller=admin&action=index");
            } else {
                header("Location: ?controller=home&action=index");
            }
            exit();

        } else {
            $_SESSION['error'] = "Email o contraseña incorrectos.";
            header("Location: ?controller=usuario&action=login");
            exit();
        }
    }

    // 3. MOSTRAR EL FORMULARIO DE REGISTRO
    public function register() {
        if (!isset($_SESSION)) {
            session_start();
        }

        $error = "";
        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']);
        }

        $view = __DIR__ . "/../views/usuario/register.php";
        include_once 'views/main.php';
    }

    // 4. PROCESAR LOS DATOS CUANDO PULSAS "REGISTRARSE"
    public function doRegister() {
        if (!isset($_SESSION)) {
            session_start();
        }

        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $fechaNacimiento = $_POST['fecha_nacimiento'];

        if ($password != $password2) {
            $_SESSION['error'] = "Las contraseñas no coinciden.";
            header("Location: ?controller=usuario&action=register");
            exit();
        }

        if (UsuarioDAO::getByEmail($email) != null) {
            $_SESSION['error'] = "Ese correo electrónico ya está registrado.";
            header("Location: ?controller=usuario&action=register");
            exit();
        }

        $creado = UsuarioDAO::registrar($nombre, $email, $password, $telefono, $direccion, $fechaNacimiento);

        if ($creado) {
            $_SESSION['exito'] = "Cuenta creada correctamente. Ya puedes iniciar sesión.";
            header("Location: ?controller=usuario&action=login");
            exit();
        } else {
            $_SESSION['error'] = "Error al crear la cuenta.";
            header("Location: ?controller=usuario&action=register");
            exit();
        }
    }

    // 5. CERRAR SESIÓN
    public function logout() {
        if (!isset($_SESSION)) {
            session_start();
        }

        unset($_SESSION['usuario']);
        session_destroy();

        header("Location: ?controller=usuario&action=login");
        exit();
    }
}
?>