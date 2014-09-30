<?php
class epsAdminSlider extends epsSliderImageClass {
    /**
     * Register slide type
     */
    private $filename= null;
    private $font_family_array=array('Georgia, serif','Palatino Linotype, Book Antiqua, Palatino','Times New Roman','Arial, Helvetica','Arial Black, Gadget','Comic Sans MS, cursive','Impact, Charcoal','Lucida Sans Unicode','Tahoma, Geneva','Trebuchet MS','Verdana, Geneva','Courier New, Courier, monospace','Lucida Console, Monaco','google'=>'Google Font','Other');
    private $font_style_array=array('bold','italic','underline');

    private $eps_in_effects = array(
        '',
        'Parallax',
        "bounce",
        "shake",
        "flash",
        "tada",
        "swing",
        "wobble",
        "pulse",
        "flip",
        "flipInX",
        "flipInY",
        "fadeIn",
        "fadeInUp",
        "fadeInDown",
        "fadeInLeft",
        "fadeInRight",
        "fadeInUpBig",
        "fadeInDownBig",
        "fadeInLeftBig",
        "fadeInRightBig",
        "bounceIn",
        "bounceInUp",
        "bounceInDown",
        "bounceInLeft",
        "bounceInRight",
        "rotateIn",
        "rotateInUpLeft",
        "rotateInDownLeft",
        "rotateInUpRight",
        "rotateInDownRight",
        "hinge",
        "rollIn",
        "lightSpeedIn",
        "wiggle"
    );
    private $eps_out_effects = array(
        '',
        'Parallax',
        "bounce",
        "shake",
        "flash",
        "tada",
        "swing",
        "wobble",
        "pulse",
        "flip",
        "flipOutX",
        "flipOutY",
        "fadeOut",
        "fadeOutUp",
        "fadeOutDown",
        "fadeOutLeft",
        "fadeOutRight",
        "fadeOutUpBig",
        "fadeOutDownBig",
        "fadeOutLeftBig",
        "fadeOutLeftBig",
        "bounceOut",
        "bounceOutUp",
        "bounceOutDown",
        "bounceOutLeft",
        "bounceOutRight",
        "rotateOut",
        "rotateOutUpLeft",
        "rotateOutDownLeft",
        "rotateOutUpRight",
        "rotateOutDownRight",
        "hinge",
        "rollOut",
        "lightSpeedOut",
        "wiggle");







    public function __construct() {
        if(!session_id()) session_start();
        $pluginmenu=explode('/',plugin_basename(__FILE__));
        $this->filename=$pluginmenu[0];
        add_filter('eps_get_image_slide', array($this, 'eps_get_slide'), 10, 2);
        add_action('eps_save_image_slide', array($this, 'eps_save_slide'), 5, 3);
        add_action('wp_ajax_create_image_slide', array($this, 'eps_ajax_create_slide'));
        add_action('wp_ajax_create_bg', array($this, 'eps_ajax_create_background'));
    }

