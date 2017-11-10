<?php
/**
 * The Beans Utilities is a set of tools to ease building applications.
 *
 * This file is filled with array helpers.
 *
 * Since these functions are used throughout the Beans framework and are therefore required, they are
 * loaded automatically when the Beans framework is included.
 *
 * @package API\Utilities
 */


/**
 * Count recursive array.
 *
 * This function is able to count a recursive array. The depth can be defined as well as if the parent should be
 * counted. For instance, if $depth is defined and $count_parent is set to false, only the level of the
 * defined depth will be counted.
 *
 * @since 1.0.0
 *
 * @param string $array The array.
 * @param int|bool $depth Optional. Depth until which the entries should be counted.
 * @param bool $count_parent Optional. Whether the parent should be counted or not.
 *
 * @return int Number of entries found.
 */
function beans_count_recursive( $array, $depth = false, $count_parent = true ) {

	if ( ! is_array( $array ) ) {
		return 0;
	}

	if ( 1 === $depth ) {
		return count( $array );
	}

	if ( ! is_numeric( $depth ) ) {
		return count( $array, COUNT_RECURSIVE );
	}

	$count = $count_parent ? count( $array ) : 0;

	foreach ( $array as $_array ) {

		if ( is_array( $_array ) ) {
			$count += beans_count_recursive( $_array, $depth - 1, $count_parent );
		} else {
			$count += 1;
		}
	}

	return $count;

}

/**
 * Checks if a value exists in a multi-dimensional array.
 *
 * @since 1.0.0
 *
 * @param string $needle The searched value.
 * @param array $haystack The multi-dimensional array.
 * @param bool $strict If the third parameter strict is set to true, the beans_in_multi_array()
 *                         function will also check the types of the needle in the haystack.
 *
 * @return bool True if needle is found in the array, false otherwise.
 */
function beans_in_multi_array( $needle, array $haystack, $strict = false ) {

	if ( in_array( $needle, $haystack, $strict ) ) {
		return true;
	}

	foreach ( (array) $haystack as $value ) {
		if ( is_array( $value ) && beans_in_multi_array( $needle, $value ) ) {
			return true;
		}
	}

	return false;

}

/**
 * Checks if a key or index exists in a multi-dimensional array.
 *
 * @since 1.0.0
 *
 * @param string $needle The searched value.
 * @param array $haystack The multi-dimensional array.
 *
 * @return bool True if needle is found in the array, False otherwise.
 */
function beans_multi_array_key_exists( $needle, array $haystack ) {

	if ( array_key_exists( $needle, $haystack ) ) {
		return true;
	}

	foreach ( $haystack as $value ) {
		if ( is_array( $value ) && beans_multi_array_key_exists( $needle, $value ) ) {
			return true;
		}
	}

	return false;

}

/**
 * Search content for shortcodes and filter shortcodes through their hooks.
 *
 * Shortcodes must be delimited with curly brackets (e.g. {key}) and correspond to the searched array key.
 *
 * @since 1.0.0
 *
 * @param string $content Content containing the shortcode(s) delimited with curly brackets (e.g. {key}).
 *                        Shortcode(s) correspond to the searched array key and will be replaced by the array
 *                        value if found.
 * @param array $haystack The associative array used to replace shortcode(s).
 *
 * @return string Content with shortcodes filtered out.
 */
function beans_array_shortcodes( $content, array $haystack ) {

	if ( ! preg_match_all( '#{(.*?)}#', $content, $matches ) ) {
		return $content;
	}

	foreach ( $matches[1] as $needle ) {

		$sub_keys = explode( '.', $needle );
		$value    = false;

		foreach ( $sub_keys as $sub_key ) {

			$search = $value ? $value : $haystack;
			$value  = beans_get( $sub_key, $search );

		}

		if ( $value ) {
			$content = str_replace( '{' . $needle . '}', $value, $content );
		}
	}

	return $content;

}

if ( ! function_exists( 'array_replace_recursive' ) ) {

	/**
	 * PHP 5.2 pollyfill fallback.
	 *
	 * @ignore
	 *
	 * @param array|mixed $base
	 * @param array|mixed $replacements
	 *
	 * @return array
	 */
	function array_replace_recursive( $base, $replacements ) {

		if ( ! is_array( $base ) || ! is_array( $replacements ) ) {
			return $base;
		}

		foreach ( $replacements as $key => $value ) {

			if ( is_array( $value ) && is_array( $from_base = beans_get( $key, $base ) ) ) {
				$base[ $key ] = array_replace_recursive( $from_base, $value );
			} else {
				$base[ $key ] = $value;
			}
		}

		return $base;

	}
}

/**
 * Remove an element from the given array via it's value (needle).
 *
 * @since 1.5.0
 *
 * @param string $needle Value to find and, if it exists, remove.
 * @param array $haystack The subject array to search.
 *
 * @return array
 */
function beans_remove_array_element( $needle, array &$haystack ) {

	$index = array_search( $needle, $haystack );
	if ( false === $index ) {
		return $haystack;
	}

	unset( $haystack[ $index ] );

	return $haystack;

}