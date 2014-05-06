<p>
    <label for="<?php echo $this->get_field_id('page'); ?>"><?php _e( 'Select Page:' ); ?></label>
    <select class="widefat" id="<?php echo $this->get_field_id('page'); ?>" name="<?php echo $this->get_field_name('page'); ?>">
    <?php
    foreach ( $posts as $p ) {
        echo '<option value="' . intval( $p->ID ) . '"' . selected( $instance['page'], $p->ID, false ) . '>' . $p->post_title. "</option>\n";        }
    ?>
    </select>
</p>
<p>
    <label for="<?php echo $this->get_field_id('view'); ?>"><?php _e( 'Select View:' ); ?></label>
    <select class="widefat" id="<?php echo $this->get_field_id('view'); ?>" name="<?php echo $this->get_field_name('view'); ?>">
        <option value="default" <?php echo selected( $instance['view'], 'default', false ); ?>><?php _ex('Default'); ?></option>
        <option value="extended" <?php echo selected( $instance['view'], 'extended', false ); ?>><?php _ex('Extended'); ?></option>
    </select>        
</p>
        