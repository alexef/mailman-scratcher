<?php

require_once 'lib.php';
require_once 'config.php';

$perpage      = 50;
$post_perpage = 8;

$list   = isset($_GET['list'])
         ? strtolower($_GET['list'])
         : '';

$list   = get_list($lists, $list);
$config = load_config();
$ids    = load_ids();

$action = isset($_GET['action'])
         ? $_GET['action']
         : '';

$id     = isset($_GET['id'])
         ? $_GET['id']
         : '';

$page   = isset($_GET['page'])
         ? $_GET['page']
         : 0;

switch ($action) {
case 'hide':
    // TODO check login
    if (!is_object($config)) {
        $config = object();
    }

    if (!is_array($config->hidden)) {
        $config->hidden = array();
    }

    if (!in_array($id, $config->hidden)) {
        $config->hidden[] = $id;
    }
    
    save_config($config);
    echo 'Ascuns! <a href="./">&laquo; Înapoi</a>';
    break;
    
case 'index':
    $title   = 'Listing';
    $banner  = '<h2>Articole din lista ' . $list['title'] .'</h2>';
    $banner .= '<h3>Universitatea din Bucure&#x219;ti</h3>';

    the_header();

    echo '<h1>Lista ' . $list['title'] . '</h1>';
    echo '<ul class="visible">';

    for ($i = 0; $i < $perpage; $i++) {
      $post = load_post($ids[$i]);

      echo '<li><a href="./?list=' . $list['name'] . '&action=view&id=' . $post['id'] . '">';
      echo $post['title'];
      echo '</a></li>';
    }

    echo "</ul><!-- .visible -->";
    break;
    
case 'view':
    if (empty($ids)) {
        break;
    }

    if ($id == '') {
        $id = $ids[0];
    }

    $post = load_post($id);
    
    the_header();
    the_post($post, $ids);
    the_footer();
    break;

case 'page':
    $title   = 'Listing posts';
    $banner  = '<h2>Arhiva ' . $list['title'] . '</h2>';
    $banner .= '<h3>Universitatea din Bucure&#x219;ti</h3>';

    $start = $page * $post_perpage;

    the_header();
    the_page_nav($page, $post_perpage, $ids);

    for ($i = $start; $i < ($start + $post_perpage) && $i < count($ids); $i++) {
        $post = load_post($ids[$i]);

        the_post($post, $ids, false);
    }

    the_footer();
    break;

default:
    $title   = 'Arhive';
    $banner  = '<h2>&#x218;coala Doctoral&#259; de Sociologie</h2>';
    $banner .= '<h3>Universitatea din Bucure&#x219;ti</h3>';

    the_header();

    echo '<h2>Selectati o lista: </h2>';
    echo '<ul class="visible">';

    foreach ($lists as $list) {
        echo '<li><a href="./?list=' . $list['name'] . '&amp;action=page">' . $list['title'] . '</a></li>';
    }

    echo '</ul><!-- .visible -->';
    the_footer();
}
