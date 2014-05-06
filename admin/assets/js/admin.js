(function ( $ ) {
    "use strict";
    $(function () {
        var insertto = "", module_index = "", msf_mid = "", msf_title = "";
        // Button for MetaBox
        $("#btnAddModuleRenovatio").live('click', function(){
            console.log("#btnAddModuleRenovatio");
            insertto = '#'+this.rel+' .module';  
            module_index = this.rel;
            jQuery( "#dialog" ).dialog( 'option', 'width',940);
            jQuery( "#dialog" ).dialog( 'option', 'title','Modules');
            jQuery( "#dialog" ).dialog( "open" ).load("admin-ajax.php?action=oc_insert_content&modal=1&width=770&height=600");
            return false;
        });
        
        //Insert Module
        $('.insertModuleRenovatio').live('click',function(){
            console.log(".insertModuleRenovatio");
            msf_mid = jQuery(this).attr('rel');
            msf_title = this.title;
            
            var currentCat = $("#currentCat").val();
            if ( currentCat == '' )
                currentCat = 0;
           
            var data = jQuery(this).attr('data')==undefined?"":"&instance="+jQuery(this).attr('data');
            var datafield = jQuery(this).attr('datafield')==undefined?"":"&datafield="+jQuery(this).attr('datafield');          
            var post = "&post="+pageid;
            var data_inst = jQuery('#'+jQuery(this).attr('datafield')).val();
            jQuery( "#dialog" ).dialog( 'option', 'title','Module Settings');
            jQuery( "#dialog" ).html( 'Loading...');
            jQuery( "#dialog" ).dialog( 'option', 'width',700);
            jQuery( "#dialog" ).dialog('open').load("admin-ajax.php?action=oc_content_setting&cat=" + currentCat + "&modal=1&width=510&height=500&module="+msf_mid+data+datafield+post,{data_inst:data_inst});
            return false;
        });
        
        jQuery('#contentSettingRenovatio').live('submit',function(){
            console.log("#contentSettingRenovatio");
            jQuery(this).append('<img src="images/loading.gif" /> Saving...');
            jQuery(this).ajaxSubmit({              
                url:ajaxurl+'?action=oc_content_setting_data',
                success:function(res){
                    var d = new Date();
                    var z = d.getTime();
                    jQuery( insertto ).append('<li id="module_'+module_index+'_'+z+'" rel="'+module_index+'"><input type="hidden" id="modid_module_'+module_index+'_'+z+'" name="modules['+module_index+'][]" value="'+msf_mid+'" /><input type="hidden" name="modules_settings['+module_index+'][]" id="modset_module_'+module_index+'_'+z+'" value="'+res+'" /><h3><nobr class="title">'+msf_title+'</nobr><nobr class="ctl"><span class="handle"></span> <img src="'+base_theme_url+'/images/delete.png"  class="deleteModuleRenovatio" rel="#module_'+module_index+'_'+z+'" />&nbsp;<img class="insertModuleRenovatio" rel="'+msf_mid+'" datafield="modset_module_'+module_index+'_'+z+'" data="'+module_index+'|0" src="'+base_theme_url+'/images/settings.png" /></nobr></h3><div class="module-preview w3eden"><img src="images/loading.gif" /> Loading Preview...</div><div class="clear"</div></li>');
                    jQuery( insertto ).sortable({handle : '.handle', connectWith: "ul.module"});
                    jQuery( insertto ).disableSelection({handle : '.handle'});   
                    jQuery("#dialog").html("Loading...");                
                    jQuery('#dialog').dialog('close');
                    //jQuery('#module_'+module_index+'_'+z+' .module-preview').load(ajaxurl+'?action=get_module_preview',{mod:msf_mid, modinfo:res});
                }   
            });
            return false;
        });
        
        jQuery('#updateContentSettingsRenovatio').live('submit',function(){
            var datafield = jQuery(this).attr('datafield');
            jQuery(this).append('<img src="images/loading.gif" /> Saving...');
            jQuery(this).ajaxSubmit({              
                url:ajaxurl+'?action=oc_content_setting_data',
                success:function(res){
                    console.log(datafield);
                    jQuery('#'+datafield).val(res);
                    jQuery("#dialog").html("Loading...");
                    jQuery('#dialog').dialog('close');
                    var mod = datafield.replace("modset_","");
                    var msf_mid =   datafield.replace("modset_","modid_");
                    msf_mid = jQuery('#'+msf_mid).val();
                    //jQuery('#'+mod+' .module-preview').html('<img src="images/loading.gif" /> Updating Preview...')
                    //jQuery('#'+mod+' .module-preview').load(ajaxurl+'?page=minimax&action=get_module_preview',{mod:msf_mid, modinfo:res});
                }   
            });
            return false;
        });        
        
        //Delete Module              
        jQuery('.deleteModuleRenovatio').live('click',function(){ 
            console.log(".deleteModuleRenovatio");
            jQuery(this).after("<div class='besure' style='display:none;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;z-index:99999999;position:absolute;color:#000;border:5px solid rgba(0,0,0,0.4);'><div style='padding:10px;background:#fff;font-family:verdana;font-size:10px'>Are you sure? <a style='-webkit-border-radius: 15px;-moz-border-radius: 15px;border-radius: 15px;background:#800;padding:4px 8px 6px 8px;color:#fff;text-decoration:none;' href='#' onclick='jQuery(\".besure\").fadeOut(function(){jQuery(this).remove();jQuery(\""+jQuery(this).attr("rel")+"\").slideUp(function(){jQuery(this).remove();});});return false;'>y</a> <a href='' style='-webkit-border-radius: 15px;-moz-border-radius: 15px;border-radius: 15px;background:#080;padding:4px 8px 6px 8px;color:#fff;text-decoration:none;' onclick='jQuery(\".besure\").fadeOut(function(){jQuery(this).remove();mxdm=null;});return false;'>n</a></div></div>");
            jQuery('.besure').fadeIn();

        });        
    });
}(jQuery));