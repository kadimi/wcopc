<?php

// Register options
paf_options( array(
	'wcopc_notice_message' => array (
		'page' => 'wcopc',
		'title' => __( 'Notice message' ),
		'default' => __( '&quot;%product_title%&quot; cannot be added to the cart.' ),
		'description' => __( 'Available replacement patters: <code>%product_title%</code>' ),
	),
	'wcopc_notice_type' => array (
		'page' => 'wcopc',
		'title' => __( 'Notice type' ),
		'type' => 'select',
		'options' => array(
			'notice' => __( 'Notice' ),
			'error' => __( 'Error' ),
			'success' => __( 'Success' ),
		),
		'default' => 'error',
	),
) );
