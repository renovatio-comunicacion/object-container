(function ( $ ) {
    "use strict";

    $(function () {
        $(".selector_item").click(function(){
            $(".selector_item").parent().removeClass('active');
            $(this).parent().addClass('active');
            var selected_cat = $(this).attr('rel');
            if ( selected_cat != 0 )
            {
                $(".cat_content").hide();
                $(".cat_" + selected_cat).fadeIn('slow');
            } else {
                $(".cat_content").fadeIn('slow');
            }
           return false;
        });
    });
}(jQuery));