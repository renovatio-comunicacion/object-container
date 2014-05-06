<script>
    (function($) {
        "use strict";
        $(function() {
            var _custom_media = true,
                _orig_send_attachment = wp.media.editor.send.attachment;

            $('.insertMediaRenovatio').click(function(e) {
                var send_attachment_bkp = wp.media.editor.send.attachment;
                var button = $(this);
                var id = button.attr('id').replace('_button', '');
                _custom_media = true;
                wp.media.editor.send.attachment = function(props, attachment){
                    if ( _custom_media ) {
                        $("#"+id).val(attachment.url);
                    } else {
                        return _orig_send_attachment.apply( this, [props, attachment] );
                    };
                };

                wp.media.editor.open(button);
                return false;
            });

            $('.add_media').on('click', function(){
                _custom_media = false;
            });
        });
    }(jQuery));    
</script>
<p>
    <label for="<?php echo $this->get_field_id('image'); ?>"><?php _e( 'Select Image:' ); ?></label>
    
    <input type="text" 
           class="widefat" 
           id="<?php echo $this->get_field_id('image'); ?>" 
           value="<?php echo $instance['image']; ?>"
           name="<?php echo $this->get_field_name('image'); ?>"/>

    <input class="button button-primary button-medium insertMediaRenovatio"
           name="<?php echo $this->get_field_id('image'); ?>_button" 
           id="<?php echo $this->get_field_id('image'); ?>_button" 
           value="Media Library" />
</p>

<p>
    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
    <input type="text" 
           class="widefat" 
           id="<?php echo $this->get_field_id('title'); ?>" 
           value="<?php echo $instance['title']; ?>"
           name="<?php echo $this->get_field_name('title'); ?>"/>
</p>

<p>
    <label for="<?php echo $this->get_field_id('description'); ?>"><?php _e( 'Description:' ); ?></label>
    <textarea type="text" 
           class="widefat" 
           id="<?php echo $this->get_field_id('description'); ?>" 
           name="<?php echo $this->get_field_name('description'); ?>"><?php echo $instance['description']; ?></textarea>
</p>

<p>
    <label for="<?php echo $this->get_field_id('author'); ?>"><?php _e( 'Select Author:' ); ?></label>

    <select class="widefat" id="<?php echo $this->get_field_id('author'); ?>" name="<?php echo $this->get_field_name('author'); ?>">
    <?php
    foreach ( $authors as $a ) {
        echo '<option value="' . intval( $a->ID ) . '"' . selected( $instance['author'], $a->ID, false ) . '>'.$a->data->display_name."</option>\n";        
    } ?>
    </select>
<p>
    
<p>
    <label for="<?php echo $this->get_field_id('category'); ?>"><?php _e( 'Select Category:' ); ?></label>

    <select class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>">
    <?php
    foreach ( $categories as $c ) {
        if ( $c->parent != '0' ) continue;
        echo '<option value="' . intval( $c->cat_ID ) . '"' . selected( $instance['category'], $c->cat_ID, false ) . '>'.$c->name."</option>\n";        
    } ?>    
    </select>
<p>    
    
<p>
    <label for="<?php echo $this->get_field_id('date'); ?>"><?php _e( 'Date:' ); ?></label>
    <input type="text" 
           class="widefat datepicker" 
           id="<?php echo $this->get_field_id('date'); ?>" 
           value="<?php echo $instance['date']; ?>"
           name="<?php echo $this->get_field_name('date'); ?>"/>
</p>    
    
<p>
    <label for="<?php echo $this->get_field_id('view'); ?>"><?php _e( 'Select View:' ); ?></label>
    <select class="widefat" id="<?php echo $this->get_field_id('view'); ?>" name="<?php echo $this->get_field_name('view'); ?>">
        <option value="default" <?php echo selected( $instance['view'], 'default', false ); ?>><?php _ex('Default'); ?></option>
        <option value="extended" <?php echo selected( $instance['view'], 'extended', false ); ?>><?php _ex('Extended'); ?></option>
    </select>        
</p>
        