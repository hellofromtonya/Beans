<?php
/**
 * Micro Profiler runtime configuration parameters.
 *
 * @package Beans\Framework\Tests\Micro_Profiler
 *
 * @since   1.5.0
 */

namespace Beans\Framework\Tests\Micro_Profiler;

return array(
	'beans_add_smart_action'        => __NAMESPACE__ . '\profile_beans_add_smart_action',
	'beans_modify_action_priority'  => __NAMESPACE__ . '\profile_beans_modify_action_priority',
	'beans_replace_action_callback' => __NAMESPACE__ . '\profile_beans_replace_action_callback',
	'beans_remove_action'           => __NAMESPACE__ . '\profile_beans_remove_action',
	'beans_reset_action'            => __NAMESPACE__ . '\profile_beans_reset_action',
	'beans_add_filter'              => __NAMESPACE__ . '\profile_beans_add_filter',
	'beans_apply_filters'           => __NAMESPACE__ . '\profile_beans_apply_filters',
	'beans_has_filters'             => __NAMESPACE__ . '\profile_beans_has_filters',
);
