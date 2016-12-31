<?php
/**
 * @package FAQ Simple Editor 
 * @version 1.7
 */
/*
Plugin Name: FAQ Simple Editor
Author: Brandon Novick
Version: 1
*/
require_once('FAQOptions.php');
class FAQEditor {

        /**
         * A Unique Identifier
         */
         protected $plugin_slug;

        /**
         * A reference to an instance of this class.
         */
        private static $instance;

        /**
         * The array of templates that this plugin tracks.
         */
        protected $templates;


        /**
         * Returns an instance of this class. 
         */
        public static function get_instance() {

                if( null == self::$instance ) {
                        self::$instance = new FAQEditor();
                } 

                return self::$instance;

        } 

        /**
         * Initializes the plugin by setting filters and administration functions.
         */
        private function __construct() {

                $this->templates = array();


                // Add a filter to the attributes metabox to inject template into the cache.
                add_filter(
                    'page_attributes_dropdown_pages_args',
                     array( $this, 'register_project_templates' ) 
                );


                // Add a filter to inject out template into the page cache
                add_filter(
                    'wp_insert_post_data', 
                    array( $this, 'register_project_templates' ) 
                );


                // Add a filter to the template include to determine if the page has our 
                // template assigned and return it's path
                add_filter(
                    'template_include', 
                    array( $this, 'view_project_template') 
                );


                // Add template to the template array.
                $this->templates = array(
                        'FAQ.php'     => 'FAQ'
                );
                
        } 


        /**
         * Adds our template to the pages cache 
         *
         */

        public function register_project_templates( $atts ) {

                // Create the key used for the themes cache
                $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

                // Retrieve the cache list. 
                // If it doesn't exist, or it's empty prepare an array
                $templates = wp_get_theme()->get_page_templates();
                if ( empty( $templates ) ) {
                        $templates = array();
                } 

                // New cache, therefore remove the old one
                wp_cache_delete( $cache_key , 'themes');

                // Now add our template to the list of templates by merging our templates
                // with the existing templates array from the cache.
                $templates = array_merge( $templates, $this->templates );

                // Add the modified cache to allow WordPress to pick it up for listing
                // available templates
                wp_cache_add( $cache_key, $templates, 'themes', 1800 );

                return $atts;

        } 

        /**
         * Checks if the template is assigned to the page
         */
        public function view_project_template( $template ) {

                global $post;

                if (!isset($this->templates[get_post_meta( 
                    $post->ID, '_wp_page_template', true 
                )] ) ) {
                    
                        return $template;
                        
                } 

                $file = plugin_dir_path(__FILE__). get_post_meta( 
                    $post->ID, '_wp_page_template', true 
                );
                
                // Just to be safe, we check if the file exist first
                if( file_exists( $file ) ) {
                        return $file;
                } 
                else { echo $file; }

                return $template;

        } 


} 
//include( plugin_dir_path( __FILE__ ) . 'Test.php');
add_action( 'plugins_loaded', array( 'FAQEditor', 'get_instance' ) );

// Hides the default WP editor on the FAQ templates.

add_action( 'admin_init', 'hide_editor' );

function hide_editor() {
    // Get the Post ID.
    $post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
    if( !isset( $post_id ) ) return;

    // Get the name of the Page Template file.
    $template_file = get_post_meta($post_id, '_wp_page_template', true);
    
    if (strpos($template_file, 'FAQ') !== FALSE){ // edit the template name
    //remove_post_type_support( 'page', 'custom-fields' );
    remove_post_type_support( 'page', 'editor' );
         
    }
}
include( plugin_dir_path( __FILE__ ) . 'metaboxes/meta_box.php');




//add_action('add_meta_boxes', $test);
$prefix = 'faq_';
$fields = array(
	array( // Repeatable & Sortable Text inputs
		'label'	=> '', // <label>
		'desc'	=> '', // description
		'id'	=> $prefix.'repeatable', // field id and name
		'type'	=> 'repeatable', // type of field
		'sanitizer' => array( // array of sanitizers with matching kets to next array
			'featured' => 'meta_box_santitize_boolean',
			'title' => 'sanitize_text_field',
			'desc' => 'wp_kses_data'
		),
		'repeatable_fields' => array ( // array of fields to be repeated
			'q' => array(
				'label' => 'Question',
				'id' => 'q',
				'type' => 'text'
			),
			'a' => array(
				'label' => 'Answer',
				'id' => 'a',
				'type' => 'textarea'
			)
		)
	)
);
$test = new custom_add_meta_box( 'FAQ', 'FAQ', $fields, 'page', true );



function add_jquery() {
    $assets_path = plugins_url('js/', __FILE__);
       wp_register_script('myjquery', 
                         $assets_path . 'jquery-1.12.4.min.js', 
                        array (), 
                        null, false);

    wp_enqueue_script( 'myjquery');
    wp_register_script('myjqueryui', 
                        $assets_path . 'jquery-ui-1.10.4.min.js', 
                        array (), 
                        null, false);

    wp_enqueue_script( 'myjqueryui');

}



//if (get_page_template_slug( get_the_ID() )=='FAQ.php'){
    add_action( 'wp_enqueue_scripts', 'add_jquery' );
//}


