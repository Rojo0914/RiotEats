# Preguntas para entender Daw2mvc

Guía de estudio orientada a alumnos. El objetivo es comprender **el 100 % del proyecto actual** y pensar **ampliaciones futuras** sin memorizar código a ciegas.

**Cómo usarla**

1. Intenta responder mirando el código (no hace falta internet).
2. Prueba en el navegador las URLs y “qué pasaría si…”.
3. Las preguntas marcadas con ★ son especialmente recomendables en clase.
4. Las de la sección *Ampliaciones* se pueden usar como parte del projecto.

---

## A. Visión general y estructura del proyecto

1. ★ ¿Qué es el patrón MVC y qué carpeta del proyecto corresponde a cada letra (Model, View, Controller)?
2. ¿Para qué sirve `index.php` en este proyecto? ¿Es una vista, un modelo o algo distinto?
3. ¿Qué papel tienen `config/parameters.php` y `config/dataBase.php`? ¿Pertenecen al MVC “puro” o son infraestructura?
4. ¿Por qué hay un fichero `sql/init.sql` si el PHP ya “habla” con la base de datos?
5. ★ Dibuja (en papel o mentalmente) el árbol de carpetas y explica en una frase cada una: `controllers/`, `models/`, `views/`, `config/`, `sql/`.
6. ¿Qué diferencia hay entre una **entidad** (`Camiseta`, `Producto`) y un **DAO** (`CamisetaDAO`)?
7. ¿Por qué `Producto` es `abstract` y `Camiseta` no lo es?
8. ¿Qué significan las constantes `TYPE_CAMISETA` y `TYPE_PANTALONES` si de momento solo usamos camisetas?

---

## B. Flujo de la aplicación (petición → respuesta)

9. ★ Explica paso a paso qué ocurre cuando abres `http://localhost:8000/` (sin parámetros).
10. ★ Explica paso a paso qué ocurre con `?controller=producto&action=index`.
11. ¿Qué ocurre si entras a `?controller=producto` **sin** `action`? ¿Quién decide la action por defecto?
12. ¿Dónde se define `default_controller` y `default_action`? ¿Qué pasa si las cambias?
13. ★ Sigue el flujo completo de **crear un producto**: desde el enlace “Crear producto” hasta que vuelve a aparecer en el listado. Nombra ficheros y métodos.
14. Sigue el flujo de **ver un producto** (`show`) y el de **eliminar** (`destroy`). ¿En qué se parecen y en qué se diferencian?
15. ¿Qué hace `header('Location:...')` seguido de `exit`? ¿Qué pasaría si olvidas el `exit`?
16. ¿Por qué `store` y `destroy` **no** incluyen una vista (`main.php`) y en cambio hacen redirect?
17. ★ ¿Qué es el patrón Post-Redirect-Get (PRG) y dónde lo ves aplicado en este código?
18. Si dos alumnos abren la misma URL a la vez, ¿comparten variables PHP `$productos`? ¿Por qué?

---

## C. Front controller (`index.php`)

19. ★ ¿Qué es un *front controller* y por qué casi todo pasa por `index.php`?
20. ¿Cómo se construye el nombre de la clase a partir de `$_GET['controller']`? Ejemplo con `producto`.
21. ¿Cómo se decide qué fichero incluir en `controllers/`?
22. ¿Qué hace `showError()`? ¿Por qué usa `http_response_code` y después `include` de `main.php`?
23. ★ ¿Qué diferencia hay entre un error 404 y un error 500 en este `index.php`? Pon un ejemplo de cada uno.
24. ¿Qué hace `class_exists` y por qué se comprueba **después** de hacer `include_once` del fichero?
25. ¿Qué hace `is_callable([$controller, $action])`? ¿Por qué no basta con `method_exists`?
26. ★ ¿Qué pasaría si alguien pide `?controller=producto&action=__construct`? ¿Está permitido? ¿Por qué?
27. ¿Qué pasaría si el fichero `productoController.php` existiera pero la clase se llamara `ProductoCtrl`?
28. ¿Qué riesgo hay si no validas el nombre del `controller` antes de usarlo en una ruta de fichero? (piensa en `../`)
29. ¿Por qué `include_once` y no `include` o `require`? ¿Cuándo preferirías `require_once`?

---

## D. Controlador (`productoController`)

