<?php
/*
Plugin Name: SP Header image slider
Plugin URL: http://sptechnolab.com
Description: A simple Header image slider plugin
Version: 1.0
Author: SP Technolab
Author URI: http://sptechnolab.com
Contributors: SP Technolab
*/
/*
 * Register CPT sp_imageslider
 *
 */
function sp_imageslider_setup_post_types() {

	$imageslider_labels =  apply_filters( 'sp_imageslider_labels', array(
		'name'                => 'Header Image Slider',
		'singular_name'       => 'Header Image Slider',
		'add_new'             => __('Add New ', 'sp_imageslider'),
		'add_new_item'        => __('Add New Image', 'sp_imageslider'),
		'edit_item'           => __('Edit Image', 'sp_imageslider'),
		'new_item'            => __('New Image', 'sp_imageslider'),
		'all_items'           => __('All Image', 'sp_imageslider'),
		'view_item'           => __('View Image', 'sp_imageslider'),
		'search_items'        => __('Search Image', 'sp_imageslider'),
		'not_found'           => __('No Image found', 'sp_imageslider'),
		'not_found_in_trash'  => __('No Image found in Trash', 'sp_imageslider'),
		'parent_item_colon'   => '',
		'menu_name'           => __('Header Image Slider', 'sp_imageslider'),
		'exclude_from_search' => true
	) );


	$imageslider_args = array(
		'labels' 			=> $imageslider_labels,
		'public' 			=> true,
		'publicly_queryable'=> true,
		'show_ui' 			=> true,
		'show_in_menu' 		=> true,
		'query_var' 		=> true,
		'capability_type' 	=> 'post',
		'has_archive' 		=> true,
		'hierarchical' 		=> false,
		'supports' => array('title','thumbnail')
	);
	register_post_type( 'sp_imageslider', apply_filters( 'sp_imageslider_post_type_args', $imageslider_args ) );

}

add_action('init', 'sp_imageslider_setup_post_types');
/*
 * Add [sp_imageslider limit="-1"] shortcode
 *
 */

function sp_imageslider_shortcode( $atts, $content = null ) {
	
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
	$post_type 		= 'sp_imageslider';
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
	$slideroption = 'slider_option';
	$slideroptionadmin = get_option( $slideroption, $default ); 
	$sliderwidth = $slideroptionadmin['slider_width']; 
	$sliderheight = $slideroptionadmin['slider_height'];
	$slidercontrol = $slideroptionadmin['slider_control'];
	$sliderpagination = $slideroptionadmin['slider_pagination'];
	
	
	
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
		if ($slidercontrol == '1') 
		{ ?>
			<style>a.prev, a.next{display:none !important;}</style>
			
		<?php	
		
	}
	if ($sliderpagination == '1') 
		{ ?>
			<style>ul.pagination{display:none;}</style>
			
		<?php	
		
	}
	
	
	if( $post_count > 0) :
	?>
	
	<div id="slides-simple">
			<div class="slides_container">
	<?php
		// Loop 
		while ($query->have_posts()) : $query->the_post();
		?>
		 <div class="slide" style="width:<?php echo $sliderdefultwidth; ?>px !important; height:<?php echo $sliderdefultheight; ?>px !important;">
		
		
		 <a href="#" ><img src="<?php if (has_post_thumbnail( $post->ID ) ): ?>
				<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); 
				 echo $image[0]; endif; ?>" width="<?php echo $sliderdefultwidth; ?>" height="<?php echo $sliderdefultheight; ?>" alt=""></a>
				<div class="caption" style="bottom:0; " >
							<p style="margin:0px"><?php the_title(); ?></p>
						</div>
		</div>
		<?php
		$i++;
		endwhile; ?>
		</div>
		
			<a href="#" class="prev"><img src="<?php echo plugins_url( 'images/arrow-prev.png' , __FILE__ ); ?>" width="24" height="43" alt="Arrow Prev"></a>
				<a href="#" class="next" style="left:<?php echo $sliderdefultwidth-40; ?>px;"><img src="<?php echo plugins_url( 'images/arrow-next.png' , __FILE__ ); ?>" width="24" height="43" alt="Arrow Next"></a>
		</div>
		
