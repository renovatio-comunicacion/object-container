<?php

/**
 * WordPress Widget Boilerplate
 *
 * The WordPress Widget Boilerplate is an organized, maintainable boilerplate for building widgets using WordPress best practices.
 *
 * @package   Renovatio Calendar
 * @author    panaewa <joaquin@renovatio-comunicacion.com>
 * @license   GPL-2.0+
 * @link      http://www.renovatio-comunicacion.com
 * @copyright 2014 Renovatio Comunicación SL
 *
 * @wordpress-plugin
 * Plugin Name:       Renovatio Calendar
 * Plugin URI:        http://www.renovatio-comunicacion.com
 * Description:       Renovatio Calendar
 * Version:           1.0.0
 * Author:            panaewa <joaquin@renovatio-comunicacion.com>
 * Author URI:        http://www.renovatio-comunicacion.com
 * Text Domain:       renovatio-widget-calendar
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/<owner>/<repo>
 */
// TODO: change 'Widget_Name' to the name of your plugin
class Renovatio_Calendar extends WP_Widget {

    /**
     * @TODO - Rename "widget-name" to the name your your widget
     *
     * Unique identifier for your widget.
     *
     *
     * The variable name is used as the text domain when internationalizing strings
     * of text. Its value should match the Text Domain file header in the main
     * widget file.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $widget_slug = 'renovatio-calendar';
    protected $widget_fields = array(
        'title' => 'Title',
        'post' => 'Post'
    );
    public $defaults;

    /* -------------------------------------------------- */
    /* Constructor
      /*-------------------------------------------------- */

    /**
     * Specifies the classname and description, instantiates the widget,
     * loads localization files, and includes necessary stylesheets and JavaScript.
     */
    public function __construct() {
        
        add_action('admin_print_styles', array($this, 'register_admin_styles'));
        add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'));

        // Register site styles and scripts
        add_action('wp_enqueue_scripts', array($this, 'register_widget_styles'));
        add_action('wp_enqueue_scripts', array($this, 'register_widget_scripts'));        

        $this->defaults = array(
            'title' => __('Renovatio:Events', 'dbem'),
            'scope' => 'future',
            'order' => 'ASC',
            'limit' => 5,
            'category' => 0,
            'format' => '#_EVENTLINK<ul><li>#_EVENTDATES</li><li>#_LOCATIONTOWN</li></ul>',
            'nolistwrap' => false,
            'orderby' => 'event_start_date,event_start_time,event_name',
            'all_events' => 0,
            'all_events_text' => __('all events', 'dbem'),
            'no_events_text' => __('No events', 'dbem')
        );
        $this->em_orderby_options = apply_filters('em_settings_events_default_orderby_ddm', array(
            'event_start_date,event_start_time,event_name' => __('start date, start time, event name', 'dbem'),
            'event_name,event_start_date,event_start_time' => __('name, start date, start time', 'dbem'),
            'event_name,event_end_date,event_end_time' => __('name, end date, end time', 'dbem'),
            'event_end_date,event_end_time,event_name' => __('end date, end time, event name', 'dbem'),
        ));
        $widget_ops = array('description' => __("Display a list of events on Events Manager.", 'dbem'));
        parent::WP_Widget(false, $name = 'Renovatio:Events', $widget_ops);
    }

// end constructor

    /**
     * Return the widget slug.
     *
     * @since    1.0.0
     *
     * @return    Plugin slug variable.
     */
    public function get_widget_slug() {
        return $this->widget_slug;
    }

    /* -------------------------------------------------- */
    /* Widget API Functions
      /*-------------------------------------------------- */