30. ★ ¿Qué responsabilidades tiene el controlador? ¿Qué **no** debería hacer (SQL directo, HTML largo…)?
31. Explica qué hace cada action: `index`, `create`, `store`, `show`, `destroy`.
32. ¿Por qué `create` solo muestra el formulario y `store` es quien guarda?
33. ★ ¿Para qué sirve `TALLAS_VALIDAS`? ¿Qué pasa si envías una talla `XYZ` por POST?
34. ¿Por qué se usa `trim` en el nombre y `(float)` en el precio?
35. ¿Qué comprueba `ctype_digit` en `show` y `destroy`? ¿Qué pasaría con `?id=3abc` o `?id=-1`?
36. ★ En `show`, si el `id` no existe en la BD, ¿qué hace el código? ¿Te parece buena UX? ¿Cómo lo mejorarías sin complicar mucho?
37. ¿Por qué `store` lee de `$_POST` y `destroy`/`show` de `$_GET`?
38. ★ ¿Qué problema de seguridad/usabilidad tiene eliminar con un enlace GET (`action=destroy`)? ¿Cómo lo harías con un formulario POST?
39. Si mañana añades `edit` y `update`, ¿qué haría cada uno y qué vistas necesitarías?

---

## E. Modelo, herencia y propiedades

40. ★ ¿Por qué los nombres de las propiedades (`nombre`, `talla`, `precio`, `id`) deben coincidir con las columnas de la tabla?
41. ¿Qué es un getter y un setter? ¿Por qué no se accede a `$producto->nombre` desde la vista?
42. ★ Explica el constructor de `Producto`: ¿por qué los parámetros por defecto son `null` y solo asigna si no son `null`?
43. ★ ¿Qué pasaría si el constructor hiciera siempre `$this->nombre = $nombre` con `$nombre = ''` por defecto, y luego usas `fetch_object("Camiseta")`?
44. ¿Qué hace `parent::__construct(...)` en `Camiseta`?
45. ¿`Camiseta` añade atributos propios ahora mismo? ¿Qué atributo nuevo pondrías (ej. `manga_corta`) y qué habría que tocar (clase, SQL, vistas, DAO)?
46. ¿Para qué sirve `setId` si el `id` lo genera la base de datos con `AUTO_INCREMENT`?

---

## F. DAO, mysqli y `fetch_object`

47. ★ ¿Qué significa DAO (Data Access Object)? ¿Qué ganas separándolo del controlador?
48. ¿Por qué los métodos del DAO son `static`? ¿Se podría hacer sin `static`? Ventajas/inconvenientes.
49. Explica línea a línea `getAll()`: `connect` → `prepare` → `execute` → `get_result` → bucle → `close`.
50. ★ ¿Qué hace exactamente `$result->fetch_object("Camiseta")`?
51. ★ ¿En qué orden ocurre: rellenar propiedades desde la fila SQL y llamar al constructor? ¿Por qué importa?
52. ¿Qué diferencia hay entre `fetch_object("Camiseta")` y `fetch_assoc()`? ¿Cuándo usarías cada uno?
53. En `getById` y `destroy`, ¿para qué sirve `bind_param("i", $id)`? ¿Qué evita respecto a concatenar el id en el SQL?
54. En `store`, ¿qué significan los tipos `"ssd"` de `bind_param`?
55. ★ ¿Por qué en `store` se copian `$nombre`, `$talla`, `$precio` a variables locales antes de `bind_param`?
56. ¿Qué devolvería `getById(99999)` si no hay fila? ¿Cómo lo representa el código (`?: null`)?
57. ¿Qué pasaría si en el SQL la columna se llamara `name` pero la propiedad de la clase fuera `$nombre`?
58. ¿Por qué se cierra `$stmt` y `$con` al final de cada método? ¿Qué pasaría a largo plazo si no los cierras?
59. ★ Compara responsabilidad: ¿quién conoce SQL, quién conoce HTML, quién conoce reglas de validación de talla?

---

## G. Vistas y layout (header / footer)

60. ★ ¿Qué es `views/main.php` y qué hace `<?php include_once $view; ?>`?
61. ¿Quién define la variable `$view` antes de incluir `main.php`?
62. ★ ¿Cómo pondrías un **header** y un **footer** comunes en toda la web sin repetir HTML en cada vista? Describe al menos dos formas (ej. bloques en `main.php`, o `header.php`/`footer.php`).
63. Si el menú de navegación debe salir en listado, create y show, ¿dónde lo colocarías y por qué?
64. ¿Qué ventaja tiene que `listado.php`, `create.php` y `show.php` **no** traigan `<html>` completo?
65. ★ ¿Qué hace `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')` en las vistas? ¿Qué riesgo evitas?
66. ¿Qué mostraría el listado si `$productos` es un array vacío? ¿Dónde está ese mensaje?
67. ¿Cómo cambiarías el título de la pestaña (`<title>`) según la página (Listado / Crear / Detalle) sin complicar demasiado?
68. Si mueves el CSS de `main.php` a `public/css/estilo.css`, ¿qué enlaces habría que añadir y dónde?

---

## H. Base de datos y configuración

