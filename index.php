<?php
include_once("config/parameters.php");

function showError($code, $message)
{
    http_response_code($code);
    $errorCode = $code;
    $errorMessage = $message;
    $view = "views/error.php";
    include_once "views/main.php";
    exit;
}

if (!isset($_GET['controller'])) {
    header("Location: " . url . "?controller=" . default_controller);
    exit;
}

$controllerParam = $_GET['controller'];
$nombre_controller = $controllerParam . "Controller";
$controllerFile = "controllers/" . $nombre_controller . ".php";

if (!file_exists($controllerFile)) {
    showError(404, "No existe el controller " . $nombre_controller);
}

include_once $controllerFile;

if (!class_exists($nombre_controller)) {
    showError(500, "El fichero existe pero no contiene la clase " . $nombre_controller);
}

$controller = new $nombre_controller();

$action = default_action;
if (isset($_GET['action']) && $_GET['action'] !== '') {
    $action = $_GET['action'];
}

if (!is_callable([$controller, $action])) {
    showError(404, "No existe la action \"" . $action . "\" en " . $nombre_controller);
}

$controller->$action();