    /**
     * Outputs the content of the widget.
     *
     * @param array args  The array of form elements
     * @param array instance The current instance of the widget
     */
    public function widget($args, $instance) {

        $instance = array_merge($this->defaults, $instance);
        $instance = $this->fix_scope($instance); // depcreciate	

        $instance['owner'] = false;

        // Check if there is a cached output
        $cache = wp_cache_get($this->get_widget_slug(), 'widget');

        if (!is_array($cache))
            $cache = array();

        if (!isset($args['widget_id']))
            $args['widget_id'] = $this->id;

        if (isset($cache[$args['widget_id']]))
            return print $cache[$args['widget_id']];

        // go on with your widget logic, put everything into a string and …
        extract($args, EXTR_SKIP);

        //$widget_string = $before_widget;
        $widget_string = "";

        //orderby fix for previous versions with old orderby values
        if (!array_key_exists($instance['orderby'], $this->em_orderby_options)) {
            //replace old values
            $old_vals = array(
                'name' => 'event_name',
                'end_date' => 'event_end_date',
                'start_date' => 'event_start_date',
                'end_time' => 'event_end_time',
                'start_time' => 'event_start_time'
            );
            foreach ($old_vals as $old_val => $new_val) {
                $instance['orderby'] = str_replace($old_val, $new_val, $instance['orderby']);
            }
        }

        $events = EM_Events::get(apply_filters('em_widget_events_get_args', $instance));
        
        ob_start();
        include( plugin_dir_path(__FILE__) . 'views/widget.php' );
        $widget_string .= ob_get_clean();
        //$widget_string .= $after_widget;

        $cache[$args['widget_id']] = $widget_string;

        wp_cache_set($this->get_widget_slug(), $cache, 'widget');
        
        print $widget_string;
    }

// end widget

    public function flush_widget_cache() {
        wp_cache_delete($this->get_widget_slug(), 'widget');
    }

    /**
     * Processes the widget's options to be saved.
     *
     * @param array new_instance The new instance of values to be generated via the update.
     * @param array old_instance The previous instance of values before the update.
     */
    public function update($new_instance, $old_instance) {

        foreach ($this->defaults as $key => $value) {
            if (!isset($new_instance[$key])) {
                $new_instance[$key] = $value;
            }
        }
        return $new_instance;
    }

// end widget

    /**
     * Generates the administration form for the widget.
     *
     * @param array instance The array of keys and values for the widget.
     */
    public function form($instance) {

        $instance = array_merge($this->defaults, $instance);
        $instance = $this->fix_scope($instance); // depcreciate
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'dbem'); ?>: </label>
            <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Number of events', 'dbem'); ?>: </label>
            <input type="text" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" size="3" value="<?php echo esc_attr($instance['limit']); ?>" />
        </p>
        <p>

            <label for="<?php echo $this->get_field_id('scope'); ?>"><?php _e('Scope', 'dbem'); ?>: </label><br/>
            <select id="<?php echo $this->get_field_id('scope'); ?>" name="<?php echo $this->get_field_name('scope'); ?>" >
                <?php foreach (em_get_scopes() as $key => $value) : ?>   
                    <option value='<?php echo $key ?>' <?php echo ($key == $instance['scope']) ? "selected='selected'" : ''; ?>>
                        <?php echo $value; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order By', 'dbem'); ?>: </label>
            <select  id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>">
                <?php
                echo $this->em_orderby_options;
                ?>
                <?php foreach ($this->em_orderby_options as $key => $value) : ?>   
                    <option value='<?php echo $key ?>' <?php echo (!empty($instance['orderby']) && $key == $instance['orderby']) ? "selected='selected'" : ''; ?>>
                        <?php echo $value; ?>
                    </option>
                <?php endforeach; ?>
            </select> 
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order', 'dbem'); ?>: </label>
            <select id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
                <?php
                $order_options = apply_filters('em_widget_order_ddm', array(
                    'ASC' => __('Ascending', 'dbem'),
                    'DESC' => __('Descending', 'dbem')
                ));
                ?>
                <?php foreach ($order_options as $key => $value) : ?>   
                    <option value='<?php echo $key ?>' <?php echo ($key == $instance['order']) ? "selected='selected'" : ''; ?>>
                        <?php echo $value; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category IDs', 'dbem'); ?>: </label>
            <input type="text" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>" size="3" value="<?php echo esc_attr($instance['category']); ?>" /><br />
            <em><?php _e('1,2,3 or 2 (0 = all)', 'dbem'); ?> </em>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('format'); ?>"><?php _e('List item format', 'dbem'); ?>: </label>
            <textarea rows="5" cols="24" id="<?php echo $this->get_field_id('format'); ?>" name="<?php echo $this->get_field_name('format'); ?>"><?php echo $instance['format']; ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('all_events'); ?>"><?php _e('Show all events link at bottom?', 'dbem'); ?>: </label>
            <input type="checkbox" id="<?php echo $this->get_field_id('all_events'); ?>" name="<?php echo $this->get_field_name('all_events'); ?>" <?php echo (!empty($instance['all_events']) && $instance['all_events']) ? 'checked' : ''; ?> >
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('all_events'); ?>"><?php _e('All events link text?', 'dbem'); ?>: </label>
            <input type="text" id="<?php echo $this->get_field_id('all_events_text'); ?>" name="<?php echo $this->get_field_name('all_events_text'); ?>" value="<?php echo (!empty($instance['all_events_text'])) ? $instance['all_events_text'] : __('all events', 'dbem'); ?>" >
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('no_events_text'); ?>"><?php _e('No events text', 'dbem'); ?>: </label>
            <input type="text" id="<?php echo $this->get_field_id('no_events_text'); ?>" name="<?php echo $this->get_field_name('no_events_text'); ?>" value="<?php echo (!empty($instance['no_events_text'])) ? $instance['no_events_text'] : __('No events', 'dbem'); ?>" >
        </p>
        <?php
    }

    function fix_scope($instance) {
        if (!empty($instance['time_limit']) && is_numeric($instance['time_limit']) && $instance['time_limit'] > 1) {
            $instance['scope'] = $instance['time_limit'] . '-months';
        } elseif (!empty($instance['time_limit']) && $instance['time_limit'] == 1) {
            $instance['scope'] = 'month';
        } elseif (!empty($instance['time_limit']) && $instance['time_limit'] == 'no-limit') {
            $instance['scope'] = 'all';
        }
        return $instance;
    }

