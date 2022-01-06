<?php

if ( ! function_exists( 'top_blogger_enqueue_styles' ) ) :

	function top_blogger_enqueue_styles() {
		wp_enqueue_style( 'top-blogger-style-parent', get_template_directory_uri() . '/style.css' );

		wp_enqueue_style( 'top-blogger-style', get_stylesheet_directory_uri() . '/style.css', array( 'top-blogger-style-parent' ), '1.0.0' );

		wp_enqueue_script( 'top-blogger-custom', get_theme_file_uri() . '/custom.js', array(), '1.0', true );

		wp_enqueue_style( 'top-blogger-fonts', top_blogger_fonts_url(), array(), null );

		wp_register_script( "top-blogger-ajax", get_theme_file_uri() . '/latest-post-ajax.js', array( 'jquery' ), '', true );

        wp_localize_script( 'top-blogger-ajax', 'top_blogger', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );    

        wp_enqueue_script( 'top-blogger-ajax' );
	}
endif;
add_action( 'wp_enqueue_scripts', 'top_blogger_enqueue_styles', 99 );

function top_blogger_customize_control_js() {

	wp_enqueue_style( 'top-blogger-customize-controls-css', get_theme_file_uri() . '/customizer-control.css' );

}
add_action( 'customize_controls_enqueue_scripts', 'top_blogger_customize_control_js' );



if ( !function_exists( 'top_blogger_block_editor_styles' ) ):

	function top_blogger_block_editor_styles() {
		wp_enqueue_style( 'top-blogger-fonts', top_blogger_fonts_url(), array(), null );
	}

endif;

add_action( 'enqueue_block_editor_assets', 'top_blogger_block_editor_styles' );


if ( ! function_exists( 'top_blogger_fonts_url' ) ) :