    /**
     * Create a new slide and echo the admin HTML
     */
    public function eps_ajax_create_slide() {
        $slide_id = intval($_POST['slide_id']);
        $slider_id = intval($_POST['slider_id']);

        $this->eps_set_slide($slide_id);
        $this->eps_set_slider($slider_id);
        $this->eps_tag_slide_to_slider();

        $this->eps_add_or_update_or_delete_meta($slide_id, 'type', 'image');

        echo $this->eps_get_admin_slide();
        die();
    }
    public function eps_ajax_create_background() {
        $bg_id = intval($_POST['bg_id']);
        $slider_id = intval($_POST['slider_id']);

        $full = wp_get_attachment_image_src($bg_id, 'full');
        if(count($full)){
            $this->eps_add_or_update_or_delete_bg_meta($slider_id, 'bg', $full[0]);
            //echo $url= get_post_meta($slider_id, 'eps-slider_bg', true); exit;
            echo $full[0];
        }else{
            echo 1;
        }
        die();
    }
    /**
     * Return the HTML used to display this slide in the admin screen
     *
     * @return string slide html
     */
    protected function eps_get_admin_slide() {

        $font_family_array=array('Georgia, serif','Palatino Linotype, Book Antiqua, Palatino','Times New Roman','Arial, Helvetica','Arial Black, Gadget','Comic Sans MS, cursive','Impact, Charcoal','Lucida Sans Unicode','Tahoma, Geneva','Trebuchet MS','Verdana, Geneva','Courier New, Courier, monospace','Lucida Console, Monaco','google'=>'Google Font');

        // get some slide settings
        $thumb   = $this->eps_get_thumb();
        $full    = wp_get_attachment_image_src($this->slide->ID, 'full');
        if (!isset($this->settings['load_from_new']) || $this->settings['load_from_new'] ==  false || $this->settings['load_from_new']=='') {
            $url     = get_post_meta($this->slide->ID, 'eps-slider_url', true);
            $readmore     = htmlspecialchars(get_post_meta($this->slide->ID, 'eps-slider_readmore', true),ENT_QUOTES);
            $target  = get_post_meta($this->slide->ID, 'eps-slider_new_window', true) ? 'checked=checked' : '';
            $heading =htmlspecialchars( get_post_meta($this->slide->ID, 'eps-slider_heading', true),ENT_QUOTES);
            $caption = htmlentities($this->slide->post_excerpt, ENT_QUOTES, 'UTF-8');
        } else {
            $url     = get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_url', true);
            $readmore     = htmlspecialchars(get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_readmore', true),ENT_QUOTES);
            $target  = get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_new_window', true) ? 'checked=checked' : '';
            $heading = htmlspecialchars(get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_heading', true),ENT_QUOTES);
            $caption = get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_caption', true);
        }
        $flag = get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_flag', true);
        $heading_font_size=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_heading_font_size', true);
        $readmore_font_size=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_readmore_font_size', true);
        $content_top_margin=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_content_top_margin', true);
        $content_font_size=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_content_font_size', true);
        $heading_font_family=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_heading_font_family', true);
        $readmore_font_family=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_readmore_font_family', true);
        $content_font_family=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_content_font_family', true);

        $readmore_google_font_family=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_readmore_google_font_family', true);
        $content_google_font_family=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_content_google_font_family', true);
        $heading_google_font_family=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_heading_google_font_family', true);

        $readmore_other_font_family=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_readmore_other_font_family', true);
        $content_other_font_family=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_content_other_font_family', true);
        $heading_other_font_family=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_heading_other_font_family', true);

        $heading_font_style=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_heading_font_style', true);
        $readmore_font_style=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_readmore_font_style', true);
        $content_font_style=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_content_font_style', true);
        $heading_font_color=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_heading_font_color', true);
        $content_font_color=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_content_font_color', true);
        $content_line_height=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_content_line_height', true);
        $readmore_font_color=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_readmore_font_color', true);
        $readmore_bg_color=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_readmore_bg_color', true);
        $readmore_border_color=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_readmore_border_color', true);
        $image_top=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_image_top', true);
        $image_left=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_image_left', true);
        $image_width=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_image_width', true);
        $image_height=get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_image_height', true);

        $heading_in_effect = get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_heading_in_effect', true);
        $heading_out_effect = get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_heading_out_effect', true);

        $content_in_effect = get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_content_in_effect', true);
        $content_out_effect = get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_content_out_effect', true);

        $readmore_in_effect = get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_readmore_in_effect', true);
        $readmore_out_effect = get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_readmore_out_effect', true);

        $image_in_effect = get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_image_in_effect', true);
        $image_out_effect = get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_image_out_effect', true);


        // localisation
        $str_heading    = __("Heading", $this->filename);
        $str_content    = __("Content", $this->filename);
        $str_new_window = __("New Window", $this->filename);
        $str_url        = __("Read More Url", $this->filename);
        $str_readmore        = __("Read More Text", $this->filename);
        $str_font_top_margin = __("Top Margin", $this->filename);
        $str_font_size = __("Font Size", $this->filename);
        $str_font_family = __("Font Family", $this->filename);
        $str_google_font_family = __("Google Font", $this->filename);
        $str_other_font_family = __("Other Font", $this->filename);
        $str_font_style = __("Font Style", $this->filename);
        $str_font_color = __("Font Color", $this->filename);
        $str_bg_color = __("BG Color", $this->filename);
        $str_border_color = __("Border Color", $this->filename);
        $str_top = __("Top", $this->filename);
        $str_left = __("Left", $this->filename);
        $str_width = __("Width", $this->filename);
        $str_height = __("Height", $this->filename);
        $str_line_height = __("Line Height", $this->filename);
        $str_in_effect = __("In Effect", $this->filename);
        $str_out_effect = __("Out Effect", $this->filename);

        // slide row HTML
        $row  = "<tr class='slide'>";
        $row .= "    <td class='col-1'>";
        $row .= "        <div class='thumb' style='background-image: url({$thumb})'>";
        $row .= "            <a class='delete-slide confirm' href='?page=".$this->filename."&id={$this->slider->ID}&deleteSlide={$this->slide->ID}'>x</a>";
        $row .= "            <span class='slide-details'>Image {$full[1]} x {$full[2]}</span>";
        $row .= "        </div>";
        $row .= "    </td>";
        $row .= "    <td class='col-2'>";
        $row .= "        <input class='url' type='text' name='attachment[{$this->slide->ID}][heading]' placeholder='{$str_heading}' value='{$heading}'/>";
        $row .= "        <textarea name='attachment[{$this->slide->ID}][caption]' placeholder='{$str_content}'>{$caption}</textarea>";
        $row .= "        <input class='url' type='url' name='attachment[{$this->slide->ID}][url]' placeholder='{$str_url}' value='{$url}' />";
        $row .= "        <div class='new_window'>";
        $row .= "            <label>{$str_new_window}<input type='checkbox' name='attachment[{$this->slide->ID}][new_window]' {$target} /></label>";
        $row .= "        </div>";
        $row .= "        <input class='url' type='text' name='attachment[{$this->slide->ID}][readmore]' placeholder='{$str_readmore}' value='{$readmore}' />";
        $row .= "        <input type='hidden' name='attachment[{$this->slide->ID}][type]' value='image' />";
        $row .= "        <input type='hidden' class='menu_order' name='attachment[{$this->slide->ID}][menu_order]' value='{$this->slide->menu_order}' />";
        $row .= "    <div class='eps-colapsable-slider'>";
        $row.='<h3 class="slide-settings">Slide Settings</h3>';
        $row .= '<div><table border="0" cellspacing="0" cellpadding="0">';
        $heading_font_family = $this->_osc_create_font_family_select($heading_font_family);
        $readmore_font_family = $this->_osc_create_font_family_select($readmore_font_family);
        $content_font_family = $this->_osc_create_font_family_select($content_font_family);

        $heading_google_font_family=$this->_osc_create_google_font_family_select($heading_google_font_family);
        $readmore_google_font_family = $this->_osc_create_google_font_family_select($readmore_google_font_family);
        $content_google_font_family = $this->_osc_create_google_font_family_select($content_google_font_family);

        $heading_font_style = $this->_osc_create_font_style_select($heading_font_style);
        $readmore_font_style = $this->_osc_create_font_style_select($readmore_font_style);
        $content_font_style = $this->_osc_create_font_style_select($content_font_style);


        $heading_in_effects = $this->_osc_create_effects_select($heading_in_effect);
        $heading_out_effects = $this->_osc_create_effects_select($heading_out_effect, 'out');

        $content_in_effects = $this->_osc_create_effects_select($content_in_effect);
        $content_out_effects = $this->_osc_create_effects_select($content_out_effect, 'out');

        $readmore_in_effects = $this->_osc_create_effects_select($readmore_in_effect);
        $readmore_out_effects = $this->_osc_create_effects_select($readmore_out_effect, 'out');

        $image_in_effects = $this->_osc_create_effects_select($image_in_effect);
        $image_out_effects = $this->_osc_create_effects_select($image_out_effect, 'out');


        $row .= <<<EOS

<tr>
    <td width="50%">
        <table class='eps_slide_setting' width="100%">
            <tr>
                <td>
                    <h4 class="slide-settings">Slide Heading Settings</h4>
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_font_size}</label>
                    <input class='option' type='number' min='1' max='100' step='1' name='attachment[{$this->slide->ID}][heading_font_size]' value='{$heading_font_size}' />
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_font_family}</label> <select class="check_google_font" show-attr="single_heading_google" other-attr="single_heading_other" name='attachment[{$this->slide->ID}][heading_font_family]'>{$heading_font_family}</select>
                </td>
            </tr>
            <tr class="single_heading_google">
                <td>
                    <label>{$str_google_font_family}</label> <select name='attachment[{$this->slide->ID}][heading_google_font_family]'>{$heading_google_font_family}</select>
                </td>
            </tr>
            <tr class="single_heading_other">
                <td>
                    <label>{$str_other_font_family}</label> <input class='option' type='text'  name="attachment[{$this->slide->ID}][heading_other_font_family]" value="{$heading_other_font_family}" />
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_font_style}</label> <select name='attachment[{$this->slide->ID}][heading_font_style][]' multiple>$heading_font_style</select>
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_font_color}</label>
                    <input  class='option settingColorSelector' type='text' name='attachment[{$this->slide->ID}][heading_font_color]' value='{$heading_font_color}'/>
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_font_color}</label>
                    <input  class='option settingColorSelector' type='text' name='attachment[{$this->slide->ID}][heading_font_color]' value='{$heading_font_color}'/>
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_in_effect}</label>
                    <select name='attachment[{$this->slide->ID}][heading_in_effect]'>{$heading_in_effects}</select>
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_out_effect}</label>
                    <select name='attachment[{$this->slide->ID}][heading_out_effect]'>{$heading_out_effects}</select>
                </td>
            </tr>
            <tr>
                <td>
                    <h4 class="slide-settings">Slide Content Settings</h4>
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_font_top_margin}</label><input class='option' type='number' min='1' max='100' step='1' name='attachment[{$this->slide->ID}][content_top_margin]' value='{$content_top_margin}' /> px
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_font_size}</label><input class='option' type='number' min='1' max='100' step='1' name='attachment[{$this->slide->ID}][content_font_size]' value='{$content_font_size}' />
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_font_family}</label> <select class="check_google_font" show-attr="single_content_google" other-attr="single_content_other" name='attachment[{$this->slide->ID}][content_font_family]'>$content_font_family</select>
                </td>
            </tr>
            <tr class="single_content_google">
                <td>
                    <label>{$str_google_font_family}</label> <select name='attachment[{$this->slide->ID}][content_google_font_family]'>{$content_google_font_family}</select>
                </td>
            </tr>
            <tr class="single_content_other">
                <td>
                    <label>{$str_other_font_family}</label> <input class='option' type='text'  name="attachment[{$this->slide->ID}][content_other_font_family]" value="{$content_other_font_family}" />
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_font_style}</label> <select name='attachment[{$this->slide->ID}][content_font_style][]' multiple>$content_font_style
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_font_color}</label>
                    <input  class='option settingColorSelector' type='text' name='attachment[{$this->slide->ID}][content_font_color]' value='{$content_font_color}'/>
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_line_height}</label>
                    <input  class='option' type='text' name='attachment[{$this->slide->ID}][content_line_height]' value='{$content_line_height}'/>
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_in_effect}</label>
                    <select name='attachment[{$this->slide->ID}][content_in_effect]'>{$content_in_effects}</select>
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_out_effect}</label>
                    <select name='attachment[{$this->slide->ID}][content_out_effect]'>{$content_out_effects}</select>
                </td>
            </tr>
        </table>
    </td>
