
<?php 
/**
 * The template for displaying Category pages
 * Imonthemes
 */


get_header(); ?>
<div class=" warp row ">


 <div id="sub_banner">
<h1>
<?php $category = get_the_category(); if($category[0]){echo '<a href="'.get_category_link($category[0]->term_id ).'">'.$category[0]->cat_name.'</a>';}?>
</h1>
</div>

<?php get_template_part(''.$hathor = of_get_option('layout1_images').''); ?>


</div>

<?php get_footer(); ?>