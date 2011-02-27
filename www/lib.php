<?php
/**
 * Mailman-scratcher Web viewer
 *
 */

function load_config()
{
  $data = file_get_contents('config.json');
  return json_decode($data);
}

function save_config($data)
{
  file_put_contents('config.json', json_encode($data));
}

function load_ids()
{
  global $config;
  
  $files = glob('articles/*.html');
  $ids = array();
  foreach($files as $f) {
    $str = basename($f);
    $id = substr($str, 0, strpos($str, '.'));
    $ids["$id"] = $id;
  }
  
  sort($ids);
  
  if (is_object($config) and is_array($config->hidden))
    foreach ($config->hidden as $del) {
      for ($i = 0; $i < count($ids); $i ++)
        if ($ids[$i] == $del) {
          unset($ids[$i]);
          break;
        }
    }
    
  return array_reverse($ids);
}

function load_post($id)
{
  $post = array();
  $lines = file('articles/'.$id.'.html');
  for ($i = 0; $i < 18; $i++)
    array_shift($lines);
  for ($i = 0; $i < 4; $i++)
    array_pop($lines);
  
  $post['id'] = $id;  
  $post['content'] = implode('', $lines);
  
  $post['title'] = preg_match_all("/<H1>(.*)<\/H1>/", $post['content'], $matches); 
  $post['title'] = $matches[1][0]; 
  
  return $post;
}

function the_post($post, $ids)
{
  $id = $post['id'];
  the_nav($id, $ids);
  
  echo $post['content'];
  
  the_actions($id);
}

function the_actions($id) 
{
  //echo "<a href='?action=hide&id=$id' id='hide'>Hide</a>";
}

function the_title()
{
  global $post, $title;
  
  if (isset($post))
    echo $post['title'];
  else
    echo $title;
}

function the_nav($id, $ids)
{
  $prev = null;
  $next = null;
  for($i=0; $i < count($ids); $i ++) {
    if ($i < count($ids) - 1)
      $next = $ids[$i + 1];
  
    if ($ids[$i] == $id)
      break;
    $prev = $ids[$i];
  }
 
  echo "<div id='nav-container'>";
  if ($prev) 
    echo " <a id='nav-prev' href='?action=view&id=$prev'>&raquo;</a>";
  if ($next) 
    echo " <a id='nav-next' href='?action=view&id=$next'>&laquo;</a>";
  echo "</div>";
}
    

function the_header()
{
  include('header.inc.php');
}

function the_footer()
{
  include('footer.inc.php');
}
