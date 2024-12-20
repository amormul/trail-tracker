<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?= $title?></title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"/>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
        <script src="https://kit.fontawesome.com/d16cee9f8d.js"></script>
        <link href="<?= DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'style.css' ?>" rel="stylesheet" />
    </head>
    <body>
        <?php include_once self::VIEWS_DIR . 'common' . DIRECTORY_SEPARATOR . 'header.php'?>
<!--        --><?php //include_once self::VIEWS_DIR . 'common' . DIRECTORY_SEPARATOR . 'ad.php' ?>
        <?php include_once self::VIEWS_DIR . 'pages' . DIRECTORY_SEPARATOR . $page . '.php'?>
        <?php include_once self::VIEWS_DIR . 'common' . DIRECTORY_SEPARATOR . 'footer.php'?>
        <script src="<?= DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'scripts.js' ?>"></script>
    </body>

</html>
