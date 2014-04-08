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
function tpl_mainmenu() {
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

  $ns = dirname(str_replace(':','/',$ID));
  if($ns == '.') $ns ='';
  $ns  = utf8_encodeFN(str_replace(':','/',$ns));

  $data = array();
  search($data,$conf['datadir'],'search_universal',$opts);
  $data2 = array();
	$first = true;
  foreach($data as $item) {
    if($conf['tpl'][$tpl]['cleanindex']) {
      if(strpos($item['id'],'playground') !== false or strpos($item['id'], 'wiki') !== false) {
        continue;
      }
    }
    if($conf['tpl'][$tpl]['hiderootlinks']) {
		$item2 = array();
    	if($item['type'] == 'f' and !$item['ns']) {
    		if($first) {
			  $item2['id'] ='start';
           $item2['ns'] = 'root';
           $item2['perm'] = 8;
           $item2['type'] = 'd';
           $item2['level'] = 1;
           $item2['open'] = 1;
           $item2['title'] = 'Start';
		     $data2[] = $item2;
		     $first = false;
         }
    		$item['ns'] = 'root';
    		$item['level'] = 2;
    	}	
    }
    if($item['id'] == $start or preg_match('/:'.$start.'$/',$item['id'])) {
      continue;
    }
    $data2[] = $item;
  }  
  echo html_buildlist($data2,'idx','_html_list_index','html_li_index');
}

/* Index item formatter
 * User function for html_buildlist()
*/
function _html_list_index($item){
  global $ID;
  global $conf;
  $ret = '';
  if($item['type']=='d'){
    if(@file_exists(wikiFN($item['id'].':'.$conf['start']))) {
      $ret .= html_wikilink($item['id'].':'.$conf['start']);
    } else {
      $ret .= html_wikilink($item['id'].':');
    }
  } else {
    $ret .= html_wikilink(':'.$item['id']);
  }
  return $ret;
}

# wallpaper modified version of pageinfo 
function wallpaper_pageinfo(){
  global $conf;
  global $lang;
  global $INFO;
  global $REV;
  global $ID;
  
  // return if we are not allowed to view the page
  if (!auth_quickaclcheck($ID)) { return; }
  
  // prepare date and path
  $date = strftime($conf['dformat'],$INFO['lastmod']);

  // echo it
  if($INFO['exists']){
    echo $lang['lastmod'];
    echo ': ';
    echo $date;
    if($_SERVER['REMOTE_USER']){
      if($INFO['editor']){
        echo ' '.$lang['by'].' ';
        echo $INFO['editor'];
      }else{
        echo ' ('.$lang['external_edit'].')';
      }
      if($INFO['locked']){
        echo ' &middot; ';
        echo $lang['lockedby'];
        echo ': ';
        echo $INFO['locked'];
      }
    }
    return true;
  }
  return false;
}
?>
