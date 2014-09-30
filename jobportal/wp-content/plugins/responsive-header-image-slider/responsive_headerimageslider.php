<?php
/*
Plugin Name: SP Responsive header image slider
Plugin URL: http://sptechnolab.com
Description: A simple Responsive header image slider
Version: 1.3
Author: SP Technolab
Author URI: http://sptechnolab.com
Contributors: SP Technolab
*/
/*
 * Register CPT sp_responsiveslider
 *
 */
function sp_responsiveslider_setup_post_types() {

	$responsiveslider_labels =  apply_filters( 'sp_responsiveslider_labels', array(
		'name'                => 'Responsive header image slider',
		'singular_name'       => 'Responsive header image slider',
		'add_new'             => __('Add New', 'sp_responsiveslider'),
		'add_new_item'        => __('Add New Image', 'sp_responsiveslider'),
		'edit_item'           => __('Edit Image', 'sp_responsiveslider'),
		'new_item'            => __('New Image', 'sp_responsiveslider'),
		'all_items'           => __('All Image', 'sp_responsiveslider'),
		'view_item'           => __('View Image', 'sp_responsiveslider'),
		'search_items'        => __('Search Image', 'sp_responsiveslider'),
		'not_found'           => __('No Image found', 'sp_responsiveslider'),
		'not_found_in_trash'  => __('No Image found in Trash', 'sp_responsiveslider'),
		'parent_item_colon'   => '',
		'menu_name'           => __('Responsive image slider', 'sp_responsiveslider'),
		'exclude_from_search' => true
	) );


	$responsiveslider_args = array(
		'labels' 			=> $responsiveslider_labels,
		'public' 			=> true,
		'publicly_queryable'		=> true,
		'show_ui' 			=> true,
		'show_in_menu' 		=> true,
		'query_var' 		=> true,
		'capability_type' 	=> 'post',
		'has_archive' 		=> true,
		'hierarchical' 		=> false,
		'supports' => array('title','thumbnail')
		
	);
	register_post_type( 'sp_responsiveslider', apply_filters( 'sp_faq_post_type_args', $responsiveslider_args ) );

}
add_action('init', 'sp_responsiveslider_setup_post_types');


add_action( 'admin_init', 'rsris_add_metaboxes' );
function rsris_add_metaboxes() {

  // This will register our metabox for all post types
  $post_types = get_post_types();
  // This will remove the meta box from our slides post type
  unset($post_types['rsris_slides']);
      foreach ( $post_types as $post_type ){
        // Box for your posts for inserting your slider element.
	 add_meta_box('rsris_slide_link_box', 'LINK URL', 'rsris_slide_link_box', $post_type , 'normal', 'core');
        //add_meta_box('rsris_multipeselect_metabox', 'LINK URL', 'rsris_multipeselect_metabox', $post_type, 'normal', 'core');
      }
  // Box for inserting the link the slide should link to.
  add_meta_box('rsris_slide_link_box', 'Slide link', 'rsris_slide_link_box', 'rsris_slides', 'normal', 'core');
  add_meta_box('rsris_slide_embed_box', 'Youtube Share link', 'rsris_slide_embed_box', 'rsris_slides', 'normal', 'core');
}
 
// Our metabox for choosing the slides
function rsris_multipeselect_metabox() {
   global $post;
   
   wp_nonce_field( plugin_basename( __FILE__ ), 'rsris_ms_metabox_nonce' );
   
   $rsris_ms_posts = get_posts( array(
   'post_type' => 'rsris_slides',
   'numberposts' => -1

   ));
   $rsris_slides = get_post_meta( $post->ID, 'rsris_slide', true );

   $rsris_ms_output = '<div class="rsris-select-wrapper"><div class="rsris-select-left"><div class="rsris-search-field-wrapper">Link Url:<input type="text" id="rsris-search-field" placeholder="http://"></div><ul class="rsris-items">';
   
   $rsris_ms_output .= '</ul></div></div>';
   $rsris_ms_output .= '<div style="clear:both;"></div>';
   echo $rsris_ms_output;
}
 
