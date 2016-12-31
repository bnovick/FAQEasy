<?php 
 /**
 * Background color admin option for toggle headers
 */

class FAQOptions {
	private $FAQ_options_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'FAQ_options_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'FAQ_options_page_init' ) );
	}

	public function FAQ_options_add_plugin_page() {
		add_options_page(
			'FAQ  Options', // page_title
			'FAQ  Options', // menu_title
			'manage_options', // capability
			'FAQ-options', // menu_slug
			array( $this, 'FAQ_options_create_admin_page' ) // function
		);
	}

	public function FAQ_options_create_admin_page() {
		$this->FAQ_options_options = get_option( 'FAQ_options_option_name' ); ?>
<script>
		jQuery(document).ready(function($){
			$('.color_field').each(function(){
        		$(this).wpColorPicker();
    		});
		});
		</script>
		<div class="wrap">
			<h2>FAQ Display Options</h2>
			<p></p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'FAQ_options_option_group' );
					do_settings_sections( 'FAQ-options-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function FAQ_options_page_init() {
		register_setting(
			'FAQ_options_option_group', // option_group
			'FAQ_options_option_name', // option_name
			array( $this, 'FAQ_options_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'FAQ_options_setting_section', // id
			'Settings', // title
			array( $this, 'FAQ_options_section_info' ), // callback
			'FAQ-options-admin' // page
		);

		add_settings_field(
			'color_0', // id
			'Color', // title
			array( $this, 'color_0_callback' ), // callback
			'FAQ-options-admin', // page
			'FAQ_options_setting_section' // section
		);
	}

	public function FAQ_options_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['color_0'] ) ) {
			$sanitary_values['color_0'] = sanitize_text_field( $input['color_0'] );
		}

		return $sanitary_values;
	}

	public function FAQ_options_section_info() {
		
	}

	public function color_0_callback() {
		printf(
			'<input class="color_field" type="hidden" name="FAQ_options_option_name[color_0]" id="color_0" value="%s">',
			isset( $this->FAQ_options_options['color_0'] ) ? esc_attr( $this->FAQ_options_options['color_0']) : ''
		);
	}

}
if ( is_admin() )
	$FAQ_options = new FAQOptions();

/* 
 * Retrieve this value with:
 * $FAQ_options_options = get_option( 'FAQ_options_option_name' ); // Array of All Options
 * $color_0 = $FAQ_options_options['color_0']; // Color
 */
 add_action( 'admin_enqueue_scripts', 'mytheme_backend_scripts');
if ( ! function_exists( 'mytheme_backend_scripts' ) ){
	function mytheme_backend_scripts($hook) {
		wp_enqueue_media();
		wp_enqueue_style( 'wp-color-picker');
		wp_enqueue_script( 'wp-color-picker');
	}
}
add_action('wp_head','hook_css');

function hook_css() {
    $FAQ_options_options = get_option( 'FAQ_options_option_name' ); 
	$color_0 = $FAQ_options_options['color_0'];
    $output='<style> .toggle h3 { background-color :';
    $output .=  $color_0;
    $output .=' !important ;} </style>';

	echo $output;

}


?>
