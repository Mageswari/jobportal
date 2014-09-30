<?php

class epsSliderClass {

    public $id = 0; // slider ID
    public $identifier = 0; // unique identifier
    public $slides = array(); //slides belonging to this slider
    public $settings = array(); // slider settings
    public $eps_google_enqueue_scripts=array();

    function __construct($id='') {
if(!session_id())
    @session_start();
        $_SESSION['enqueue_script']='';
        $this->id = $id;

        $this->settings = $this->eps_get_settings();

        $this->identifier = 'eps_' . $this->id;

        $this->eps_save();

        $this->eps_populate_slides();
        if(!session_id())
            session_start();

    }

    private function eps_get_settings() {
        $settings = get_post_meta($this->id, 'eps-slider_settings', true);

        if (is_array($settings)) {
            return $settings;
        } else {
            return $this->_default_settings();
        }
    }

    public function _default_settings() {
        $params = array(
            'cssClass' => '',
            'printCss' => true,
            'printJs' => true,
            'slider_width' => 565,
            'slider_height' => 290,
            'width' => 300,
            'height' => 290,
            'navigation' => true,
            'interval' => 3,
            'autoplay' => true,
            'bshadow'=>true,
            'bg_position_increment'=>50,
            'content_font_line_height'=>20,
            'content_font_size'=>14,
            'heading_font_size'=>22,
            'readmore_font_size'=>16,
            'content_font_family'=>'Times New Roman',
            'heading_font_family'=>'Arial, Helvetica',
            'readmore_font_family'=>'Arial Black, Gadget',
            'content_font_style'=>array('none'),
            'heading_font_style'=>array('none'),
            'readmore_font_style'=>array('none'),
            'content_font_color'=>'#916C05',
            'heading_font_color'=>'#ffffff',
            'readmore_font_color'=>'#ffffff',
            'readmore_bg_color'=>'#ffffff',
            'readmore_border_color'=>'#ffffff',
            'content_top_margin'=> 0,
            'topPer'=>0,
            'leftPer'=>0,
            'heading_other_font_family'=> 'Georgia, serif',
            'content_other_font_family'=> 'Georgia, serif',
            'readmore_other_font_family'=> 'Georgia, serif'
        );

        return $params;
    }

    /**
     * Update the slider settings, converting checkbox values (on/off) to true or false.
     */
    public function eps_update_settings($new_settings) {
        $old_settings = $this->eps_get_settings();
//print_r($new_settings);
        // convert submitted checkbox values from 'on' or 'off' to boolean values
        $checkboxes = array('links', 'navigation','printCss', 'printJs','autoplay','bshadow');

        foreach ($checkboxes as $checkbox) {
            if (isset($new_settings[$checkbox]) && $new_settings[$checkbox] == 'on') {
                $new_settings[$checkbox] = "true";
            } else {
                $new_settings[$checkbox] = "false";
            }
        }

        // update the slider settings
        update_post_meta($this->id, 'eps-slider_settings', array_merge((array)$old_settings, $new_settings));

        $this->settings = $this->eps_get_settings();
    }

    /**
     * Return an individual setting
     *
     * @param string $name Name of the setting
     * @return string setting value or 'false'
     */
    public function get_setting($name) {

        if (!isset($this->settings[$name])) {
            $defaults = $this->_default_settings();

            if (isset($defaults[$name])) {

                return $defaults[$name] ? $defaults[$name] : 'false';
            }
        } else {
            if (is_array($this->settings[$name]) || strlen($this->settings[$name]) > 0) {
                return $this->settings[$name];
            }
        }

        return 'false';
    }
    /**
     * Update the title of the slider
     */
    private function eps_update_title($title) {
        $slide = array(
            'ID' => $this->id,
            'post_title' => $title
        );

        wp_update_post($slide);
    }

    /**
     * Delete a slide. This doesn't actually remove the slide from WordPress, simply untags
     * it from the slide taxonomy
     */
    private function delete_slide($slide_id) {
        // Get the existing terms and only keep the ones we don't want removed
        $new_terms = array();
        $current_terms = wp_get_object_terms($slide_id, 'eps-slider', array('fields' => 'ids'));
        $term = get_term_by('name', $this->id, 'eps-slider');

        foreach ($current_terms as $current_term) {
                     if ($current_term != $term->term_id) {
                $new_terms[] = intval($current_term);
            }
        }

        return wp_set_object_terms($slide_id, $new_terms, 'eps-slider');
    }

