<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    User_Reviews
 * @subpackage User_Reviews/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    User_Reviews
 * @subpackage User_Reviews/includes
 * @author     Developer Junayed <admin@easeare.com>
 */
class User_Reviews_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$user_reviews = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}user_reviews` (
			`ID` INT NOT NULL AUTO_INCREMENT,
			`name` VARCHAR(55) NOT NULL,
			`email` VARCHAR(55) NOT NULL,
			`star` INT NOT NULL,
			`feedback` TEXT NOT NULL,
			`images` TEXT NOT NULL,
			`reference` INT NOT NULL,
			`status` VARCHAR(15) NOT NULL,
			`date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`ID`)) ENGINE = InnoDB";
		dbDelta($user_reviews);
	}

}
