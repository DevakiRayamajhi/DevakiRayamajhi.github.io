<?php do_action( 'blogslog_doctype' ); ?>
<head>
<?php

	do_action( 'blogslog_before_wp_head' );

	wp_head(); 
?>
</head>

<body <?php body_class(); ?>>

<?php do_action( 'wp_body_open' ); ?>

<?php

	do_action( 'blogslog_page_start_action' ); 

	do_action( 'blogslog_before_header' );

	do_action( 'top_blogger_header_action' );

	do_action( 'blogslog_content_start_action' );

	do_action( 'blogslog_header_image_action' );
	
	if ( blogslog_is_frontpage() ) {
    	$options = blogslog_get_theme_options();

		$sorted = array( 'banner','list_articles', 'featured', 'instagram' );
	
		foreach ( $sorted as $section ) {
			if ( $section == 'list_articles' || $section == 'featured' || $section == 'instagram' ) {
				add_action( 'top_blogger_primary_content', 'top_blogger_add_'. $section .'_section' );
			}else{
				add_action( 'top_blogger_primary_content', 'blogslog_add_'. $section .'_section' );
			}	
		}

		do_action( 'top_blogger_primary_content' );
	}