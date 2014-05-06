<?php
/**
 * Object_Container
 *
 * @package   Object_Container
 * @author    joaquin <joaquin@renovatio-comunicacion.com>
 * @license   GPL-2.0+
 * @link      http://www.renovatio-comunicacion.com
 * @copyright 2014 joaquin
 */

/**
 * Object_Container class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-object-container-admin.php`
 *
 * @package Object_Container
 * @author  joaquin <joaquin@renovatio-comunicacion.com>
 */
class Object_Container {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '0.0.1';

	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'object-container';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

            // Load plugin text domain
            add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

            // Activate plugin when new blog is added
            add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

            // Load public-facing style sheet and JavaScript.
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

            /* Define custom functionality.
             * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
             */
            add_action( 'init', array( $this, 'objectContainerInit' ) );
            add_filter( 'the_content', array( $this, 'objectContainerRender' ) );
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		// @TODO: Define activation functionality here
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
	}
        
	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function objectContainerInit() {
            // @TODO: Define your action hook callback here
            apply_filters('the_content','');
	}

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function objectContainerRender($content) {
            // @TODO: Define your filter hook callback here
            $content .= $this->getContent();
            return $content;
	}
                
        public function getContent()
        {
            $currentPost     = get_post();
            $currentPostMeta = unserialize(base64_decode(get_post_meta($currentPost->ID, '_oc_container', TRUE)));
            
            $this->categories = array();
            $identifier = uniqid('tab_');
            $widgetContent = array();
            
            foreach( $currentPostMeta as $cpm )
            {
                $widgetContent[] = $this->renderContent($cpm);
            }
            ob_start();
            include( plugin_dir_path(__FILE__) . 'views/widget.php' );
            $addedContent = ob_get_clean();
            //$addedContent .= $this->getSelectorHeader() . '<ul class="selector_content">' . $tabsContent. '</ul>';            
            return $addedContent;
        }

        private function renderContent($c)
        {
            $obj = $this->getContentObject($c);

            ob_start();
            include( plugin_dir_path(__FILE__) . 'views/widget-'.$obj->view.'.php' );
            $addedContent .= ob_get_clean();                

            return $addedContent;
        }
        
        private function getContentObject($c)
        {
            $c['data'] = unserialize(base64_decode($c['data']));
            $newData = array_shift(array_shift($c['data']));
            $c['data'] = $newData;    
         
            $obj = new stdClass();
            $obj->type = $c['widget'];
            
            if ( isset($c['data']['page']) )
            {
                if ( defined(ICL_LANGUAGE_CODE) )
                {
                    $post = get_post(icl_object_id( $c['data']['page'], 'page', TRUE, ICL_LANGUAGE_CODE ));
                } else {
                    $post = get_post($c['data']['page']);
                }
                
                $image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
                $image_thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'quad');
                
                $obj->image = $image[0];
                $obj->image_thumb = $image_thumb[0];
                $obj->id = $post->ID;
                $obj->title = $post->post_title;
                $obj->description = $post->post_excerpt;
                $obj->author = $this->getAuthorName($post->post_author);
                $obj->category = $this->getCategoryName($post);
                $obj->category_id = $this->getCategoryID($post);
                $obj->url = get_permalink($post->ID); 
                $obj->short_url = wp_get_shortlink($post->ID); 
                $obj->view = $c['data']['view'];
            } else if (isset($c['data']['post'])) {
                if ( defined(ICL_LANGUAGE_CODE) )
                {
                    $post = get_post(icl_object_id( $c['data']['post'], 'post', TRUE, ICL_LANGUAGE_CODE ));
                } else {
                    $post = get_post($c['data']['post']);
                }  
                $image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
                $image_thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'quad');
                $obj->image = $image[0];
                $obj->image_thumb = $image_thumb[0];
                $obj->id = $post->ID;
                $obj->title = $post->post_title;
                $obj->description = $post->post_excerpt;
                $obj->author = $this->getAuthorName($post->post_author);
                $obj->category = $this->getCategoryName($post);
                $obj->category_id = $this->getCategoryID($post);
                $obj->url = get_permalink($post->ID);
                $obj->short_url = wp_get_shortlink($post->ID); 
                $obj->view = $c['data']['view'];
            } else {
                $obj->image = $c['data']['image'];
                $obj->image_thumb =  wp_get_attachment_image_src( $c['data']['attid'], 'quad');
                $obj->id = NULL;
                $obj->title = $c['data']['title'];
                $obj->description = $c['data']['description'];
                $obj->author = $this->getAuthorName($c['data']['author']);
                $obj->category = get_the_category_by_ID($c['data']['category']);
                $obj->category_id = $c['data']['category'];
                $obj->url = get_permalink($c['data']['attid']);
                $obj->short_url = wp_get_shortlink($c['data']['attid']); 
                $obj->view = $c['data']['view'];
                
                $this->categories[$c['data']['category']] = $obj->category;
            }
            return $obj;
        }
        
        private function getAuthorName($id)
        {
            $a = get_userdata($id);
            return $a->data->display_name;
        }
        
        private function getCategoryName($post)
        {
            $cats = get_the_category($post->ID);
            
            $catActualPage = get_the_category();
            $currentCat = ( is_null($catActualPage[0]->cat_ID) ) ? 0 : $catActualPage[0]->cat_ID;
            $name = array();
            foreach( $cats as $cat )
            {
                if ( $cat->parent === $currentCat )
                {
                    $this->categories[$cat->term_id] = $cat->name;
                    $name[] = $cat->name;
                }
            }
            return implode(", ",$name);
        }
        
        private function getCategoryID($post)
        {
            $cats = get_the_category($post->ID);
            $catActualPage = get_the_category();
            $currentCat = ( is_null($catActualPage[0]->cat_ID) ) ? 0 : $catActualPage[0]->cat_ID;
            
            $ids = array();
            
            foreach( $cats as $cat )
            {
                if ( $cat->parent === $currentCat )
                {
                    $ids[] = $cat->term_id;
                }
            }
            return $ids;
        }
}
    