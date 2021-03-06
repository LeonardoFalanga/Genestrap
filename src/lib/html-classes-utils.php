<?php
/**
 * Some utility functions to make easier to do some common operstions in the theme.
 *
 * @package Genestrap
 */

/*
 * This file is part of the Genestrap Genesis WordPress theme.
 *
 * (c) Adamo Crespi <hello@Aerendir.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Appends the given $class to the given $tag.
 *
 * @param string $tag The tag to which apply the class.
 * @param string $class The class to apply to the tag.
 *
 * @return mixed
 */
function shq_genestrap_add_html_structural_class( string $tag, string $class ) {
	if ( false === strpos( $tag, $class ) ) {
		$tag = str_replace( '">', sprintf( ' %s">', $class ), $tag );
	}

	return $tag;
}

/**
 * Adds a class to the array of classes to apply.
 *
 * NOTE: When calling this method do it this way:
 *
 *      return shq_genstrap_add_html_class();
 *
 * The return value is required by shq_genestrap_process_genesis_attr() to work properly.
 *
 * @param string $node This is the context of a genesis_structural_wrap_* or of a genesis_attr_*.
 * @param string $class The class to apply.
 *
 * @return array
 */
function shq_genestrap_add_html_class( string $node, string $class ):array {
	// Assign the classes to a global variable so they can be retrieved by apply_classes().
	global $shq_genestrap_classes;

	if ( null === $shq_genestrap_classes ) {
		$shq_genestrap_classes = [];
	}

	if ( false === shq_genestrap_has_html_class( $node, $class ) ) {
		// Sanitize the class.
		$class = sanitize_html_class( $class );

		// If it is not empty after the sanitization...
		if ( false === empty( $class ) ) {
			// Adds it to the array.
			$shq_genestrap_classes[ $node ][] = $class;
		}
	}

	return $shq_genestrap_classes;
}

/**
 * Checks if the given class is already set for the node.
 *
 * @param string $node This is the context of a genesis_structural_wrap_* or of a genesis_attr_*.
 * @param string $class The class to check if exists.
 *
 * @return bool
 */
function shq_genestrap_has_html_class( string $node, string $class ):bool {
	global $shq_genestrap_classes;

	return is_array( $shq_genestrap_classes ) &&
		// If the node isn't already set...
		array_key_exists( $node, $shq_genestrap_classes ) &&
		// If the class isn't already added to the list of the node...
		in_array( $class, $shq_genestrap_classes[ $node ], true );
}

/**
 * Checks if the given class is already set for the node.
 *
 * @param string $node This is the context of a genesis_structural_wrap_* or of a genesis_attr_*.
 * @param string $class_needle The class to check if exists.
 *
 * @return bool
 */
function shq_genestrap_has_html_class_that_contains( string $node, string $class_needle ):bool {
	global $shq_genestrap_classes;

	$found = false;

	if (
		is_array( $shq_genestrap_classes ) &&
		// If the node isn't already set...
		array_key_exists( $node, $shq_genestrap_classes )
	) {
		foreach ( $shq_genestrap_classes[ $node ] as $class ) {
			if ( false !== strpos( $class, $class_needle ) ) {
				$found = true;
			}
		}
	}

	return $found;
}

/**
 * Helper method to apply multiple classes in one pass.
 *
 * @param string $node This is the context of a genesis_structural_wrap_* or of a genesis_attr_*.
 * @param array  $classes The classes to add.
 *
 * @return array
 */
function shq_genestrap_add_html_classes( string $node, array $classes ):array {
	$return = [];
	foreach ( $classes as $class ) {
		$return = shq_genestrap_add_html_class( $node, $class );
	}

	return $return;
}

/**
 * Helper method to remove a class from the given node.
 *
 * @param string $node This is the context of a genesis_structural_wrap_* or of a genesis_attr_*.
 * @param string $class The class to remove.
 *
 * @return array
 */
function shq_genestrap_remove_html_class( string $node, string $class ):array {
	global $shq_genestrap_classes;

	if ( shq_genestrap_has_html_class( $node, $class ) ) {
		$class_id = array_search( $class, $shq_genestrap_classes[ $node ], true );

		unset( $shq_genestrap_classes[ $node ][ $class_id ] );
	}

	return $shq_genestrap_classes;
}

/**
 * Applies the given classes.
 *
 * @param array  $attr The attribute to which the classes have to be applied.
 * @param string $context The context.
 *
 * @return array
 */
function apply_classes( $attr, $context ):array {
	global $shq_genestrap_classes;

	$classes = '';
	if ( isset( $shq_genestrap_classes[ $context ] ) ) {
		$classes = $shq_genestrap_classes[ $context ];
		if ( false === empty( $attr['class'] ) ) {
			$attr_classes = explode( ' ', $attr['class'] );

			$classes = array_unique( array_merge( $attr_classes, $classes ) );
		}

		$classes = implode( ' ', $classes );
	}

	$attr['class'] = $classes;

	return $attr;
}
