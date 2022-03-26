<?php

use natoxCore\Config;
use natoxCore\Session;

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <!-- <link rel="icon" type="image/png" sizes="32x32" href="./assets/images/favicon.png"> -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?= ROOT ?>public/resources/img/favicon.png">

    <link rel="stylesheet" href="<?= ROOT ?>public/resources/fonts/remixicon.css">
    <link rel="stylesheet" href="<?= ROOT ?>public/resources/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= ROOT ?>public/resources/css/natox.css?v=<?= Config::get("version") ?>">
    <?php $this->content('head') ?>
    <title>Natox | <?= $this->getSiteTitle() ?></title>
</head>

<body>
    <div class="container">
        <?= Session::displaySessionAlerts(); ?>
        <?php $this->content('content') ?>
    </div>

    <script src="<?= ROOT ?>public/resources/js/bootstrap.bundle.min.js"></script>
    <script src="<?= ROOT ?>public/resources/js/natox.js?v=<?= Config::get("version") ?>"></script>
    <?php $this->content('footer') ?>
</body>

</html>