    /**
     * Loop over each slide and call the save action on each
     */
    private function eps_update_slides($data) {
        foreach ($data as $slide_id => $fields) {
            do_action("eps_save_{$fields['type']}_slide", $slide_id, $this->id, $fields);
        }
    }

    /**
     * Save the slider details and initiate the update of all slides associated with slider.
     */
    private function eps_save() {
        if (!is_admin()) {
            return;
        }
        // make changes to slider
        if (isset($_POST['settings'])) {
            $this->eps_update_settings($_POST['settings']);
        }
        if (isset($_POST['title'])) {
            $this->eps_update_title($_POST['title']);
        }
        if (isset($_GET['deleteSlide'])) {
            $this->delete_slide(intval($_GET['deleteSlide']));
        }

        // make changes to slides
        if (isset($_POST['attachment'])) {
            $this->eps_update_slides($_POST['attachment']);
        }
    }
    /**
     * Render each slide belonging to the slider out to the screen
     */
    public function eps_render_admin_slides() {
        foreach($this->slides as $slide) {
            echo $slide;
        }
    }
    /**
     * Return slides for the current slider
     *
     * @return array collection of slides belonging to the current slider
     */
    private function eps_populate_slides() {
        $slides = array();

        $args = array(
            'force_no_custom_order' => true,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'eps-slider',
                    'field' => 'slug',
                    'terms' => $this->id
                )
            )
        );

        $query = new WP_Query($args);

        $slides = array();

        while ($query->have_posts()) {
            $query->next_post();

            $type = get_post_meta($query->post->ID, 'eps-slider_type', true);
            $type = $type ? $type : 'image'; // backwards compatibility, fall back to 'image'

            if (has_filter("eps_get_{$type}_slide")) {
                $return = apply_filters("eps_get_{$type}_slide", $query->post->ID, $this->id);
                if (is_array($return)) {
                    $slides = array_merge($slides, $return);
                } else {
                    $slides[] = $return;
                }
            }
        }