// Save data from meta box
add_action('save_post', 'rsris_checkbox_metabox_save');
function rsris_checkbox_metabox_save($post_id) {
  // verify nonce
  if ( !wp_verify_nonce( $_POST['rsris_ms_metabox_nonce'], plugin_basename( __FILE__ ) ) )
        return;
 
  // check autosave
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
 
  // check permissions
  if (!current_user_can('edit_post', $post_id))
    return;
 
        $old['rsris_slide'] = get_post_meta( $post_id, 'rsris_slide', true );
        $new['rsris_slide'] = $_POST['rsris_slide'];
       
        if ( $new['rsris_slide'] && $new['rsris_slide'] != $old['rsris_slide'] ) {
          update_post_meta($post_id, 'rsris_slide', $new['rsris_slide']);
        } elseif ( '' == $new['rsris_slide'] && $old['rsris_slide'] ) {
          delete_post_meta($post_id, 'rsris_slide', $old['rsris_slide']);
        }
}


/**
* Register meta boxes for inserting a links and embeds
*/
function rsris_slide_link_box() {
  global $post;
  $rsris_slide_link = get_post_meta( $post->ID, 'rsris_slide_link', true );
  
  wp_nonce_field( plugin_basename( __FILE__ ), 'rsris_slide_link_box_nounce' );
  
  $rsris_slide_link_output .= '<input type="text" name="rsris_slide_link" id="rsris_slide_link" class="widefat" value="'.$rsris_slide_link.'" />';
  	echo $rsris_slide_link_output;
}

function rsris_slide_embed_box() {
  global $post;
  $rsris_slide_embed = get_post_meta( $post->ID, 'rsris_slide_embed', true );
  
  wp_nonce_field( plugin_basename( __FILE__ ), 'rsris_slide_embed_box_nounce' );
  
  $rsris_slide_embed_output = rsris_embed_video( $post->ID, 260, 120);
  $rsris_slide_embed_output .= '<label for="rsris_slide_embed"><span class="howto">Copy and paste the link to your YouTube video</span></label>';
  $rsris_slide_embed_output .= '<input type="text" name="rsris_slide_embed" id="rsris_slide_embed" class="widefat" value="'.$rsris_slide_embed.'" />';
  echo $rsris_slide_embed_output;
}



add_action( 'save_post', 'rsris_link_save' );  
function rsris_link_save( $post_id )  
{  
    // Bail if we're doing an auto save  
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; 
     
    // verify nonce
    if ( !wp_verify_nonce( $_POST['rsris_slide_link_box_nounce'], plugin_basename( __FILE__ ) ) )
        return; 
     
  

}


/*
 * Add [sp_responsiveslider limit="-1"] shortcode
 *
 */
function sp_responsiveslider_shortcode( $atts, $content = null ) {
	
	extract(shortcode_atts(array(
		"limit" => ''
	), $atts));
	
	// Define limit
	if( $limit ) { 
		$posts_per_page = $limit; 
	} else {
		$posts_per_page = '-1';
	}
	
	ob_start();

	// Create the Query

	$post_type 		= 'sp_responsiveslider';
	$orderby 		= 'post_date';
	$order 			= 'DESC';
				
	$query = new WP_Query( array ( 
								'post_type'      => $post_type,
								'posts_per_page' => $posts_per_page,
								'orderby'        => $orderby, 
								'order'          => $order,
								'no_found_rows'  => 1
								) 
						);
	//Get post type count
	$post_count = $query->post_count;
	$i = 1;
	
	// Displays Custom post info
	
	
	
	if( $post_count > 0) :
	?>
	
	  <div id="slides">
	<?php
		
		// Loop 
		while ($query->have_posts()) : $query->the_post();

		?>
		<?php $respslideroption = 'responsiveslider_option';
	$respslideroptionadmin = get_option( $respslideroption, $default ); 
	$link = $respslideroptionadmin['link'];  
		 	if ($link == '' || $link == 'yes' )
		{?>
		<a href="<?php echo get_post_meta( get_the_ID(),'rsris_slide_link', true ) ?>" target="_blank">
		<?php } ?>
		<img src="<?php if (has_post_thumbnail( $post->ID ) ): ?>
				<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); 
				 echo $image[0]; endif; ?>"  alt="">
				<?php	if ($link == '' || $link == 'yes'  )
		{?> 
				 </a>
			<?php } ?>
		<?php
		$i++;
		endwhile; ?>
		</div>
		
