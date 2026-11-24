<?php
include_once(__DIR__ . "/../models/UsuarioDAO.php");

class UsuarioController {

    private function iniciarSesion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Muestra el formulario de Login (?controller=usuario&action=login)
    public function login() {
        $this->iniciarSesion();
        $error = $_SESSION['auth_error'] ?? null;
        unset($_SESSION['auth_error']);

        $view = __DIR__ . "/../views/usuario/login.php";
        include_once 'views/main.php';
    }

    // Procesa el POST del Login (?controller=usuario&action=doLogin)
    public function doLogin() {
        $this->iniciarSesion();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?controller=usuario&action=login");
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $usuario = UsuarioDAO::getByEmail($email);

        if ($usuario && password_verify($password, $usuario->getPassword())) {
            $_SESSION['usuario'] = [
                'id'     => $usuario->getId(),
                'nombre' => $usuario->getNombre(),
                'email'  => $usuario->getEmail(),
                'rol'    => $usuario->getRol()
            ];

            // Si es admin, lo mandamos directo al panel de administración
            if ($usuario->esAdmin()) {
                header("Location: ?controller=admin&action=index");
            } else {
                header("Location: ?controller=home");
            }
            exit;
        }

        $_SESSION['auth_error'] = "Email o contraseña incorrectos.";
        header("Location: ?controller=usuario&action=login");
        exit;
    }

    // Muestra el formulario de Registro (?controller=usuario&action=register)
    public function register() {
        $this->iniciarSesion();
        $error = $_SESSION['auth_error'] ?? null;
        unset($_SESSION['auth_error']);

        $view = __DIR__ . "/../views/usuario/register.php";
        include_once 'views/main.php';
    }

    // Procesa el POST del Registro (?controller=usuario&action=doRegister)
    public function doRegister() {
        $this->iniciarSesion();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?controller=usuario&action=register");
            exit;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';
        $telefono = trim($_POST['telefono'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $fechaNacimiento = $_POST['fecha_nacimiento'] ?? '';

        if (empty($nombre) || empty($email) || empty($password)) {
            $_SESSION['auth_error'] = "Rellena los campos obligatorios (nombre, email y contraseña).";
            header("Location: ?controller=usuario&action=register");
            exit;
        }

        if ($password !== $password2) {
            $_SESSION['auth_error'] = "Las contraseñas no coinciden.";
            header("Location: ?controller=usuario&action=register");
            exit;
        }

        if (UsuarioDAO::getByEmail($email)) {
            $_SESSION['auth_error'] = "Ya existe una cuenta registrada con ese correo electrónico.";
            header("Location: ?controller=usuario&action=register");
            exit;
        }

        $nuevoId = UsuarioDAO::registrar($nombre, $email, $password, $telefono, $direccion, $fechaNacimiento);

        if ($nuevoId) {
            $_SESSION['usuario'] = [
                'id'     => $nuevoId,
                'nombre' => $nombre,
                'email'  => $email,
                'rol'    => 'usuario'
            ];
            header("Location: ?controller=home");
            exit;
        }

        $_SESSION['auth_error'] = "Error al crear la cuenta. Inténtalo de nuevo.";
        header("Location: ?controller=usuario&action=register");
        exit;
    }

    // Cerrar sesión (?controller=usuario&action=logout)
    public function logout() {
        $this->iniciarSesion();
        unset($_SESSION['usuario']);
        session_destroy();
        header("Location: ?controller=home");
        exit;
    }
}
?>