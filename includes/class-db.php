<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit();

/**
* PLCL_Database class
*/
class PLCL_Database {
	
	public function __construct() {
		add_action( 'init', array( $this, 'create_database' ), 1 );
	}

	public function create_database() {
		$db_version = '1.0';

		$options = get_option( 'plcl_settings' );

		if ( isset( $options['db_version'] ) && $db_version == $options['db_version'] ) {
			return;
		}

		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$sql = "CREATE TABLE {$wpdb->prefix}plcl_links (
			id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			name text NOT NULL,
			target_url varchar(255) NOT NULL,
			cloaking_type varchar(20) NOT NULL,
			cloaked_url varchar(255) NOT NULL,
			keywords varchar(255),
			PRIMARY KEY  (id),
			UNIQUE KEY cloaked_url (cloaked_url(190))
		) $charset_collate;";

		$sql .= "CREATE TABLE {$wpdb->prefix}plcl_categories (
			id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			name varchar(255) NOT NULL,
			description text NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		$sql .= "CREATE TABLE {$wpdb->prefix}plcl_cat_relationships (
			link_id bigint(20) UNSIGNED NOT NULL,
			cat_id bigint(20) UNSIGNED NOT NULL,
			PRIMARY KEY  (link_id,cat_id)
		) $charset_collate;";

		$sql .= "CREATE TABLE {$wpdb->prefix}plcl_clicks (
			id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			link_id bigint(20) UNSIGNED NOT NULL,
			url varchar(255),
			referrer varchar(255),
			ip varchar(255),
			date datetime,
			PRIMARY KEY  (id),
			KEY link_id (link_id)
		) $charset_collate;";

		dbDelta( $sql );

		$options['db_version'] = $db_version;
		update_option( 'plcl_settings', $options );
	}
}

new PLCL_Database();