<?php	else : ?>
	
		<div id="slides-simple">
			<div class="slides_container">
			
				 <div class="slide">
				 
				 <img src="<?php echo  plugin_dir_url( __FILE__ ); ?>/images/slide-1.jpg"  alt="" >
				  <div class="caption" style="bottom:0; " >
							<p style="margin:0px">First Slider Image</p>
						</div>
				 </div>		
				  <div class="slide">
	  <img src="<?php echo  plugin_dir_url( __FILE__ ); ?>/images/slide-2.jpg"  alt="">
	   <div class="caption" style="bottom:0; " >
							<p style="margin:0px">Second Slider Image</p>
						</div>
						</div>
						  <div class="slide">
	   <img src="<?php echo  plugin_dir_url( __FILE__ ); ?>/images/slide-3.jpg"  alt="">
	    <div class="caption" style="bottom:0; " >
							<p style="margin:0px">Third Slider Image</p>
						</div></div>
				
				
			
			</div>
			<a href="#" class="prev"><img src="<?php echo plugins_url( 'images/arrow-prev.png' , __FILE__ ); ?>" width="24" height="43" alt="Arrow Prev"></a>
				<a href="#" class="next" style="left:<?php echo $sliderdefultwidth-40; ?>px;"><img src="<?php echo plugins_url( 'images/arrow-next.png' , __FILE__ ); ?>" width="24" height="43" alt="Arrow Next"></a>
		</div>
		
	
	<?php	
	endif ;
	// Reset query to prevent conflicts
	wp_reset_query();
	
	?>
	
	<?php
	
	return ob_get_clean();

}

	add_shortcode("sp_imageslider", "sp_imageslider_shortcode");

	wp_register_style( 'myslidercss', plugin_dir_url( __FILE__ ) . 'css/imageslider.css' );
	wp_register_script( 'mysliderjs', plugin_dir_url( __FILE__ ) . 'js/slides.min.jquery.js', array( 'jquery' ) );	

	wp_enqueue_style( 'myslidercss' );
	wp_enqueue_script( 'mysliderjs' );
	function myimagesliderscript() {
	$slideroption = 'slider_option';
	$slideroptionadmin = get_option( $slideroption, $default ); 	
	$autoplayspeed = $slideroptionadmin['auto_speed'];
	$pausedelay = $slideroptionadmin['slider_pause'];
	$pausehover = $slideroptionadmin['hover_pause'];	
		
		if ($autoplayspeed == '' )
		{
			$autoplaydefultspeed = 2000;
		} else { $autoplaydefultspeed = $autoplayspeed;
		}
		if ($pausedelay == '' )
		{
			$pausedefultdelay = 2000;
		} else { $pausedefultdelay = $pausedelay;
		}
		if ($pausehover == '' || $pausehover == '0') 
		{
			$pausedefulthover = 'true';
		} else { $pausedefulthover = 'false';
		}
	?>
	<script type="text/javascript">
	 jQuery(function(){ 
			jQuery('#slides-simple').slides({
				preload: true,
				preloadImage: '<?php echo plugins_url( 'images/loading.gif' , __FILE__ ); ?>',
				play: <?php echo $autoplaydefultspeed; ?>,
				pause: <?php echo $pausedefultdelay; ?>,
				hoverPause: <?php echo $pausedefulthover; ?>,
				animationStart: function(current){
					jQuery('.caption').animate({
						bottom:-35
					},100);
					if (window.console && console.log) {
						// example return of current slide number
						console.log('animationStart on slide: ', current);
					};
				},
				animationComplete: function(current){
					jQuery('.caption').animate({
						bottom:0
					},200);
					if (window.console && console.log) {
						// example return of current slide number
						console.log('animationComplete on slide: ', current);
					};
				},
				slidesLoaded: function() {
					jQuery('.caption').animate({
						bottom:0
					},200);
				}
			});
		});
	</script>
	<?php
	}
add_action('wp_head', 'myimagesliderscript'); 