function top_blogger_fonts_url() {
	
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	if ( 'off' !== _x( 'on', 'Poppins font: on or off', 'top-blogger' ) ) {
		$fonts[] = 'Poppins:400,500,600,700';
	}

	$query_args = array(
		'family' => urlencode( implode( '|', $fonts ) ),
		'subset' => urlencode( $subsets ),
	);

	if ( $fonts ) {
		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return esc_url_raw( $fonts_url );
}

endif;

if ( ! function_exists( 'top_blogger_header_start' ) ) :

	function top_blogger_header_start() { ?>
        <div class="menu-overlay"></div>
		<header id="masthead" class="site-header" role="banner">
			<div class="wrapper">
			 	<div id="site-menu">
		<?php
	}
endif;
add_action( 'top_blogger_header_action', 'top_blogger_header_start', 10 );

if ( ! function_exists( 'blogslog_site_branding' ) ) :

	function blogslog_site_branding() {
		$options  = blogslog_get_theme_options();
		$header_txt_logo_extra = $options['header_txt_logo_extra'];		
		?>
		
		<div class="site-branding">
			<div class="site-branding-wrapper">
				<?php if ( in_array( $header_txt_logo_extra, array( 'show-all', 'logo-title', 'logo-tagline' ) )  ) { ?>
					<div class="site-logo">
						<?php the_custom_logo(); ?>
					</div>
				<?php } 
				if ( in_array( $header_txt_logo_extra, array( 'show-all', 'title-only', 'logo-title', 'show-all', 'tagline-only', 'logo-tagline' ) ) ) : ?>
					<div id="site-details">
						<?php
						if( in_array( $header_txt_logo_extra, array( 'show-all', 'title-only', 'logo-title' ) )  ) {
							if ( blogslog_is_latest_posts() ) : ?>
								<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
							<?php else : ?>
								<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
							<?php
							endif;
						} 
						if ( in_array( $header_txt_logo_extra, array( 'show-all', 'tagline-only', 'logo-tagline' ) ) ) {
							$description = get_bloginfo( 'description', 'display' );
							if ( $description || is_customize_preview() ) : ?>
								<p class="site-description"><?php echo esc_html( $description ); /* WPCS: xss ok. */ ?></p>
							<?php
							endif; 
						}?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
			<?php
			echo blogslog_get_svg( array( 'icon' => 'menu', 'class' => 'icon-menu' ) );
			echo blogslog_get_svg( array( 'icon' => 'close', 'class' => 'icon-menu' ) );
			?>					
			<span class="menu-label"><?php esc_html_e( 'Menu', 'top-blogger' ); ?></span>
		</button>
		<?php
	}
endif;
add_action( 'top_blogger_header_action', 'blogslog_site_branding', 20 );

if ( ! function_exists( 'top_blogger_site_navigation' ) ) :

	function top_blogger_site_navigation() { 
		$options = blogslog_get_theme_options();
		?>
		<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="Primary Menu">

			<?php  
				$social = '';
				if ( has_nav_menu( 'social' ) ) :
            	
					$social .= '<li class="social-menu-item"><div class="social-icons">';
					$social .= wp_nav_menu( array(
            			'theme_location' => 'social',
            			'container' => false,
            			'menu_class' => '',
            			'echo' => false,
            			'fallback_cb' => 'blogslog_menu_fallback_cb',
            			'depth' => 1,
            			'link_before' => '<span class="screen-reader-text">',
						'link_after' => '</span>',
            		) );
					$social .= '</div></li>';
                endif;

    
            	$search = '<li class="search-menu">';
				$search .= '<a href="#" class="">';
				$search .= blogslog_get_svg( array( 'icon' => 'search' ) );
				$search .= blogslog_get_svg( array( 'icon' => 'close' ) );
				$search .= '</a><div id="search" style="display: none;">';
				$search .= get_search_form( $echo = false );
                $search .= '</div></li>';
            
        	
        		wp_nav_menu( array(
        			'theme_location' => 'primary',
        			'container' => 'div',
        			'menu_class' => 'menu nav-menu',
        			'menu_id' => 'primary-menu',
        			'echo' => true,
        			'fallback_cb' => 'blogslog_menu_fallback_cb',
        			'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s' . $search . $social . '</ul>',
        		) );
        	?>
		</nav>

		<?php 
	}
endif;
add_action( 'top_blogger_header_action', 'top_blogger_site_navigation', 30 );



if ( ! function_exists( 'top_blogger_header_end' ) ) :

	function top_blogger_header_end() { ?>
				</div><!-- .site-menu -->
				<?php if ( has_nav_menu( 'social' ) ) : ?>
					<div id="social-navigation">
		                <div class="social-icons">
							<?php  

							$search_html = sprintf(
								'<li class="search-menu"><a href="#" title="%1$s">%2$s%3$s</a><div id="search">%4$s</div>',
								esc_attr__('Search','top-blogger'),
								blogslog_get_svg( array( 'icon' => 'search' ) ), 
								blogslog_get_svg( array( 'icon' => 'close' ) ), 
								get_search_form( $echo = false )
							);			

							wp_nav_menu( 
								array(
									'theme_location' => 'social',
									'container' => false,
									'menu_class' => false,
									'menu_id' => false,
									'echo' => true,
									'fallback_cb' => false,
									'depth' => 1,
									'link_before' => '<span class="screen-reader-text">',
									'items_wrap' => '<ul id="%1$s" class="%2$s">'.$search_html.'%3$s</ul>',
									'link_after' => '</span>',
									)
								);
				        		
				        	?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</header>
		<?php
	}
endif;

add_action( 'top_blogger_header_action', 'top_blogger_header_end', 40 );

if ( ! function_exists( 'top_blogger_posts_ajax_handler' ) ) :
 
    function top_blogger_posts_ajax_handler(){
        $blog_count = 4;
        $options = blogslog_get_theme_options();
        $readmore = ! empty( $options['list_articles_readmore'] ) ? $options['list_articles_readmore'] : esc_html__( 'Continue Reading', 'top-blogger' );
        $page = isset( $_POST['LBpageNumber'] ) ? absint( wp_unslash( $_POST['LBpageNumber'] ) ) : 1;
        header("Content-Type: text/html");

  
        $latest_posts_args = array(
            'post_type'         => 'post',
            'posts_per_page'    => 4,
            'cat'               => ! empty( $options['list_articles_content_category'] ) ? $options['list_articles_content_category'] : '',
            'ignore_sticky_posts'   => true,
            'post_status'       => array( 'publish' ),
            'paged'             => $page,
            );                    
    
        $latest_posts = new WP_Query( $latest_posts_args );
        $i = 1;
        if ( $latest_posts -> have_posts() ) : while ( $latest_posts -> have_posts() ) : $latest_posts -> the_post(); 
				$content['id']        = get_the_id();
                $content['title']     = get_the_title();
                $content['url']       = get_the_permalink();
                $content['excerpt']   = blogslog_trim_content( 25 );
                $content['author']    = blogslog_author();
                $content['image']     = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_id(), 'post-thumbnail' ) : '';
            ?>
            <article class="<?php echo ! empty( $content['image'] ) ? 'has' : 'no'; ?>-featured-image <?php echo ( $i%2 ) == 0 ? 'even' : '' ?>">
                <?php if ( ! empty( $content['image'] ) ) : ?>
                    <div class="featured-image" style="background-image: url('<?php echo esc_url( $content['image'] ); ?>');">
                        <a href="<?php echo esc_url( $content['url'] ); ?>" class="post-thumbnail-link"></a>
                    </div>
                <?php endif; ?>

                <div class="entry-container">
                    <span class="cat-links">
                        <?php the_category( '', '', $content['id'] ); ?>
                    </span>

                    <header class="entry-header">
                        <h2 class="entry-title"><a href="<?php echo esc_url( $content['url'] ); ?>"><?php echo esc_html( $content['title'] ); ?></a></h2>
                    </header>

                    <div class="entry-content">
                        <p><?php echo esc_html( $content['excerpt'] ); ?></p>
                    </div>

                    <div class="entry-meta">
                        <?php 
                            blogslog_posted_on( $content['id'] ); 
                            echo wp_kses_post( $content['author'] );
                        ?>  
                    </div>

                    <div class="read-more">
                        <a href="<?php echo esc_url( $content['url'] ); ?>" class="btn"><?php echo esc_html( $readmore ); ?></a>
                    </div>
                </div>
            </article>

        <?php $i++; endwhile; endif;
        wp_reset_postdata();
        ?>
        <?php die();
    }
endif;
add_action("wp_ajax_top_blogger_posts_ajax_handler", "top_blogger_posts_ajax_handler");
add_action("wp_ajax_nopriv_top_blogger_posts_ajax_handler", "top_blogger_posts_ajax_handler");

require get_theme_file_path() . '/inc/customizer.php';

require get_theme_file_path() . '/inc/front-sections/featured.php';

require get_theme_file_path() . '/inc/front-sections/list-articles.php';

require get_theme_file_path() . '/inc/front-sections/instagram.php';