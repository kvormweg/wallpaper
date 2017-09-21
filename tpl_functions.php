<?php
/*
 * DokuWiki Template Wallpaper Functions - adapted from arctic template
 *
 * @license GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author Michael Klier <chi@chimeric.de>
 * @author Klaus Vormweg <klaus.vormweg@gmx.de>
 */

// must be run from within DokuWiki
if (!defined('DOKU_INC')) die();

/* prints the menu */
function _wp_tpl_mainmenu() {
  require_once(DOKU_INC.'inc/search.php');
  global $conf;
  global $ID;

/* options for search() function */
  $opts = array(
   'depth' => 0,
   'listfiles' => true,
   'listdirs'  => true,
   'pagesonly' => true,
   'firsthead' => true,
   'sneakyacl' => true
  );

  $dir = $conf['datadir'];
  $tpl = $conf['template'];
  if(isset($conf['start'])) {
    $start = $conf['start'];
  } else {
    $start = 'start';
  }

  $data = array();
  $ff = TRUE;
  if($conf['tpl'][$tpl]['menufilename']) {
    $menufilename = $conf['tpl'][$tpl]['menufilename'];
  } else {
    $menufilename = 'menu';
  }
  if($conf['tpl'][$tpl]['usemenufile']) {
    $filepath = wikiFN($menufilename);
    if(!file_exists($filepath)) {
      $ff = FALSE;
    } else {
      _wp_tpl_parsemenufile($data, $menufilename, $start);
    }
  }
  $data2 = array();
  if(!$conf['tpl'][$tpl]['usemenufile'] or ($conf['tpl'][$tpl]['usemenufile'] and !$ff)) {
    search($data,$conf['datadir'],'search_universal',$opts);
    $i = 0;
    $cleanindexlist = array();
    if($conf['tpl'][$tpl]['cleanindexlist']) {
       $cleanindexlist = explode(',', $conf['tpl'][$tpl]['cleanindexlist']);
       $i = 0;
       foreach($cleanindexlist as $tmpitem) {
         $cleanindexlist[$i] = trim($tmpitem);
         $i++;
       }
    }
    $first = true;
    $i = 0;
    $countroot = 0;
    foreach($data as $item) {
      if($conf['tpl'][$tpl]['hiderootlinks']) {
        $item2 = array();
        if($item['type'] == 'f' and !$item['ns'] and $item['id']) {
          if($first) {
            $item2['id'] = $start;
            $item2['ns'] = 'root';
            $item2['perm'] = 8;
            $item2['type'] = 'd';
            $item2['level'] = 1;
            $item2['open'] = 1;
            $item2['title'] = $conf['tpl'][$tpl]['rootmenutext'];
            $data2[$i] = $item2;
            $i++;
            $first = false;
          }
          $item['ns'] = 'root';
          $item['level'] = 2;
          $countroot++;
        }
      }
      if($conf['tpl'][$tpl]['cleanindex']) {
        if(strpos($item['id'],'playground') !== false
           or strpos($item['id'], 'wiki') !== false) {
          continue;
        }
        if(count($cleanindexlist)) {
          if(strpos($item['id'], ':')) {
            list($tmpitem) = explode(':',$item['id']);
          } else {
            $tmpitem = $item['id'];
          }
          if(in_array($tmpitem, $cleanindexlist)) {
            continue;
          }
        }
      }
      if($item['id'] == $menufilename) {
        continue;
      }
      if($item['id'] == $start or preg_match('/:'.$start.'$/',$item['id'])
         or preg_match('/(\w+):\1$/',$item['id'])) {
        continue;
      }
      $data2[$i] = $item;
      $i++;
    }
    if($countroot) {
      $pos = $i - $countroot + 2;
      $tmparr = array_splice($data2,$pos);
      $data2 = array_merge($tmparr, $data2);
    }
//    ksort($data2);
  } else {
    $data2 = $data;
  }
  // print_r($data2);
  echo html_buildlist($data2,'idx','_wp_tpl_list_index','_wp_tpl_html_li_index');
}

/* Index item formatter
 * Callback function for html_buildlist()
*/
function _wp_tpl_list_index($item){
  global $ID;
  global $conf;
  $ret = '';
  if($item['type']=='d'){
    if(@file_exists(wikiFN($item['id'].':'.$conf['start']))) {
      $ret .= html_wikilink($item['id'].':'.$conf['start'], $item['title']);
    } elseif(@file_exists(wikiFN($item['id'].':'.$item['id']))) {
      $ret .= html_wikilink($item['id'].':'.$item['id'], $item['title']);
    } else {
      $ret .= html_wikilink($item['id'].':', $item['title']);
    }
  } else {
    $ret .= html_wikilink(':'.$item['id'], $item['title']);
  }
  return $ret;
}

/**
 * Index List item
 *
 * This user function is used in html_buildlist to build the
 * <li> tags for namespaces when displaying the page index
 * it gives different classes to opened or closed "folders"
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 *
 * @param array $item
 * @return string html
 */
function _wp_tpl_html_li_index($item){
  global $INFO;

  $class = '';
  $id = '';

  if($item['type'] == "f"){
    // scroll to the current item
    return '<li class="level'.$item['level'].$class.'" '.$id.'>';
  } elseif($item['open']) {
    return '<li class="open">';
  } else {
    return '<li class="closed">';
  }
}