<?php	else : ?>
 <div id="slides">
	 <img src="<?php echo  plugin_dir_url( __FILE__ ); ?>/img/1.png"  alt="">
	  <img src="<?php echo  plugin_dir_url( __FILE__ ); ?>/img/2.png"  alt="">
	   <img src="<?php echo  plugin_dir_url( __FILE__ ); ?>/img/3.png"  alt="">
	</div>
	<?php
	endif;
	// Reset query to prevent conflicts
	wp_reset_query();
	
	?>
	
	<?php
	
	return ob_get_clean();

}
	add_shortcode("sp_responsiveslider", "sp_responsiveslider_shortcode");

	wp_register_style( 'respslidercss', plugin_dir_url( __FILE__ ) . 'css/responsiveimgslider.css' );
	wp_register_script( 'respsliderjs', plugin_dir_url( __FILE__ ) . 'js/jquery.slides.min.js', array( 'jquery' ) );	

	wp_enqueue_style( 'respslidercss' );
	wp_enqueue_script( 'respsliderjs' );

	function sp_responsiveslider_script() {
	
	$respslideroption = 'responsiveslider_option';
	$respslideroptionadmin = get_option( $respslideroption, $default ); 
	$sliderwidth = $respslideroptionadmin['slider_width']; 
	$sliderheight = $respslideroptionadmin['slider_height'];
	$sliderstart = $respslideroptionadmin['start'];	
	$slidernavigation = $respslideroptionadmin['slider_navigation'];
	$slidernavigationeffect = $respslideroptionadmin['slider_navigation_effect'];
	
	$auto_play = $respslideroptionadmin['auto_play'];
	$slide_speed = $respslideroptionadmin['slide_speed'];
	$play_effect = $respslideroptionadmin['play_effect'];
	$auto_play_load = $respslideroptionadmin['auto_play_load'];
	$autoplayspeed = $respslideroptionadmin['auto_speed'];	
		
		
		$pagination = $respslideroptionadmin['pagination']; 
		$slider_pagination_effect = $respslideroptionadmin['slider_pagination_effect'];
		
		if ($sliderwidth == '' )
		{
			$sliderdefultwidth = 980;
		} else { $sliderdefultwidth = $sliderwidth;
		}
		if ($sliderheight == '' )
		{
			$sliderdefultheight = 300;
		} else { $sliderdefultheight = $sliderheight;
		}
		
		if($auto_play == '')
			{
				$auto_play_def = 'true';
			} else { $auto_play_def  = $auto_play; }  
			
		if($auto_play_load == '')
			{
				$auto_play_load_def = 'true';
			} else { $auto_play_load_def  = $auto_play_load; }	
		
		if ($autoplayspeed == '' )
		{
			$autoplaydefultspeed = 2000;
		} else { $autoplaydefultspeed = $autoplayspeed;
		}
		
		if ($slide_speed == '' )
		{
			$slidedefultspeed = 2000;
		} else { $slidedefultspeed = $slide_speed;
		}
		
		
		if ($sliderstart == '' ) 
		{
			$sliderstartno = '1';
		} else { $sliderstartno = $sliderstart;
		}
		
		if ($pagination == '') 
		{
			$paginationtrue = 'true';
		} else { $paginationtrue = $pagination;
		}
		
		if ($slider_pagination_effect == '') 
		{
			$slider_pagination_effect_def = 'slide';
		} else { $slider_pagination_effect_def = $slider_pagination_effect;
		}
		
		if ($slidernavigation == '') 
		{
			$slidernavigation_def = 'true';
		} else { $slidernavigation_def = $slidernavigation;
		}
		
		if ($play_effect == '')
		{
			$play_effect_def = 'slide';
		} else { $play_effect_def = $play_effect;
		}
		
		if ($slidernavigationeffect == '') 
		{
			$slidernavigationeffect_def = 'slide';
		} else { $slidernavigationeffect_def = $slidernavigationeffect;
		}
		
		
	
	
	?>
	<script type="text/javascript">
	
	 jQuery(function() {
      jQuery('#slides').slidesjs({
        width: <?php echo $sliderdefultwidth ; ?>,
        height: <?php echo $sliderdefultheight ; ?>,
		start: <?php echo $sliderstartno ; ?>,	
        play: {
          active: <?php echo $auto_play_def; ?>,
          auto: <?php echo $auto_play_load_def; ?>,
          interval: <?php echo $autoplaydefultspeed; ?>,
          swap: true,
		  effect: "<?php echo $play_effect_def; ?>"
        },
      effect: { 
		 slide: {       
        speed: <?php echo $slidedefultspeed; ?>          
      }
    },
	navigation: {
      active: <?php echo $slidernavigation_def; ?>,
	  effect: "<?php echo $slidernavigationeffect_def; ?>"
	  },
        
	pagination: {
      active: <?php echo $paginationtrue; ?>,
	   effect: "<?php echo $slider_pagination_effect_def; ?>"
	  
    }
   }); 
 });
</script>
	<?php
	}
