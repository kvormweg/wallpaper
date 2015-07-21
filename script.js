jQuery(function(){
  jQuery(document).ready(function() {
    var mmul = jQuery('div.mainmenu ul');
    jQuery(window).resize(function () {
      if(jQuery(window).width() > 899) {
        mmul.show();
      } else {
        mmul.hide();
      }
    });
    jQuery('a#hamburger').click(function(e) {
      e.preventDefault();
      e.stopPropagation();
      if(mmul.is(":hidden")) {
        mmul.slideDown(300);
      } else {
        mmul.slideUp(300);
      }
    });
  });
});
