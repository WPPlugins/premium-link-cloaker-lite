<?php

/**
* PLCL_Category class
*/
class PLCL_Category {
	
	public function __construct() {
		add_action( 'admin_init', array( $this, 'form_handler' ) );
	}

	public function form_handler() {
		if ( ! isset( $_GET['page'] ) || ! is_admin() ) {
			return;
		}

		if ( 'plcl' != substr( $_GET['page'], 0, 4 ) ) {
			return;
		}

		if ( isset( $_POST['action'] ) && 'add_cat' == $_POST['action'] ) {
			if ( ! wp_verify_nonce( $_POST['plcl_nonce'], 'add_cat' ) ) {
				return;
			}

			$add = $this->add( $_POST );
			premium_link_cloaker_lite()->settings->redirect( $_POST['action'], $add, 'plcl_categories' );

		} elseif ( isset( $_POST['action'] ) && 'edit_cat' == $_POST['action'] ) {
			if ( ! wp_verify_nonce( $_POST['plcl_nonce'], 'edit_cat' . $_POST['cat_id'] ) ) {
				return;
			}

			$edit = $this->edit( $_POST );
			premium_link_cloaker_lite()->settings->redirect( $_POST['action'], $edit, 'plcl_categories' );
		
		} elseif ( isset( $_GET['action'] ) && 'delete_cat' == $_GET['action'] ) {
			if ( ! wp_verify_nonce( $_GET['plcl_nonce'], 'delete_cat' . $_GET['cat'] ) ) {
				return;
			}

			$delete = $this->delete( $_GET['cat'] );
			premium_link_cloaker_lite()->settings->redirect( 'delete_cat', $delete, 'plcl_categories' );

		} elseif ( ( isset( $_POST['cat_bulk_action_1'] ) && 'delete_cat' == $_POST['cat_bulk_action_1'] ) || ( isset( $_POST['cat_bulk_action_2'] ) && 'delete_cat' == $_POST['cat_bulk_action_2'] ) ) {
			if ( ! wp_verify_nonce( $_POST['plcl_nonce'], 'cat_bulk_action' ) ) {
				return;
			}

			foreach ( $_POST['cat_cb'] as $cat_id ) {
				$delete = $this->delete( $cat_id );
			}

			premium_link_cloaker_lite()->settings->redirect( 'delete_cat', $delete, 'plcl_categories' );
		}
	}

	public function add( $cat = array() ) {
		global $wpdb;

		$add = $wpdb->insert(
			"{$wpdb->prefix}plcl_categories",
			array(
				'name'        => $cat['name'],
				'description' => $cat['description'],
			),
			array(
				'%s',
				'%s',
			)
		);

		if ( false !== $add ) {
			return true;
		} else {
			return $wpdb->last_error;
		}
	}

	public function edit( $cat = array() ) {
		global $wpdb;
		$edit = $wpdb->update(
			"{$wpdb->prefix}plcl_categories",
			array(
				'name'        => $cat['name'],
				'description' => $cat['description'],
			),
			array(
				'id' => $cat['cat_id'],
			),
			array(
				'%s',
				'%s',
			),
			array(
				'%d',
			)
		);

		if ( false !== $edit ) {
			return true;
		} else {
			return $wpdb->last_error;
		}
	}

	public function delete( $cat_id = 0 ) {
		global $wpdb;

		$delete = $wpdb->delete(
			"{$wpdb->prefix}plcl_categories",
			array(
				'id' => $cat_id,
			),
			array(
				'%d',
			)
		);

		if ( false !== $delete ) {
			$this->delete_rel_by_cat( $cat_id );
			return true;
		} else {
			return $wpdb->last_error;
		}
	}

	public function add_rel( $link_id = 0, $cat_id = 0 ) {
		global $wpdb;

		$insert = $wpdb->insert(
			"{$wpdb->prefix}plcl_cat_relationships",
			array(
				'link_id' => $link_id,
				'cat_id'  => $cat_id,
			),
			array(
				'%d',
				'%d',
			)
		);

		if ( false !== $insert ) {
			return true;
		} else {
			return $wpdb->last_error;
		}
	}

	public function delete_rel_by_cat( $cat_id = 0 ) {
		global $wpdb;

		$remove = $wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->prefix}plcl_cat_relationships WHERE cat_id = %d",
				$cat_id
			)
		);

		if ( false !== $remove ) {
			return true;
		} else {
			return $wpdb->last_error;
		}
	}

	public function delete_rel_by_link( $link_id = 0 ) {
		global $wpdb;

		$remove = $wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->prefix}plcl_cat_relationships WHERE link_id = %d",
				$link_id
			)
		);

		if ( false !== $remove ) {
			return true;
		} else {
			return $wpdb->last_error;
		}
	}

	public function get( $cat_id ) {
		global $wpdb;

		$cat = $wpdb->get_row(
			$wpdb->prepare( 
				"SELECT * FROM {$wpdb->prefix}plcl_categories WHERE id = %d",
				$cat_id
			),
			ARRAY_A
		);

		return $cat;
	}

	public function get_all( $args = array() ) {
		global $wpdb;

		$defaults = array(
			'order_by'      => 'name',
			'order'         => 'ASC',
			'cats_per_page' => 10,
			'paged'         => 1,
		);

		$args = wp_parse_args( $args, $defaults );
		$offset = ( $args['paged'] - 1 ) * $args['cats_per_page'];

		$cats = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * 
				FROM {$wpdb->prefix}plcl_categories 
				ORDER BY {$args['order_by']} {$args['order']} 
				LIMIT %d OFFSET %d",
				$args['cats_per_page'],
				$offset
			),
			ARRAY_A
		);

		return $cats;
	}
}