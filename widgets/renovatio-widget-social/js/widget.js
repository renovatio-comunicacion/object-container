(function ($) {
	"use strict";
	$(function () {
            // Place your public-facing JavaScript here
            $("#newsletterForm").live('submit',function(){
                if ( $("#NewsletterAccept:checked").length > 0 )
                {
                    $.ajax({              
                        url:ajaxurl,
                        type: 'POST',
                        data: {action:'newsletter_action',email:$("#NewsletterEmail").val(), clave:$("#NewsletterClave").val()},
                        success:function(res){
                            console.log(res);
                        }   
                    });
                }
                return false;                
            });
	});
}(jQuery));