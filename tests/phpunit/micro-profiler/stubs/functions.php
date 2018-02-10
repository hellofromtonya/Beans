<?php
/**
 * Stubbed functions for the Filters API tests.
 *
 * @package Beans\Framework\Tests\Unit\API\Filters\Stubs
 *
 * @since   1.5.0
 */

if ( ! function_exists( 'beans_loop_query_args_base' ) ) {
	/**
	 * Modify the Bean's loop query arguments.
	 *
	 * @since 1.5.0
	 *
	 * @return array
	 */
	function beans_loop_query_args_base() {
		return array( 'base' );
	}
}

if ( ! function_exists( 'beans_loop_query_args_main' ) ) {
	/**
	 * Modify the Bean's loop query arguments. Callback for the sub-hook.
	 *
	 * @since 1.5.0
	 *
	 * @param array $args The query's arguments.
	 *
	 * @return array
	 */
	function beans_loop_query_args_main( array $args ) {
		$args[] = '_main';

		return $args;
	}
}
