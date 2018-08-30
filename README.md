# WP Discourse WooCommerce Support

This plugin allows you to integrate the **WP Discourse** plugin with **WooCommerce**.

## What it does

- hooks into the `woocommerce_login_redirect` filter to add the query parameters to the redirect path
that are required for **Discourse Single Sign On** to work.
- hooks into the `woocommerce_product_get_review_count` filter so that **Discourse** comments are used for
the review count.

## How to make it work

This plugin depends on both having a [Discourse forum](http://www.discourse.org/) and on having
the [WP Discourse plugin](https://github.com/discourse/wp-discourse) installed and actived on your site.

If you have enabled **Discourse SSO** on your site, to use a **WooCommerce** login
page as your site's main login page requires setting the **login path** option in the **WP Discourse**
settings. The **login path** option is found under the **SSO** tab of the **WP Discourse** settings page.
If you would like your login page to be at `http://example.com/my-account`, then the login path in the
**WP Discourse** settings should be set to `/my-account`.

## Installation

To install this plugin, download a zip file of it from this page and then install it through your
site's admin section.

## Alternatives to install the plugin

For now, the plugin only contains two functions. If you prefer, you can just add these functions to your
theme's `functions.php` file:

```php

/**
 * Sets the login redirect so that it can include the query parameters required for single sign on with Discourse.
 *
 * @param string $redirect The redirect URL supplied by WooCommerce.
 *
 * @return mixed
 */
function my_namespace_wp_discourse_woocommerce_redirect( $redirect ) {
	if ( isset( $_GET['redirect_to'] ) && esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ) ) { // Input var okay.
		$redirect = esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ); // Input var okay.

		return $redirect;
	}

	return $redirect;
}
add_filter( 'woocommerce_login_redirect', 'my_namespace_wp_discourse_woocommerce_redirect' );
```

```php

/**
 * Replaces the WooCommerce comments count with the Discourse comments count.
 *
 * @param int $count The comments count returned from WooCommerce.
 *
 * @return mixed
 */

function my_namespace_wp_discourse_comments_number( $count ) {
	global $post;
	$discourse_post_id = get_post_meta( $post->ID, 'discourse_post_id', true );

	if ( $discourse_post_id > 0 ) {
		$count = get_post_meta( $post->ID, 'discourse_comments_count', true );

		return $count;
	}

	return $count;
}
add_filter( 'woocommerce_product_review_count', 'my_namespace_wp_discourse_comments_number' );

```