//		 apply random setting
        if ($this->get_setting('random') == 'true' && !is_admin()) {
            shuffle($slides);
        }

        $this->slides = $slides;

        return $this->slides;
    }
    public function eps_render_public_slides() {
        $class = "eps eps-slide eps-{$this->id} eps-slider";

        // apply the css class setting
        if ($this->get_setting('cssClass') != 'false') {
            $class .= " " . $this->get_setting('cssClass');
        }

        // handle any custom classes
        $class = apply_filters('eps_css_classes', $class, $this->id, $this->settings);

        // carousels are always 100% wide
        if ($this->get_setting('carouselMode') != 'true' && $this->get_setting('slider_width') != 'false' && $this->get_setting('slider_width') != 0) {

            $style = "max-width: {$this->get_setting('slider_width')}px; max-height:{$this->get_setting('slider_height')}px;";
        } else {

            $style = "width: 100%;";

        }

        // center align the slideshow
        if ($this->get_setting('center') != 'false') {
            $style .= " margin: 0 auto;";
        }

        // build the HTML
        $html  = "\n<!--easy slider-->";
        $html .= "\n<div style='{$style}' class='{$class}'>";
        $html .= "\n    " . $this->eps_get_inline_css();
        $html .= "\n    <div id='da-slider-eps_{$this->id}' class='da-slider eps-custom-{$this->id}'>";
        $html .= "\n        " . $this->eps_get_html();
        if($this->get_setting('navigation')=='true'){
            $html .= "\n   <nav class='da-arrows'>
					<span class='da-arrows-prev'></span>
					<span class='da-arrows-next'></span>
				</nav> \n";
        }
        $html .= "\n  </div>";
        $html .= $this->eps_get_inline_javascript();
        $html .= "\n</div>";
        $html .= "\n<!--//easy slider-->";

        return $html;
    }

    /**
     * Return the Javascript to kick off the slider. Code is wrapped in a timer
     * to allow for themes that load jQuery at the bottom of the page.
     *
     * Delay execution of slider code until jQuery is ready (supports themes where
     * jQuery is loaded at the bottom of the page)
     *
     * @return string javascript
     */

    /**
     * Include slider assets, JS and CSS paths are specified by child classes.
     */
    public function eps_enqueue_scripts() {
        if ($this->get_setting('printJs') == 'true') {
            wp_enqueue_script('eps-slider-extra', EPS_ASSETS_URL . $this->js_path_extra, array('jquery'), EPS_VERSION);
            //wp_enqueue_script('eps-slider-touch', EPS_ASSETS_URL .'js/jquery.mobile-1.3.2.min.js', array('jquery'), EPS_VERSION);
            wp_enqueue_script('eps-slider-touch', EPS_ASSETS_URL .'js/jquery.hammer.js', array('jquery'), EPS_VERSION);
            wp_enqueue_script('eps-slider-touch', EPS_ASSETS_URL .'js/jquery.easing.min.js', array('jquery'), EPS_VERSION);
            wp_enqueue_script('eps-slider', EPS_ASSETS_URL . $this->js_path, array('jquery'), EPS_VERSION);
        }
        if ($this->get_setting('printCss') == 'true') {
            wp_enqueue_style('eps-slider-public-css',EPS_ASSETS_URL . $this->publiccss_path,'',EPS_VERSION);
            wp_enqueue_style('eps-slider-font','http://fonts.googleapis.com/css?family=Economica:700,400italic','',EPS_VERSION);
            if($_SESSION['enqueue_script']!=''){
            wp_enqueue_style('dynamic_css',EPS_ASSETS_URL .'css/dynamic_css.php','',EPS_VERSION);
            }
            wp_enqueue_style('eps-slider-css',EPS_ASSETS_URL . $this->css_path,'',EPS_VERSION);
            wp_enqueue_style('eps-animate-slider-css',EPS_ASSETS_URL . 'css/animate.css','',EPS_VERSION);

        }

    }

    private function eps_get_inline_javascript() {
        $identifier = $this->identifier;
        $options='';
        if($this->get_setting('autoplay')=='true' || $this->get_setting('autoplay')=='on'){
            $options .='autoplay:true,';
        }
        if($this->get_setting('interval')){
            $options .='interval:'.($this->get_setting('interval')*1000);
        }
        if($this->get_setting('bg_position_increment')){
            $options .=',bgincrement:'.$this->get_setting('bg_position_increment');
        }

        $script  = "\n    <script type='text/javascript'>";
        $script .= "\n        var da_slider_" . $identifier . " = function($) {";
        $script .= "\n            jQuery('#da-slider-" . $identifier . "')." . $this->js_function . "({ $options";
        //$script .= "\n                " . $this->get_javascript_parameters();
        $script .= "\n            });";

        $script .= "\n        };";
        $script .= "\n        var timer_" . $identifier . " = function() {";
        $script .= "\n            var slider = !window.jQuery ? window.setTimeout(timer_{$identifier}, 100) : !jQuery.isReady ? window.setTimeout(timer_{$identifier}, 100) : da_slider_{$identifier}(window.jQuery);";
        $script .= "\n        };";
        $script .= "\n        timer_" . $identifier . "();";
        $script .= "\n    </script>";

        return $script;
    }
    public function colourBrightness($hex, $percent) {
        $hash = '';
        if (stristr($hex,'#')) {
            $hex = str_replace('#','',$hex);
            $hash = '#';
        }
        $rgb = array(hexdec(substr($hex,0,2)), hexdec(substr($hex,2,2)), hexdec(substr($hex,4,2)));
        for ($i=0; $i<3; $i++) {
            // See if brighter or darker
            if ($percent > 0) {
                $rgb[$i] = round($rgb[$i] * $percent) + round(255 * (1-$percent));
            } else {
                // Darker
                $positivePercent = $percent - ($percent*2);
                $rgb[$i] = round($rgb[$i] * $positivePercent) + round(0 * (1-$positivePercent));
            }
            if ($rgb[$i] > 255) {
                $rgb[$i] = 255;
            }
        }
        $hex = '';
        for($i=0; $i < 3; $i++) {
            $hexDigit = dechex($rgb[$i]);
            if(strlen($hexDigit) == 1) {
                $hexDigit = "0" . $hexDigit;
            }
            $hex .= $hexDigit;
        }
        return $hash.$hex;
    }
    /**
     * Apply any custom inline styling
     *
     * @return string
     */
    private function eps_get_inline_css() {

        $scoped = ' scoped';

        if (isset($_SERVER['HTTP_USER_AGENT'])){
            $agent = $_SERVER['HTTP_USER_AGENT'];
            if (strlen(strstr($agent,"Firefox")) > 0 ){
                $scoped = '';
            }
        }
        $bg_type=$this->get_setting('slider_bg_type');
        if($bg_type=='default'){
            $bg_img=EPS_ASSETS_URL.'images/waves.gif';
            $bg_img_style='transparent';
        } elseif($bg_type=='image'){
            $url= get_post_meta($this->id, 'eps-slider_bg', true);
            if($url){
                $bg_img=$url;
                $border_top='';
                $border_bottom='';
            } else{
                $bg_img=EPS_ASSETS_URL.'images/waves.gif';
            }
            $bg_img_style='transparent';
        }elseif($bg_type=='color'){
            $bg_img_style=$this->get_setting('sbg_color');
            $bg_img='';
        } else{
            $bg_img=EPS_ASSETS_URL.'images/waves.gif';
            $bg_img_style='transparent';
        }
        $border_top='border-top: 8px solid #efc34a';
        $border_bottom='border-bottom: 8px solid #efc34a;';
        $height='height:390px;';
        //echo $this->get_setting('slider_height');
        if($this->get_setting('slider_height')){
            $height='height:'.$this->get_setting('slider_height').'px;';
        }

        if($this->get_setting('heading_font_family')=='Google Font'){
            $heading_family='font-family:'.$this->get_setting('heading_google_font_family').';';
            if(strpos($_SESSION['enqueue_script'],urlencode($this->get_setting('heading_google_font_family')))===false){
                $prefix= $_SESSION['enqueue_script']==''?'':'|';
                $_SESSION['enqueue_script'].=$prefix.urlencode($this->get_setting('heading_google_font_family'));
            }

        } elseif($this->get_setting('heading_font_family')=='Other'){
            $heading_family='font-family:'.$this->get_setting('heading_other_font_family').';';
        }else{
            $heading_family='font-family:'.$this->get_setting('heading_font_family').';';
        }
        if($this->get_setting('content_font_family')=='Google Font'){
            $content_family='font-family:'.$this->get_setting('content_google_font_family').';';

            if(strpos($_SESSION['enqueue_script'],urlencode($this->get_setting('content_google_font_family')))===false){
                $prefix= $_SESSION['enqueue_script']==''?'':'|';
                $_SESSION['enqueue_script'].=$prefix.urlencode($this->get_setting('content_google_font_family'));
            }
        }elseif($this->get_setting('content_font_family')=='Other'){
            $content_family='font-family:'.$this->get_setting('content_other_font_family').';';
        }else{
            $content_family='font-family:'.$this->get_setting('content_font_family').';';
        }
        $z_index = 1000;
        if($this->get_setting('slider_z_index') && is_numeric($this->get_setting('slider_z_index'))){
            $z_index = $this->get_setting('slider_z_index');
        }

        if($this->get_setting('readmore_font_family')=='Google Font'){
            $readmore_family='font-family:'.$this->get_setting('readmore_google_font_family').';';

            if(strpos($_SESSION['enqueue_script'],urlencode($this->get_setting('readmore_google_font_family')))===false){
                $prefix= $_SESSION['enqueue_script']==''?'':'|';
                $_SESSION['enqueue_script'].=$prefix.urlencode($this->get_setting('readmore_google_font_family'));
            }
        }elseif($this->get_setting('readmore_font_family')=='Other'){
            $readmore_family='font-family:'.$this->get_setting('readmore_other_font_family').';';
        }else{
            $readmore_family='font-family:'.$this->get_setting('readmore_font_family').';';
        }
        $bottom_navigations='';
        if ($this->get_setting('links') != 'true') {
            $bottom_navigations='display:none;';
        } else{
            $bottom_navigations='display:block;';
        }

        if($this->get_setting('bshadow')=='true' || $this->get_setting('bshadow')=='on'){
            $boxshadow='box-shadow: 0px 1px 1px rgba(0,0,0,0.2), 0px -2px 1px #fff;';

        } else{
            $boxshadow='box-shadow:none';
        }
        $heading_font='text-decoration:none;';
        $content_font='text-decoration:none;';
        $readmore_font='text-decoration:none;';

        $heading_size='font-size:'.$this->get_setting('heading_font_size').'px;';

        $heading_color='color:'.$this->get_setting('heading_font_color').';';

        $heading_font='';
        if(is_array($this->get_setting('heading_font_style')) && in_array('italic',$this->get_setting('heading_font_style'))){
            $heading_font.='font-style:italic;';
        }
        if(is_array($this->get_setting('heading_font_style')) && in_array('bold',$this->get_setting('heading_font_style'))){
            $heading_font.='font-weight:bold;';
        }
        if(is_array($this->get_setting('heading_font_style')) && in_array('underline',$this->get_setting('heading_font_style'))){
            $heading_font.='text-decoration:underline;';
        }

        $content_size='font-size:'.$this->get_setting('content_font_size').'px;';

        $content_color='color:'.$this->get_setting('content_font_color').';';
        $content_font='';
        if(is_array($this->get_setting('content_font_style')) && in_array('italic',$this->get_setting('content_font_style'))){
            $content_font.='font-style:italic;';
        }
        if(is_array($this->get_setting('content_font_style')) && in_array('bold',$this->get_setting('content_font_style'))){
            $content_font.='font-weight:bold;';
        }
        if(is_array($this->get_setting('content_font_style')) && in_array('underline',$this->get_setting('content_font_style'))){
            $content_font.='text-decoration:underline;';
        }

        $readmore_size='font-size:'.$this->get_setting('readmore_font_size').'px;';

        $readmore_color='color:'.$this->get_setting('readmore_font_color').';';
        $readmore_bgcolor='background-color:'.$this->get_setting('readmore_bg_color').';';
        $readmore_bordercolor='border-color:'.$this->get_setting('readmore_border_color').';';
        $readmore_hoverbgcolor='background-color:'.$this->colourBrightness($this->get_setting('readmore_bg_color'),0.80).';';

        $readmore_font='';
        if(is_array($this->get_setting('readmore_font_style')) && in_array('italic',$this->get_setting('readmore_font_style'))){
            $readmore_font.='font-style:italic;';
        }
        if(is_array($this->get_setting('readmore_font_style')) && in_array('bold',$this->get_setting('readmore_font_style'))){
            $readmore_font.='font-weight:bold;';
        }
        if(is_array($this->get_setting('readmore_font_style')) && in_array('underline',$this->get_setting('readmore_font_style'))){
            $readmore_font.='text-decoration:underline;';
        }

        $line_height='line-height:20px';
        if($this->get_setting('content_font_line_height')!=='false'){
            $line_height='line-height:'.$this->get_setting('content_font_line_height').'px;';
        }

        $css =<<<EOF
                    .eps-custom-{$this->id}{
                    background: url({$bg_img}) {$bg_img_style} repeat 0% 0%;
                    {$border_top}
                    {$border_bottom}
                    {$height}
                    {$boxshadow}
                    }
                  .eps-custom-{$this->id} .da-dots{
                    {$bottom_navigations}
                    }
                    .eps-custom-{$this->id} .da-slide  h2{
                       {$heading_size}
                       {$heading_family}
                       {$heading_color}
                       {$heading_font}
                    }
                    .eps-custom-{$this->id} .da-slide p{
                     {$content_size}
                       {$content_family}
                       {$content_color}
                       {$content_font}
                       {$line_height}
                    }
                    .eps-custom-{$this->id} .da-slide .da-link{
                       {$readmore_size}
                       {$readmore_family}
                       {$readmore_color}
                       {$readmore_bordercolor}
                       {$readmore_bgcolor}
                       {$readmore_font}
                    }
                    .eps-custom-{$this->id} .da-link:hover{
                    {$readmore_hoverbgcolor}
                    }
                    .da-slide-current{
	z-index: {$z_index} !important;
}

EOF;

        if (strlen($css)) {
//                $_SESSION['ebp_comman_css'].=$css;
            return "<style type='text/css'{$scoped}>{$css}\n    </style>";
        }


        return "";
    }



}