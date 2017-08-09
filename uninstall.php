<?php

if ( ! defined( 'ABSPATH' ) && ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();

$plcl_settings = get_option( 'plcl_settings' );

if ( 1 == $plcl_settings['misc_delete_on_uninstall'] ) {
	delete_option( 'plcl_settings' );

	global $wpdb;
	$sql = "DROP TABLE IF EXISTS
		{$wpdb->prefix}plcl_links, 
		{$wpdb->prefix}plcl_categories, 
		{$wpdb->prefix}plcl_cat_relationships, 
		{$wpdb->prefix}plcl_clicks;";

	$wpdb->query( $sql );	
}