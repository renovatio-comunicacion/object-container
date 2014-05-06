<p>
    <label for="<?php echo $this->get_field_id('post'); ?>"><?php _e( 'Select Post:' ); ?></label>
    <select class="widefat" id="<?php echo $this->get_field_id('post'); ?>" name="<?php echo $this->get_field_name('post'); ?>">
    <?php
    foreach ( $posts as $p ) {
        //var_dump($p->ID, $instance);die;
        echo '<option value="' . intval( $p->ID ) . '"' . selected( $instance['post'], $p->ID, false ) . '>' . $p->post_title. "</option>\n";        }
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
        