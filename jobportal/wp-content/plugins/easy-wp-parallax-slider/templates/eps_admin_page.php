<?php
//$this->eps_admin_process();
$sliderId = $this->slider->id;

$font_family_array=array('Georgia, serif','Palatino Linotype, Book Antiqua, Palatino','Times New Roman','Arial, Helvetica','Arial Black, Gadget','Comic Sans MS, cursive','Impact, Charcoal','Lucida Sans Unicode','Tahoma, Geneva','Trebuchet MS','Verdana, Geneva','Courier New, Courier, monospace','Lucida Console, Monaco','google'=>'Google Font','Other');

$font_style_array=array('none','bold','italic','underline');


$eps_in_effects = array('Parallax',
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

$eps_out_effects = array('Parallax',
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



global $eps_google_font_family;
?>

<script type='text/javascript'>
    var eps_id = <?php echo $sliderId; ?>;
</script>

<div class="wrap easy-parallax-slider">
<form accept-charset="UTF-8" action="?page=<?php echo $this->filename;?>&id=<?php echo $sliderId ?>" method="post">
<h2 class="nav-tab-wrapper">
    <?php

    if ($tabs = $this->eps_all_easy_sliders()) {
        foreach ($tabs as $tab) {
            if ($tab['active']) {
                echo "<div class='nav-tab nav-tab-active'><input type='text' name='title'  value='" .  $tab['title'] . "' onkeypress='this.style.width = ((this.value.length + 1) * 9) + \"px\"' /></div>";
            } else {
                echo "<a href='?page=".$this->filename."&id={$tab['id']}' class='nav-tab'>" .$tab['title'] . "</a>";
            }
        }
    }
    ?>

    <a href="?page=<?php echo $this->filename;?>&add=true" id="create_new_tab" class="nav-tab">+</a>
</h2>
<?php
if (!$sliderId) {
    return;
}
?>
<input type='hidden' name="settings[load_from_new]" value='1' />
<div class="left">
    <table class="widefat sortable append_slide">
        <thead>
        <tr>
            <th style="width: 100px;">
                <?php _e("Slides", $this->filename) ?>
            </th>
            <th>
                <a href='#' class='button alignright add-slide' data-editor='content' title='<?php _e("Add Slide", $this->filename) ?>'>
                    <span class='wp-media-buttons-icon'></span> <?php _e("Add Slide", $this->filename) ?>
                </a>
            </th>

        </tr>
        </thead>

        <tbody>
        <?php
        $this->slider->eps_render_admin_slides();
        ?>
        </tbody>
    </table>
</div>

<div class='right'>
<table class="widefat settings eps-slider-settings-tbl" cellspacing="3" cellpadding="3">
<thead>
<tr>
    <th colspan='2'>
        <span class='configuration'><?php _e("Settings", $this->filename) ?></span>
        <input class='alignright button button-primary' type='submit' name='save' id='save' value='<?php _e("Save", $this->filename) ?>' />
        <input class='alignright button button-primary' type='submit' name='preview' id='preview' value='<?php _e("Save & Preview", $this->filename) ?>' id='quickview' data-slider_id='<?php echo $sliderId ?>' data-slider_width='<?php //echo $this->slider->get_setting('width') ?>' data-slider_height='<?php //echo $this->slider->get_setting('height') ?>' />
        <span class="spinner"></span>
    </th>
</tr>
</thead>

<tbody>
<tr>
    <td colspan='2' class='highlight'><?php _e("Slider Settings", $this->filename) ?></td>
</tr>

<tr>
    <td width='115px' class='tipsy-tooltip' title="<?php _e("Set the default background  for slider.",
        $this->filename) ?>">
        <?php _e("Background", $this->filename) ?>
        <input class="select-slider" id='flex' rel='flex' type='hidden' name="settings[type]" value='flex' />
    </td>
    <td>
        <input class='option check_sbg' type='radio' id="sbg_def" name="settings[slider_bg_type]" <?php if ($this->slider->get_setting('slider_bg_type') == 'default') echo 'checked="checked"'; if($this->slider->get_setting('slider_bg_type')== false || $this->slider->get_setting('slider_bg_type')== '' || $this->slider->get_setting('slider_bg_type')== 'false')  { echo 'checked="checked"'; }?> value="default"/> <label class='option' for="sbg_def">Default</label>
        <input class='option check_sbg' type='radio' id="sbg_img" name="settings[slider_bg_type]" <?php if ($this->slider->get_setting('slider_bg_type') == 'image') echo 'checked="checked"' ?> value="image"/><label class='option' for="sbg_img">Upload Image</label>
        <input class='option check_sbg' type='radio' id="sbg_color" name="settings[slider_bg_type]" <?php if ($this->slider->get_setting('slider_bg_type') == 'color') echo 'checked="checked"' ?> value="color"/><label class='option' for="sbg_color">Color</label>
    </td>
</tr>
<tr>
    <td width='100%' colspan="2">
        <div id="bg-preview" class="bg-preview" style="width: 130px; float: left;">
            <div id="used_sbg_def" class="hide_all_bg">
                <?php

                $bg_url=EPS_ASSETS_URL.'images/waves.gif';

                ?>
                <img src="<?php echo $bg_url; ?>" width="100" height="50" />
            </div>
            <div id="used_sbg_img" class="hide_all_bg">
                <?php
                if($sliderId && get_post_meta($sliderId, 'eps-slider_bg', true)){
                    $bg_url=get_post_meta($this->slider->id, 'eps-
                    ', true);
                    echo '<img src="'.$bg_url.'" id="sbg_upload_img" width="100" height="50" />';
                    $imgtitle='Change';
                } else{
                    $imgtitle='Upload';
                }
                ?>
                <a href='#' class='button alignright add-bg' data-editor='content' title='<?php echo $imgtitle; ?>' style="margin-top: 14px;">
                    <span class='wp-media-buttons-icon'></span> <?php echo $imgtitle; ?>
                </a>
            </div>
            <div id="used_sbg_color" class="hide_all_bg">
                <label class='option' ><input  class='option settingColorSelector' type='text' name="settings[sbg_color]" value="<?php echo $this->slider->get_setting('sbg_color'); ?>" size="3" css-prop="color" /></label>
            </div>
        </div>

    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set the initial size for the slider (width x height)", $this->filename) ?>">
        <?php _e("Size", $this->filename) ?>
    </td>
    <td>
        <label class='option' >Width :</label><input type='number' min='1' max='100' step='1' size='3' class="width tipsytop" title='<?php _e("Width", $this->filename) ?>' name="settings[slider_width]" value='<?php echo ($this->slider->get_setting('slider_width')!= 'false' ? $this->slider->get_setting('slider_width') : 0) ?>' />px &nbsp;&nbsp;&nbsp;
        <label class='option' >Height :</label></label></label><input type='number' min='1' max='100' step='1' size='3' class="height tipsytop" title='<?php _e("Height", $this->filename) ?>' name="settings[slider_height]" value='<?php echo ($this->slider->get_setting('slider_height')!= 'false' ? $this->slider->get_setting('slider_height') : 0) ?>' />px
    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Show slide navigation row", $this->filename) ?>">
        <?php _e("Controls", $this->filename) ?>
    </td>
    <td>
        <label class='option' ><input type='checkbox' name="settings[links]" <?php if ($this->slider->get_setting('links') == 'true') echo 'checked=checked' ?> /><?php _e("Pager", $this->filename) ?></label>
        <label class='option' ><input  class='option settingColorSelector' type='text' name="settings[pager_color]" value="<?php echo $this->slider->get_setting('pager_color'); ?>" size="3" css-prop="color" /></label>

    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Show slide navigation row", $this->filename) ?>">
        &nbsp;
    </td>
    <td>
        <label class='option coin' ><input type='checkbox' name="settings[navigation]" <?php if ($this->slider->get_setting('navigation') == 'true') echo 'checked=checked' ?> /><?php _e("Navigation", $this->filename) ?></label>
        <label class='option' ><input  class='option settingColorSelector' type='text' name="settings[navigation_color]" value="<?php echo $this->slider->get_setting('navigation_color'); ?>" size="3" css-prop="color" /></label>
    </td>
</tr>

<tr>
    <td class='tipsy-tooltip' title="<?php _e("Start the slideshow on page load", $this->filename) ?>">
        <?php _e("Auto play", $this->filename) ?>
    </td>
    <td>
        <input class='option' type='checkbox' name="settings[autoplay]" <?php if ($this->slider->get_setting('autoplay') == 'true') echo 'checked=checked' ?> />
    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Show box shadow of slider", $this->filename) ?>">
        <?php _e("Box shadow", $this->filename) ?>
    </td>
    <td>
        <input class='option' type='checkbox' name="settings[bshadow]" <?php if ($this->slider->get_setting('bshadow') == 'true') echo 'checked=checked' ?> />
    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("(parallax effect) when sliding", $this->filename) ?>">
        <?php _e("Bg position increment", $this->filename) ?>
    </td>
    <td>
        <input class='option' type='number' min='1' max='100' step='1' name="settings[bg_position_increment]" value="<?php echo $this->slider->get_setting('bg_position_increment'); ?>" size="3" />px
    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Time between transitions", $this->filename) ?>">
        <?php _e("Interval", $this->filename) ?>
    </td>
    <td>
        <input class='option' type='number' min='0.5' max='100' step='0.5' name="settings[interval]" value="<?php echo $this->slider->get_setting('interval'); ?>" size="3" />Sec
    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("z-index to define the order", $this->filename) ?>">
        <?php _e("Z-Index", $this->filename) ?>
    </td>
    <td>
        <?php $z_index = $this->slider->get_setting('slider_z_index', 1000); ?>
        <input class='option' type='text'  name="settings[slider_z_index]" value="<?php echo $z_index !== 'false' ? $z_index : 1000; ?>" size="3" />
    </td>
</tr>
<!-- slide heading -->
<tr>
    <td colspan='2' class='highlight'><?php _e("Slide Heading Settings", $this->filename) ?></td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set slide heading font size", $this->filename) ?>">
        <?php _e("Font Size", $this->filename) ?>
    </td>
    <td>
        <input class='option' type='number' min='1' max='100' step='1' name="settings[heading_font_size]" value="<?php echo $this->slider->get_setting('heading_font_size'); ?>" size="3" />px
    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set slide heading font Family", $this->filename) ?>">
        <?php _e("Font Family", $this->filename) ?>
    </td>
    <td>
        <select class='option check_google_font' show-attr="heading_google" other-attr="heading_other"  name="settings[heading_font_family]" >
            <?php foreach($font_family_array as $heading_font_family_value){
                echo '<option value="'.$heading_font_family_value.'" '.($this->slider->get_setting('heading_font_family')==$heading_font_family_value?'selected="selected"':'').'>'.$heading_font_family_value.'</option>';
            }?>

        </select>
    </td>
</tr>
<tr class="heading_google">
    <td class='tipsy-tooltip' title="<?php _e("Set slide heading google font", $this->filename) ?>">
        <?php _e("Google Font", $this->filename) ?>
    </td>
    <td>
        <select class='option' name="settings[heading_google_font_family]" >
            <?php foreach($eps_google_font_family as $google_font_family_value){
                echo '<option value="'.$google_font_family_value.'" '.($this->slider->get_setting('heading_google_font_family')==$google_font_family_value?'selected="selected"':'').'>'.$google_font_family_value.'</option>';
            }?>

        </select>
    </td>
</tr>
<tr class="heading_other">
    <td class='tipsy-tooltip' title="<?php _e("Provide name of your desired font, to add multiple fonts separate them by comma(,)", $this->filename) ?>">
        <?php _e("Other Fonts", $this->filename) ?>
    </td>
    <td>
        <input class='option' type='text'  name="settings[heading_other_font_family]" value="<?php echo $this->slider->get_setting('heading_other_font_family'); ?>" />
    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set slide heading font Style", $this->filename) ?>">
        <?php _e("Font Style", $this->filename) ?>
    </td>
    <td>
        <select class='option' name="settings[heading_font_style][]" multiple>
            <?php foreach($font_style_array as $heading_font_style_value){
                echo '<option value="'.$heading_font_style_value.'" '.(is_array($this->slider->get_setting('heading_font_style')) && in_array($heading_font_style_value,$this->slider->get_setting('heading_font_style'))?'selected="selected"':'').'>'.$heading_font_style_value.'</option>';
            }?>
        </select>
    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set slide heading font color", $this->filename) ?>">
        <?php _e("Font Color", $this->filename) ?>
    </td>
    <td>
        <input  class='option settingColorSelector' type='text' name="settings[heading_font_color]" value="<?php echo $this->slider->get_setting('heading_font_color'); ?>" size="3" css-prop="color" />

    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set slide heading font color", $this->filename) ?>">
        <?php _e("In Effect", $this->filename) ?>
    </td>
    <td>
        <select class='option' name="settings[heading_in_effect]">
            <?php foreach($eps_in_effects as $eps_in_effect){
                echo '<option value="'.$eps_in_effect.'" '.($this->slider->get_setting('heading_in_effect')==$eps_in_effect?'selected="selected"':'').'>'.$eps_in_effect.'</option>';
            }?>
        </select>
    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set slide heading font color", $this->filename) ?>">
        <?php _e("Out Effect", $this->filename) ?>
    </td>
    <td>
        <select class='option' name="settings[heading_out_effect]">
            <?php foreach($eps_out_effects as $eps_out_effect){
                echo '<option value="'.$eps_out_effect.'" '.($this->slider->get_setting('heading_out_effect')==$eps_out_effect?'selected="selected"':'').'>'.$eps_out_effect.'</option>';
            }?>
        </select>
    </td>
</tr>
<!--slide content -->

<tr>
    <td colspan='2' class='highlight'><?php _e("Slide Content Settings", $this->filename) ?></td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set slide content top margin", $this->filename) ?>">
        <?php _e("Top Margin", $this->filename) ?>
    </td>
    <td>
        <input class='option' type='number' min='1' max='100' step='1' name="settings[content_top_margin]" value="<?php echo $this->slider->get_setting('content_top_margin'); ?>" size="3" />px
    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set slide content font size", $this->filename) ?>">
        <?php _e("Font Size", $this->filename) ?>
    </td>
    <td>
        <input class='option' type='number' min='1' max='100' step='1' name="settings[content_font_size]" value="<?php echo $this->slider->get_setting('content_font_size'); ?>" size="3" />px
    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set slide content font line height", $this->filename) ?>">
        <?php _e("Line Height", $this->filename) ?>
    </td>
    <td>
        <input class='option' type='number' min='1' max='100' step='1' name="settings[content_font_line_height]" value="<?php echo $this->slider->get_setting('content_font_line_height'); ?>" size="3" />px
    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set slide content font Family", $this->filename) ?>">
        <?php _e("Font Family", $this->filename) ?>
    </td>
    <td>
        <select class='option check_google_font' show-attr="content_google" other-attr="content_other" name="settings[content_font_family]" >
            <?php foreach($font_family_array as $content_font_family_value){
                echo '<option value="'.$content_font_family_value.'" '.($this->slider->get_setting('content_font_family')==$content_font_family_value?'selected="selected"':'').'>'.$content_font_family_value.'</option>';
            }?>
        </select>

    </td>
</tr>
<tr class="content_google">
    <td class='tipsy-tooltip' title="<?php _e("Set slide content google font family", $this->filename) ?>">
        <?php _e("Google Font", $this->filename) ?>
    </td>
    <td>
        <select class='option' name="settings[content_google_font_family]" >
            <?php foreach($eps_google_font_family as $google_font_family_value){
                echo '<option value="'.$google_font_family_value.'" '.($this->slider->get_setting('content_google_font_family')==$google_font_family_value?'selected="selected"':'').'>'.$google_font_family_value.'</option>';
            }?>

        </select>
    </td>
</tr>
<tr class="content_other">
    <td class='tipsy-tooltip' title="<?php _e("Provide name of your desired font, to add multiple fonts separate them by comma(,)", $this->filename) ?>">
        <?php _e("Other Fonts", $this->filename) ?>
    </td>
    <td>
        <input  type='text'  name="settings[content_other_font_family]" value="<?php echo $this->slider->get_setting('content_other_font_family'); ?>" />
    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set slide content font style", $this->filename) ?>">
        <?php _e("Font style", $this->filename) ?>
    </td>
    <td>
        <select class='option' name="settings[content_font_style][]" multiple>
            <?php foreach($font_style_array as $content_font_style_value){
                echo '<option value="'.$content_font_style_value.'" '.(is_array($this->slider->get_setting('content_font_style')) && in_array($content_font_style_value,$this->slider->get_setting('content_font_style'))?'selected="selected"':'').'>'.$content_font_style_value.'</option>';
            }?>
        </select>
    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set slide content font color", $this->filename) ?>">
        <?php _e("Font Color", $this->filename) ?>
    </td>
    <td>

        <input   class='option settingColorSelector' type='text' name="settings[content_font_color]" value="<?php echo $this->slider->get_setting('content_font_color'); ?>" size="3" css-prop="color"   />

    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set slide content in effect", $this->filename) ?>">
        <?php _e("In Effect", $this->filename) ?>
    </td>
    <td>
        <select class='option' name="settings[content_in_effect]">
            <?php foreach($eps_in_effects as $eps_in_effect){
                echo '<option value="'.$eps_in_effect.'" '.($this->slider->get_setting('content_in_effect')==$eps_in_effect?'selected="selected"':'').'>'.$eps_in_effect.'</option>';
            }?>
        </select>
    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set slide content out effect", $this->filename) ?>">
        <?php _e("Out Effect", $this->filename) ?>
    </td>
    <td>
        <select class='option' name="settings[content_out_effect]">
            <?php foreach($eps_out_effects as $eps_out_effect){
                echo '<option value="'.$eps_out_effect.'" '.($this->slider->get_setting('content_out_effect')==$eps_out_effect?'selected="selected"':'').'>'.$eps_out_effect.'</option>';
            }?>
        </select>
    </td>
</tr>

<!--slide read more url -->
<tr>
    <td colspan='2' class='highlight'><?php _e("Slide Read More Url Settings", $this->filename) ?></td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set slide read more font size", $this->filename) ?>">
        <?php _e("Font Size", $this->filename) ?>
    </td>
    <td>
        <input class='option'  type='number' min='1' max='100' step='1' name="settings[readmore_font_size]" value="<?php echo $this->slider->get_setting('readmore_font_size'); ?>" size="3" />px
    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set slide read more font Family", $this->filename) ?>">
        <?php _e("Font Family", $this->filename) ?>
    </td>
    <td>
        <select class='option check_google_font' show-attr="readmore_google" other-attr="readmore_other" name="settings[readmore_font_family]" >
            <?php foreach($font_family_array as $readmore_font_family_value){
                echo '<option value="'.$readmore_font_family_value.'" '.($this->slider->get_setting('readmore_font_family')==$readmore_font_family_value?'selected="selected"':'').'>'.$readmore_font_family_value.'</option>';
            }?>
        </select>
    </td>
</tr>
<tr class="readmore_google">
    <td class='tipsy-tooltip' title="<?php _e("Set slide read more link google font family", $this->filename) ?>">
        <?php _e("Google Font", $this->filename) ?>
    </td>
    <td>
        <select class='option' name="settings[readmore_google_font_family]" >
            <?php foreach($eps_google_font_family as $google_font_family_value){
                echo '<option value="'.$google_font_family_value.'" '.($this->slider->get_setting('readmore_google_font_family')==$google_font_family_value?'selected="selected"':'').'>'.$google_font_family_value.'</option>';
            }?>

        </select>
    </td>
</tr>
<tr class="readmore_other">
    <td class='tipsy-tooltip' title="<?php _e("Provide name of your desired font, to add multiple fonts separate them by comma(,)", $this->filename) ?>">
        <?php _e("Other Fonts", $this->filename) ?>
    </td>
    <td>
        <input class='option' type='text'  name="settings[readmore_other_font_family]" value="<?php echo $this->slider->get_setting('readmore_other_font_family'); ?>" />
    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set slide read more font style", $this->filename) ?>">
        <?php _e("Font style", $this->filename) ?>
    </td>
    <td>
        <select class='option' name="settings[readmore_font_style][]" multiple>
            <?php foreach($font_style_array as $readmore_font_style_value){
                echo '<option value="'.$readmore_font_style_value.'" '.(is_array($this->slider->get_setting('readmore_font_style')) && in_array($readmore_font_style_value,$this->slider->get_setting('readmore_font_style'))?'selected="selected"':'').'>'.$readmore_font_style_value.'</option>';
            }?>
        </select>
    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set slide read more font color", $this->filename) ?>">
        <?php _e("Font Color", $this->filename) ?>
    </td>
    <td>

        <input   class='option settingColorSelector' type='text' name="settings[readmore_font_color]" value="<?php echo $this->slider->get_setting('readmore_font_color'); ?>" size="3" css-prop="color"  />

    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set slide read more background color", $this->filename) ?>">
        <?php _e("Background Color", $this->filename) ?>
    </td>
    <td>

        <input   class='option settingColorSelector' type='text' name="settings[readmore_bg_color]" value="<?php echo $this->slider->get_setting('readmore_bg_color'); ?>" size="3" css-prop="color"  />

    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set slide read more border color", $this->filename) ?>">
        <?php _e("Border Color", $this->filename) ?>
    </td>
    <td>

        <input   class='option settingColorSelector' type='text' name="settings[readmore_border_color]" value="<?php echo $this->slider->get_setting('readmore_border_color'); ?>" size="3" css-prop="color"  />

    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set slide readmore in effect", $this->filename) ?>">
        <?php _e("In Effect", $this->filename) ?>
    </td>
    <td>
        <select class='option' name="settings[readmore_in_effect]">
            <?php foreach($eps_in_effects as $eps_in_effect){
                echo '<option value="'.$eps_in_effect.'" '.($this->slider->get_setting('readmore_in_effect')==$eps_in_effect?'selected="selected"':'').'>'.$eps_in_effect.'</option>';
            }?>
        </select>
    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set slide readmore out effect", $this->filename) ?>">
        <?php _e("Out Effect", $this->filename) ?>
    </td>
    <td>
        <select class='option' name="settings[readmore_out_effect]">
            <?php foreach($eps_out_effects as $eps_out_effect){
                echo '<option value="'.$eps_out_effect.'" '.($this->slider->get_setting('readmore_out_effect')==$eps_out_effect?'selected="selected"':'').'>'.$eps_out_effect.'</option>';
            }?>
        </select>
    </td>
</tr>

<!--slide image settings -->
<tr>
    <td colspan='2' class='highlight'><?php _e("Slide Image Settings", $this->filename) ?></td>
</tr>

<tr>
    <td class='tipsy-tooltip' title="<?php _e("Specify the top position of the slides images (in %age)", $this->filename) ?>">
        <?php _e("Top Position", $this->filename) ?>
    </td>
    <td>
        <input type='number' min='1' max='100' step='1' name="settings[topPer]" value='<?php if ($this->slider->get_setting('topPer') != 'false') echo $this->slider->get_setting('topPer') ?>' />%
    </td>
</tr>
<tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Specify the left position of the slides images (in %age)", $this->filename) ?>">
        <?php _e("Left Position", $this->filename) ?>
    </td>
    <td>
        <input type='number' min='1' max='100' step='1' name="settings[leftPer]" value='<?php if ($this->slider->get_setting('leftPer') != 'false') echo $this->slider->get_setting('leftPer') ?>' />%
    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set the initial size for the slides images (width x height)", $this->filename) ?>">
        <?php _e("Size", $this->filename) ?>
    </td>
    <td>
        <label class="option">Width :</label><input type='number' min='1' max='100' step='1' size='3' class="width tipsytop" title='<?php _e("Width", $this->filename) ?>' name="settings[width]" value='<?php echo ($this->slider->get_setting('width') != 'false' ? $this->slider->get_setting('width') : '') ?>' />px<label class="option">&nbsp;</label>
        <label class="option">Height :</label><input type='number' min='1' max='100' step='1' size='3' class="height tipsytop" title='<?php _e("Height", $this->filename) ?>' name="settings[height]" value='<?php echo ($this->slider->get_setting('height') != 'false' ? $this->slider->get_setting('height') : '') ?>' />px
    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set slide image in effect", $this->filename) ?>">
        <?php _e("In Effect", $this->filename) ?>
    </td>
    <td>
        <select class='option' name="settings[image_in_effect]">
            <?php foreach($eps_in_effects as $eps_in_effect){
                echo '<option value="'.$eps_in_effect.'" '.($this->slider->get_setting('image_in_effect')==$eps_in_effect?'selected="selected"':'').'>'.$eps_in_effect.'</option>';
            }?>
        </select>
    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Set slide image out effect", $this->filename) ?>">
        <?php _e("Out Effect", $this->filename) ?>
    </td>
    <td>
        <select class='option' name="settings[image_out_effect]">
            <?php foreach($eps_out_effects as $eps_out_effect){
                echo '<option value="'.$eps_out_effect.'" '.($this->slider->get_setting('image_out_effect')==$eps_out_effect?'selected="selected"':'').'>'.$eps_out_effect.'</option>';
            }?>
        </select>
    </td>
</tr>

<!--developer tools settings -->
<tr>
    <td colspan='2' class='highlight'><?php _e("Developer Options", $this->filename) ?></td>
</tr>


<tr>
    <td class='tipsy-tooltip' title="<?php _e("Specify any custom CSS Classes you would like to be added to the slider wrapper", $this->filename) ?>">
        <?php _e("CSS classes", $this->filename) ?>
    </td>
    <td>
        <input type='text' name="settings[cssClass]" value='<?php if ($this->slider->get_setting('cssClass') != 'false') echo $this->slider->get_setting('cssClass') ?>' />
    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Uncheck this is you would like to include your own CSS", $this->filename) ?>">
        <?php _e("Print CSS", $this->filename) ?>
    </td>
    <td>
        <input type='checkbox' class='useWithCaution' name="settings[printCss]" <?php if ($this->slider->get_setting('printCss') == 'true') echo 'checked=checked' ?> />
    </td>
</tr>
<tr>
    <td class='tipsy-tooltip' title="<?php _e("Uncheck this is you would like to include your own Javascript", $this->filename) ?>">
        <?php _e("Print JS", $this->filename) ?>
    </td>
    <td>
        <input type='checkbox' class='useWithCaution' name="settings[printJs]" <?php if ($this->slider->get_setting('printJs') == 'true') echo 'checked=checked' ?> />
    </td>
</tr>
<tr>
    <td colspan='2'>
        <a class='alignright delete-slider button-secondary confirm' href="?page=<?php echo $this->filename?>&delete=<?php echo $this->slider->id ?>"><?php _e("Delete Slider", $this->filename) ?></a>
    </td>
</tr>
</tbody>
</table>

<table class="widefat shortcode">
    <thead>
    <tr>
        <th><?php _e("Usage", $this->filename) ?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class='highlight'><?php _e("Shortcode", $this->filename) ?></td>
    </tr>
    <tr>
        <td><input readonly='readonly' type='text' value='[epsshortcode id=<?php echo $this->slider->id ?>]' /></td>
    </tr>
    <tr>
        <td class='highlight'><?php _e("Template Include", $this->filename) ?></td>
    </tr>
    <tr>
        <td><input readonly='readonly' type='text' value='&lt;?php echo do_shortcode("[epsshortcode id=<?php echo $this->slider->id ?>]"); ?>' /></td>
    </tr>
    </tbody>
</table>

</div>
</form>
</div>

