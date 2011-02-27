<?php
/**
 * Mailman-scratcher Web viewer
 *
 */

require_once('lib.php');

$perpage = 50;

$config = load_config();
$ids = load_ids();

$action = isset($_GET['action'])? $_GET['action']:'';
$id = isset($_GET['id'])? $_GET['id']:'';

switch ($action) 
{
  case 'hide':
    // TODO check login
    if (!is_object($config)) 
      $config = object();
    if (!is_array($config->hidden))
      $config->hidden = array();
    if (!in_array($id, $config->hidden))
      $config->hidden[] = $id;
    
    save_config($config);
    echo "Hidden! <a href='./'>&laquo; back</a>";
    
    break;
    
  case 'index':
    $title = 'Listing';
    the_header();
    
    echo "<h1>Lista</h1>";
    echo "<ul class='visible'>";
    for ($i = 0; $i < $perpage; $i ++) {
      $post = load_post($ids[$i]);
      echo "<li><a href='./?action=view&id=".$post['id']."'>".$post['title']."</a></li>\n";
    }      
    echo "</ul>";
    break;
    
  case 'view':
  default:
    if ($id == '')
      $id = $ids[0];
    
    $post = load_post($id);
    
    the_header();
    the_post($post, $ids);
    the_footer();

}
