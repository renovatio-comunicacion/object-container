<?php

/**
 * Object_Container
 *
 * @package   Object_Container_Admin
 * @author    joaquin <joaquin@renovatio-comunicacion.com>
 * @license   GPL-2.0+
 * @link      http://www.renovatio-comunicacion.com
 * @copyright 2014 joaquin
 */

/**
 * Object_Container_Admin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-plugin-name.php`
 *
 * @package Object_Container_Admin
 * @author  joaquin <joaquin@renovatio-comunicacion.com>
 */
class Object_Container_Admin {

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;
    
    protected static $allowWidget = array('Renovatio_Post', 'Renovatio_Page', 'Renovatio_Free');

    /**
     * Slug of the plugin screen.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_screen_hook_suffix = array('page');

    /**
     * Initialize the plugin by loading admin scripts & styles and adding a
     * settings page and menu.
     *
     * @since     1.0.0
     */
    private function __construct() {

        /*
         * @TODO :
         *
         * - Uncomment following lines if the admin class should only be available for super admins
         */
        /* if( ! is_super_admin() ) {
          return;
          } */

        /*
         * Call $plugin_slug from public plugin class.
         */
        $plugin = Object_Container::get_instance();
        $this->plugin_slug = $plugin->get_plugin_slug();

        add_action('admin_init', array($this, 'init'));
    }

    public function init() {
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('save_post', array($this, 'save'));

        add_action('wp_ajax_oc_insert_content', array($this, 'oc_insert_content'));
        add_action('wp_ajax_oc_content_setting', array($this, 'oc_content_setting'));
        add_action('wp_ajax_oc_content_setting_data', array($this, 'oc_content_setting_data'));

        // Load admin style sheet and JavaScript.
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

        // Add the options page and menu item.
        add_action('admin_menu', array($this, 'add_plugin_admin_menu'));

        // Add an action link pointing to the options page.
        $plugin_basename = plugin_basename(plugin_dir_path(__DIR__) . $this->plugin_slug . '.php');
        add_filter('plugin_action_links_' . $plugin_basename, array($this, 'add_action_links'));
        
        
        /*
         * Define custom functionality.
         *
         * Read more about actions and filters:
         * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
         */
        //add_action( '@TODO', array( $this, 'action_method_name' ) );
        //add_filter( '@TODO', array( $this, 'filter_method_name' ) );            
    }

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        /*
         * @TODO :
         *
         * - Uncomment following lines if the admin class should only be available for super admins
         */
        /* if( ! is_super_admin() ) {
          return;
          } */

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Register and enqueue admin-specific style sheet.
     *
     * @since     1.0.0
     *
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_styles() {
        if (!isset($this->plugin_screen_hook_suffix)) {
            return;
        }

        $screen = get_current_screen();
        if ($this->plugin_screen_hook_suffix == $screen->id) {
            wp_enqueue_style($this->plugin_slug . '-admin-styles', plugins_url('assets/css/admin.css', __FILE__), array(), Object_Container::VERSION);
        }
    }

    /**
     * Register and enqueue admin-specific JavaScript.
     *
     * @since     1.0.0
     *
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_scripts() {
        if (!isset($this->plugin_screen_hook_suffix)) {
            return;
        }

        $screen = get_current_screen();
        if (in_array($screen->id, $this->plugin_screen_hook_suffix)) {
            wp_enqueue_script($this->plugin_slug . '-admin-script', plugins_url('assets/js/admin.js', __FILE__), array('jquery'), Object_Container::VERSION);
        }
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu() {

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         * @TODO:
         *
         * - Change 'manage_options' to the capability you see fit
         *   For reference: http://codex.wordpress.org/Roles_and_Capabilities
         */

        /*
          $this->plugin_screen_hook_suffix = add_options_page(
          __( 'Object Container Settings', $this->plugin_slug ),
          __( 'Object Container', $this->plugin_slug ),
          'manage_options',
          $this->plugin_slug,
          array( $this, 'display_plugin_admin_page' )
          );
         */


