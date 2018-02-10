<?php
/**
 * FIlters API functions to be profiled.
 *
 * @package Beans\Framework\Tests\Micro_Profiler
 *
 * @since   1.5.0
 */

namespace Beans\Framework\Tests\Micro_Profiler;

/**
 * Profile beans_add_filter().
 *
 * @since 1.5.0
 *
 * @param Micro_Profiler $profiler Instance of the Micro Profiler.
 *
 * @return void
 */
function profile_beans_add_filter( Micro_Profiler $profiler ) {
	$profiler->start_segment( 'beans_add_filter' );
	beans_add_filter( 'beans_loop_query_args', 'beans_loop_query_args_main', 20, 1 );
	$profiler->stop_segment( 'beans_add_filter' );

	remove_action( 'beans_loop_query_args', 'beans_loop_query_args_main', 20 );
}

/**
 * Profile beans_apply_filters().
 *
 * @since 1.5.0
 *
 * @param Micro_Profiler $profiler Instance of the Micro Profiler.
 *
 * @return void
 */
function profile_beans_apply_filters( Micro_Profiler $profiler ) {
	add_filter( 'beans_loop_query_args', 'beans_loop_query_args_base' );
	add_filter( 'beans_loop_query_args[_main]', 'beans_loop_query_args_main' );

	$profiler->start_segment( 'beans_apply_filters' );
	beans_apply_filters( 'beans_loop_query_args[_main]', 'foo' );
	$profiler->stop_segment( 'beans_apply_filters' );

	remove_filter( 'beans_loop_query_args', 'beans_loop_query_args_base' );
	remove_filter( 'beans_loop_query_args[_main]', 'beans_loop_query_args_main' );
}

/**
 * Profile beans_has_filters().
 *
 * @since 1.5.0
 *
 * @param Micro_Profiler $profiler Instance of the Micro Profiler.
 *
 * @return void
 */
function profile_beans_has_filters( Micro_Profiler $profiler ) {
	add_filter( 'beans_loop_query_args[_first][_second]', 'beans_loop_query_args_main' );

	$profiler->start_segment( 'beans_has_filters' );
	beans_has_filters( 'beans_loop_query_args[_first][_second]', 'beans_loop_query_args_main' );
	$profiler->stop_segment( 'beans_has_filters' );

	remove_filter( 'beans_loop_query_args[_first][_second]', 'beans_loop_query_args_main' );
}