EOS;
        $row .= <<<EOS
    <td width="50%">
        <table class='eps_slide_setting' width="100%">
            <tr>
                <td>
                    <h4 class="slide-settings">Slide Read More Settings</h4>
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_font_size}</label>
                    <input class='option' type='number' min='1' max='100' step='1' name='attachment[{$this->slide->ID}][readmore_font_size]' value='{$readmore_font_size}' />
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_font_family}</label> <select class="check_google_font" show-attr="single_readmore_google" other-attr="single_readmore_other" name='attachment[{$this->slide->ID}][readmore_font_family]'>$readmore_font_family</select>
                </td>
            </tr>
            <tr class="single_readmore_google">
                <td>
                    <label>{$str_google_font_family}</label> <select name='attachment[{$this->slide->ID}][readmore_google_font_family]'>{$readmore_google_font_family}</select>
                </td>
            </tr>
            <tr class="single_readmore_other">
                <td>
                    <label>{$str_other_font_family}</label> <input class='option' type='text'  name="attachment[{$this->slide->ID}][readmore_other_font_family]" value="{$readmore_other_font_family}" />
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_font_style}</label> <select name='attachment[{$this->slide->ID}][readmore_font_style][]' multiple>$readmore_font_style</select>
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_font_color}</label>
                    <input  class='option settingColorSelector' type='text' name='attachment[{$this->slide->ID}][readmore_font_color]' value='{$readmore_font_color}'/>
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_bg_color}</label><input  class='option settingColorSelector' type='text' name='attachment[{$this->slide->ID}][readmore_bg_color]' value='{$readmore_bg_color}'/>
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_border_color}</label>
                 <input  class='option settingColorSelector' type='text' name='attachment[{$this->slide->ID}][readmore_border_color]' value='{$readmore_border_color}'/>
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_in_effect}</label>
                    <select name='attachment[{$this->slide->ID}][readmore_in_effect]'>{$readmore_in_effects}</select>
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_out_effect}</label>
                    <select name='attachment[{$this->slide->ID}][readmore_out_effect]'>{$readmore_out_effects}</select>
                </td>
            </tr>
            <tr>
                <td>
                    <h4 class='slide-settings'>Slide Image Settings</h4>
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_top}</label><input class='option' type='number' min='1' max='100' step='1' name='attachment[{$this->slide->ID}][image_top]' value='{$image_top}' />%
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_left}</label><input class='option' type='number' min='1' max='100' step='1' name='attachment[{$this->slide->ID}][image_left]' value='{$image_left}' />%
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_width}</label><input class='option' type='text' name='attachment[{$this->slide->ID}][image_width]' value='{$image_width}' />px &nbsp;<label>{$str_height}</label><input class='option' type='text'  name='attachment[{$this->slide->ID}][image_height]' value='{$image_height}' />px
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_in_effect}</label>
                    <select name='attachment[{$this->slide->ID}][image_in_effect]'>{$image_in_effects}</select>
                </td>
            </tr>
            <tr>
                <td>
                    <label>{$str_out_effect}</label>
                    <select name='attachment[{$this->slide->ID}][image_out_effect]'>{$image_out_effects}</select>
                </td>
            </tr>
        </table>
    </td>
</tr>

