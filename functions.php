<?php
// Post type and taxonomy
add_action( 'acf/include_fields', function() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
	'key' => 'group_67cfefedd4f61',
	'title' => 'Branch Location',
	'fields' => array(
		array(
			'key' => 'field_67cfefeeba1fe',
			'label' => 'Latitude',
			'name' => 'lat',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_67cff035ca814',
			'label' => 'Longitude',
			'name' => 'lng',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_67cff044ca815',
			'label' => 'Website Link',
			'name' => 'websiteLink',
			'aria-label' => '',
			'type' => 'url',
			'instructions' => '',
			'required' => false,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'branch-location',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
) );
} );

add_action( 'init', function() {
	register_taxonomy( 'branch_ctg', array(
	0 => 'branch-location',
), array(
	'labels' => array(
		'name' => 'Branch Categories',
		'singular_name' => 'Branch Category',
	),
	'public' => true,
	'hierarchical' => true,
	'show_in_menu' => true,
	'show_in_rest' => true,
	'show_admin_column' => true,
	'rewrite' => array(
		'hierarchical' => true,
	),
) );
} );

add_action( 'init', function() {
	register_post_type( 'branch-location', array(
	'labels' => array(
		'name' => 'Branch Locations',
		'singular_name' => 'Branch Location',
		'menu_name' => 'Branch locations',
		'all_items' => 'All Branch locations',
		'edit_item' => 'Edit Branch Location',
		'view_item' => 'View Branch Location',
		'view_items' => 'View Branch locations',
		'add_new_item' => 'Add New Branch Location',
		'add_new' => 'Add New Branch Location',
		'new_item' => 'New Branch Location',
		'parent_item_colon' => 'Parent Branch Location:',
		'search_items' => 'Search Branch locations',
		'not_found' => 'No branch locations found',
		'not_found_in_trash' => 'No branch locations found in the bin',
		'archives' => 'Branch Location Archives',
		'attributes' => 'Branch Location Attributes',
		'insert_into_item' => 'Insert into branch location',
		'uploaded_to_this_item' => 'Uploaded to this branch location',
		'filter_items_list' => 'Filter branch locations list',
		'filter_by_date' => 'Filter branch locations by date',
		'items_list_navigation' => 'Branch locations list navigation',
		'items_list' => 'Branch locations list',
		'item_published' => 'Branch Location published.',
		'item_published_privately' => 'Branch Location published privately.',
		'item_reverted_to_draft' => 'Branch Location reverted to draft.',
		'item_scheduled' => 'Branch Location scheduled.',
		'item_updated' => 'Branch Location updated.',
		'item_link' => 'Branch Location Link',
		'item_link_description' => 'A link to a branch location.',
	),
	'public' => true,
	'show_in_rest' => true,
	'menu_icon' => 'dashicons-location',
	'supports' => array(
		0 => 'title',
		1 => 'author',
		2 => 'editor',
		3 => 'thumbnail',
		4 => 'custom-fields',
	),
	'delete_with_user' => false,
) );
} );

