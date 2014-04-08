<?php
/**
 * DokuWiki Wallpaper Template
 *
 * @link:   http://wiki.splitbrain.org/wiki:tpl:templates
 * @author: Andreas Gohr <andi@splitbrain.org>
 * @author: Klaus Vormweg <klaus.vormweg@gmx.de>
 */

// must be run from within DokuWiki
if (!defined('DOKU_INC')) die();

// include custom template functions stolen from arctic template 
require_once(dirname(__FILE__).'/tpl_functions.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $conf['lang']?>"
 lang="<?php echo $conf['lang']?>" dir="<?php echo $lang['direction']?>">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>
    <?php tpl_pagetitle()?>
    [<?php echo strip_tags($conf['title'])?>]
  </title>

  <?php tpl_metaheaders()?>

  <link rel="shortcut icon" href="<?php echo DOKU_TPL?>images/favicon.ico" />

</head>

<body>
<div class="dokuwiki">
   <img id="fakebackground" src="<?php echo DOKU_TPL?>images/bg.jpg" alt="" />
  <?php html_msgarea()?>
  <div class="stylehead">
    <div class="header">
      <div class="pagename">
        <?php tpl_link(wl(),$conf['title'],'name="dokuwiki__top" id="dokuwiki__top" accesskey="h" title="[ALT+H]"')?>
      </div>

      <div class="clearer"></div>
	   <div class="mainmenu">
       <?php tpl_mainmenu('left'); ?>
      </div>
    </div>

    <?php if($conf['breadcrumbs']){?>
    <div class="breadcrumbs">
      <?php tpl_breadcrumbs()?>
    </div>
    <?php }?>

    <?php if($conf['youarehere']){?>
    <div class="breadcrumbs">
      <?php tpl_youarehere() ?>
    </div>
    <?php }?>

  </div>
  <?php flush()?>
  <?php if($ACT != 'diff' && $ACT != 'edit' && $ACT != 'preview' && $ACT != 'admin' && $ACT != 'login' && $ACT != 'logout' && $ACT != 'profile' && $ACT != 'revisions') { ?>
  <div class="wrap">
     <div class="page">
       <?php tpl_content(); ?>
     </div>
  </div>
  <?php } else { ?>
  <div class="wrap">
     <div class="page" style="margin-left:0; max-width: 78em;">
       <?php tpl_content(); ?>
     </div>
  </div>
  <?php } ?>
  <?php flush()?>

  <div class="stylefoot">
     <?php if($ACT != 'diff' && $ACT != 'edit' && $ACT != 'preview' && $ACT != 'admin' && $ACT != 'login' && $ACT != 'logout' && $ACT != 'profile' && $ACT != 'revisions') { ?>

    <div class="meta">
     <div class="homelink">
  		   <a href="http://wiki.splitbrain.org/wiki:dokuwiki" title="Driven by DokuWiki"><img src="<?php echo DOKU_TPL; ?>images/button-dw.png" width="80" height="15" alt="Driven by DokuWiki" /></a>
   	 <a href="<?php echo DOKU_BASE; ?>feed.php" title="Recent changes RSS feed"><img src="<?php echo DOKU_TPL; ?>images/button-rss.png" width="80" height="15" alt="Recent changes RSS feed" /></a>
    </div>
    <?php wallpaper_pageinfo(); ?>
    </div>
    <?php } else { ?>
    <div class="meta">
     <div class="homelink">
  		   <a href="http://wiki.splitbrain.org/wiki:dokuwiki" title="Driven by DokuWiki"><img src="<?php echo DOKU_TPL; ?>images/button-dw.png" width="80" height="15" alt="Driven by DokuWiki" /></a>
   	 <a href="<?php echo DOKU_BASE; ?>feed.php" title="Recent changes RSS feed"><img src="<?php echo DOKU_TPL; ?>images/button-rss.png" width="80" height="15" alt="Recent changes RSS feed" /></a>
    </div>
    </div>
    <?php } ?>

    <div class="bar" id="bar__bottom">
       <div class="bar-left" id="bar__bottomleft">
           <?php tpl_button('admin')?>
   <?php if($ACT != 'login' && $ACT != 'logout') { ?>        
           <?php tpl_button('login')?>&nbsp;
   <?php }?>
   <?php if($_SERVER['REMOTE_USER']){ ?>
           <?php tpl_button('subscription')?>
           <?php tpl_button('profile')?>
           <?php tpl_button('history')?>
   <?php }?>
          &nbsp;
       </div>
       <div class="bar-right" id="bar__bottomright">
   <?php if(!$_SERVER['REMOTE_USER'] && $ACT != 'login' && $ACT != 'logout'){ ?> 
     <?php if(!$conf['tpl']['wallpaper']['showsearch']) { ?>  
       <?php tpl_searchform()?>
     <?php }?>
   <?php }?>
     <?php if($conf['tpl']['wallpaper']['showsearch']) { ?>  
           <?php tpl_searchform()?>&nbsp;
     <?php } ?>
           <?php tpl_button('edit')?>
      </div>
      <div class="clearer"></div>
    </div>
  </div>
  <div class="no"><?php /* provide DokuWiki housekeeping, required in all templates */ tpl_indexerWebBug()?></div>
</div>
</body>
</html>
