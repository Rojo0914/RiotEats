<?php
include_once("bbdd/dataBase.php");
include_once("models/Categoria.php");

class CategoriaDAO {
    
    public static function store(Categoria $categoria): bool {

        $con = DataBase::connect();
        
        // Almacena el comando SQL
        $sql = "INSERT INTO categorias (nombre, slug, descripcion, activa) VALUES (?, ?, ?, ?)";
        
        // Aviso a la bbdd de que se va a hacer un insert
        $stmt = $con->prepare($sql);
        
        // Uso de los GETTERS de Categoria
        $nombre = $categoria->getNombre();
        $slug = $categoria->getSlug();
        $descripcion = $categoria->getDescripcion();
        $activa = $categoria->isActiva() ? 1 : 0; // $activa: Guarda la conversión del valor booleano a int para MySQL

        // Pone las variables diciendo si es un string
        $stmt->bind_param("sssi", $nombre, $slug, $descripcion, $activa);
        
        // Decimos si se ha ejecutado bien, o si ha habido un error
        $exito = $stmt->execute();
        
        // Cierra $stmt para liberar memoria en MySQL
        $stmt->close();
        
        // Cerramos la conexión con la bbdd
        $con->close();

        // Devuelve el resultado del envío
        return $exito;
    }

    
    public static function getAll(): array {

        $con = DataBase::connect();
        
        // $sql: Selecciona todo de la tabla categorias
        $sql = "SELECT * FROM categorias";
        
        // $result: Almacena el conjunto de resultados (mysqli_result) devueltos por la consulta SQL
        $result = $con->query($sql);

        // $categorias: Inicializa un array vacío para guardar los objetos de cada categoría
        $categorias = [];
        
        while ($row = $result->fetch_assoc()) {
            $categorias[] = new Categoria(
                $row['nombre'],
                $row['slug'],
                $row['descripcion'],
                (bool)$row['activa'],
                (int)$row['id']
            );
        }

        // Cierre de la conexión a la base de datos
        $con->close();

        // Devuelve el listado completo de objetos Categoria
        return $categorias;
    }
}
?>