# Wallpaper Template
## General Description

This template offers a semi transparent body over a background image. Its main feature is a static dropdown menu. The Menu supports up to three menu levels (that means three levels of namespaces).

The name was inspired by photo wallpapers popular in the seventies

## License

This template is published under the GNU General Public Licence (GPL) V2.

## Special Thanks

This template uses code from the default template and from Michael Klier’s Arctic template.

The CSS-code used in the dropdown menu was inspired by http://www.lwis.net/free-css-drop-down-menu/

The resizable fake background image was inspired by Chris Coyier (http://css-tricks.com/how-to-resizeable-background-image/)

Menu animation inspired by Brian Huisman (http://www.greywyvern.com/?post=337)

Hamburger menu without javascript inspired by Austin Wulf (http://www.sitepoint.com/pure-css-off-screen-navigation-menu/)

## Installation

Unpack the file into the `/lib/tpl` directory of your Dokuwiki installation. It will create a directory named `wallpaper`. You can also use the bundled plugin installer.

In the configuration plugin chose `wallpaper` as your template.

## Configuration

The basic configuration is done via style.ini. In addition to general dokuwiki style options there are two template specific options (under normal circumstances you will not have to change these):
````
;wallpaper special variables
;---------------------------------------------------------------------------
; width of menu items
__menuwidth__= "15em"
; text color inactive buttons
__color_disabled__="#ccc"
````
Here you can set the width of the submenus __menuwidth__ (CSS width units apply). The text color of inactive buttons __color_disabled__ should only be changed if your reconfigure the whole color scheme.

Further configuration can be done via the configuration dialog in the admin functions:

## Configuration options

* clean index: this option will remove the standard dokuwiki namespaces (‚wiki‘ and ‚playground‘) from the menu (default: off).
* clean index of further namespaces comma separated list of namespaces to be removed from the index if “clean index” is switched on (default: empty).
* show backlink button: will show a backlink button in the bottom bar (default: off).
* show search box if logged in: will show a search box, if you are logged in (default: off).
* create a submenu for pages in the root namespace: this option will put all pages in the top level namespace into a sub menu instead of listing each in the topmost menu level (default: off).
* submenu entry for the root namespace: menu entry text for the top level namepsace (default: "Start").
* the menu is based on a user-supplied file: this option will switch off the automatically generated menu and look for a menu file (the name can be configured in the next option) for the menu. For syntax of this file see below. (default: off).
* menu file name: configure the file name for the menu file (without .txt extension) (default: menu).

## Menu file syntax

Prepare a wiki file with a structure of unordered list of links. This structure may be three levels deep. These links will be used to construct the menu. The file must be saved in the root namepsace of your wiki.

Example:
````
=====My menu=====
  * [[first level item]]
  * [[first level item]]
    * [[second level item]]
    * [[second level item]]
      * [[third level item]]
  * [[first level item]]
````
Non-list lines (like the heading in the example) will be ignored. List lines without wikilinks will be ignored too. Links that do not point to existing files will be eliminated from the menu.

If a linked page is a start page of a namespace it will be always be shown in the menu as if this page has sub-entries in the menu even if that is not the case.

## Suggested Dokuwiki Configuration

This template works best with “Hierarchical breadcrumbs” switched on and the corresponding “Number of breadcrumbs” set to “0”.

Switching “Use first heading for pagenames” to “Always” or “Navigation” results in a nicer menu.

## Background Image

The background image can be changed if you drop a JPG-image named `bg.jpg` into the `images` directory inside the template directory.

The background image should be at least as wide as the biggest possible window width. The image should have a color gradient fading into black in the bottom.

The background for namespaces and/or individual pages may be changed by uploading JPG images. Picture files have to be named `(page name)_bg.jpg` or `(namespace name)_bg.jpg`. Replace `(page name)` or `(namespace name)` by the page name or namespace name the way it is converted by Dokuwiki (see the page URL). If you use namespaces background pictures must be uploaded into the corresponding folder for the namespace.

## Browser compatibility

This template should work well with all recent browsers that support HTML5 and CSS3.
