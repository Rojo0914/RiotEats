<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Riot Eats</title>
    <link rel="stylesheet" href="<?= url ?>styles.css?v=1.5">
</head>
<body>
<div class="body">
    
    <?php include_once("views/header/header.php"); ?>

    <main>
        <?php include_once($view); ?>
    </main>

    <?php include_once("views/footer/footer.php"); ?>
</div>
</body>
</html>