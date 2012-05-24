<?php
/**
 * Mailman-scratcher Web viewer
 *
 */

function load_config()
{
  global $list;

  $data = @file_get_contents($list['name'] . '/config.json');
  return json_decode($data);
}

function save_config($data)
{
  global $list;
  @file_put_contents($list['name'] .'/config.json', json_encode($data));
}

function load_ids()
{
  global $config, $list;
  
  $files = @glob($list['name'] .'/articles/*.html');
  $ids = array();
  foreach($files as $f) {
    $str = basename($f);
    $id = substr($str, 0, strpos($str, '.'));
    $ids[] = $id;
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
	global $list;

  $post = array();
  $lines = @file($list['name'].'/articles/'.$id.'.html');
  for ($i = 0; $i < 18; $i++)
    array_shift($lines);
  for ($i = 0; $i < 4; $i++)
    array_pop($lines);
  
  $post['id'] = $id;  
  $post['content'] = implode('', $lines);
  
  $post['title'] = preg_match_all("/<H1>(.*)<\/H1>/", $post['content'], $matches); 
  $post['title'] = $matches[1][0]; 
  
  // fix display
  $post['content'] = str_replace('<H1>','<H2>', $post['content']);
  $post['content'] = str_replace('</H1>','</H2>', $post['content']);

  return $post;
}

function the_post($post, $ids, $single=true)
{
  $id = $post['id'];
  if ($single)
	  the_nav($id, $ids);
  
  $content = $post['content'];
  $content = str_replace('<B>Cosima Rughinis</B>', '<B>DOCSOC</B>', $content);
  $content = str_replace('cosima.rughinis la sas.unibuc.ro', 'docsoc la sas.unibuc.ro', $content);

  echo $content;
  
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
	global $list;
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
    echo " <a id='nav-prev' href='?list=$list[name]&action=view&id=$prev'>&raquo;</a>";
  if ($next) 
    echo " <a id='nav-next' href='?list=$list[name]&action=view&id=$next'>&laquo;</a>";
  echo "</div>";
}
  
function the_page_nav($page, $perpage, $ids)
{
	global $list;
	$prev = null;
	$next = null;
	if ($page > 0)
		$prev = $page - 1;
	if (($page+1) * $perpage < count($ids))
		$next = $page + 1;

	if ($prev < 0)
		$prev = 0;

  echo "<div id='nav-container'>";
  if ($prev !== null) 
    echo " <a id='nav-prev' href='?list=$list[name]&action=page&page=$prev'>&raquo;</a>";
  if ($next) 
    echo " <a id='nav-next' href='?list=$list[name]&action=page&page=$next'>&laquo;</a>";
  echo "</div>";
}

function the_header()
{
  global $action;
  include('header.inc.php');
}

function the_footer()
{
  include('footer.inc.php');
}

function the_banner(){
  //returns the title for the banner
  global $banner;
  return $banner;
}

function get_list($lists, $name)
{
	foreach ($lists as $list) {
		if ($list['name'] == $name)
			return $list;
	}
	return array('name'=>'', 'title'=>'');
}