function _wp_tpl_parsemenufile(&$data, $filename, $start) {
  $ret = TRUE;
  $filepath = wikiFN($filename);
  if(file_exists($filepath)) {
    $lines = file($filepath);
    $i = 0;
    $lines2 = array();
// read only lines formatted as wiki lists
    foreach($lines as $line) {
      if(preg_match('/^\s+\*/', $line)) {
        $lines2[] = $line;
      }
      $i++;
    }
    $numlines = count($lines2);
    $oldlevel = 0;
// Array is read back to forth so pages with children can be found easier
// you do not have to go back in the array if a child entry is found
    for($i = $numlines - 1;$i >= 0; $i--) {
      if(!$lines2[$i]) {
        continue;
      }
      $tmparr = explode('*', $lines2[$i]);
      $level = intval(strlen($tmparr[0]) / 2);
      if($level > 3) {
        $level = 3;
      }
// ignore lines without links
      if(!preg_match('/\s*\[\[[^\]]+\]\]/', $tmparr[1])) {
        continue;
      }
      $tmparr[1] = str_replace(array(']','['),'',trim($tmparr[1]));
      list($id, $title) = explode('|', $tmparr[1]);
// ignore links to non-existing pages
      if(!file_exists(wikiFN($id))) {
        continue;
      }
      if(!$title) {
        $title = p_get_first_heading($id);
      }
      $data[$i]['id'] = $id;
      $data[$i]['ns'] = '';
      $data[$i]['perm'] = 8;
      $data[$i]['type'] = 'f';
      $data[$i]['level'] = $level;
      $data[$i]['open'] = 1;
      $data[$i]['title'] = $title;
// namespaces must be tagged correctly (type = 'd') or they will not be found by the
// html_wikilink function : means that they will marked as having subpages
// even if there is no submenu
      if(strpos($id,':') !== FALSE) {
        $nsarray = explode(':', $id);
        $pid = array_pop($nsarray);
        $nspace = implode(':',$nsarray);
        if($pid == $start) {
          $data[$i]['id'] = $nspace;
          $data[$i]['type'] = 'd';
        } else {
          $data[$i]['ns'] = $nspace;
        }
      }
      if($oldlevel > $level) {
        $data[$i]['type'] = 'd';
      }
      $oldlevel = $level;
    }
  } else {
    $ret = FALSE;
  }
  ksort($data);
  return $ret;
}

# wallpaper modified version of pageinfo
function _wp_tpl_pageinfo(){
  global $conf;
  global $lang;
  global $INFO;
  global $REV;
  global $ID;

  // return if we are not allowed to view the page
  if (!auth_quickaclcheck($ID)) { return; }

  // prepare date
  $date = dformat($INFO['lastmod']);

  // echo it
  if($INFO['exists']) {
    echo '<span class="resp">', $lang['lastmod'], '</span> ', $date;
    if($_SERVER['REMOTE_USER']) {
      if($INFO['editor']) {
        echo ' ',$lang['by'],' ', $INFO['editor'];
      } else {
        echo ' (',$lang['external_edit'],')';
      }
      if($INFO['locked']){
        echo ' &middot; ', $lang['lockedby'], ': ', $INFO['locked'];
      }
    }
    return true;
  }
  return false;
}

function _wp_tpl_bgimage() {
/*
 * Search for individual background images for pages or namespace
 */

  global $ID;
  $nsarr = explode(':',$ID);
  $i = count($nsarr);
  for($j = $i; $j > 0; $j--) {
    $ptarr = array_slice($nsarr, 0, $j);
    if($j == $i) {
      $sep = '';
    } else {
      $sep = '/'.$nsarr[($j-1)];
    }
    $pn = implode('/',$ptarr).$sep.'_bg.jpg';
    if(file_exists(DOKU_INC.'data/media/'.$pn)) {
      return ml(str_replace('/', ':', $pn));
    }
  }
  return DOKU_TPL.'images/bg.jpg';
}

/*
 * Hierarchical breadcrumbs
 *
 * This code was suggested as replacement for the usual breadcrumbs.
 * It only makes sense with a deep site structure.
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author Nigel McNie <oracle.shinoda@gmail.com>
 * @author Sean Coates <sean@caedmon.net>
 * @author <fredrik@averpil.com>
 * @todo   May behave strangely in RTL languages
 * @param string $sep Separator between entries
 * @return bool
 */
function _wp_tpl_youarehere($sep = ' » ') {
  global $conf;
  global $ID;
  global $lang;

  $lspace = $_SESSION[DOKU_COOKIE]['translationlc'];

  // check if enabled
  if(!$conf['youarehere']) return false;

  $parts = explode(':', $ID);
  $count = count($parts);

  echo '<span class="bchead">',$lang['youarehere'],' </span>';

  // always print the startpage
  if(!$lspace) tpl_pagelink(':'.$conf['start']);

  // print intermediate namespace links
  $part = '';
  for($i = 0; $i < $count - 1; $i++) {
    $part .= $parts[$i].':';
    $page = $part;
    if($page == $conf['start']) continue; // Skip startpage

    // output
    echo $sep;
    tpl_pagelink($page);
  }

  // print current page, skipping start page, skipping for namespace index
  resolve_pageid('', $page, $exists);
  if(isset($page) && $page == $part.$parts[$i]) return true;
  $page = $part.$parts[$i];
  if($page == $conf['start']) return true;
  echo $sep;
  tpl_pagelink($page);
  return true;
}
?>