        /* register_post_type("oc_container", array(
          'labels' => array(
          'name' => __('Container'),
          'singular_name' => __('Container'),
          'add_new' => __('Add Container'),
          'add_new_item' => __('Add New Container'),
          'edit_item' => __('Edit Container'),
          'new_item' => __('New Container'),
          'view_item' => __('View Container'),
          'search_items' => __('Search Container'),
          'not_found' => __('No Container found'),
          'not_found_in_trash' => __('No Container found in Trash'),
          'parent_item_colon' => ''
          ),
          'public' => true,
          'publicly_queryable' => true,
          'has_archive' => true,
          'show_ui' => true,
          'query_var' => true,
          'rewrite' => array('slug' => 'oc_container', 'with_front' => true),
          'capability_type' => 'post',
          'hierarchical' => false,
          'menu_icon' => plugins_url() . '/object-container/assets/images/tab.png',
          'supports' => array('title', 'editor')
          //'taxonomies' => array('ptype')
          )
          ); */
    }

    /**
     * NOTE: Add the meta box container
     */
    public function add_meta_box($post_type) {
        $post_types = $this->plugin_screen_hook_suffix;     //limit meta box to certain post types
        if (in_array($post_type, $post_types)) {
            add_meta_box(
                    'oc_container'
                    , __('Tabs Container', $this->plugin_slug)
                    , array($this, 'render_meta_box_content')
                    , $post_type
                    , 'advanced'
                    , 'high'
            );
        }
    }

    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_content($currentPost) {

        // Add an nonce field so we can check for it later.
        wp_nonce_field('oc_inner_custom_box', 'oc_inner_custom_box_nonce');
        
        $cats = get_the_category($currentPost->ID);
        $currentCat = NULL;
        if ( is_array($cats) && count($cats) > 0 )
        {
            $currentCat = $cats[0];
        }

        // Use get_post_meta to retrieve an existing value from the database.
        $modules = unserialize(base64_decode(get_post_meta($currentPost->ID, '_oc_container', true)));
        
        $newModules = array();
        $base_theme_url = base_theme_url;
        foreach($modules as $mod )
        {
            $mod['olddata'] = $mod['data'];
            $mod['data'] = unserialize(base64_decode($mod['data']));
            $newData = array_shift(array_shift($mod['data']));
            $mod['data'] = $newData;
            
            if ( isset($mod['data']['page']) )
            {
                $mod['type'] = 'page';
            } else if ( isset($mod['data']['post']) ){
                $mod['type'] = 'post';
            } else {
                $mod['type'] = 'free';
            }
            
            if ( $mod['type'] == 'page' || $mod['type'] == 'post' )
            {
                if ( defined(ICL_LANGUAGE_CODE) )
                {
                    $id = icl_object_id( $mod['data'][$mod['type']], $mod['type'], TRUE, ICL_LANGUAGE_CODE );
                } else {
                    $id = $mod['data'][$mod['type']];
                }   

                $post = get_post($id);
            } else {
                $post = new WP_Post();
            }
            $mod['post'] = $post;
            $newModules[] = $mod;
        }
        include_once( 'views/metabox.php' );
    }

    public function oc_insert_content() {
        global $wp_widget_factory;

        $allowedWidget = array();
        foreach ($wp_widget_factory->widgets as $w_name => $w_definition) {
            if (in_array($w_name, array('Renovatio_Post', 'Renovatio_Page', 'Renovatio_Free'))) {
                $allowedWidget[$w_name] = $w_definition;
            }
        }
        include_once('views/insert-module.php');
        die();
    }

    public function oc_content_setting() 
    {  
        global $wp_widget_factory;
        
        $formID = isset($_REQUEST['instance']) ? "updateContentSettingsRenovatio" : "contentSettingRenovatio";
        
        $mod = $wp_widget_factory->widgets[$_GET['module']];
        
        $data_inst = @unserialize(@base64_decode($_POST['data_inst']));
        
        $datafield = isset($_REQUEST['datafield']) ? $_REQUEST['datafield'] : "";
        
        $data_inst = @array_pop($data_inst["widget-" . $mod->id_base]);
        echo "<form class='ui-form' datafield='".$datafield."' method='post' id='".$formID."'>";
        
        if ($data_inst):
            foreach ($data_inst as $k => $c) {
                $data_inst[$k] = is_array($c) ? $c : stripcslashes($c);
            }
        endif;

        $iinstance = array();
        if (is_array($data_inst))
            $iinstance = $data_inst;

        $wp_widget_factory->widgets[$_GET['module']]->form($iinstance);
        echo "
            <input type='submit' class='ui-button' value='Save Settings' />        
            <input type='button' class='ui-button' onclick='jQuery(\"#dialog\").dialog(\"close\");jQuery(\"#dialog\").html(\"Loading...\");' value='Cancel' />
            </form><script>jQuery('#ui-dialog-title-dialog').html('" . $mxwidgets[$_GET['module']]->name . " Options');jQuery('.ui-button,.ui-form input[type=button]').button();</script>";
        
        die();
    }
    
    public function oc_content_setting_data()
    {
        $data = base64_encode(serialize($_POST));
        echo $data;
        die();
    }

    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save($post_id) {

        // Check if our nonce is set.
        if (!isset($_POST['oc_inner_custom_box_nonce']))
            return $post_id;

        $nonce = $_POST['oc_inner_custom_box_nonce'];
        
        // Verify that the nonce is valid.
        if (!wp_verify_nonce($nonce, 'oc_inner_custom_box'))
            return $post_id;

        // If this is an autosave, our form has not been submitted,
        //     so we don't want to do anything.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;

        // Check the user's permissions.
        if ('page' == $_POST['post_type']) {

            if (!current_user_can('edit_page', $post_id))
                return $post_id;
        } else {

            if (!current_user_can('edit_post', $post_id))
                return $post_id;
        }

        /* OK, its safe for us to save the data now. */
        $modules = array();
        for( $i = 0; $i < count($_POST['modules']['oc_container']); $i++)
        {
            $modules[$i] = array(
                'widget'=>$_POST['modules']['oc_container'][$i],
                'data'=>$_POST['modules_settings']['oc_container'][$i]
            );
        }
        // Sanitize the user input.
        // $mydata = sanitize_text_field($_POST['oc_container']);

        // Update the meta field.
        update_post_meta($post_id, '_oc_container', base64_encode(serialize($modules)));
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_plugin_admin_page() {
        include_once( 'views/admin.php' );
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */
    public function add_action_links($links) {

        return array_merge(
                array(
            'settings' => '<a href="' . admin_url('options-general.php?page=' . $this->plugin_slug) . '">' . __('Settings', $this->plugin_slug) . '</a>'
                ), $links
        );
    }

    /**
     * NOTE:     Actions are points in the execution of a page or process
     *           lifecycle that WordPress fires.
     *
     *           Actions:    http://codex.wordpress.org/Plugin_API#Actions
     *           Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
     *
     * @since    1.0.0
     */
    public function action_method_name() {
        // @TODO: Define your action hook callback here
    }

    /**
     * NOTE:     Filters are points of execution in which WordPress modifies data
     *           before saving it or sending it to the browser.
     *
     *           Filters: http://codex.wordpress.org/Plugin_API#Filters
     *           Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
     *
     * @since    1.0.0
     */
    public function filter_method_name() {
        // @TODO: Define your filter hook callback here
    }

}