69. ¿Qué crea `sql/init.sql`? ¿Por qué usa `DROP TABLE IF EXISTS`? ¿Es peligroso en producción?
70. ¿Qué tipo de dato es `talla` en SQL (`ENUM`) y qué implicación tiene al insertar valores?
71. ★ Si cambias la contraseña de MySQL, ¿qué fichero tocas? ¿Deberías subir esa contraseña a Git? ¿Por qué?
72. ¿Para qué sirve `set_charset("utf8mb4")`?
73. Si la base se llama distinto a `daw2mvc`, ¿qué hay que cambiar?
74. ¿Qué diferencia hay entre conectar a `localhost` y a `127.0.0.1`? (nivel introductorio)

---

## I. “¿Qué pasaría si…?” (depuración y comprensión)

75. ★ ¿Qué pasaría si renombras la clase a `ProductoController` (P mayúscula) pero la URL sigue siendo `controller=producto`?
76. ¿Qué pasaría si en `listado.php` escribes `$producto->nombre` en lugar de `getNombre()`?
77. ★ ¿Qué pasaría si borras `include_once 'views/main.php'` en `index()` y solo dejas el path en `$view`?
78. ¿Qué pasaría si en el formulario de create el `action` del form apunta a `action=index` en vez de `store`?
79. ¿Qué pasaría si `DataBase::connect()` falla? ¿Dónde se ve el error?
80. ★ ¿Qué pasaría si alguien entra a `?controller=producto&action=store` con GET (sin formulario)?
81. ¿Qué pasaría si dos productos se llaman igual? ¿Lo impide el código actual?
82. ¿Qué pasaría si el precio llega como `"12,50"` (coma) en vez de `"12.50"`?
83. ★ Abre `?controller=noexiste`. ¿Qué ves? ¿Qué código HTTP debería devolver?
84. Abre `?controller=producto&action=foo`. ¿Qué ocurre y por qué?

---

## J. MVC en la práctica (diseño)

85. ★ Si metes un `echo "<h1>Hola</h1>"` dentro de `CamisetaDAO`, ¿rompiendo qué principio del MVC estás?
86. Si metes la consulta SQL dentro de `productoController::index`, ¿qué problema tendrás cuando haya otro controlador que también liste camisetas?
87. ★ ¿La validación de tallas debería vivir en el controlador, en el modelo o en ambos? Razona una respuesta sencilla para clase.
88. ¿Por qué este proyecto no usa un framework (Laravel, Symfony) y aun así es útil para aprender?

---

## K. Ampliaciones: otro apartado (administrador) y otras vistas

89. ★ Quieres un área **administrador** con otra “cara” (otro layout). ¿Crearías `views/admin/main.php` distinto de `views/main.php`? ¿Cómo decidiría el controlador qué layout usar?
90. ★ Diseña a alto nivel un `adminController` (o `usuarioController`): ¿qué actions tendrías (`login`, `logout`, `dashboard`)?
91. ¿Cómo protegerías las rutas de admin para que un visitante normal no pueda entrar? (sesión PHP, idea general)
92. Si el admin lista productos pero con botones extra (editar, estadísticas), ¿reutilizas `CamisetaDAO::getAll()` o creas otro DAO? ¿Por qué?
93. ★ Propón la estructura de carpetas para: tienda pública + panel admin, manteniendo un solo `index.php`.
94. ¿Cómo harías que `?controller=admin&action=index` use CSS distinto sin copiar todas las vistas?
95. Si añades la entidad `Pantalones`, ¿qué ficheros nuevos creas y cuáles reutilizas gracias a `Producto`?
96. ¿Cómo implementarías `edit` + `update` reutilizando casi el mismo formulario que `create`?
97. ★ Cómo añadirías un campo `stock` (entero) de punta a punta: SQL → modelo → DAO → formulario → listado.
98. Cómo añadirías búsqueda/filtro por talla en el listado (`?talla=M`). ¿En qué capa pondrías el `WHERE`?
99. Cómo paginarías el listado (10 por página). ¿Qué parámetros GET usarías?
100. ★ Cómo sacarías header y footer a `views/partials/header.php` y `footer.php` y los incluirías desde `main.php`. Escribe el esqueleto mental del `main.php` resultante.

---

## L. Ampliaciones: calidad, seguridad y “siguiente nivel”

101. ¿Qué es XSS y dónde estáis ya protegiendo (parcialmente) en las vistas?
102. ¿Qué es SQL Injection y cómo os ayudan los *prepared statements*?
103. ¿Qué es un token CSRF (idea) y en qué formularios lo pondrías primero?
104. ¿Cómo mostrarías un mensaje “Producto creado correctamente” tras el redirect? (idea de *flash* con `$_SESSION`)
105. ¿Qué mejorarías del `README.md` para que un compañero monte el proyecto en 5 minutos?
106. ★ Si el proyecto crece a 10 controladores, ¿qué problema tiene cargar includes a mano? ¿Qué es un autoload (idea)?
107. ¿Qué testras a mano antes de dar por buena una práctica? Lista un checklist de 5 pruebas en el navegador.