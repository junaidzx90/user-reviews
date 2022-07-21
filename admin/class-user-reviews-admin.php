<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    User_Reviews
 * @subpackage User_Reviews/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    User_Reviews
 * @subpackage User_Reviews/admin
 * @author     Developer Junayed <admin@easeare.com>
 */
class User_Reviews_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in User_Reviews_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The User_Reviews_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/user-reviews-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in User_Reviews_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The User_Reviews_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/user-reviews-admin.js', array( 'jquery' ), $this->version, false );

	}

	function admin_menu_page(){
		add_menu_page( "User reviews", "User reviews", "manage_options", "user-reviews", [$this, "user_reviews_menu"], "dashicons-star-half", 45 );
		add_submenu_page( "user-reviews", "Settings", "Settings", "manage_options", "ur-settings", [$this, "user_reviews_settings"], null );
		add_settings_section( 'urreviews_general_opt_section', '', '', 'urreviews_general_opt_page' );

		// Shortcodes
		add_settings_field( 'ur_reviews_shortcode', 'Shortcode', [$this, 'ur_reviews_shortcode_cb'], 'urreviews_general_opt_page','urreviews_general_opt_section' );

		// Reviews Title
		add_settings_field( 'ur_reviews_title', 'Reviews Title', [$this, 'ur_reviews_title_cb'], 'urreviews_general_opt_page','urreviews_general_opt_section' );
		register_setting( 'urreviews_general_opt_section', 'ur_reviews_title' );
		// Reviews form Title
		add_settings_field( 'ur_reviews_form_title', 'Reviews form title', [$this, 'ur_reviews_form_title_cb'], 'urreviews_general_opt_page','urreviews_general_opt_section' );
		register_setting( 'urreviews_general_opt_section', 'ur_reviews_form_title' );
	}

	function ur_reviews_shortcode_cb(){
		echo '<input type="text" readonly value="[user_reviews]">';
	}
	function ur_reviews_title_cb(){
		echo '<input type="text" class="widefat" placeholder="Recensioni degli utenti" value="'.get_option('ur_reviews_title').'" name="ur_reviews_title">';
	}
	function ur_reviews_form_title_cb(){
		echo '<input type="text" class="widefat" placeholder="Scrivi una recensione" value="'.get_option('ur_reviews_form_title').'" name="ur_reviews_form_title">';
	}

	function user_reviews_menu(){
		if(isset($_GET['page']) && $_GET['page'] === 'user-reviews' && isset($_GET['action']) && $_GET['action'] === 'view' && isset($_GET['id'])){
			require_once plugin_dir_path( __FILE__ )."partials/user-reviews-admin-display.php";
		}else{
			$userReviews = new User_Reviews_Table();
			?>
			<div class="wrap" id="userReviews-table">
				<h3 class="heading3">User Reviews</h3>
				<hr>
				<form action="" method="post">
				<?php $userReviews->prepare_items(); ?>
				<?php $userReviews->display(); ?>
				</form>
			</div>
			<?php
		}
	}

	function user_reviews_settings(){
		?>
		<h3>Settings</h3>
		<hr>

		<form style="width: 50%;" method="post" action="options.php">
			<?php
			settings_fields( 'urreviews_general_opt_section' );
			do_settings_sections('urreviews_general_opt_page');
			echo get_submit_button( 'Save Changes', 'secondary', 'ur_reviews-setting' );
			?>
		</form>
		
		<?php
	}

}
