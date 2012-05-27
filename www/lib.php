<?php

function load_config() {
    global $list;

    $data = @file_get_contents($list['name'] . '/config.json');
    return json_decode($data);
}

function save_config($data) {
    global $list;

    @file_put_contents($list['name'] . '/config.json', json_encode($data));
}

function load_ids() {
    global $config, $list;
  
    $files = @glob($list['name'] . '/articles/*.html');
    $ids = array();

    foreach ($files as $f) {
        $str = basename($f);
        $id = substr($str, 0, strpos($str, '.'));
        $ids[] = $id;
    }
  
    sort($ids);
  
    if (is_object($config) && is_array($config->hidden)) {
        foreach ($config->hidden as $del) {
            for ($i = 0; $i < count($ids); $i++) {
                if ($ids[$i] == $del) {
                    unset($ids[$i]);
                    break;
                }
            }
        }
    }

    return array_reverse($ids);
}

function load_post($id) {
    global $list;

    $post  = array();
    $lines = @file($list['name'] . '/articles/' . $id . '.html');

    for ($i = 0; $i < 18; $i++) {
        array_shift($lines);
    }

    $n = count($lines) - array_search('        <!--threads-->' . "\n", $lines) + 1;

    for ($i = 0; $i < $n; $i++) {
        array_pop($lines);
    }

    $n = array_search('<!--beginarticle-->' . "\n", $lines);

    $article_info = array_slice($lines, 0, 6);
    $article_body = array_slice($lines, $n);

    $article_info = implode('', $article_info);
    $article_body = implode('', $article_body);

    $article_begin = substr($article_body, 0, 25);
    $article_pre = substr($article_body, 25, strlen($article_body) - 60);
    $article_end = substr($article_body, strlen($article_body) - 35);

    $article_body = $article_begin . nl2br($article_pre) . $article_end;

    $content = $article_info . $article_body;

    $post['id'] = $id;

    $post['title'] = preg_match_all('/<H1>(.*)<\/H1>/', $content, $matches);
    $post['title'] = $matches[1][0];

    $post['content'] = $content;
    $post['content'] = str_replace('<B>',   '<strong>',  $post['content']);
    $post['content'] = str_replace('</B>',  '</strong>', $post['content']);
    $post['content'] = str_replace('<H1>',  '<h2>',      $post['content']);
    $post['content'] = str_replace('</H1>', '</h2>',     $post['content']);
    $post['content'] = str_replace('<I>',   '<em>',      $post['content']);
    $post['content'] = str_replace('</I>',  '</em>',     $post['content']);
    $post['content'] = str_replace('<PRE>',   '<p>',     $post['content']);
    $post['content'] = str_replace('</PRE>',  '</p>',    $post['content']);

    return $post;
}

function the_post($post, $ids, $single = true, $element_id = false) {
    $id = $post['id'];

    if ($single) {
        the_nav($id, $ids);
    }

    $post['title'] = preg_replace('#\[.*?\]\s*#s', '', $post['title']);

    $content = $post['content'];
    $content = str_replace('<strong>Cosima Rughinis</strong>', '<strong>DOCSOC</strong>', $content);
    $content = str_replace('cosima.rughinis la sas.unibuc.ro', 'docsoc la sas.unibuc.ro', $content);

    if ($element_id) {
        $content = preg_replace('/(<h2>).*?(<\/h2>)/', '<h2 id="' . $element_id . '">' . $post['title'] . '</h2>', $content);
    } else {
        $content = preg_replace('/(<h2>).*?(<\/h2>)/', '<h2>' . $post['title'] . '</h2>', $content);
    }

    echo $content;
  
    the_actions($id);
}

function the_actions($id) {
    //echo '<a href="?action=hide&amp;id=' . $id . '" id="hide">Hide</a>';
}

function the_title() {
    global $post, $title;

    if (isset($post)) {
        echo $post['title'];
    } else {
        echo $title;
    }
}

function the_nav($id, $ids) {
    global $list;

    $prev = null;
    $next = null;

    for ($i = 0; $i < count($ids); $i++) {
        if ($i < count($ids) - 1) {
            $next = $ids[$i + 1];
        }

        if ($ids[$i] == $id) {
            break;
        }

        $prev = $ids[$i];
    }
 
    echo '<div id="nav-container">';

    if ($prev) {
        echo ' <a id="nav-prev" href="?list=' . $list['name'] . '&amp;action=view&amp;id=' . $prev . '">&raquo;</a>';
    }

    if ($next) {
        echo ' <a id="nav-next" href="?list=' . $list['name'] . '&amp;action=view&amp;id=' . $next . '">&laquo;</a>';
    }

    echo '</div><!-- #nav-container -->';
}
  
function the_page_nav($page, $perpage, $ids) {
    global $list;

    $prev = null;
    $next = null;

    if ($page > 0) {
        $prev = $page - 1;
    }

    if (($page+1) * $perpage < count($ids)) {
        $next = $page + 1;
    }

    if ($prev < 0) {
        $prev = 0;
    }

    echo "<div id='nav-container'>";

    if ($prev !== null) {
        echo ' <a id="nav-prev" href="?list=' . $list['name'] . '&amp;action=page&amp;page=' . $prev . '">&raquo;</a>';
    }

    if ($next) {
        echo ' <a id="nav-next" href="?list=' . $list['name'] . '&amp;action=page&amp;page=' . $next . '">&laquo;</a>';
    }

    echo '</div><!-- #nav-container -->';
}

function the_header() {
    global $action, $list;

    include 'header.inc.php';
}

function the_footer() {
    include 'footer.inc.php';
}

function the_banner() {
    global $banner;

    return $banner;
}

function get_list($lists, $name) {
    foreach ($lists as $list) {
        if ($list['name'] == $name) {
            return $list;
        }
    }

    return array(
        'name'  => '',
        'title' => ''
    );
}

?>
