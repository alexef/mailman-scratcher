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
                <?php echo the_banner(); ?>
            </div><!-- #brand -->

            <?php if ($action != 'page' && $action != '') : ?>
                <a class="list-label" href="./?list=<?php echo $list['name']; ?>&amp;action=index">
                    <span id='list-label'></span>
                </a><!-- .list-label -->
            <?php endif; ?>
