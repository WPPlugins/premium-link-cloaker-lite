<?php

/**
* PLCL_Link Class
*/
class PLCL_Link {
	
	public function __construct() {
		add_action( 'init', array( $this, 'check_url' ), 0 );
		add_action( 'admin_init', array( $this, 'form_handler' ) );
	}

	public function check_url() {
		if ( is_admin() ) {
			return;
		}
		
		$prefix = isset( $_SERVER['HTTPS'] ) && ! empty( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';

		$url = esc_url( untrailingslashit( $prefix . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ) );
		
		$cloaked_links = $this->get_cloaked_urls();

		// check if url is one of cloaked links
		if ( in_array( $url, $cloaked_links ) ) {
			// if cloaked link, get data from db
			$link  = $this->get_by_cloaked_url( $url );

			// if robot, exit
			$robot = isset( $_SERVER['HTTP_USER_AGENT'] ) && preg_match( '#bot|crawl|slurp|spider|curl#i', $_SERVER['HTTP_USER_AGENT'] ) ? 1 : 0;
			if ( 1 == $robot ) return;

			$referrer = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '';

			$data = array(
				'link_id'  => $link['id'],
				'url'      => $url,
				'referrer' => $referrer,
				'ip'       => $this->get_visitor_ip(),
				'date'     => date( 'Y-m-d H-i-s' ),
			);

			// store visitor's data to db
			$this->store_visitor( $data );
			
			// redirect cloaked link to link target url
			$this->redirect( $link['target_url'] );
		}
	}

	public function get_visitor_ip() {

	    if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
	        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	    } 
	    elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    }
	    elseif ( isset( $_SERVER['HTTP_X_FORWARDED'] ) ) {
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	    }
	    elseif ( isset( $_SERVER['HTTP_FORWARDED_FOR'] ) ) {
	        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	    }
	    elseif ( isset( $_SERVER['HTTP_FORWARDED'] ) ) {
	        $ipaddress = $_SERVER['HTTP_FORWARDED'];
	    }
	    elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
	        $ipaddress = $_SERVER['REMOTE_ADDR'];
	    }
	    else {
	        $ipaddress = 'UNKNOWN';
	    }
	 
	    return $ipaddress;
	}

	public function store_visitor( $data ) {
		global $wpdb;

		$wpdb->insert(
			"{$wpdb->prefix}plcl_clicks",
			array(
				'link_id'  => $data['link_id'],
				'url'      => $data['url'],
				'referrer' => $data['referrer'],
				'ip'       => $data['ip'],
				'date'     => $data['date'],
			),
			array(
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
			)
		);
	}

	public function redirect( $url ) {
		wp_redirect( htmlspecialchars_decode( $url ) );
		exit();
	}

	public function form_handler() {
		if ( ! isset( $_GET['page'] ) || ! is_admin() ) {
			return;
		}

		if ( 'plcl' != substr( $_GET['page'], 0, 4 ) ) {
			return;
		}

		if ( isset( $_POST['submit'] ) && 'add' == $_POST['action'] ) {
			if ( ! wp_verify_nonce( $_POST['plcl_nonce'], 'add_link' ) ) {
				return;
			}

			$add = $this->add( $_POST );
			premium_link_cloaker_lite()->settings->redirect( 'add', $add );

		} elseif ( isset( $_POST['submit'] ) && 'edit' == $_POST['action'] ) {
			if ( ! wp_verify_nonce( $_POST['plcl_nonce'], 'edit_link' . $_POST['link_id'] ) ) {
				return;
			}

			$edit = $this->edit( $_POST );
			premium_link_cloaker_lite()->settings->redirect( 'edit', $edit );
		} elseif ( isset( $_GET['action'] ) && 'delete' == $_GET['action'] ) {
			if ( ! wp_verify_nonce( $_GET['plcl_nonce'], 'delete_link' . $_GET['link'] ) ) {
				return;
			}

			$delete = $this->delete( $_GET['link'] );
			premium_link_cloaker_lite()->settings->redirect( 'delete', $delete );

		} elseif ( ( isset( $_POST['bulk_action_1'] ) && 'delete' == $_POST['bulk_action_1'] ) || ( isset( $_POST['bulk_action_2'] ) && 'delete' == $_POST['bulk_action_2'] ) ) {
			if ( ! wp_verify_nonce( $_POST['plcl_nonce'], 'bulk_action' ) ) {
				return;
			}

			foreach ($_POST['link_cb'] as $link_id ) {
				$delete = $this->delete( $link_id );
			}

			premium_link_cloaker_lite()->settings->redirect( 'delete', $delete );
		}
	}

	private function add( $post ) {
		global $wpdb;

		$add = $wpdb->insert(
			"{$wpdb->prefix}plcl_links",
			array(
				'name'          => esc_attr( $post['name'] ),
				'target_url'    => esc_url( $post['target_url'] ),
				'cloaking_type' => esc_attr( $post['cloaking_type'] ),
				'cloaked_url'   => esc_url( home_url( '/' ) . $post['cloaked_url'] ),
				'keywords'      => esc_textarea( $post['keywords'] ),
			),
			array(
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
			)
		);

		$link_id = $wpdb->insert_id;

		if ( false !== $add ) {
			if ( ! empty( $post['categories'] ) ) {
				premium_link_cloaker_lite()->category->delete_rel_by_link( $link_id );
				foreach ( $post['categories'] as $cat ) {
					premium_link_cloaker_lite()->category->add_rel( $link_id, $cat );
				}
			} else {
				premium_link_cloaker_lite()->category->delete_rel_by_link( $link_id );
			}

			return true;
		} else {
			return $wpdb->last_error;
		}
	
	}

	private function edit( $post ) {
		global $wpdb;

		$edit = $wpdb->update(
			"{$wpdb->prefix}plcl_links",
			array(
				'name'          => esc_attr( $post['name'] ),
				'target_url'    => esc_url( $post['target_url'] ),
				'cloaking_type' => esc_attr( $post['cloaking_type'] ),
				'cloaked_url'   => esc_url( home_url( '/' ) . $post['cloaked_url'] ),
				'keywords'      => esc_textarea( $post['keywords'] ),
			),
			array(
				'id' => esc_attr( $post['link_id'] ),
			),
			array(
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
			),
			array(
				'%d',
			)
		);

		if ( false !== $edit ) {
			if ( ! empty( $post['categories'] ) ) {
				premium_link_cloaker_lite()->category->delete_rel_by_link( $post['link_id'] );
				foreach ( $post['categories'] as $cat ) {
					premium_link_cloaker_lite()->category->add_rel( $post['link_id'], $cat );
				}
			} else {
				premium_link_cloaker_lite()->category->delete_rel_by_link( $post['link_id'] );
			}

			return true;
		} else {
			return $wpdb->last_error;
		}
	}

	private function delete( $link_id ) {
		global $wpdb;

		$delete = $wpdb->delete(
			"{$wpdb->prefix}plcl_links",
			array(
				'id' => $link_id,
			),
			array(
				'%d',
			)			
		);

		if ( false !== $delete ) {
			premium_link_cloaker_lite()->category->delete_rel_by_link( $link_id );
			premium_link_cloaker_lite()->click->delete( $link_id );
			return true;
		} else {
			return $wpdb->last_error;
		}
	}

	public function get_cats( $link_id = 0 ) {
		if ( empty( $link_id ) ) {
			return $cats = array();
		}

		global $wpdb;
		$rels = $wpdb->get_results(
			$wpdb->prepare( 
				"SELECT cat_id FROM {$wpdb->prefix}plcl_cat_relationships WHERE link_id = %d",
				$link_id
			),
			ARRAY_A
		);

		$cats = array();
		foreach ( $rels as $rel ) {
			$cat = $wpdb->get_row(
				$wpdb->prepare( 
					"SELECT id FROM {$wpdb->prefix}plcl_categories WHERE id = %d",
					$rel['cat_id']
				),
				ARRAY_A
			);

			$cats[] = $cat['id'];
		}

		return $cats;
	}

	public function get( $link_id ) {
		global $wpdb;

		$link = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}plcl_links WHERE id = %d",
				$link_id
			),
			ARRAY_A
		);

		return $link;
	}

	public function get_all( $args = array() ) {
		global $wpdb;

		$defaults = array(
			'order_by'        => 'id',
			'order'           => 'DESC',
			'links_per_page'  => 10,
			'paged'           => 1,
		);

		$args   = wp_parse_args( $args, $defaults );
		$offset = ( $args['paged'] - 1 ) * $args['links_per_page'];

		$links = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}plcl_links
				ORDER BY {$args['order_by']} {$args['order']} 
				LIMIT %d OFFSET %d",
				$args['links_per_page'],
				$offset
			),
			ARRAY_A
		);

		return $links;
	}

	public function get_by_cat( $cat_id, $args = array() ) {
		global $wpdb;

		$defaults = array(
			'order_by'        => 'id',
			'order'           => 'DESC',
			'links_per_page'  => 10,
			'paged'           => 1,
		);

		$args   = wp_parse_args( $args, $defaults );
		$offset = ( $args['paged'] - 1 ) * $args['links_per_page'];

		$links = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT link.* FROM {$wpdb->prefix}plcl_links AS link
				INNER JOIN {$wpdb->prefix}plcl_cat_relationships AS rel ON link.id = rel.link_id
				INNER JOIN {$wpdb->prefix}plcl_categories AS cat ON rel.cat_id = cat.id
				WHERE cat.id = %d
				ORDER BY {$args['order_by']} {$args['order']} 
				LIMIT %d OFFSET %d",
				$cat_id,
				$args['links_per_page'],
				$offset
			), 
			ARRAY_A
		);

		return $links;
	}

	public function get_by_cloaked_url( $url ) {
		global $wpdb;

		$link = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}plcl_links WHERE cloaked_url = %s",
				$url
			),
			ARRAY_A
		);

		return $link;
	}

	public function get_cloaked_urls() {
		global $wpdb;

		$links = $wpdb->get_results(
			"SELECT cloaked_url FROM {$wpdb->prefix}plcl_links",
			ARRAY_A
		);

		$cloaked_urls = array();
		foreach ( $links as $link ) {
			$cloaked_urls[] = untrailingslashit( $link['cloaked_url'] );
		}

		return $cloaked_urls;
	}

	public function get_link_name( $id ) {
		global $wpdb;

		$link = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT name from {$wpdb->prefix}plcl_links WHERE id = %d",
				$id
			),
			ARRAY_A
		);

		return $link['name'];
	}
}