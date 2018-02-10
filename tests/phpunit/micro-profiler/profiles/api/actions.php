<?php
/**
 * Actions API functions to be profiled.
 *
 * @package Beans\Framework\Tests\Micro_Profiler
 *
 * @since   1.5.0
 */

namespace Beans\Framework\Tests\Micro_Profiler;

/**
 * Profile beans_add_smart_action().
 *
 * @since 1.5.0
 *
 * @param Micro_Profiler $profiler Instance of the Micro Profiler.
 *
 * @return void
 */
function profile_beans_add_smart_action( Micro_Profiler $profiler ) {
	$profiler->start_segment( 'beans_add_smart_action' );
	beans_add_smart_action( 'beans_header', __FUNCTION__, 15, 3 );
	$profiler->stop_segment( 'beans_add_smart_action' );

	remove_action( 'beans_header', __FUNCTION__, 15 );
}

/**
 * Profile beans_modify_action_priority().
 *
 * @since 1.5.0
 *
 * @param Micro_Profiler $profiler Instance of the Micro Profiler.
 *
 * @return void
 */
function profile_beans_modify_action_priority( Micro_Profiler $profiler ) {
	$id = __FUNCTION__;
	beans_add_smart_action( 'beans_header', $id, 15, 3 );

	$profiler->start_segment( 'beans_modify_action_priority' );
	beans_modify_action_priority( $id, 4 );
	$profiler->stop_segment( 'beans_modify_action_priority' );

	remove_action( 'beans_header', $id, 4 );
}

/**
 * Profile beans_replace_action_callback().
 *
 * @since 1.5.0
 *
 * @param Micro_Profiler $profiler Instance of the Micro Profiler.
 *
 * @return void
 */
function profile_beans_replace_action_callback( Micro_Profiler $profiler ) {
	$id = __FUNCTION__;
	beans_add_smart_action( 'beans_header', $id, 15, 3 );

	$profiler->start_segment( 'beans_replace_action_callback' );
	beans_replace_action_callback( $id, 'beans_loop_query_args_base' );
	$profiler->stop_segment( 'beans_replace_action_callback' );

	remove_action( 'beans_header', 'beans_loop_query_args_base', 15 );
}

/**
 * Profile beans_remove_action().
 *
 * @since 1.5.0
 *
 * @param Micro_Profiler $profiler Instance of the Micro Profiler.
 *
 * @return void
 */
function profile_beans_remove_action( Micro_Profiler $profiler ) {
	$id = __FUNCTION__;
	beans_add_smart_action( 'beans_header', $id, 15, 3 );

	$profiler->start_segment( 'beans_remove_action' );
	beans_remove_action( $id );
	$profiler->stop_segment( 'beans_remove_action' );
}

/**
 * Profile beans_reset_action().
 *
 * @since 1.5.0
 *
 * @param Micro_Profiler $profiler Instance of the Micro Profiler.
 *
 * @return void
 */
function profile_beans_reset_action( Micro_Profiler $profiler ) {
	$id = __FUNCTION__;
	beans_add_smart_action( 'beans_header', $id, 15, 3 );
	beans_replace_action_callback( $id, 'beans_loop_query_args_base' );

	$profiler->start_segment( 'beans_reset_action' );
	beans_reset_action( $id );
	$profiler->stop_segment( 'beans_reset_action' );

	remove_action( 'beans_header', $id, 15 );
}