EOS;

        $row .= "    </table></div>";
        $row .= "    </div>";
        $row .= "    </td>";



        $row .= "</tr>";

        return $row;
    }


    protected function _osc_create_font_family_select($selected_font_family='') {
        $html = '';
        foreach($this->font_family_array as $font_family_value){
            $html .='<option value="'.$font_family_value.'" '.($selected_font_family==$font_family_value?'selected="selected"':'').'>'.$font_family_value.'</option>';
        }
        return $html;
    }

    protected function _osc_create_effects_select($selected_font_family='', $inOut= 'in') {
        $effects = $this->eps_in_effects;
        if ($inOut == 'out') {
            $effects = $this->eps_out_effects;
        }
        $html = '';
        foreach($effects as $font_family_value){
            $html .='<option value="'.$font_family_value.'" '.($selected_font_family==$font_family_value?'selected="selected"':'').'>'.$font_family_value.'</option>';
        }
        return $html;
    }

    protected function _osc_create_google_font_family_select($selected_font_family='') {
        $html = '';
        global $eps_google_font_family;
        foreach($eps_google_font_family as $font_family_value){
            $html .='<option value="'.$font_family_value.'" '.($selected_font_family==$font_family_value?'selected="selected"':'').'>'.$font_family_value.'</option>';
        }
        return $html;
    }
    protected function _osc_create_font_style_select($selected_font_style='') {
        $html = '';
        $html .='<option value="inherit" '.(is_array($selected_font_style) && in_array('inherit',$selected_font_style)?'selected="selected"':'').'>Inherit</option>';
        $html .='<option value="none" '.(is_array($selected_font_style) && in_array('none',$selected_font_style)?'selected="selected"':'').'>None</option>';
        foreach($this->font_style_array as $font_style_value){
            $html .='<option value="'.$font_style_value.'" '.(is_array($selected_font_style) && in_array($font_style_value,$selected_font_style)?'selected="selected"':'').'>'.$font_style_value.'</option>';
        }
        return $html;
    }

    protected function eps_get_public_slide() {
        // get the image url (and handle cropping)
        $imageHelper = new epsImageHelperClass(
            $this->slide->ID,
            $this->settings['width'],
            $this->settings['height'],
            isset($this->settings['smartCrop']) ? $this->settings['smartCrop'] : 'false'
        );

        $url = $imageHelper->eps_get_image_url();

        if (is_wp_error($url)) {
            return ""; // bail out here. todo: look at a way of notifying the admin
        }

        if (!isset($this->settings['load_from_new']) || $this->settings['load_from_new'] ==  false || $this->settings['load_from_new']=='') {
            $url1     = get_post_meta($this->slide->ID, 'eps-slider_url', true);
            $readmore     = get_post_meta($this->slide->ID, 'eps-slider_readmore', true);
            $target  = get_post_meta($this->slide->ID, 'eps-slider_new_window', true) ? 'checked=checked' : '';
            $heading = get_post_meta($this->slide->ID, 'eps-slider_heading', true);
            $caption = htmlentities($this->slide->post_excerpt, ENT_QUOTES, 'UTF-8');
        } else {
            $url1     = get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_url', true);
            $readmore     = get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_readmore', true);
            $target  = get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_new_window', true) ? 'checked=checked' : '';
            $heading = get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_heading', true);
            $caption = get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_caption', true);
        }


        // store the slide details
        $slide = array(
            'thumb' => $url,
            'url' => $url1,
            'readmore' => $readmore,
            'heading' => $heading,
            'alt' => get_post_meta($this->slider->ID, '_wp_attachment_'.$this->slide->ID.'_image_alt', true),
            'target' => $target,
            'content' => $caption,
            'content_raw' =>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_caption', true),
            'heading_font_size'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_heading_font_size', true),
            'readmore_font_size'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_readmore_font_size', true),
            'content_top_margin'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_content_top_margin', true),
            'content_font_size'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_content_font_size', true),
            'heading_font_family'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_heading_font_family', true),
            'heading_google_font_family'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_heading_google_font_family', true),
            'heading_other_font_family'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_heading_other_font_family', true),
            'readmore_font_family'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_readmore_font_family', true),
            'readmore_google_font_family'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_readmore_google_font_family', true),
            'readmore_other_font_family'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_readmore_other_font_family', true),
            'content_font_family'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_content_font_family', true),
            'content_google_font_family'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_content_google_font_family', true),
            'content_other_font_family'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_content_other_font_family', true),
            'heading_font_style'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_heading_font_style', true),
            'readmore_font_style'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_readmore_font_style', true),
            'content_font_style'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_content_font_style', true),
            'heading_font_color'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_heading_font_color', true),
            'content_font_color'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_content_font_color', true),
            'content_line_height'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_content_line_height', true),
            'readmore_font_color'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_readmore_font_color', true),
            'readmore_bg_color'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_readmore_bg_color', true),
            'readmore_border_color'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_readmore_border_color', true),
            'image_top'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_image_top', true),
            'image_left'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_image_left', true),
            'image_width'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_image_width', true),
            'image_height'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_image_height', true),
            'heading_in_effect'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_heading_in_effect', true),
            'heading_in_effect'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_heading_in_effect', true),
            'heading_out_effect'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_heading_out_effect', true),
            'content_in_effect'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_content_in_effect', true),
            'content_out_effect'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_content_out_effect', true),
            'readmore_in_effect'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_readmore_in_effect', true),
            'readmore_out_effect'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_readmore_out_effect', true),
            'image_in_effect'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_image_in_effect', true),
            'image_out_effect'=>get_post_meta($this->slider->ID, 'eps-slider_'.$this->slide->ID.'_image_out_effect', true),
        );

        // return the slide HTML

        return $this->eps_get_parallax_slider_markup($slide).$this->eps_get_parallax_slider_markup_style($slide);
    }

    function _return_effect_value($slide, $name) {
        $value = $slide[$name];
        if (!$value) {
            $value = $this->settings[$name];
        }
        //echo $value;
        return $value;
    }

    private function eps_get_parallax_slider_markup_style($slide){
        global $eps_google_enqueue_scripts;
        if($slide['heading_font_family']!=false || $slide['heading_font_family']!=''){
            if($slide['heading_font_family']=='Google Font'){
                $heading_font_family= 'font-family:'.$slide['heading_google_font_family'].';';
                if(strpos($_SESSION['enqueue_script'],urlencode($slide['heading_google_font_family']))===false){
                    $prefix= $_SESSION['enqueue_script']==''?'':'|';
                    $_SESSION['enqueue_script'].=$prefix.urlencode($slide['heading_google_font_family']);
                }
            }elseif($slide['heading_font_family']=='Other'){
                $heading_font_family='font-family:'.$slide['heading_other_font_family'].';';
            }else{
                $heading_font_family='font-family:'.$slide['heading_font_family'].';';
            }
        }
        else{
            $heading_font_family= '';
        }
        if($slide['content_font_family']!=false || $slide['content_font_family']!=''){
            if($slide['content_font_family']=='Google Font'){
                $content_font_family= 'font-family:'.$slide['content_google_font_family'].';';

                if(strpos($_SESSION['enqueue_script'],urlencode($slide['content_google_font_family']))===false){
                    $prefix= $_SESSION['enqueue_script']==''?'':'|';

                    $_SESSION['enqueue_script'].=$prefix.urlencode($slide['content_google_font_family']);
                }
            }elseif($slide['content_font_family']=='Other'){
                $content_font_family='font-family:'.$slide['content_other_font_family'].';';
            }else{
                $content_font_family='font-family:'.$slide['content_font_family'].';';
            }
        }
        else{
            $content_font_family= '';
        }

        if($slide['readmore_font_family']!=false || $slide['readmore_font_family']!=''){
            if($slide['readmore_font_family']=='Google Font'){
                $readmore_font_family= 'font-family:'.$slide['readmore_google_font_family'].';';
                if(strpos($_SESSION['enqueue_script'],urlencode($slide['readmore_google_font_family']))===false){
                    $prefix= $_SESSION['enqueue_script']==''?'':'|';
                    $_SESSION['enqueue_script'].=$prefix.urlencode($slide['readmore_google_font_family']);
                }
            }elseif($slide['readmore_font_family']=='Other'){
                $readmore_font_family='font-family:'.$slide['readmore_other_font_family'].';';
            }else{
                $readmore_font_family='font-family:'.$slide['readmore_font_family'].';';
            }
        }
        else{
            $readmore_font_family= '';
        }
        $heading_font_size=($slide['heading_font_size']!=false || $slide['heading_font_size']!=0)?'font-size:'.$slide['heading_font_size'].'px;':'';

        $heading_font_color=($slide['heading_font_color']!=false || $slide['heading_font_color']!='')?'color:'.$slide['heading_font_color'].';':'';

        $heading_font_style='';

        if(is_array($slide['heading_font_style']) && !in_array('inherit',$slide['heading_font_style'])){
            if(is_array($slide['heading_font_style']) && in_array('none',$slide['heading_font_style'])){
                $heading_font_style.='font-style:normal;';
                $heading_font_style.='text-decoration:none;';
                $heading_font_style.='font-weight:normal;';
            }
            if(is_array($slide['heading_font_style']) && in_array('italic',$slide['heading_font_style'])){
                $heading_font_style.='font-style:italic;';
            } else{
                $heading_font_style.='font-style:normal;';
            }
            if(is_array($slide['heading_font_style']) && in_array('underline',$slide['heading_font_style'])){
                $heading_font_style.='text-decoration:underline;';
            } else{
                $heading_font_style.='text-decoration:none;';
            }
            if(is_array($slide['heading_font_style']) && in_array('bold',$slide['heading_font_style'])){
                $heading_font_style.='font-weight:bold;';
            } else{
                $heading_font_style.='font-weight:normal;';
            }
        }
        $content_top_margin=($slide['content_top_margin']!=false || $slide['content_top_margin']!='')?'margin-top:'.$slide['content_top_margin'].'px;':'';
        $content_font_size=($slide['content_font_size']!=false || $slide['content_font_size']!='')?'font-size:'.$slide['content_font_size'].'px;':'';

        $content_font_color=($slide['content_font_color']!=false || $slide['content_font_color']!='')?'color:'.$slide['content_font_color'].';':'';
        $content_line_height=($slide['content_line_height']!=false || $slide['content_line_height']!='')?'line-height:'.$slide['content_line_height'].';':'';
        $content_font_style='';


        if(is_array($slide['content_font_style']) && !in_array('inherit',$slide['content_font_style'])){
            if(is_array($slide['content_font_style']) && in_array('none',$slide['content_font_style'])){
                $content_font_style.='font-style:normal;';
                $content_font_style.='text-decoration:none;';
                $content_font_style.='font-weight:normal;';
            }
            if(is_array($slide['content_font_style']) && in_array('italic',$slide['content_font_style'])){
                $content_font_style.='font-style:italic;';
            } else{
                $content_font_style.='font-style:normal;';
            }
            if(is_array($slide['content_font_style']) && in_array('underline',$slide['content_font_style'])){
                $content_font_style.='text-decoration:underline;';
            } else{
                $content_font_style.='text-decoration:none;';
            }
            if(is_array($slide['content_font_style']) && in_array('bold',$slide['content_font_style'])){
                $content_font_style.='font-weight:bold;';
            } else{
                $content_font_style.='font-weight:normal;';
            }
        }
        $readmore_font_size=($slide['readmore_font_size']!=false || $slide['readmore_font_size']!='')?'font-size:'.$slide['readmore_font_size'].'px;':'';

        $readmore_font_color=($slide['readmore_font_color']!=false || $slide['readmore_font_color']!='')?'color:'.$slide['readmore_font_color'].';':'';
        $readmore_font_style='';

        if(is_array($slide['readmore_font_style']) && !in_array('inherit',$slide['readmore_font_style'])){
            if(is_array($slide['readmore_font_style']) && in_array('none',$slide['readmore_font_style'])){
                $readmore_font_style.='font-style:normal;';
                $readmore_font_style.='text-decoration:none;';
                $readmore_font_style.='font-weight:normal;';
            }
            if(is_array($slide['readmore_font_style']) && in_array('italic',$slide['readmore_font_style'])){
                $readmore_font_style.='font-style:italic;';
            } else{
                $readmore_font_style.='font-style:normal;';
            }
            if(is_array($slide['readmore_font_style']) && in_array('underline',$slide['readmore_font_style'])){
                $readmore_font_style.='text-decoration:underline;';
            } else{
                $readmore_font_style.='text-decoration:none;';
            }
            if(is_array($slide['readmore_font_style']) && in_array('bold',$slide['readmore_font_style'])){
                $readmore_font_style.='font-weight:bold;';
            } else{
                $readmore_font_style.='font-weight:normal;';
            }
        }
        $readmore_border_color=($slide['readmore_border_color']!=false || $slide['readmore_border_color']!='')?'border-color:'.$slide['readmore_border_color'].';':'';
        $readmore_bg_color=($slide['readmore_bg_color']!=false || $slide['readmore_bg_color']!='')?'background:'.$slide['readmore_bg_color'].';':'';
//        $readmore_hoverbgcolor='background-color:'.$this->colourBrightness($slide['readmore_bg_color'],0.80).';';


        if(!$content_top_margin && (isset($this->settings['content_top_margin']) &&  ($this->settings['content_top_margin'] != 'false' || $this->settings['content_top_margin']!=0))) {
            $content_top_margin = " margin-top:{$this->settings['content_top_margin']}px; ";
        }

        if( $slide['image_top']== 'true'){
            $topPer = "top:0;";
        } elseif($slide['image_top']!= false || $slide['image_top']!= 0){

            $topPer = " top:{$slide['image_top']}%; ";

        } elseif ($this->settings['topPer'] != 'false' || $this->settings['topPer']!=0) {
            $topPer = " top:{$this->settings['topPer']}%; ";
        }
        else {
            $topPer = "top:0;";
        }


        if( $slide['image_left']== 'true'){
            $leftPerval = "left:0;";
            $leftPer=0;
        } elseif($slide['image_left']!= false || $slide['image_left']!= 0){

            $leftPerval = " left:{$slide['image_left']}%; ";
            $leftPer=$slide['image_left'];
        } elseif ($this->settings['leftPer'] != false || $this->settings['leftPer']!=0) {
            $leftPerval = " left:{$this->settings['leftPer']}%; ";
            $leftPer=$this->settings['leftPer'];
        }
        else {
            $leftPerval = "left:0;";
            $leftPer=0;
        }

        if( $slide['image_height']== 'true'){
            $height = " height: auto;";
        } elseif($slide['image_height']!= false || $slide['image_height']!= 0){
            $height = " height:{$slide['image_height']}px; ";
        } elseif ($this->settings['height'] != false || $this->settings['height']!= 0){
            $height = " height:{$this->settings['height']}px; ";
        } else{
            $height = " height: auto;";
        }
        if( $slide['image_width']== 'true'){
            $width = " width: 100%;";
        } elseif($slide['image_width']!= false ||  $slide['image_width']!= 0){
            $width = " width:{$slide['image_width']}px; ";
        } elseif ($this->settings['width']!= false || $this->settings['width']!= 0) {
            $width = " width:{$this->settings['width']}px; ";
        } else{
            $width = " width: 100%;";
        }

        if (isset($this->settings['navigation_color']) && ($this->settings['navigation_color']!= false || $this->settings['navigation_color']!= '')) {
            $navigation_color = $this->settings['navigation_color'];
        } else {
            $navigation_color = '#E4B42D';
        }

        if (isset($this->settings['pager_color']) && ($this->settings['pager_color']!= false || $this->settings['pager_color']!= '')) {
            $pager_color = $this->settings['pager_color'];
        } else {
            $pager_color = '#E4B42D';
        }

        $leftper5=intval($leftPer)-5;
        $leftper6=intval($leftPer)-10;
        $leftper7=intval($leftPer)+10;
        $css =<<<EOF

     .eps-custom-{$this->slider->ID} #da-img-{$this->slide->ID}{
        {$height}
        {$width}
        {$leftPerval}
        {$topPer}
        }
        .eps-custom-{$this->slider->ID} #da-slide-heading-{$this->slide->ID} h2{
        {$heading_font_size}
        {$heading_font_family}
        {$heading_font_color}
        {$heading_font_style}
        }
         .eps-custom-{$this->slider->ID} #da-slide-heading-{$this->slide->ID} div.da-slide-content{
         {$content_top_margin}
        {$content_font_size}
        {$content_font_family}
        {$content_font_color}
        {$content_font_style}
        {$content_line_height}
        }
         .eps-custom-{$this->slider->ID} #da-link-{$this->slide->ID}{
        {$readmore_font_size}
        {$readmore_font_family}
        {$readmore_font_color}
        {$readmore_font_style}
        {$readmore_border_color}
        {$readmore_bg_color}
        }

