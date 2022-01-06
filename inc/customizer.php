<?php

function top_blogger_customize_register( $wp_customize ) {

	Class Top_Blogger_Switch_Control extends WP_Customize_Control{

		public $type = 'switch';

		public $on_off_label = array();

		public function __construct( $manager, $id, $args = array() ){
	        $this->on_off_label = $args['on_off_label'];
	        parent::__construct( $manager, $id, $args );
	    }

		public function render_content(){
	    ?>
		    <span class="customize-control-title">
				<?php echo esc_html( $this->label ); ?>
			</span>

			<?php if( $this->description ){ ?>
				<span class="description customize-control-description">
				<?php echo wp_kses_post( $this->description ); ?>
				</span>
			<?php } ?>

			<?php
				$switch_class = ( $this->value() == 'true' ) ? 'switch-on' : '';
				$on_off_label = $this->on_off_label;
			?>
			<div class="onoffswitch <?php echo esc_attr( $switch_class ); ?>">
				<div class="onoffswitch-inner">
					<div class="onoffswitch-active">
						<div class="onoffswitch-switch"><?php echo esc_html( $on_off_label['on'] ) ?></div>
					</div>

					<div class="onoffswitch-inactive">
						<div class="onoffswitch-switch"><?php echo esc_html( $on_off_label['off'] ) ?></div>
					</div>
				</div>	
			</div>
			<input <?php $this->link(); ?> type="hidden" value="<?php echo esc_attr( $this->value() ); ?>"/>
			<?php
	    }
	}

	Class Top_Blogger_Dropdown_Chooser extends WP_Customize_Control{

		public $type = 'dropdown_chooser';

		public function render_content(){
			if ( empty( $this->choices ) )
	                return;
			?>
	            <label>
	                <span class="customize-control-title">
	                	<?php echo esc_html( $this->label ); ?>
	                </span>

	                <?php if($this->description){ ?>
		            <span class="description customize-control-description">
		            	<?php echo wp_kses_post($this->description); ?>
		            </span>
		            <?php } ?>

	                <select class="top-blogger-chosen-select" <?php $this->link(); ?>>
	                    <?php
	                    foreach ( $this->choices as $value => $label )
	                        echo '<option value="' . esc_attr( $value ) . '"' . selected( $this->value(), $value, false ) . '>' . esc_html( $label ) . '</option>';
	                    ?>
	                </select>
	            </label>
			<?php
		}
	}

	Class Top_Blogger_Dropdown_Taxonomies_Control extends WP_Customize_Control {

		public $type = 'dropdown-taxonomies';

		public $taxonomy = '';

		public function __construct( $manager, $id, $args = array() ) {

			$taxonomy = 'category';
			if ( isset( $args['taxonomy'] ) ) {
				$taxonomy_exist = taxonomy_exists( esc_attr( $args['taxonomy'] ) );
				if ( true === $taxonomy_exist ) {
					$taxonomy = esc_attr( $args['taxonomy'] );
				}
			}
			$args['taxonomy'] = $taxonomy;
			$this->taxonomy = esc_attr( $taxonomy );

			parent::__construct( $manager, $id, $args );
		}

		public function render_content() {

			$tax_args = array(
				'hierarchical' => 0,
				'taxonomy'     => $this->taxonomy,
			);
			$taxonomies = get_categories( $tax_args );

		?>
	    <label>
	      <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
	      <?php if ( ! empty( $this->description ) ) : ?>
	      	<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
	      <?php endif; ?>
	       <select <?php $this->link(); ?>>
				<?php
				printf( '<option value="%s" %s>%s</option>', '', selected( $this->value(), '', false ), esc_html__( '--None--', 'top-blogger') );
				?>
				<?php if ( ! empty( $taxonomies ) ) :  ?>
	            <?php foreach ( $taxonomies as $key => $tax ) :  ?>
					<?php
					printf( '<option value="%s" %s>%s</option>', esc_attr( $tax->term_id ), selected( $this->value(), $tax->term_id, false ), esc_html( $tax->name ) );
					?>
	            <?php endforeach ?>
				<?php endif ?>
	       </select>
	    </label>
	    <?php
		}
	}


	$wp_customize->remove_section( 'colors' );


	// Add Subscribe section
	$wp_customize->add_section( 'blogslog_instagram_section', array(
		'title'             => esc_html__( 'Instagram','top-blogger' ),
		'description'       => wp_kses_post( 'Please install <a href="https://wordpress.org/plugins/instagram-feed/">Instagram-Feed</a>.  Plugin to connect instagarm account', 'house-state-pro' ),
		'panel'             => 'blogslog_front_page_panel',
		'priority' => 45,
	) );

	// Subscribe content enable control and setting
	$wp_customize->add_setting( 'top_blogger_instagram_section_enable', array(
		'sanitize_callback' => 'blogslog_sanitize_switch_control',
	) );

	$wp_customize->add_control( new Top_Blogger_Switch_Control( $wp_customize, 'top_blogger_instagram_section_enable', array(
		'label'             => esc_html__( 'Instagram Enable', 'top-blogger' ),
		'section'           => 'blogslog_instagram_section',
		'on_off_label' 		=> blogslog_switch_options(),
	) ) );

	// Add featured section
	$wp_customize->add_section( 'blogslog_featured_section', array(
		'title'             => esc_html__( 'Featured','top-blogger' ),
		'description'       => esc_html__( 'featured Section options.', 'top-blogger' ),
		'panel'             => 'blogslog_front_page_panel',
		'priority' => 35,
	) );

	// featured content enable control and setting
	$wp_customize->add_setting( 'top_blogger_featured_section_enable', array(
		'sanitize_callback' => 'blogslog_sanitize_switch_control',
	) );

	$wp_customize->add_control( new Top_Blogger_Switch_Control( $wp_customize, 'top_blogger_featured_section_enable', array(
		'label'             => esc_html__( 'featured Enable', 'top-blogger' ),
		'section'           => 'blogslog_featured_section',
		'on_off_label' 		=> blogslog_switch_options(),
	) ) );

	
	// featured sub_title setting and control
	$wp_customize->add_setting( 'top_blogger_featured_section_title', array(
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'top_blogger_featured_section_title', array(
		'label'           	=> esc_html__( 'Section Title ', 'top-blogger' ),
		'section'        	=> 'blogslog_featured_section',
		'active_callback' 	=> 'top_blogger_is_featured_section_enable',
		'type'				=> 'text',
	) );

	$wp_customize->add_setting( 'top_blogger_featured_section_show_more_label', array(
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'top_blogger_featured_section_show_more_label', array(
		'label'           	=> esc_html__( 'Section Show More Label', 'top-blogger' ),
		'section'        	=> 'blogslog_featured_section',
		'active_callback' 	=> 'top_blogger_is_featured_section_enable',
		'type'				=> 'text',
	) );

	$wp_customize->add_setting( 'top_blogger_featured_section_show_more_url', array(
		'sanitize_callback' => 'esc_url_raw',
	) );

	$wp_customize->add_control( 'top_blogger_featured_section_show_more_url', array(
		'label'           	=> esc_html__( 'Show More Url', 'top-blogger' ),
		'section'        	=> 'blogslog_featured_section',
		'active_callback' 	=> 'top_blogger_is_featured_section_enable',
		'type'				=> 'url',
	) );

	$wp_customize->add_setting(  'top_blogger_featured_section_category', array(
		'sanitize_callback' => 'blogslog_sanitize_single_category',
	) ) ;

	$wp_customize->add_control( new Top_Blogger_Dropdown_Taxonomies_Control( $wp_customize,'top_blogger_featured_section_category', array(
		'label'             => esc_html__( 'Select Category', 'top-blogger' ),
		'description'      	=> esc_html__( 'Note: Latest six posts will be shown from selected category', 'top-blogger' ),
		'section'           => 'blogslog_featured_section',
		'type'              => 'dropdown-taxonomies',
		'active_callback'	=> 'top_blogger_is_featured_section_enable'
	) ) );

}
add_action( 'customize_register', 'top_blogger_customize_register' );


function top_blogger_is_featured_section_enable( $control ) {
	return ( $control->manager->get_setting( 'top_blogger_featured_section_enable' )->value() );
}
