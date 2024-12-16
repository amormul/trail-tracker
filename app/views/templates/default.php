<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0 shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?></title>
    <link
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        rel="stylesheet" />
    <link href="<?= DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'styles.css' ?>" rel="stylesheet" />
</head>

<body>
    <?php include_once self::VIEWS_DIR . 'common' . DIRECTORY_SEPARATOR . 'header.php' ?>
    <?php include_once self::VIEWS_DIR . 'common' . DIRECTORY_SEPARATOR . 'ad.php' ?>
    <?php include_once self::VIEWS_DIR . 'pages' . DIRECTORY_SEPARATOR . $page . '.php' ?>
    <?php include_once self::VIEWS_DIR . 'common' . DIRECTORY_SEPARATOR . 'footer.php' ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="<?= DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'scripts.js' ?>"></script>
</body>

</html>