EOF;


        $elems = array();
        $showFrom = false;
        $showTo = false;

        if ($this->_return_effect_value($slide, 'heading_in_effect') == 'Parallax') {
            $elems['#da-slide-heading-'.$this->slide->ID.' h2']['fromright']=array(
                'name'=>'fromright',
                'css'=>'fromRightAnim1 0.6s ease-in 0.8s both;'
            );
            $elems['#da-slide-heading-'.$this->slide->ID.' h2']['fromleft']=array(
                'name'=>'fromleft',
                'css'=>'fromLeftAnim1 0.6s ease-in 0.6s both;'
            );
        }
        if ($this->_return_effect_value($slide, 'content_in_effect') == 'Parallax') {
            $elems['#da-slide-heading-'.$this->slide->ID.' .da-slide-content']['fromright']=array(
                'name'=>'fromright',
                'css'=>'fromRightAnim2 0.6s ease-in 0.8s both;'
            );
            $elems['#da-slide-heading-'.$this->slide->ID.' .da-slide-content']['fromleft']=array(
                'name'=>'fromleft',
                'css'=>'fromLeftAnim2 0.6s ease-in 0.6s both;'
            );
        }
        if ($this->_return_effect_value($slide, 'readmore_in_effect') == 'Parallax') {
            $elems['#da-link-'.$this->slide->ID]['fromright']=array(
                'name'=>'fromright',
                'css'=>'fromRightAnim3 0.4s ease-in 1.2s both;'
            );
            $elems['#da-link-'.$this->slide->ID]['fromleft']=array(
                'name'=>'fromleft',
                'css'=>'fromLeftAnim3 0.4s ease-in 1.2s both;'
            );
        }
        if ($this->_return_effect_value($slide, 'image_in_effect') == 'Parallax') {
            $showFrom = true;
            $elems['#da-img-'.$this->slide->ID]['fromright']=array(
                'name'=>'fromright',
                'css'=>"fromRightAnim{$this->slider->ID}{$this->slide->ID} 0.6s ease-in 0.8s both;"
            );
            $elems['#da-img-'.$this->slide->ID]['fromleft']=array(
                'name'=>'fromleft',
                'css'=>"fromLeftAnim{$this->slider->ID}{$this->slide->ID} 0.6s ease-in 0.6s both;"
            );
        }


        if ($this->_return_effect_value($slide, 'heading_out_effect') == 'Parallax') {
            $elems['#da-slide-heading-'.$this->slide->ID.' h2']['toright']=array(
                'name'=>'toright',
                'css'=>'toRightAnim1 0.6s ease-in 0.6s both;'
            );
            $elems['#da-slide-heading-'.$this->slide->ID.' h2']['toleft']=array(
                'name'=>'toleft',
                'css'=>'toLeftAnim1 0.6s ease-in both;'
            );
        }
        if ($this->_return_effect_value($slide, 'content_out_effect') == 'Parallax') {
            $elems['#da-slide-heading-'.$this->slide->ID.' .da-slide-content']['toright']=array(
                'name'=>'toright',
                'css'=>'toRightAnim2 0.6s ease-in 0.3s both;'
            );
            $elems['#da-slide-heading-'.$this->slide->ID.' .da-slide-content']['toleft']=array(
                'name'=>'toleft',
                'css'=>'toLeftAnim2 0.6s ease-in 0.3s both;'
            );
        }
        if ($this->_return_effect_value($slide, 'readmore_out_effect') == 'Parallax') {
            $elems['#da-link-'.$this->slide->ID]['toright']=array(
                'name'=>'toright',
                'css'=>'toRightAnim3 0.4s ease-in both;'
            );
            $elems['#da-link-'.$this->slide->ID]['toleft']=array(
                'name'=>'toleft',
                'css'=>'toLeftAnim3 0.4s ease-in both;'
            );
        }
        if ($this->_return_effect_value($slide, 'image_out_effect') == 'Parallax') {
            $showTo = true;
            $elems['#da-img-'.$this->slide->ID]['toright']=array(
                'name'=>'toright',
                'css'=>"toRightAnim{$this->slider->ID}{$this->slide->ID} 0.6s ease-in both;"
            );
            $elems['#da-img-'.$this->slide->ID]['toleft']=array(
                'name'=>'toleft',
                'css'=>"toLeftAnim{$this->slider->ID}{$this->slide->ID} 0.6s ease-in 0.6s both;"
            );
        }


        foreach ($elems as $eindex=>$elem) {
            foreach ($elem as $el) {
                $css .=<<<EOF
                    .eps-custom-{$this->slider->ID} .da-slide-{$el['name']} {$eindex} {
                        -webkit-animation: {$el['css']}
                        -moz-animation: {$el['css']}
                        -o-animation: {$el['css']}
                        -ms-animation: {$el['css']}
                        animation: {$el['css']}
                    }
EOF;
            }
        }

        if ($showFrom) {



        $css .=<<<EOF

            @-webkit-keyframes fromRightAnim{$this->slider->ID}{$this->slide->ID}{
                0%{ left: 110%; opacity: 0; }
                100%{ left: {$leftPer}%; opacity: 1; }
            }
            @-moz-keyframes fromRightAnim{$this->slider->ID}{$this->slide->ID}{
                0%{ left: 110%; opacity: 0; }
                100%{ left: {$leftPer}%; opacity: 1; }
            }
            @-o-keyframes fromRightAnim{$this->slider->ID}{$this->slide->ID}{
                0%{ left: 110%; opacity: 0; }
                100%{ left: {$leftPer}%; opacity: 1; }
            }
            @-ms-keyframes fromRightAnim{$this->slider->ID}{$this->slide->ID}{
                0%{ left: 110%; opacity: 0; }
                100%{ left: {$leftPer}%; opacity: 1; }
            }
            @keyframes fromRightAnim{$this->slider->ID}{$this->slide->ID}{
                0%{ left: 110%; opacity: 0; }
                100%{ left: {$leftPer}%; opacity: 1; }
            }
            @-webkit-keyframes fromLeftAnim{$this->slider->ID}{$this->slide->ID}{
                0%{ left: -110%; opacity: 0; }
                100%{ left: {$leftPer}%; opacity: 1; }
            }
            @-moz-keyframes fromLeftAnim{$this->slider->ID}{$this->slide->ID}{
                0%{ left: -110%; opacity: 0; }
                100%{ left: {$leftPer}%; opacity: 1; }
            }
            @-o-keyframes fromLeftAnim{$this->slider->ID}{$this->slide->ID}{
                0%{ left: -110%; opacity: 0; }
                100%{ left: {$leftPer}%; opacity: 1; }
            }
            @-ms-keyframes fromLeftAnim{$this->slider->ID}{$this->slide->ID}{
                0%{ left: -110%; opacity: 0; }
                100%{ left: {$leftPer}%; opacity: 1; }
            }
            @keyframes fromLeftAnim{$this->slider->ID}{$this->slide->ID}{
                0%{ left: -110%; opacity: 0; }
                100%{ left: {$leftPer}%; opacity: 1; }
            }

EOF;
        }
        if ($showTo) {
		    $css .=<<<EOF
                @-webkit-keyframes toRightAnim{$this->slider->ID}{$this->slide->ID}{
					0%{ left: {$leftPer}%;  opacity: 1; }
					30%{ left: {$leftper5}%;  opacity: 1; }
					100%{ left: 100%; opacity: 0; }
				}
				@-moz-keyframes toRightAnim{$this->slider->ID}{$this->slide->ID}{
					0%{ left: {$leftPer}%;  opacity: 1; }
					30%{ left: {$leftper5}%;  opacity: 1; }
					100%{ left: 100%; opacity: 0; }
				}
				@-o-keyframes toRightAnim{$this->slider->ID}{$this->slide->ID}{
					0%{ left: {$leftPer}%;  opacity: 1; }
					30%{ left: {$leftper5}%;  opacity: 1; }
					100%{ left: 100%; opacity: 0; }
				}
				@-ms-keyframes toRightAnim{$this->slider->ID}{$this->slide->ID}{
					0%{ left: {$leftPer}%;  opacity: 1; }
					30%{ left: {$leftper5}%;  opacity: 1; }
					100%{ left: 100%; opacity: 0; }
				}
				@keyframes toRightAnim{$this->slider->ID}{$this->slide->ID}{
					0%{ left: {$leftPer}%;  opacity: 1; }
					30%{ left: {$leftper5}%;  opacity: 1; }
					100%{ left: 100%; opacity: 0; }
				}
				@-webkit-keyframes toLeftAnim{$this->slider->ID}{$this->slide->ID}{
					0%{ left: {$leftPer}%;  opacity: 1; }
					40%{ left: {$leftper7}%;  opacity: 1; }
					90%{ left: 0%;  opacity: 0; }
					100%{ left: -50%; opacity: 0; }
				}
				@-moz-keyframes toLeftAnim{$this->slider->ID}{$this->slide->ID}{
					0%{ left: {$leftPer}%;  opacity: 1; }
					40%{ left: {$leftper7}%;  opacity: 1; }
					90%{ left: 0%;  opacity: 0; }
					100%{ left: -50%; opacity: 0; }
				}
				@-o-keyframes toLeftAnim{$this->slider->ID}{$this->slide->ID}{
					0%{ left: {$leftPer}%;  opacity: 1; }
					40%{ left: {$leftper7}%;  opacity: 1; }
					90%{ left: 0%;  opacity: 0; }
					100%{ left: -50%; opacity: 0; }
				}
				@-ms-keyframes toLeftAnim{$this->slider->ID}{$this->slide->ID}{
					0%{ left: {$leftPer}%;  opacity: 1; }
					40%{ left: {$leftper7}%;  opacity: 1; }
					90%{ left: 0%;  opacity: 0; }
					100%{ left: -50%; opacity: 0; }
				}
				@keyframes toLeftAnim{$this->slider->ID}{$this->slide->ID}{
					0%{ left: {$leftPer}%;  opacity: 1; }
					40%{ left: {$leftper7}%;  opacity: 1; }
					90%{ left: 0%;  opacity: 0; }
					100%{ left: -50%; opacity: 0; }
				}
EOF;
        }
        $css .=<<<EOF
				#da-slider-eps_{$this->slider->ID} .da-dots span {
				    background: none repeat scroll 0 0 {$pager_color};
				}

				#da-slider-eps_{$this->slider->ID} .da-arrows span {
				    background: none repeat scroll 0 0 {$navigation_color};
				}