class Imageslidersetting
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
        add_action( 'admin_menu', array( $this, 'header_image_slider' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function header_image_slider()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Slider Settings', 
            'manage_options', 
            'slider-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'slider_option' );
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>Header image slider Setting</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'slider_option_group' );   
                do_settings_sections( 'slider-setting-admin' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'slider_option_group', // Option group
            'slider_option', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'My Custom Slider Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'slider-setting-admin' // Page
        );  

        add_settings_field(
            'slider_width', // ID
            'Slider Width', // Title 
            array( $this, 'slider_width_callback' ), // Callback
            'slider-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'slider_height', 
            'Slider Height', 
            array( $this, 'slider_height_callback' ), 
            'slider-setting-admin', 
            'setting_section_id'
        );     
		 add_settings_field(
            'auto_speed', // ID
            'Auto play speed', // Title 
            array( $this, 'auto_speed_callback' ), // Callback
            'slider-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'slider_pause', 
            'Slider pause after hover', 
            array( $this, 'pause_callback' ), 
            'slider-setting-admin', 
            'setting_section_id'
        );   
		  add_settings_field(
            'hover_pause', 
            'Slider hover pause', 
            array( $this, 'hover_pause_callback' ), 
            'slider-setting-admin', 
            'setting_section_id'
        );  
		 add_settings_field(
            'slider_control', 
            'Slider control', 
            array( $this, 'slider_control_callback' ), 
            'slider-setting-admin', 
            'setting_section_id'
        );   
		  add_settings_field(
            'slider_pagination', 
            'Slider pagination', 
            array( $this, 'slider_pagination_callback' ), 
            'slider-setting-admin', 
            'setting_section_id'
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
            $new_input['slider_width'] = absint( $input['slider_width'] );

        if( isset( $input['slider_height'] ) )
            $new_input['slider_height'] = sanitize_text_field( $input['slider_height'] );
		
		 if( isset( $input['auto_speed'] ) )
            $new_input['auto_speed'] = absint( $input['auto_speed'] );

        if( isset( $input['slider_pause'] ) )
            $new_input['slider_pause'] = sanitize_text_field( $input['slider_pause'] );
			
		 if( isset( $input['hover_pause'] ) )
            $new_input['hover_pause'] = absint( $input['hover_pause'] );
			
		  if( isset( $input['slider_control'] ) )
            $new_input['slider_control'] = sanitize_text_field( $input['slider_control'] );
			
		 if( isset( $input['slider_pagination'] ) )
            $new_input['slider_pagination'] = absint( $input['slider_pagination'] );	

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
            '<input type="text" id="slider_width" name="slider_option[slider_width]" value="%s" />',
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
            '<input type="text" id="slider_height" name="slider_option[slider_height]" value="%s" />',
            isset( $this->options['slider_height'] ) ? esc_attr( $this->options['slider_height']) : ''
        );
			printf('px');
    }
	
	public function auto_speed_callback()
    {
        printf(
            '<input type="text" id="auto_speed" name="slider_option[auto_speed]" value="%s" />',
            isset( $this->options['auto_speed'] ) ? esc_attr( $this->options['auto_speed']) : ''
        );
		printf(' ie 500, 1000 milliseconds delay');
    }
	
	public function pause_callback()
    {
        printf(
            '<input type="text" id="slider_pause" name="slider_option[slider_pause]" value="%s" />',
            isset( $this->options['slider_pause'] ) ? esc_attr( $this->options['slider_pause']) : ''
        );
		printf(' ie 500, 1000 milliseconds delay');
    }
	
	public function hover_pause_callback()
    {
        printf(
            '<input type="text" id="hover_pause" name="slider_option[hover_pause]" value="%s" />',
            isset( $this->options['hover_pause'] ) ? esc_attr( $this->options['hover_pause']) : ''
        );
		printf(' Enter "0" for <b>True</b> and "1" for <b>False</b>');
    }
	public function slider_control_callback()
    {
        printf(
            '<input type="text" id="slider_control" name="slider_option[slider_control]" value="%s" />',
            isset( $this->options['slider_control'] ) ? esc_attr( $this->options['slider_control']) : ''
        );
		printf(' Enter "0" for <b>True</b> and "1" for <b>False</b>');
    }
	
	public function slider_pagination_callback()
    {
        printf(
            '<input type="text" id="slider_pagination" name="slider_option[slider_pagination]" value="%s" />',
            isset( $this->options['slider_pagination'] ) ? esc_attr( $this->options['slider_pagination']) : ''
        );
		printf(' Enter "0" for <b>True</b> and "1" for <b>False</b>');
    }
}

if( is_admin() )
    $my_settings_page = new Imageslidersetting();