<?php
include_once(__DIR__ . "/../config/dataBase.php");
include_once(__DIR__ . "/Usuario.php");

class UsuarioDAO {

    // Buscar usuario por email (para Login y comprobar duplicados en Register)
    public static function getByEmail($email) {
        $con = DataBase::connect();
        $stmt = $con->prepare("SELECT * FROM usuarios WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $usuario = $stmt->get_result()->fetch_object('Usuario');

        $stmt->close();
        $con->close();

        return $usuario ?: null;
    }

    // Registrar un nuevo usuario (rol por defecto 'usuario')
    public static function registrar($nombre, $email, $passwordPlana, $telefono = null, $direccion = null, $fechaNacimiento = null) {
        $con = DataBase::connect();

        $hash = password_hash($passwordPlana, PASSWORD_BCRYPT);
        $rol = 'usuario';

        // Convertir strings vacíos a null para columnas opcionales
        $telefono = !empty($telefono) ? $telefono : null;
        $direccion = !empty($direccion) ? $direccion : null;
        $fechaNacimiento = !empty($fechaNacimiento) ? $fechaNacimiento : null;

        $stmt = $con->prepare(
            "INSERT INTO usuarios (nombre, email, password, telefono, direccion, fecha_nacimiento, rol) 
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("sssssss", $nombre, $email, $hash, $telefono, $direccion, $fechaNacimiento, $rol);

        $ok = $stmt->execute();
        $insertId = $ok ? $con->insert_id : false;

        $stmt->close();
        $con->close();

        return $insertId;
    }
}
?>