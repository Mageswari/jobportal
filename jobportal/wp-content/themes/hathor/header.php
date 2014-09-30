<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>
  
 <?php wp_title('-',true,'left'); ?>

</title>

<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />	
	<?php wp_head(); ?>

</head>


<body <?php body_class(); ?>>
<div class="c_header">
<div class="c_head2_1"><p style="color:#ccc;float:left;">Stay tuned</p>
  	<div id="social_logo1"></div>
        <div id="social_logo2"></div>
        <div id="social_logo3"></div>
        <div id="social_logo4"></div>
	
  </div>

<div class="c_head2">JOB PORTAL</div>
   <div class="c_head1">
           <div class="c_head1_1"><h2>Students</h2></div>
 	    <a href="http://localhost/jobportal/?page_id=67"><div class="c_head1_2"><h2>Employer</h2></div></a>
  
   </div>

</div>

<!--HEADER START-->

<?php if( get_option( 'hathor' )){ ?>
 
 <?php get_template_part(''.$head = of_get_option('head_select', 'header').''); ?>
<?php }else{ ?>
 
 <?php get_template_part('dummy/dummy','head1'); ?>
        <?php } ?> 
<?php if(is_page('HOME')) :?>
<div style="width:100%; height:10px; background-color:#2F3236;"></div>
<?php putRevSlider("news") ?>
<?php endif; ?>

