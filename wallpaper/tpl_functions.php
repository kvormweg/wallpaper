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
function _tpl_mainmenu() {
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
  $ff = TRUE;
  if($conf['tpl'][$tpl]['usemenufile']) {
  	if($conf['tpl'][$tpl]['menufilename']) {
  		$menufilename = $conf['tpl'][$tpl]['menufilename'];
  	} else {
  		$menufilename = 'menu';
		}
		$filepath = wikiFN($menufilename);
		if(!file_exists($filepath)) {
			$ff = FALSE;
		} else {
  		_tpl_parsemenufile($data, $menufilename, $start);
		} 
  } 
  if(!$conf['tpl'][$tpl]['usemenufile'] or ($conf['tpl'][$tpl]['usemenufile'] and !$ff)) {
  	search($data,$conf['datadir'],'search_universal',$opts);
  }
  $data2 = array();
	$first = true;
  foreach($data as $item) {
    if($conf['tpl'][$tpl]['cleanindex']) {
      if(strpos($item['id'],'playground') !== false 
         or strpos($item['id'], 'wiki') !== false) {
        continue;
      }
    }
    if(strpos($item['id'],$menufilename) !== false and $item['level'] == 1) {
    	continue;
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
      $ret .= html_wikilink($item['id'].':'.$conf['start'], $item['title']);
    } else {
      $ret .= html_wikilink($item['id'].':', $item['title']);
    }
  } else {
    $ret .= html_wikilink(':'.$item['id'], $item['title']);
  }
  return $ret;
}

function _tpl_parsemenufile(&$data, $filename, $start) {
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
function _tpl_pageinfo(){
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
