# DokuCMS Template
## General Description

This template (templates in Dokuwiki are equivalent to skins in other wiki systems) can be of value if you use Dokuwiki as a lightweight CMS. It is best suited for a wiki with read access for everybody and restricted write access. It is not suited for a totally closed wiki. From version 0.1.1 onward it can be used for a totally open wiki.

The template offers a sidebar with either an index or content from a wiki page. It is based on the "Arctic" and the default template.

## License

This template is published under the GNU General Public Licence (GPL) V2.
Special Thanks

Thanks to Andreas Gohr and fellow programmers for creating such a wonderful tool.
Thanks to Michael Klier for the Arctic template
Thanks to the users of this template for hints and ideas how to make this template more usable.

## Installation

Unpack the file somewhere. Move the directory named `dokucms` into the `/lib/tpl` directory of your Dokuwiki installation.

In the configuration plugin chose dokucms as your template.

## Suggested Dokuwiki Configuration

This template works best with "Hierarchical breadcrumbs" switched on and the corresponding "Number of breadcrumbs" set to "0".

Turning "Use first heading for pagenames" on results in a nicer index.

## Configuration

Two general looks are included and may be switched by editing the style.ini.
````
;dokucms special variables
;---------------------------------------------------------------------------
__content_width__ = 60em;
; red look
; activate either these four lines or the four lines below
;__text__           = "#300"
;__imgblend__ = blend_red.png
;__yourlogo__ = flower_red.png
;__background_alt__ = "#911"

; blue look
; activate either these four lines or the four lines above
__text__           = "#003"
__yourlogo__ = flower_blue.png
__imgblend__ = blend_blue.png
__background_alt__ = "#119"
````
By commenting either the "red" or "blue" lines (with a semicolon!) you can switch the looks.

From version 0.4.4 you can define the maximum width (`__content_width__`) of the content area (page width minus sidebar column). All CSS distance units apply. The smallest possible value is 700 pixel (55em). The minimum width of the entire page is 950 pixel. The maximum value can be as high as you like. High values result in long lines on large monitors which can be hard to read.

If you want to use another logo, prepare a picture with max dimension of 230 pixel width and 92 pixel height. Copy this picture into the image folder of the template. Edit the (activated) __yourlogo__ line in the style.ini accordingly.

The template has six configuration options:

* sidebar content can be either "file" or "index". This option controls the content of the sidebar. The default option is "index".
  * file: Displays the content of a file in the sidebar. The file has to be named sidebar. You can define a sidebar for every namespace in your wiki. If a namespace contains no sidebar, the file from the namespace above will be used. If the file option is chosen and no sidebar file exists, the sidebar will remain empty.
  * index: The sidebar contains the index of all pages in your wiki, alphabetically sorted. Namespaces are displayed as closed sublist that open if you open a page in the namespace. Sidebar pages are always excluded from the index.
* clean index will remove the standard dokuwiki namespaces ('wiki' and 'playground') from the index if switched on (default: off) (for "index" sidebar content).
* clean index of further namespaces comma separated list of namespaces to be removed from the index if "clean index" is switched on (default: empty) (for "index" sidebar content).
* show edit button in sidebar: will show an edit button in the sidebar if you may edit the contents of the sidebar (default: on) (for "file" sidebar content)
* show backlink button: will show a backlink button in the bottom bar (default: off)
* show search box if logged in: will show a search box, if you are logged in (default: off)

## Update

Backup your DOKUWIKI_ROOT/lib/tpl/dokucms/style.ini. Unpack the file in DOKUWIKI_ROOT/lib/tpl/. This will overwrite the current template with the new version. Overwrite the delivered style.ini with your backup copy, if there were no changes to style.ini content. I will explicitly list style.ini changes in the changelog.
Otherwise you will have to check for differences between your backup copy and the default style.ini. I recommend meld for that purpose.
Recommendation

Clean the cache (DOKUWIKI_ROOT/data/cache) after installation or update of this template.

## Browser compatibility

This template works well with current Opera, Firefox, Google Chrome and Internet Explorer (from Version 8 upwards). Safari has a problem displaying the color blend in the page header correctly, but works otherwise. With IE6 all functions work, but there are some display faults.
