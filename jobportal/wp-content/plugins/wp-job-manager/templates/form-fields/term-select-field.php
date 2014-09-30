<?php 

// Get selected value
if ( isset( $field['value'] ) ) {
	$selected = $field['value'];
} elseif ( is_int( $field['default'] ) ) {
	$selected = $field['default'];
} elseif ( ! empty( $field['default'] ) && ( $term = get_term_by( 'slug', $field['default'], $field['taxonomy'] ) ) ) {
	$selected = $term->term_id;
} else {
	$selected = '';
}

// Select only supports 1 value
if ( is_array( $selected ) ) {
	$selected = current( $selected );
}

wp_dropdown_categories( array( 
	'taxonomy'         => $field['taxonomy'],
	'hierarchical'     => 1, 
	'show_option_all'  => false,
	'show_option_none' => $field['required'] ? '' : '-',
	'name'             => isset( $field['name'] ) ? $field['name'] : $key, 
	'orderby'          => 'name', 
	'selected'         => $selected,
	'hide_empty'       => false
) );