add_action('wp_head', 'sp_responsiveslider_script'); 
class Responsiveimageslidersetting
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'res_header_image_slider' ) );
        add_action( 'admin_init', array( $this, 'resppage_init' ) );
    }

    /**
     * Add options page
     */
    public function res_header_image_slider()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Responsive slider Settings', 
            'manage_options', 
            'responsive-slider-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'responsiveslider_option' );
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>Responsive header image slider Setting</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'respslider_option_group' );   
                do_settings_sections( 'responsive-slider-setting-admin' );
                submit_button(); 
		
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function resppage_init()
    {        
        register_setting(
            'respslider_option_group', // Option group
            'responsiveslider_option', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'My Custom Slider Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'responsive-slider-setting-admin' // Page
        );  

        add_settings_field(
            'slider_width', // ID
            'Slider Width', // Title 
            array( $this, 'slider_width_callback' ), // Callback
            'responsive-slider-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'slider_height', 
            'Slider Height', 
            array( $this, 'slider_height_callback' ), 
            'responsive-slider-setting-admin', 
            'setting_section_id'
        );     
		
		 add_settings_field(
            'start', 
            'Start', 
            array( $this, 'slider_start_callback' ), 
            'responsive-slider-setting-admin', 
            'setting_section_id'
        );     
		
	add_settings_field(
            'slider_navigation', 
            'Navigation', 
            array( $this, 'slider_navigation_callback' ), 
            'responsive-slider-setting-admin', 
            'setting_section_id'
        );  
		
	add_settings_field(
            'slider_navigation_effect', 
            'Navigation Effect', 
            array( $this, 'slider_navigation_effect_callback' ), 
            'responsive-slider-setting-admin', 
            'setting_section_id'
        );  

add_settings_field(
            'pagination', // ID
            'Pagination', // Title 
            array( $this, 'pagination_callback' ), // Callback
            'responsive-slider-setting-admin', // Page
            'setting_section_id' // Section           
        );  
		
		 add_settings_field(
            'slider_pagination_effect', // ID
            'Pagination Effect', // Title 
            array( $this, 'slider_pagination_effect_callback' ), // Callback
            'responsive-slider-setting-admin', // Page
            'setting_section_id' // Section           
        ); 		
		
		add_settings_field(
            'auto_play', 
            'Play ', 
            array( $this, 'auto_play_callback' ), 
            'responsive-slider-setting-admin', 
            'setting_section_id'
        );  
		
		add_settings_field(
            'auto_play_load', 
            ' Auto Play On Page Load', 
            array( $this, 'auto_play_load_callback' ), 
            'responsive-slider-setting-admin', 
            'setting_section_id'
        );  
		
			add_settings_field(
            'play_effect', 
            'Play Effect', 
            array( $this, 'play_effect_callback' ), 
            'responsive-slider-setting-admin', 
            'setting_section_id'
        );  
		
		 add_settings_field(
            'auto_speed', // ID
            'Auto play speed', // Title 
            array( $this, 'auto_speed_callback' ), // Callback
            'responsive-slider-setting-admin', // Page
            'setting_section_id' // Section           
        );
        
       add_settings_field(
            'slide_speed', // ID
            'Transition Speed Between Image', // Title 
            array( $this, 'slide_speed_callback' ), // Callback
            'responsive-slider-setting-admin', // Page
            'setting_section_id' // Section           
        );   
		
		 
		
		add_settings_field(
            'link', // ID
            'Custom link to image', // Title 
            array( $this, 'link_callback' ), // Callback
            'responsive-slider-setting-admin', // Page
            'setting_section_id' // Section           
        );     		
			
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['slider_width'] ) )
            $new_input['slider_width'] = sanitize_text_field( $input['slider_width'] );

        if( isset( $input['slider_height'] ) )
            $new_input['slider_height'] = sanitize_text_field( $input['slider_height'] );
			
			 if( isset( $input['start'] ) )
            $new_input['start'] = sanitize_text_field( $input['start'] );	
			
		 if( isset( $input['slider_navigation'] ) )
            $new_input['slider_navigation'] = sanitize_text_field( $input['slider_navigation'] ); 
			
		 if( isset( $input['slider_navigation_effect'] ) )
            $new_input['slider_navigation_effect'] = sanitize_text_field( $input['slider_navigation_effect'] );	

		if( isset( $input['auto_play'] ) )
            $new_input['auto_play'] = sanitize_text_field( $input['auto_play'] );		
			
		if( isset( $input['auto_play_load'] ) )
            $new_input['auto_play_load'] = sanitize_text_field( $input['auto_play_load'] );	
			
			 if( isset( $input['play_effect'] ) )
            $new_input['play_effect'] = sanitize_text_field( $input['play_effect'] );

		if( isset( $input['pagination'] ) )
            $new_input['pagination'] = sanitize_text_field( $input['pagination'] );		
			 
		 if( isset( $input['slider_pagination_effect'] ) )
            $new_input['slider_pagination_effect'] = sanitize_text_field( $input['slider_pagination_effect'] );
		
		 if( isset( $input['auto_speed'] ) )
            $new_input['auto_speed'] = sanitize_text_field( $input['auto_speed'] );
       if( isset( $input['slide_speed'] ) )
            $new_input['slide_speed'] = sanitize_text_field( $input['slide_speed'] );     	
			
	 if( isset( $input['link'] ) )
            $new_input['link'] = sanitize_text_field( $input['link'] );
        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function slider_width_callback()
    {
        printf(
            '<input type="text" id="slider_width" name="responsiveslider_option[slider_width]" value="%s" />',
            isset( $this->options['slider_width'] ) ? esc_attr( $this->options['slider_width']) : ''
        );
			printf('px');
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function slider_height_callback()
    {
        printf(
            '<input type="text" id="slider_height" name="responsiveslider_option[slider_height]" value="%s" />',
            isset( $this->options['slider_height'] ) ? esc_attr( $this->options['slider_height']) : ''
        );
			printf('px');
    }
	
	  public function slider_start_callback()
    {
        printf(
            '<input type="text" id="start" name="responsiveslider_option[start]" value="%s" />',
            isset( $this->options['start'] ) ? esc_attr( $this->options['start']) : ''
        );
			printf('&nbsp;&nbsp;Set the first slide in the slideshow (Default value is 1)');
    }
	
	public function slider_navigation_callback()
    {
        printf(
            '<input type="radio" id="slider_navigation" name="responsiveslider_option[slider_navigation]" value="true" /> True 
			<input type="radio" id="slider_navigation1" name="responsiveslider_option[slider_navigation]" value="false"  /> False',
            isset( $this->options['slider_navigation'] ) ? esc_attr( $this->options['slider_navigation']) : ''
        );
		printf(' &nbsp;&nbsp;&nbsp;&nbsp;<b>(Next and previous button settings)</b>');
		
	?><script type="text/javascript"><?php
		if($this->options['slider_navigation']=='true'){?>
			document.getElementById("slider_navigation").checked = true; 
		<?php } else if($this->options['slider_navigation']=='false') { ?>
			document.getElementById("slider_navigation1").checked = true; <?php } ?>
	</script>
	<?php	
    } 
	
		public function slider_navigation_effect_callback()
    {
        printf(
            '<input type="radio" id="slider_navigation_effect" name="responsiveslider_option[slider_navigation_effect]" value="slide" /> Slide 
			<input type="radio" id="slider_navigation_effect1" name="responsiveslider_option[slider_navigation_effect]" value="fade"  /> Fade' ,
            isset( $this->options['slider_navigation_effect'] ) ? esc_attr( $this->options['slider_navigation_effect']) : ''
        );
		printf(' &nbsp;&nbsp;&nbsp;&nbsp; <b>(Effect for Navigation)</b>');
		
	?><script type="text/javascript"><?php
		if($this->options['slider_navigation_effect']=='slide'){?>
			document.getElementById("slider_navigation_effect").checked = true; 
		<?php } else if($this->options['slider_navigation_effect']=='fade') { ?>
			document.getElementById("slider_navigation_effect1").checked = true; <?php } ?>
	</script>
	<?php	
    }
	
	public function pagination_callback()
    {
        printf(
            '<input type="radio" id="pagination" name="responsiveslider_option[pagination]" value="true" /> True 
			<input type="radio" id="pagination1" name="responsiveslider_option[pagination]" value="false"  /> False' ,
            isset( $this->options['pagination'] ) ? esc_attr( $this->options['pagination']) : ''
        );
		printf('&nbsp;&nbsp;&nbsp;&nbsp; <b>(Pagination settings)</b>');
		
	?><script type="text/javascript"><?php
		if($this->options['pagination']=='true'){?>
			document.getElementById("pagination").checked = true; 
		<?php } else if($this->options['pagination']=='false') { ?>
			document.getElementById("pagination1").checked = true; <?php } ?>
	</script>
	<?php	
    }
	
		public function slider_pagination_effect_callback()
    {
        printf(
            '<input type="radio" id="slider_pagination_effect" name="responsiveslider_option[slider_pagination_effect]" value="slide" /> Slide 
			<input type="radio" id="slider_pagination_effect1" name="responsiveslider_option[slider_pagination_effect]" value="fade"  /> Fade' ,
            isset( $this->options['slider_pagination_effect'] ) ? esc_attr( $this->options['slider_pagination_effect']) : ''
        );
		printf('&nbsp;&nbsp;&nbsp;&nbsp; <b>(Effect for Pagination)</b>');
		
	?><script type="text/javascript"><?php
		if($this->options['slider_pagination_effect']=='slide'){?>
			document.getElementById("slider_pagination_effect").checked = true; 
		<?php } else if($this->options['slider_pagination_effect']=='fade') { ?>
			document.getElementById("slider_pagination_effect1").checked = true; <?php } ?>
	</script>
	<?php	
    }
	

	
	public function auto_play_callback() 
    {
        printf(
            'Active : <input type="radio" id="auto_play" name="responsiveslider_option[auto_play]" value="true" /> True 
			<input type="radio" id="auto_play1" name="responsiveslider_option[auto_play]" value="false"  /> False' ,
            isset( $this->options['auto_play'] ) ? esc_attr( $this->options['auto_play']) : ''
        );
		printf('&nbsp;&nbsp;&nbsp;&nbsp; <b>(Play and stop button setting.)</b>');
		
	?><script type="text/javascript"><?php
		if($this->options['auto_play']=='true'){?>
			document.getElementById("auto_play").checked = true; 
		<?php } else if($this->options['auto_play']=='false') { ?>
			document.getElementById("auto_play1").checked = true; <?php } ?>
	</script>
	<?php	
    }  
	
		public function auto_play_load_callback() 
    {
        printf(
            '<input type="radio" id="auto_play_load" name="responsiveslider_option[auto_play_load]" value="true" /> True 
			<input type="radio" id="auto_play_load1" name="responsiveslider_option[auto_play_load]" value="false"  /> False' ,
            isset( $this->options['auto_play_load'] ) ? esc_attr( $this->options['auto_play_load']) : ''
        );
		printf('&nbsp;&nbsp;&nbsp;&nbsp; <b>(Start playing the slideshow on load)</b>');
		
	?><script type="text/javascript"><?php
		if($this->options['auto_play_load']=='true'){?>
			document.getElementById("auto_play_load").checked = true; 
		<?php } else if($this->options['auto_play_load']=='false') { ?>
			document.getElementById("auto_play_load1").checked = true; <?php } ?>
	</script>
	<?php	
    }
	
		public function play_effect_callback() 
    {
        printf(
            '<input type="radio" id="play_effect" name="responsiveslider_option[play_effect]" value="slide" /> Slide 
			<input type="radio" id="play_effect1" name="responsiveslider_option[play_effect]" value="fade"  /> Fade' ,
            isset( $this->options['play_effect'] ) ? esc_attr( $this->options['play_effect']) : ''
        );
		printf('&nbsp;&nbsp;&nbsp;&nbsp; <b>(Play effect setting.)</b>');
		
	?><script type="text/javascript"><?php
		if($this->options['play_effect']=='slide'){?>
			document.getElementById("play_effect").checked = true; 
		<?php } else if($this->options['play_effect']=='fade') { ?>
			document.getElementById("play_effect1").checked = true; <?php } ?>
	</script>
	<?php	
    }
	
	
	
	public function auto_speed_callback()
    {
        printf(
            '<input type="text" id="auto_speed" name="responsiveslider_option[auto_speed]" value="%s" />',
            isset( $this->options['auto_speed'] ) ? esc_attr( $this->options['auto_speed']) : ''
        );
		printf(' ie 500, 1000 milliseconds delay');
    }
	
	
	public function slide_speed_callback()
    {
        printf(
            '<input type="text" id="slide_speed" name="responsiveslider_option[slide_speed]" value="%s" />',
            isset( $this->options['slide_speed'] ) ? esc_attr( $this->options['slide_speed']) : ''
        );
		printf(' ie 500, 1000 milliseconds ');
    }
	
	public function link_callback() 
    {
        printf(
            '<input type="radio" id="link" name="responsiveslider_option[link]" value="yes" /> Yes 
			<input type="radio" id="link1" name="responsiveslider_option[link]" value="no"  /> No' ,
            isset( $this->options['link'] ) ? esc_attr( $this->options['link']) : ''
        );
		printf('&nbsp;&nbsp;&nbsp;&nbsp; <b>(Add Link to the Image.)</b>');
		
	?><script type="text/javascript"><?php
		if($this->options['link']=='yes'){?>
			document.getElementById("link").checked = true; 
		<?php } else if($this->options['link']=='no') { ?>
			document.getElementById("link1").checked = true; <?php } ?>
	</script>
	<?php	
    }
	
	
	
	
}

if( is_admin() )
    $my_settings_page = new Responsiveimageslidersetting();
	
	
