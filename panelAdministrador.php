<?php
// 1. Cargamos los parámetros globales de la aplicación desde la carpeta bbdd
require_once "bbdd/parameters.php";

// 2. Determinamos el nombre del controlador
if (isset($_GET['controller'])) {
    $nombre_controlador = $_GET['controller'] . 'Controller';
} else {
    // Si no viene controlador en la URL, usa el default_controller definido en parameters.php
    $nombre_controlador = default_controller . 'Controller';
}

// 3. Definimos la ruta del archivo del controlador
$file_controller = 'controllers/' . $nombre_controlador . '.php';

// 4. Verificamos si el archivo físico del controlador existe
if (file_exists($file_controller)) {
    
    // Incluimos el archivo para que PHP reconozca la clase
    require_once $file_controller;

    // Comprobamos que la clase existe dentro del archivo
    if (class_exists($nombre_controlador)) {
        $controlador = new $nombre_controlador();

        // 5. Determinamos la acción (método) a ejecutar
        if (isset($_GET['action']) && method_exists($controlador, $_GET['action'])) {
            $action = $_GET['action'];
            $controlador->$action();
        } elseif (!isset($_GET['action']) && method_exists($controlador, default_action)) {
            $action_default = default_action;
            $controlador->$action_default();
        } else {
            echo "<h1>Error 404: La acción requerida no existe.</h1>";
        }

    } else {
        echo "<h1>Error 404: La clase $nombre_controlador no está definida.</h1>";
    }

} else {
    echo "<h1>Error 404: El controlador $nombre_controlador no existe.</h1>";
}
?>