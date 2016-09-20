<?php
/**
 * Plugin Name: WP Discourse WooCommerce Support
 * Description: Extends the WP Discourse plugin to allow it to be used with WooCommerce
 * Version: 0.1
 * Author: scossar
 */

namespace WPDiscourse\WooCommerceSupport;

add_action( 'plugins_loaded', __NAMESPACE__ . '\\init' );

function init() {
	if ( class_exists( '\WPDiscourse\Discourse\Discourse' ) ) {
		$woocommerce_support = new \WPDiscourse\WooCommerceSupport\WooCommerceSupport();
	}

}

class WooCommerceSupport {

	public function __construct() {
		add_filter( 'woocommerce_login_redirect', array( $this, 'set_redirect' ) );
		add_filter( 'woocommerce_product_review_count', array( $this, 'comments_number' ) );
	}

	/**
	 * Sets the login redirect so that it can include the query parameters required for single sign on with Discourse.
	 *
	 * @param string $redirect The redirect URL supplied by WooCommerce.
	 *
	 * @return mixed
	 */
	public function set_redirect( $redirect ) {
		if ( isset( $_GET['redirect_to'] ) && esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ) ) { // Input var okay.
			$redirect = esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ); // Input var okay.

			return $redirect;
		}

		return $redirect;
	}

	/**
	 * Replaces the WooCommerce comments count with the Discourse comments count.
	 *
	 * @param int $count The comments count returned from WooCommerce.
	 *
	 * @return mixed
	 */
	function comments_number( $count ) {
		global $post;
		$discourse_post_id = get_post_meta( $post->ID, 'discourse_post_id', true );

		if ( $discourse_post_id > 0 ) {
			$count = get_post_meta( $post->ID, 'discourse_comments_count', true );

			return $count;
		}

		return $count;
	}
}