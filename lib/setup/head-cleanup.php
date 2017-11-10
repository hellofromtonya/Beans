<?php
/**
 * The Beans Utilities is a set of tools to ease building applications.
 *
 * Since these functions are used throughout the Beans framework and are therefore required, they are
 * loaded automatically when the Beans framework is included.
 *
 * @package API\Utilities
 */

add_action( 'get_header', 'beans_cleanup_head', 99 );
/**
 * Cleans up the unnecessary code that WordPress loads into the `<head>`.
 *
 * @since 1.5.0
 */
function beans_cleanup_head() {

	$config = array(
		array( 'wp_generator' ),
		array( 'adjacent_posts_rel_link_wp_head' ),
		array( 'wlwmanifest_link' ),
		array( 'wp_shortlink_wp_head' ),
	);

	if ( ( is_single() && get_option( 'beans_post_comments_disabled', false ) ) ||
	     ( is_page() && get_option( 'beans_page_comments_disabled', false ) ) ) {
		$config[] = array( 'feed_links_extra', 3 );
	}

	/**
	 * Configurable filter for cleaning up the <head>, i.e. removing unnecessary
	 * hooked features.
	 *
	 * To disable, return an empty array or register `__return_false` to the filter.
	 *
	 * @since 1.5.0
	 *
	 * @param array Array of callbacks to unregister from the `wp_head` event.
	 */
	$config = (array) apply_filters( 'beans_head_cleanup', $config );
	foreach ( $config as $cleanupItem ) {
		remove_action(
			'wp_head',
			$cleanupItem[0],
			isset( $cleanupItem[1] ) ? $cleanupItem[1] : 10
		);
	}

}


add_action( 'init', 'beans_disable_emojis', 9999 );
/**
 * Disable the emojis.
 *
 * @since 1.5.0
 *
 * @return null|void
 */
function beans_disable_emojis() {

	if ( true !== (bool) get_option( 'beans_disable_emojis', 0 ) ) {
		return;
	}

	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );

	remove_action( 'embed_head', 'print_emoji_detection_script' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}

add_action( 'admin_init', 'beans_disable_admin_emojis', 9999 );
/**
 * Disable Emojicons in the back-end.
 *
 * @since 1.5.0
 *
 * @return void
 */
function beans_disable_admin_emojis() {

	if ( true !== (bool) get_option( 'beans_disable_emojis', 0 ) ) {
		return;
	}

	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	add_filter( 'tiny_mce_plugins', 'beans_disable_wpemoji_plugin' );

}

/**
 * Disable the 'wpemoji' plugin for the TinyMCE editor.
 *
 * @since 1.5.0
 *
 * @param array $plugins An array of default TinyMCE plugins.
 *
 * @return array
 */
function beans_disable_wpemoji_plugin( array $plugins ) {
	if ( ! $plugins ) {
		return array();
	}

	return beans_remove_array_element( 'wpemoji', $plugins );
}
