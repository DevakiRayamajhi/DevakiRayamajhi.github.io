<?php

if ( ! function_exists( 'top_blogger_add_instagram_section' ) ) :

    function top_blogger_add_instagram_section() {

        if ( true !== get_theme_mod( 'top_blogger_instagram_section_enable' ) ) {
            return false;
        }   

        top_blogger_render_instagram_section();
    }
endif;

if ( ! function_exists( 'top_blogger_render_instagram_section' ) ) :

   function top_blogger_render_instagram_section() {

    ?>
    <div id="instagram-section" class="relative page-section blog-posts">
        <?php echo do_shortcode('[instagram-feed num=6 cols=6 showfollow=false]'); ?>
    </div>          

    <?php }
endif;