EOF;

        if (strlen($css)) {
            return "<style type='text/css'>{$css}\n    </style>";
        }
    }
    private function eps_get_parallax_slider_markup($slide) {


        $html = " <div class='da-slide'>";
        $html .= " <div  id='da-slide-heading-".$this->slide->ID."' class='da-slide-heading-content'>";
        if (strlen($slide['heading'])) {
            $in_effect = $slide['heading_in_effect'];
            $out_effect = $slide['heading_out_effect'];
            if ( !$in_effect) {
                $in_effect = isset($this->settings['heading_in_effect']);
            }
            if ( !$out_effect) {
                $out_effect = isset($this->settings['heading_out_effect']);
            }
            $html .= "  <h2 data-in_effect='{$in_effect}' data-out_effect='{$out_effect}'>{$slide['heading']}</h2>";
        }
        if (strlen($slide['content'])) {
            $in_effect = $slide['content_in_effect'];
            $out_effect = $slide['content_out_effect'];
            if ( !$in_effect) {
                $in_effect = isset($this->settings['content_in_effect']);
            }
            if ( !$out_effect) {
                $out_effect = isset($this->settings['content_out_effect']);
            }
            $html .= "<div class='da-slide-content' data-in_effect='{$in_effect}' data-out_effect='{$out_effect}'>{$slide['content']}</div>";
        }
        $html .= "</div>";
        if (strlen($slide['url'])) {
            $in_effect = $slide['readmore_in_effect'];
            $out_effect = $slide['readmore_out_effect'];
            if ( !$in_effect) {
                $in_effect = isset($this->settings['readmore_in_effect']);
            }
            if ( !$out_effect) {
                $out_effect = isset($this->settings['readmore_out_effect']);
            }
            $readmoretext=strlen($slide['readmore']) ? $slide['readmore']:'Read More';
            $html .= "<a href='{$slide['url']}' target='{$slide['target']}' id='da-link-".$this->slide->ID."' class='da-link' data-in_effect='{$in_effect}' data-out_effect='{$out_effect}'>{$readmoretext}</a>";
        }
        $html=trim($html);
        $in_effect = $slide['image_in_effect'];
        $out_effect = $slide['image_out_effect'];
        if ( !$in_effect) {
            $in_effect = isset($this->settings['image_in_effect']);
        }
        if ( !$out_effect) {
            $out_effect = isset($this->settings['image_out_effect']);
        }
        $html .="<div id='da-img-".$this->slide->ID."' class='da-img' style=' background: url({$slide['thumb']});' title='{$slide['alt']}'  data-in_effect='{$in_effect}' data-out_effect='{$out_effect}'>&nbsp;</div>";
        //$html .="<div id='da-img-".$this->slide->ID."' class='da-img'><img src='{$slide['thumb']}' alt='{$slide['alt']}' /></div>";
        $html .='</div>';

        return trim($html);
    }

    protected function eps_save($fields) {
        // update the slide
        wp_update_post(array(
            'ID' => $this->slide->ID,
            'post_excerpt' => $fields['post_excerpt'],
            'menu_order' => $fields['menu_order']
        ));

        // store the URL as a meta field against the attachment
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_url', $fields['url']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_readmore', $fields['readmore']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID, $this->slide->ID.'_heading', $fields['heading']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_heading_font_size', $fields['heading_font_size']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_caption', $fields['caption']);

        // store the 'new window' setting
        $new_window = isset($fields['new_window']) && $fields['new_window'] == 'on' ? 'true' : 'false';

        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_new_window', $new_window);

        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_readmore_font_size', $fields['readmore_font_size']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_content_top_margin', $fields['content_top_margin']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_content_font_size', $fields['content_font_size']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_heading_font_family', $fields['heading_font_family']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_readmore_font_family', $fields['readmore_font_family']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_content_font_family', $fields['content_font_family']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_heading_google_font_family', $fields['heading_google_font_family']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_readmore_google_font_family', $fields['readmore_google_font_family']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_content_google_font_family', $fields['content_google_font_family']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_heading_other_font_family', $fields['heading_other_font_family']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_readmore_other_font_family', $fields['readmore_other_font_family']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_content_other_font_family', $fields['content_other_font_family']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_heading_font_style', $fields['heading_font_style']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_readmore_font_style', $fields['readmore_font_style']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_content_font_style', $fields['content_font_style']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_heading_font_color', $fields['heading_font_color']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_content_font_color', $fields['content_font_color']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_content_line_height', $fields['content_line_height']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_readmore_font_color', $fields['readmore_font_color']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_readmore_bg_color', $fields['readmore_bg_color']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_readmore_border_color', $fields['readmore_border_color']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_image_top', $fields['image_top']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_image_left', $fields['image_left']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_image_width', $fields['image_width']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_image_height', $fields['image_height']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_heading_in_effect', $fields['heading_in_effect']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_heading_out_effect', $fields['heading_out_effect']);

        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_content_in_effect', $fields['content_in_effect']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_content_out_effect', $fields['content_out_effect']);

        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_image_in_effect', $fields['image_in_effect']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_image_out_effect', $fields['image_out_effect']);

        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_readmore_in_effect', $fields['readmore_in_effect']);
        $this->eps_add_or_update_or_delete_meta($this->slider->ID,  $this->slide->ID.'_readmore_out_effect', $fields['readmore_out_effect']);


    }
}
