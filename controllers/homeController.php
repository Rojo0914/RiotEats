<?php
include_once(__DIR__ . "/../models/ProductoDAO.php");

class HomeController {
    public function index() {
        $packs = ProductoDAO::getByCategoriaSlug("packs", "DESC", 5);
        $cock = ProductoDAO::getByCategoriaSlug("bebidas-con-alcohol", "ASC");

        $view = __DIR__ . "/../views/home/home.php";
        include_once 'views/main.php';
    }
}
?>