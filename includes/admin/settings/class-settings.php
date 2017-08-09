<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit();

/**
* PLCL_Settings class
*
* This class is responsible for managing plugin settings
*
* @since 1.0
*/
class PLCL_Settings {

	/**
	 * Plugin options
	 *
	 * @since 1.0
	 * @var array
	 */
	private $options;

	/**
	 * Class __construct function
	 *
	 * @since 1.0
	 */
	public function __construct() {
		global $plcl_settings;
		$this->options = get_option( 'plcl_settings', array() );

		register_activation_hook( PLCL_FILE, array( $this, 'add_options' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'plugin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_filter( 'style_loader_tag', array( $this, 'style_loader_tag' ), 10, 2 );
		add_filter( 'script_loader_tag', array( $this, 'script_loader_tag' ), 10, 2 );
		add_action( 'admin_footer', array( $this, 'admin_footer' ) );
	}

	public function add_options() {
		$settings = array(
			'misc_delete_on_uninstall' => 0,
		);
		add_option( 'plcl_settings', $settings );
	}

	public function register_settings() {
		register_setting( 'plcl_settings_group', 'plcl_settings', array( $this, 'sanitize_settings' ) );
	}

	public function sanitize_settings( $input ) {
		$plcl_settings = get_option( 'plcl_settings' );

		if ( isset( $input['misc_delete_on_uninstall'] ) ) {
			$input['misc_delete_on_uninstall'] = esc_attr( $input['misc_delete_on_uninstall'] );
		} else {
			$input['misc_delete_on_uninstall'] = 0;
		}

		$settings = array_merge( $plcl_settings, $input );

		return $settings;
	}

	public function plugin_menu() {
		add_menu_page( __return_empty_string(), 'PLC Lite', 'manage_options', 'plcl', __return_empty_array(), 'dashicons-admin-links' );

		add_submenu_page( 'plcl', __( 'PLC Lite: Cloaked Links', 'premium-link-cloaker-lite' ), __( 'Cloaked Links', 'premium-link-cloaker-lite' ), 'manage_options', 'plcl', array( $this, 'links_page' ) );

		add_submenu_page( 'plcl', __( 'PLC Lite: Add New Link', 'premium-link-cloaker-lite' ), __( 'Add New Link', 'premium-link-cloaker-lite' ), 'manage_options', 'plcl_add_link', array( $this, 'add_link_page' ) );
		
		add_submenu_page( 'plcl', __( 'PLC Lite: Categories', 'premium-link-cloaker-lite' ), __( 'Categories', 'premium-link-cloaker-lite' ), 'manage_options', 'plcl_categories', array( $this, 'categories_page' ) );

		add_submenu_page( 'plcl', __( 'PLC Lite: Stats', 'premium-link-cloaker-lite' ), __( 'Stats', 'premium-link-cloaker-lite' ), 'manage_options', 'plcl_stats', array( $this, 'stats_page' ) );
		
		add_submenu_page( 'plcl', __( 'PLC Lite: Settings', 'premium-link-cloaker-lite' ), __( 'Settings', 'premium-link-cloaker-lite' ), 'manage_options', 'plcl_settings', array( $this, 'settings_page' ) );

		add_submenu_page( 'plcl', __( 'PLC Lite: Go Pro!', 'premium-link-cloaker-lite' ), __( 'Go PRO', 'premium-link-cloaker-lite' ), 'manage_options', 'plcl_go_pro', array( $this, 'go_pro_page' ) );
	}

	public function admin_scripts() {
		if ( ! isset( $_GET['page'] ) || ! is_admin() ) {
			return;
		}

		if ( 'plcl' != substr( $_GET['page'], 0,  4) ) {
			return;
		}

		// Plugin
		wp_register_style( 'plcl_style_admin', PLCL_PLUGIN_URL . 'assets/css/style-admin.min.css', array(), PLCL_VERSION );
		wp_register_script( 'plcl_script_admin', PLCL_PLUGIN_URL . 'assets/js/script-admin.min.js', array( 'jquery' ), PLCL_VERSION, true );

		$args = array(
			'text_copied' => __( 'Copied to clipboard: ', 'premium-link-cloaker-lite' ),
		);
		wp_localize_script( 'plcl_script_admin', 'PLC', $args );

		// jQuery UI
		wp_register_style( 'plcl_jquery_ui_redmond', '//code.jquery.com/ui/1.10.3/themes/redmond/jquery-ui.css', array(), '1.10.3' );

		// Bootstrap
		wp_register_script( 'plcl_bootstrap_script', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js', array( 'jquery' ), '3.3.5', true );
		wp_register_style( 'plcl_bootstrap_style', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css', array(), '3.3.5' );
		wp_register_style( 'plcl_bootstrap_theme_style', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css', array(), '3.3.5' );

		// Morris JS
		wp_register_style( 'plcl_morris_style', '//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css', array(), '0.5.1' );
		wp_register_script( 'plcl_morris_script', '//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js', array( 'jquery' ), '0.5.1', false );

		// Raphael JS
		wp_register_script( 'plcl_raphael_script', '//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js', array( 'jquery' ), '2.1.0', false );

		// Select2
		wp_register_style( 'plcl_select2_style', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1-rc.1/css/select2.min.css', array(), '4.0.1-rc.1' );
		wp_register_style( 'plcl_select2_bootstrap_style', PLCL_PLUGIN_URL . 'assets/lib/select2/select2-bootstrap.min.css', array(), '0.1.0-beta.4' );
		wp_register_script( 'plcl_select2_script', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1-rc.1/js/select2.min.js', array( 'jquery' ), '4.0.1-rc.1', false );

		// Font Awesome
		wp_register_style( 'plcl_font_awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', array(), '4.4.0' );

		// AddThis Sharing Button
		wp_register_script( 'plcl_addthis_script', '//s7.addthis.com/js/300/addthis_widget.js', array(), PLCL_VERSION, true );

		// Zero Clipboard
		wp_register_script( 'plcl_zeroclipboard', PLCL_PLUGIN_URL . 'assets/lib/zeroclipboard/ZeroClipboard.min.js', array( 'jquery' ), '2.2.0', false );

		wp_enqueue_style( 'plcl_jquery_ui_redmond' );
		wp_enqueue_style( 'plcl_bootstrap_style' );
		wp_enqueue_style( 'plcl_bootstrap_theme_style' );
		wp_enqueue_style( 'plcl_at_style' );
		wp_enqueue_style( 'plcl_font_awesome' );
		wp_enqueue_style( 'plcl_morris_style' );
		wp_enqueue_style( 'plcl_select2_style' );
		wp_enqueue_style( 'plcl_select2_bootstrap_style' );
		wp_enqueue_style( 'plcl_style_admin' );
		
		wp_enqueue_script( 'plcl_bootstrap_script' );
		wp_enqueue_script( 'plcl_addthis_script' );
		wp_enqueue_script( 'plcl_zeroclipboard' );
		wp_enqueue_script( 'plcl_raphael_script' );
		wp_enqueue_script( 'plcl_morris_script' );
		wp_enqueue_script( 'plcl_select2_script' );
		wp_enqueue_script( 'plcl_script_admin' );
	}

	public function style_loader_tag( $tag, $handle ) {
		if ( 'plcl_bootstrap_style' == $handle ) {
			$tag = str_replace( 'href', 'integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous" href', $tag );
		}

		if ( 'plcl_bootstrap_theme_style' == $handle ) {
			$tag = str_replace( 'href', 'integrity="sha384-aUGj/X2zp5rLCbBxumKTCw2Z50WgIr1vs/PFN4praOTvYXWlVyh2UtNUU0KAUhAX" crossorigin="anonymous" href', $tag );
		}

		return $tag;
	}

	public function script_loader_tag( $tag, $handle ) {
		if ( 'plcl_bootstrap_script' == $handle ) {
			$tag = str_replace( 'src', 'integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous" src', $tag );
		}

		return $tag;
	}

	public function links_page() {
		$messages  = $this->get_messages();
		$cats      = premium_link_cloaker_lite()->category->get_all( array( 'cats_per_page' => 9999999 ) );
		$args = array(
			'paged' => isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1, 
		);

		if ( ! isset( $_GET['action'] ) ) {
			$links     = premium_link_cloaker_lite()->link->get_all( $args );
			$all_links = premium_link_cloaker_lite()->link->get_all( array( 'links_per_page' => 9999999 ) );
			$count = count( $all_links );
			include PLCL_PLUGIN_PATH . 'templates/admin/links.php';

		} elseif ( 'edit' == $_GET['action'] && isset( $_GET['link'] ) ) {
			$action = 'edit';
			$nonce  = wp_nonce_field( 'edit_link' . $_GET['link'], 'plcl_nonce' );
			$value  = $this->set_value( $_GET['link'] );
			$l_cats = premium_link_cloaker_lite()->link->get_cats( $_GET['link'] );
			include PLCL_PLUGIN_PATH . 'templates/admin/edit-link.php';

		} elseif ( 'search' == $_GET['action'] && isset( $_GET['cat'] ) ) {
			$links     = premium_link_cloaker_lite()->link->get_by_cat( absint( $_GET['cat'] ), $args );
			$all_links = premium_link_cloaker_lite()->link->get_by_cat( absint( $_GET['cat'] ), array( 'links_per_page' => 9999999 ) );
			$count = count( $all_links );
			include PLCL_PLUGIN_PATH . 'templates/admin/links.php';
		}
	}

	public function add_link_page() {
		$action = 'add';
		$nonce  = wp_nonce_field( 'add_link', 'plcl_nonce' );
		$value  = $this->set_value();
		$cats   = premium_link_cloaker_lite()->category->get_all( array( 'cats_per_page' => 9999999 ) );
		$l_cats = premium_link_cloaker_lite()->link->get_cats();
		include PLCL_PLUGIN_PATH . 'templates/admin/add-link.php';
	}

	public function categories_page() {
		if ( isset( $_GET['cat'] ) && isset( $_GET['action'] ) ) {
			$action = 'edit_cat';
			$nonce  = wp_nonce_field( 'edit_cat' . $_GET['cat'], 'plcl_nonce' );
			$value  = $this->set_cat_value( $_GET['cat'] );
		} else {
			$action = 'add_cat';
			$nonce  = wp_nonce_field( 'add_cat', 'plcl_nonce' );
			$value  = $this->set_cat_value();
		}

		if ( isset( $_GET['paged'] ) && is_numeric( $_GET['paged'] ) ) {
			$cats = premium_link_cloaker_lite()->category->get_all( 
				array( 
					'paged' => $_GET['paged'],
				) 
			);
		} else {
			$cats = premium_link_cloaker_lite()->category->get_all( array( 'cats_per_page' => 9999999 ) );
		}

		$all_cats = premium_link_cloaker_lite()->category->get_all( array( 'cats_per_page' => 9999999 ) );
		$count    = count( $all_cats );
		$messages = $this->get_messages();
		include PLCL_PLUGIN_PATH . 'templates/admin/categories.php';
	}
	
	public function stats_page() {
		$links = premium_link_cloaker_lite()->link->get_all( array( 'links_per_page' => 99999999 ) );
		$page  = isset( $_GET['paged'] ) ? $_GET['paged'] : 1; 
		if ( ! isset( $_GET['link'] ) && ! isset( $_GET['timeframe'] ) ) {
			$clicks = premium_link_cloaker_lite()->click->get_clicks( array(
				'paged' => $page,
			) );
			$args = array(
				'clicks_per_page' => 9999999,
			);
			$all_clicks = premium_link_cloaker_lite()->click->get_clicks( $args );
		} elseif ( isset( $_GET['link'] ) && is_numeric( $_GET['link'] ) && isset( $_GET['timeframe'] ) ) {
			$args = array(
				'day'     => absint( $_GET['timeframe'] ),
				'link_id' => absint( $_GET['link'] ),
				'paged'   => $page,
			);
			$clicks = premium_link_cloaker_lite()->click->get_clicks( $args );
			$args = array(
				'clicks_per_page' => 9999999,
				'day' => absint( $_GET['timeframe'] ),
				'link_id' => absint( $_GET['link'] ),
			);
			$all_clicks = premium_link_cloaker_lite()->click->get_clicks( $args );
		} elseif ( isset( $_GET['link'] ) && 'all' == $_GET['link'] && isset( $_GET['timeframe'] ) ) {
			$args = array(
				'day'     => absint( $_GET['timeframe'] ),
				'paged'   => $page,
			);
			$clicks = premium_link_cloaker_lite()->click->get_clicks( $args );
			$args = array(
				'clicks_per_page' => 9999999,
				'day' => absint( $_GET['timeframe'] ),
			);
			$all_clicks = premium_link_cloaker_lite()->click->get_clicks( $args );
		} else {
			echo '<div class="wrap">';
			_e( '<strong>Error</strong>: You have to select both of link and timeframe.', 'premium-link-cloaker-lite' );
			echo '</div>';
			exit();
		}
		$count = count( $all_clicks );
		include PLCL_PLUGIN_PATH . 'templates/admin/stats.php';
	}

	public function settings_page() {
		global $plcl_settings;
		include PLCL_PLUGIN_PATH . 'templates/admin/settings.php';
	}

	public function go_pro_page() {
		include PLCL_PLUGIN_PATH . 'templates/admin/go-pro.php';
	}

	public function admin_footer() {
		if ( isset( $_GET['page'] ) && 'plcl_stats' == $_GET['page'] ) {
			if ( ! isset( $_GET['link'] ) && ! isset( $_GET['timeframe'] ) ) {
				$clicks  = premium_link_cloaker_lite()->click->count_raw_clicks();
				$uniques = premium_link_cloaker_lite()->click->count_unique_clicks();

			} elseif ( isset( $_GET['link'] ) && is_numeric( $_GET['link'] ) && isset( $_GET['timeframe'] ) ) {
				$args = array(
					'link_id' => absint( $_GET['link'] ),
					'day'     => absint( $_GET['timeframe'] ),
				);
				$clicks  = premium_link_cloaker_lite()->click->count_raw_clicks( $args );
				$uniques = premium_link_cloaker_lite()->click->count_unique_clicks( $args );

			} elseif ( isset( $_GET['link'] ) && 'all' == $_GET['link'] &&  isset( $_GET['timeframe'] ) ) {
				$args = array(
					'day'     => absint( $_GET['timeframe'] ),
				);
				$clicks  = premium_link_cloaker_lite()->click->count_raw_clicks( $args );
				$uniques = premium_link_cloaker_lite()->click->count_unique_clicks( $args );
			}
		}
	}

	public function set_value( $link_id = '' ) {
		if ( ! empty( $link_id ) ) {
			$value = premium_link_cloaker_lite()->link->get( $link_id );
		} else {
			$value = array_fill_keys( array( 'name', 'target_url', 'cloaking_type', 'cloaked_url', 'keywords' ), '' );
		}
		return $value;
	}

	public function set_cat_value( $cat_id = '' ) {
		if ( ! empty( $cat_id ) ) {
			$value = premium_link_cloaker_lite()->category->get( $cat_id );
		} else {
			$value = array_fill_keys( array( 'name', 'description' ), '' );
		}
		return $value;
	}

	public function redirect( $action, $result = true, $page = 'plcl' ) {
		$status = true === $result ? 'success' : 'failed';

		wp_redirect( 
			add_query_arg( array(
				'status' => $action . '_' . $status
			), menu_page_url( $page, false ) ) 
		);
		exit();
	}

	public function get_messages() {
		$messages = array(
			'add_success'    => __( 'New link created.', 'premium-link-cloaker-lite' ),
			'add_failed'     => __( 'Failed to create new link.', 'premium-link-cloaker-lite' ),
			'edit_success'   => __( 'Link updated.', 'premium-link-cloaker-lite' ),
			'edit_failed'    => __( 'Failed to update the link.', 'premium-link-cloaker-lite' ),
			'delete_success' => __( 'Link(s) deleted.', 'premium-link-cloaker-lite' ),
			'delete_failed'  => __( 'Failed to delete the link.', 'premium-link-cloaker-lite' ),
			'add_cat_success'    => __( 'New category created.', 'premium-link-cloaker-lite' ),
			'add_cat_failed'     => __( 'Failed to create new category.', 'premium-link-cloaker-lite' ),
			'edit_cat_success'   => __( 'Category updated.', 'premium-link-cloaker-lite' ),
			'edit_cat_failed'    => __( 'Failed to update the category.', 'premium-link-cloaker-lite' ),
			'delete_cat_success' => __( 'Category(s) deleted.', 'premium-link-cloaker-lite' ),
			'delete_cat_failed'  => __( 'Failed to delete the category.', 'premium-link-cloaker-lite' ),
		);
		return $messages;
	}

	public function get_post_types() {
		$args = array(
			'public' => true,
		);

		$post_types = get_post_types( $args, 'objects', 'and' );

		return $post_types;
	}
}