<?php

if ( ! function_exists( 'top_blogger_add_featured_section' ) ) :

    function top_blogger_add_featured_section() {

        if ( true !== get_theme_mod( 'top_blogger_featured_section_enable' ) ) {
            return false;
        }

        $content_details = array();
        $cat_id = ! empty( get_theme_mod( 'top_blogger_featured_section_category' ) ) ? get_theme_mod( 'top_blogger_featured_section_category' ) : '';
        $args = array(
            'post_type'         => 'post',
            'posts_per_page'    => 6,
            'cat'               => absint( $cat_id ),
            'ignore_sticky_posts'   => true,
            );                    


        $query = new WP_Query( $args );
        if ( $query->have_posts() ) : 
            while ( $query->have_posts() ) : $query->the_post();
                $page_post['id']        = get_the_id();
                $page_post['title']     = get_the_title();
                $page_post['url']       = get_the_permalink();
                $page_post['excerpt']   = blogslog_trim_content( 25 );
                $page_post['author']    = blogslog_author();
                $page_post['image']     = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_id(), 'post-thumbnail' ) : '';

                array_push( $content_details, $page_post );
            endwhile;
        endif;
        wp_reset_postdata();

        top_blogger_render_featured_section(  $content_details );
    }
endif;

if ( ! function_exists( 'top_blogger_render_featured_section' ) ) :

   function top_blogger_render_featured_section(  $content_details ) {

    ?>
    <div id="featured-posts" class="relative page-section blog-posts">
        <?php if ( !empty( get_theme_mod( 'top_blogger_featured_section_title' ) && !empty( get_theme_mod( 'top_blogger_featured_section_show_more_label' ) ) ) ): ?>
             <div class="section-header wrapper">
                <h2 class="section-title"><?php echo esc_html( get_theme_mod( 'top_blogger_featured_section_title' ) ); ?></h2>
                <?php if ( !empty( get_theme_mod( 'top_blogger_featured_section_show_more_url' ) && !empty( get_theme_mod( 'top_blogger_featured_section_show_more_label' ) ) ) ): ?>
                    <a href="<?php echo esc_url( get_theme_mod( 'top_blogger_featured_section_show_more_url' ) ); ?>" class="more-link"><?php echo esc_html( get_theme_mod( 'top_blogger_featured_section_show_more_label' ) ); ?></a>
                <?php endif ?>                
            </div>
        <?php endif ?>
       

        <div class="wrapper">
            <div class="posts-slider" data-slick='{"slidesToShow": 5, "slidesToScroll": 1, "infinite": true, "speed": 1000, "dots": false, "arrows":true, "autoplay": false, "draggable": true, "fade": false, "centerMode": true }'>
                <?php foreach ( $content_details as $content ) : ?>
                    <article>
                        <div class="featured-image" style="background-image: url('<?php echo esc_url( $content['image'] ); ?>');">
                            <a href="<?php echo esc_url( $content['url'] ); ?>" class="post-thumbnail-link"></a>
                        </div>

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
        
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="section-header view-more">
            <a href="#" class="more-link">Show More</a>
        </div>
    </div>

    <?php }
endif;
