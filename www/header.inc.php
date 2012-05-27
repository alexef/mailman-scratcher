<!DOCTYPE html>
<html lang="ro">
    <head>
        <meta charset="ISO-8859-2">
        <title><?php echo the_title(); ?></title>
        <link href="assets/css/style.css" rel="stylesheet" type="text/css">
    </head>

    <body>
        <div class="container">
            <div id="brand">
                <a href="./" id="logo" rel="Home" title="&#x218;coala Doctoral&#259; de Sociologie">
                    <img alt="&#x218;coala Doctoral&#259; de Sociologie" class="logo" src="assets/img/logo.png">
                </a><!-- #logo -->

                <div id="site-info">
                    <h1 id="site-name">
                        &#x218;coala Doctoral&#259; de Sociologie
                    </h1><!-- #site-name -->

                    <h2 id="site-description">
                        Universitatea din Bucure&#x219;ti
                    </h2><!-- #site-description -->
                </div><!-- #site-info -->
            </div><!-- #brand -->

            <hr>

            <?php echo the_banner(); ?>

            <hr>

            <?php if ($action != 'page' && $action != '') : ?>
                <a class="list-label" href="./?list=<?php echo $list['name']; ?>&amp;action=index">
                    &laquo; &Icirc;napoi la <?php echo $list['title'] ?>
                </a><!-- .list-label -->
            <?php endif; ?>
