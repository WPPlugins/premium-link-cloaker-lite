<?php

if ( ! defined( 'ABSPATH' ) ) exit();

/**
* PLCL_Click class
*/
class PLCL_Click {

	private $day = 7;
	
	public function __construct() {

	}

	public function delete( $link_id ) {
		global $wpdb;

		$delete = $wpdb->delete(
			"{$wpdb->prefix}plcl_clicks",
			array(
				'link_id' => $link_id,
			),
			array(
				'%d',
			)
		);

		if ( false !== $delete ) {
			return true;
		} else {
			return $wpdb->last_error;
		}
	}

	public function get_clicks( $args = array() ) {
		$defaults = array(
			'day'             => $this->day,
			'link_id'         => '',
			'order_by'        => 'id',
			'order'           => 'DESC',
			'clicks_per_page' => 10,
			'paged'           => 1,
		);

		$args   = wp_parse_args( $args, $defaults );

		$offset = ( $args['paged'] - 1 ) * $args['clicks_per_page'];
		$where 	= '';
		if ( is_numeric( $args['link_id'] ) ) {
			$where .= "AND link_id = {$args['link_id']}";
		}
		$date = date( 'Y-m-d', time() );

		global $wpdb;
		$clicks = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * 
				FROM {$wpdb->prefix}plcl_clicks pc
				WHERE DATE(pc.date) BETWEEN ( %s - INTERVAL %d DAY ) AND %s
				{$where}
				ORDER BY {$args['order_by']} {$args['order']}
				LIMIT %d OFFSET %d",
				$date,
				$args['day'],
				$date,
				$args['clicks_per_page'],
				$offset
			),
			ARRAY_A
		);

		return $clicks;
	}

	public function count_raw_clicks( $args = array() ) {
		global $wpdb;

		$defaults = array(
			'day'     => $this->day,
			'link_id' => '',
		);

		$args  = wp_parse_args( $args, $defaults );
		$where = '';
		if ( is_numeric( $args['link_id'] ) ) {
			$where .= "AND link_id = {$args['link_id']}";
		}

		$clicks = array();

		for ( $i = 0; $i < $args['day']; $i++ ) { 
			$date = date( 'Y-m-d', strtotime( "-$i days", time() ) );

			$count = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*) 
					FROM {$wpdb->prefix}plcl_clicks 
					WHERE DATE(date) = %s
					{$where}",
					$date
				)
			);

			$date = date( 'Y-m-d', strtotime( "-$i days", time() ) );
			$clicks[ $date ] = $count;
		}

		return $clicks;
	}

	public function count_unique_clicks( $args = array() ) {
		global $wpdb;

		$defaults = array(
			'day'     => $this->day,
			'link_id' => '',
		);

		$args   = wp_parse_args( $args, $defaults );
		$where  = '';
		$clicks = array();
		if ( is_numeric( $args['link_id'] ) ) {
			for ( $i = 0; $i < $args['day']; $i++ ) { 
				$date = date( 'Y-m-d', strtotime( "-$i days", time() ) );

				$count = $wpdb->get_var(
					$wpdb->prepare(
						"SELECT COUNT(DISTINCT ip) 
						FROM {$wpdb->prefix}plcl_clicks 
						WHERE DATE(date) = %s
						AND link_id = %d",
						$date,
						$args['link_id']
					)
				);

				$date = date( 'Y-m-d', strtotime( "-$i days", time() ) );
				$clicks[ $date ] = $count[0];
			}
		
		} else {
			for ( $i = 0; $i < $args['day']; $i++ ) { 
				$date = date( 'Y-m-d', strtotime( "-$i days", time() ) );

				$count = $wpdb->get_var(
					$wpdb->prepare(
						"SELECT COUNT(*)
						FROM
						(
							SELECT COUNT(*)
							FROM {$wpdb->prefix}plcl_clicks 
							WHERE DATE(date) = %s
							GROUP BY ip    
						) AS grp",
						$date
					)
				);

				$date = date( 'Y-m-d', strtotime( "-$i days", time() ) );
				$clicks[ $date ] = $count[0];
			}
		}

		return $clicks;
	}
}