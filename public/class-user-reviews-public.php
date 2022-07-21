<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    User_Reviews
 * @subpackage User_Reviews/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    User_Reviews
 * @subpackage User_Reviews/public
 * @author     Developer Junayed <admin@easeare.com>
 */
class User_Reviews_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_shortcode( "user_reviews", [$this, "user_reviews_callback"] );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/user-reviews-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/user-reviews-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, "ajax_data", array(
			'max_upload'	=> wp_max_upload_size()
		));
	}

	function user_reviews_callback(){
		ob_start();
		require_once plugin_dir_path( __FILE__ )."partials/user-reviews-public-display.php";
		return ob_get_clean();
	}

	function upload_review_image($file){
		global $imageAlert;
		$imageAlert = "";

		$wpdir = wp_upload_dir(  );
		$max_upload_size = wp_max_upload_size();
		$fileSize = $file['size'];
		$imageFileType = strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));

		$filename = rand(10,99999);

		$folderPath = $wpdir['basedir'];
		$uploadPath = $folderPath."/".$filename.".".$imageFileType;
		$uploadedUrl = $wpdir['baseurl']."/".$filename.".".$imageFileType;

		// Allow certain file formats
		$allowedExt = array("jpg", "jpeg", "png", "PNG", "JPG", "gif");

		if(!in_array($imageFileType, $allowedExt)) {
			$imageAlert = "Unsupported file format!";
		}

		if ($fileSize > $max_upload_size) {
			$imageAlert = "Maximum upload size $max_upload_size";
		}

		if(empty($imageAlert)){
			if (move_uploaded_file($file["tmp_name"], $uploadPath)) {
				return $uploadedUrl;
			}
		}
	}

	function save_review(){
		if(isset($_POST['review_submit'])){

			$name = '';
			$email = '';

			if(isset($_POST['ur_yourname']) && isset($_POST['ur_youremail'])){
				$name = sanitize_text_field( stripslashes($_POST['ur_yourname']) );
				$email = sanitize_email( $_POST['ur_youremail'] );
			}

			if(is_user_logged_in(  )){
				$user = get_user_by( "ID", get_current_user_id(  ) );
				$name = $user->display_name;
				$email = $user->user_email;
			}

			$stars = intval($_POST['selected_star']);
			$feedback = sanitize_text_field( stripslashes($_POST['ur_feedback']) );

			$image1Url = '';
			$image2Url = '';
			$image3Url = '';
			$image4Url = '';

			$image1 = $_FILES['ur_images1'];
			$image2 = $_FILES['ur_images2'];
			$image3 = $_FILES['ur_images3'];
			$image4 = $_FILES['ur_images4'];

			if(!empty($image1['tmp_name'])){
				$image1Url = $this->upload_review_image($image1);
			}
			if(!empty($image2['tmp_name'])){
				$image2Url = $this->upload_review_image($image2);
			}
			if(!empty($image3['tmp_name'])){
				$image3Url = $this->upload_review_image($image3);
			}
			if(!empty($image4['tmp_name'])){
				$image4Url = $this->upload_review_image($image4);
			}

			$imagesArr = [$image1Url,$image2Url,$image3Url,$image4Url];

			$reference = intval($_POST['reference']);
			
			global $wpdb;
			$wpdb->insert($wpdb->prefix.'user_reviews', array(
				'name' => $name,
				'email' => $email,
				'star' => $stars,
				'feedback' => $feedback,
				'images' => serialize($imagesArr),
				'reference' => $reference,
				'status' => 'pending',
				'date' => date("Y-m-d h:i:s a")
			));

			if($wpdb->insert_id > 0){
				wp_safe_redirect( esc_url_raw( $_POST['current_url'] )."#ur_reviews_wrap" );
				exit;
			}
		}
	}

}