// end form

    /* -------------------------------------------------- */
    /* Public Functions
      /*-------------------------------------------------- */

    /**
     * Loads the Widget's text domain for localization and translation.
     */
    public function widget_textdomain() {

        // TODO be sure to change 'widget-name' to the name of *your* plugin
        load_plugin_textdomain($this->get_widget_slug(), false, plugin_dir_path(__FILE__) . 'lang/');
    }

// end widget_textdomain

    /**
     * Fired when the plugin is activated.
     *
     * @param  boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
     */
    public function activate($network_wide) {
        // TODO define activation functionality here
    }

// end activate

    /**
     * Fired when the plugin is deactivated.
     *
     * @param boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
     */
    public function deactivate($network_wide) {
        // TODO define deactivation functionality here
    }

// end deactivate

    /**
     * Registers and enqueues admin-specific styles.
     */
    public function register_admin_styles() {

        wp_enqueue_style($this->get_widget_slug() . '-admin-styles', plugins_url('css/admin.css', __FILE__));
    }

// end register_admin_styles

    /**
     * Registers and enqueues admin-specific JavaScript.
     */
    public function register_admin_scripts() {

        wp_enqueue_script($this->get_widget_slug() . '-admin-script', plugins_url('js/admin.js', __FILE__), array('jquery'));
    }

// end register_admin_scripts

    /**
     * Registers and enqueues widget-specific styles.
     */
    public function register_widget_styles() {

        wp_enqueue_style($this->get_widget_slug() . '-widget-styles', plugins_url('css/widget.css', __FILE__));
        wp_enqueue_style($this->get_widget_slug() . '-widget-styles-timeline', plugins_url('css/timeline.css', __FILE__));
    }

// end register_widget_styles

    /**
     * Registers and enqueues widget-specific scripts.
     */
    public function register_widget_scripts() {

        wp_enqueue_script($this->get_widget_slug() . '-script-jsapi', "http://www.google.com/jsapi");
        wp_enqueue_script($this->get_widget_slug() . '-script-timeline', plugins_url('js/timeline.js', __FILE__), array('jquery'));
        wp_enqueue_script($this->get_widget_slug() . '-script-timeline-locale', plugins_url('js/timeline-locales.js', __FILE__), array('jquery'));
        wp_enqueue_script($this->get_widget_slug() . '-script', plugins_url('js/widget.js', __FILE__), array('jquery'));
        
    }

// end register_widget_scripts
}

// end class
// TODO: Remember to change 'Widget_Name' to match the class name definition
add_action('widgets_init', create_function('', 'register_widget("Renovatio_Calendar");'));
