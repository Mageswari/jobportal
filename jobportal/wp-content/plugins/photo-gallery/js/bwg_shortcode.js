function bwg_shortcode_load() {
  jQuery(".spider_int_input").keypress(function (event) {
    var chCode1 = event.which || event.paramlist_keyCode;
    if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57) && (chCode1 != 46)) {
      return false;
    }
    return true;
  });
  jQuery("#display_panel").tooltip({
    track: true,
    content: function () {
      return jQuery(this).prop('title');
    }
  });
}

function bwg_watermark(watermark_type) {
  jQuery("#" + watermark_type).prop('checked', true);
  jQuery("#tr_watermark_link").css('display', 'none');
  jQuery("#tr_watermark_url").css('display', 'none');
  jQuery("#tr_watermark_width_height").css('display', 'none');
  jQuery("#tr_watermark_opacity").css('display', 'none');
  jQuery("#tr_watermark_text").css('display', 'none');
  jQuery("#tr_watermark_font_size").css('display', 'none');
  jQuery("#tr_watermark_font").css('display', 'none');
  jQuery("#tr_watermark_color").css('display', 'none');
  jQuery("#tr_watermark_position").css('display', 'none');
  bwg_enable_disable('', '', 'watermark_bottom_right');
  switch (watermark_type) {
    case 'watermark_type_text': {
      jQuery("#tr_watermark_link").css('display', '');
      jQuery("#tr_watermark_opacity").css('display', '');
      jQuery("#tr_watermark_text").css('display', '');
      jQuery("#tr_watermark_font_size").css('display', '');
      jQuery("#tr_watermark_font").css('display', '');
      jQuery("#tr_watermark_color").css('display', '');
      jQuery("#tr_watermark_position").css('display', '');
      break;

    }
    case 'watermark_type_image': {
      jQuery("#tr_watermark_link").css('display', '');
      jQuery("#tr_watermark_url").css('display', '');
      jQuery("#tr_watermark_width_height").css('display', '');
      jQuery("#tr_watermark_opacity").css('display', '');
      jQuery("#tr_watermark_position").css('display', '');
      break;
    }
  }
}

function bwg_enable_disable(display, id, current) {
  jQuery("#" + current).prop('checked', true);
  jQuery("#" + id).css('display', display);
}

function bwg_popup_fullscreen() { 
  if (jQuery("#popup_fullscreen_1").is(':checked')) {
    jQuery("#tr_popup_width_height").css('display', 'none');
  }
  else {
    jQuery("#tr_popup_width_height").css('display', '');
  }
}

function bwg_thumb_click_action() {
  if (!jQuery("#thumb_click_action_2").is(':checked')) {
    jQuery("#tr_thumb_link_target").css('display', 'none');
    jQuery("#tbody_popup").css('display', '');
    jQuery("#tr_popup_width_height").css('display', '');
    jQuery("#tr_popup_effect").css('display', '');
    jQuery("#tr_popup_interval").css('display', '');
    jQuery("#tr_popup_enable_filmstrip").css('display', '');
    if (jQuery("input[name=popup_enable_filmstrip]:checked").val() == 1) {
      bwg_enable_disable('', 'tr_popup_filmstrip_height', 'popup_filmstrip_yes');
    }
    else {
      bwg_enable_disable('none', 'tr_popup_filmstrip_height', 'popup_filmstrip_no');
    }
    jQuery("#tr_popup_enable_ctrl_btn").css('display', '');
    if (jQuery("input[name=popup_enable_ctrl_btn]:checked").val() == 1) {
      bwg_enable_disable('', 'tbody_popup_ctrl_btn', 'popup_ctrl_btn_yes');
    }
    else {
      bwg_enable_disable('none', 'tbody_popup_ctrl_btn', 'popup_ctrl_btn_no');
    }
    jQuery("#tr_popup_enable_fullscreen").css('display', '');
    jQuery("#tr_popup_enable_info").css('display', '');
    jQuery("#tr_popup_enable_rate").css('display', '');
    jQuery("#tr_popup_enable_comment").css('display', '');
    jQuery("#tr_popup_enable_facebook").css('display', '');
    jQuery("#tr_popup_enable_twitter").css('display', '');
    jQuery("#tr_popup_enable_google").css('display', '');
    jQuery("#tr_popup_enable_pinterest").css('display', '');
    jQuery("#tr_popup_enable_tumblr").css('display', '');
    bwg_popup_fullscreen();
  }
  else {
    jQuery("#tr_thumb_link_target").css('display', '');
    jQuery("#tbody_popup").css('display', 'none');
    jQuery("#tbody_popup_ctrl_btn").css('display', 'none');
  }
}

function bwg_show_search_box() { 
  if (jQuery("#show_search_box_1").is(':checked')) {
    jQuery("#tr_search_box_width").css('display', '');
  }
  else {
    jQuery("#tr_search_box_width").css('display', 'none');
  }
}

function bwg_change_compuct_album_view_type() {
  if (jQuery("input[name=compuct_album_view_type]:checked").val() == 'thumbnail') {
    jQuery("#compuct_album_image_thumb_dimensions").html('Image thumbnail dimensions: ');
    jQuery("#compuct_album_image_thumb_dimensions_x").css('display', '');
    jQuery("#compuct_album_image_thumb_height").css('display', '');
    jQuery("#tr_compuct_album_image_title").css('display', '');
  }
  else {
    jQuery("#compuct_album_image_thumb_dimensions").html('Image thumbnail width: '); 
    jQuery("#compuct_album_image_thumb_dimensions_x").css('display', 'none');
    jQuery("#compuct_album_image_thumb_height").css('display', 'none');
    jQuery("#tr_compuct_album_image_title").css('display', 'none');
  }
}

function bwg_change_extended_album_view_type() {
  if (jQuery("input[name=extended_album_view_type]:checked").val() == 'thumbnail') {
    jQuery("#extended_album_image_thumb_dimensions").html('Image thumbnail dimensions: ');
    jQuery("#extended_album_image_thumb_dimensions_x").css('display', '');
    jQuery("#extended_album_image_thumb_height").css('display', '');
    jQuery("#tr_extended_album_image_title").css('display', '');
  }
  else {
    jQuery("#extended_album_image_thumb_dimensions").html('Image thumbnail width: '); 
    jQuery("#extended_album_image_thumb_dimensions_x").css('display', 'none');
    jQuery("#extended_album_image_thumb_height").css('display', 'none');
    jQuery("#tr_extended_album_image_title").css('display', 'none');
  }
}

function bwg_change_label(id, text) {
  jQuery('#' + id).html(text);
}

function bwg_gallery_type(gallery_type) {
  jQuery("#" + gallery_type).prop('checked', true);
  jQuery("#tr_gallery").css('display', 'none');
  jQuery("#tr_sort_by").css('display', 'none');
  jQuery("#tr_order_by").css('display', 'none');
  jQuery("#tr_show_search_box").css('display', 'none');
  jQuery("#tr_search_box_width").css('display', 'none');
  jQuery("#tr_album").css('display', 'none');

  // Thumbnails, Masonry.
  jQuery("#tr_masonry_hor_ver").css('display', 'none');
  bwg_change_label("col_num_label", 'Max. number of image columns');
  jQuery("#tr_image_column_number").css('display', 'none');
  jQuery("#tr_images_per_page").css('display', 'none');
  jQuery("#tr_image_title_hover").css('display', 'none');
  jQuery("#tr_image_enable_page").css('display', 'none');
  jQuery("#tr_thumb_width_height").css('display', 'none');

  // Compact Album.
  jQuery("#tr_compuct_album_column_number").css('display', 'none');
  jQuery("#tr_compuct_albums_per_page").css('display', 'none');
  jQuery("#tr_compuct_album_title_hover").css('display', 'none');
  jQuery("#tr_compuct_album_view_type").css('display', 'none');
  jQuery("#tr_compuct_album_thumb_width_height").css('display', 'none');
  jQuery("#tr_compuct_album_image_column_number").css('display', 'none');
  jQuery("#tr_compuct_album_images_per_page").css('display', 'none');
  jQuery("#tr_compuct_album_image_title").css('display', 'none');
  jQuery("#tr_compuct_album_image_title").css('display', 'none');
  jQuery("#tr_compuct_album_image_thumb_width_height").css('display', 'none');
  jQuery("#tr_compuct_album_enable_page").css('display', 'none');

  // Extended Album.
  jQuery("#tr_extended_albums_per_page").css('display', 'none');
  jQuery("#tr_extended_album_height").css('display', 'none');
  jQuery("#tr_extended_album_description_enable").css('display', 'none');
  jQuery("#tr_extended_album_view_type").css('display', 'none');
  jQuery("#tr_extended_album_thumb_width_height").css('display', 'none');
  jQuery("#tr_extended_album_image_column_number").css('display', 'none');
  jQuery("#tr_extended_album_images_per_page").css('display', 'none');
  jQuery("#tr_extended_album_image_title").css('display', 'none');
  jQuery("#tr_extended_album_image_thumb_width_height").css('display', 'none');
  jQuery("#tr_extended_album_enable_page").css('display', 'none');

  // Image Browser.
  jQuery("#tr_image_browser_width_height").css('display', 'none');
  jQuery("#tr_image_browser_title_enable").css('display', 'none');
  jQuery("#tr_image_browser_description_enable").css('display', 'none');

  // Blog Style.
  jQuery("#tr_blog_style_width_height").css('display', 'none');
  jQuery("#tr_blog_style_title_enable").css('display', 'none');
  jQuery("#tr_blog_style_images_per_page").css('display', 'none');
  jQuery("#tr_blog_style_enable_page").css('display', 'none');

  // Slideshow.
  jQuery("#tbody_slideshow").css('display', 'none');
  jQuery("#tr_slideshow_effect").css('display', 'none');
  jQuery("#tr_slideshow_interval").css('display', 'none');
  jQuery("#tr_slideshow_width_height").css('display', 'none');
  jQuery("#tr_enable_slideshow_autoplay").css('display', 'none');
  jQuery("#tr_enable_slideshow_shuffle").css('display', 'none');
  jQuery("#tr_enable_slideshow_ctrl").css('display', 'none');
  jQuery("#tr_enable_slideshow_filmstrip").css('display', 'none');
  jQuery("#tr_slideshow_filmstrip_height").css('display', 'none');
  jQuery("#tr_slideshow_enable_title").css('display', 'none');
  jQuery("#tr_slideshow_title_position").css('display', 'none');
  jQuery("#tr_slideshow_enable_description").css('display', 'none');
  jQuery("#tr_slideshow_description_position").css('display', 'none');
  jQuery("#tr_enable_slideshow_music").css('display', 'none');
  jQuery("#tr_slideshow_music_url").css('display', 'none');

  // Popup.
  jQuery("#tbody_popup_other").css('display', 'none');
  jQuery("#tbody_popup").css('display', 'none');
  jQuery("#tr_popup_width_height").css('display', 'none');
  jQuery("#tr_popup_effect").css('display', 'none');
  jQuery("#tr_popup_interval").css('display', 'none');
  jQuery("#tr_popup_enable_filmstrip").css('display', 'none');
  jQuery("#tr_popup_filmstrip_height").css('display', 'none');
  jQuery("#tr_popup_enable_ctrl_btn").css('display', 'none');
  jQuery("#tr_popup_enable_fullscreen").css('display', 'none');
  jQuery("#tr_popup_enable_info").css('display', 'none');
  jQuery("#tr_popup_enable_rate").css('display', 'none');
  jQuery("#tr_popup_enable_comment").css('display', 'none');
  jQuery("#tr_popup_enable_facebook").css('display', 'none');
  jQuery("#tr_popup_enable_twitter").css('display', 'none');
  jQuery("#tr_popup_enable_google").css('display', 'none');
  jQuery("#tr_popup_enable_pinterest").css('display', 'none');
  jQuery("#tr_popup_enable_tumblr").css('display', 'none');

  // Watermark.
  jQuery("#tr_watermark_type").css('display', '');
  if (jQuery("input[name=watermark_type]:checked").val() == 'image') {
    bwg_watermark('watermark_type_image');
  }
  else if (jQuery("input[name=watermark_type]:checked").val() == 'text'){
    bwg_watermark('watermark_type_text');
  }
  else {
    bwg_watermark('watermark_type_none');
  }
  switch (gallery_type) {
    case 'thumbnails': {
      jQuery("#tr_gallery").css('display', '');
      jQuery("#tr_sort_by").css('display', '');
      jQuery("#tr_order_by").css('display', '');
      jQuery("#tr_show_search_box").css('display', '');
      bwg_change_label('image_column_number_label', 'Max. number of image columns: ');
      bwg_change_label('thumb_width_height_label', 'Image thumbnail dimensions: ');
      jQuery('#thumb_width').show();
      jQuery('#thumb_height').show();
      jQuery('#thumb_width_height_separator').show();
      jQuery("#tr_image_column_number").css('display', '');
      jQuery("#tr_images_per_page").css('display', '');
      jQuery("#tr_image_title_hover").css('display', '');
      jQuery("#tr_image_enable_page").css('display', '');
      jQuery("#tr_thumb_width_height").css('display', '');
      bwg_show_search_box();
      jQuery("#bwg_pro_version").html('Thumbnails');
      jQuery("#bwg_pro_version_link").attr("href", "http://wpdemo.web-dorado.com/thumbnails-view-2/");
      break;

    }
    case 'thumbnails_masonry': {
      jQuery("#tr_gallery").css('display', '');
      jQuery("#tr_sort_by").css('display', '');
      jQuery("#tr_order_by").css('display', '');
      jQuery("#tr_show_search_box").css('display', '');
      if (jQuery("input[name=masonry_hor_ver]:checked").val() == 'horizontal') {
        bwg_change_label('image_column_number_label', 'Number of image rows: ');
        bwg_change_label('thumb_width_height_label', 'Image thumbnail height: ');
        jQuery('#thumb_width').hide();
        jQuery('#thumb_height').show();
      }
      else {
        bwg_change_label('image_column_number_label', 'Max. number of image columns: ');
        bwg_change_label('thumb_width_height_label', 'Image thumbnail width: ');
        jQuery('#thumb_width').show();
        jQuery('#thumb_height').hide();
      }
      jQuery("#tr_masonry_hor_ver").css('display', '');
      jQuery('#thumb_width_height_separator').hide();
      jQuery("#tr_image_column_number").css('display', '');
      jQuery("#tr_images_per_page").css('display', '');
      jQuery("#tr_image_enable_page").css('display', '');
      jQuery("#tr_thumb_width_height").css('display', '');
      bwg_show_search_box();
      break;

    }
    case 'slideshow': {
      jQuery("#tr_gallery").css('display', '');
      jQuery("#tr_sort_by").css('display', '');
      jQuery("#tr_order_by").css('display', '');
      jQuery("#tr_slideshow_effect").css('display', '');
      jQuery("#tr_slideshow_interval").css('display', '');
      jQuery("#tr_slideshow_width_height").css('display', '');
      jQuery("#tbody_slideshow").css('display', '');
      jQuery("#tr_enable_slideshow_autoplay").css('display', '');
      jQuery("#tr_enable_slideshow_shuffle").css('display', '');
      jQuery("#tr_enable_slideshow_ctrl").css('display', '');
      jQuery("#tr_enable_slideshow_filmstrip").css('display', '');
      if (jQuery("input[name=enable_slideshow_filmstrip]:checked").val() == 1) {
        bwg_enable_disable('', 'tr_slideshow_filmstrip_height', 'slideshow_filmstrip_yes');
      }
      else {
        bwg_enable_disable('none', 'tr_slideshow_filmstrip_height', 'slideshow_filmstrip_no');
      }
      jQuery("#tr_slideshow_enable_title").css('display', '');
      if (jQuery("input[name=slideshow_enable_title]:checked").val() == 1) {
        bwg_enable_disable('', 'tr_slideshow_title_position', 'slideshow_title_yes');
      }
      else {
        bwg_enable_disable('none', 'tr_slideshow_title_position', 'slideshow_title_no');
      }
      jQuery("#tr_slideshow_enable_description").css('display', '');
      if (jQuery("input[name=slideshow_enable_description]:checked").val() == 1) {
        bwg_enable_disable('', 'tr_slideshow_description_position', 'slideshow_description_yes');
      }
      else {
        bwg_enable_disable('none', 'tr_slideshow_description_position', 'slideshow_description_no');
      }
      jQuery("#tr_enable_slideshow_music").css('display', '');
      if (jQuery("input[name=enable_slideshow_music]:checked").val() == 1) {
        bwg_enable_disable('', 'tr_slideshow_music_url', 'slideshow_music_yes');
      }
      else {
        bwg_enable_disable('none', 'tr_slideshow_music_url', 'slideshow_music_no');
      }
      jQuery("#bwg_pro_version").html('Slideshow');
      jQuery("#bwg_pro_version_link").attr("href", "http://wpdemo.web-dorado.com/slideshow-view/");
      break;

    }
    case 'image_browser': {
      jQuery("#tr_gallery").css('display', '');
      jQuery("#tr_sort_by").css('display', '');
      jQuery("#tr_order_by").css('display', '');
      jQuery("#tr_show_search_box").css('display', '');
      jQuery("#tr_image_browser_width_height").css('display', '');
      jQuery("#tr_image_browser_title_enable").css('display', '');
      jQuery("#tr_image_browser_description_enable").css('display', '');
      bwg_show_search_box();
      jQuery("#bwg_pro_version").html('Image Browser');
      jQuery("#bwg_pro_version_link").attr("href", "http://wpdemo.web-dorado.com/image-browser-view/");
      break;

    }
    case 'album_compact_preview': {
      jQuery("#tr_album").css('display', '');
      jQuery("#tr_sort_by").css('display', '');
      jQuery("#tr_order_by").css('display', '');
      jQuery("#tr_show_search_box").css('display', '');
      jQuery("#tr_compuct_album_column_number").css('display', '');
      jQuery("#tr_compuct_albums_per_page").css('display', '');
      jQuery("#tr_compuct_album_title_hover").css('display', '');
      jQuery("#tr_compuct_album_view_type").css('display', '');
      jQuery("#tr_compuct_album_thumb_width_height").css('display', '');
      jQuery("#tr_compuct_album_image_column_number").css('display', '');
      jQuery("#tr_compuct_album_images_per_page").css('display', '');
      jQuery("#tr_compuct_album_image_title").css('display', '');
      jQuery("#tr_compuct_album_image_title").css('display', '');
      jQuery("#tr_compuct_album_image_thumb_width_height").css('display', '');
      jQuery("#tr_compuct_album_enable_page").css('display', '');
      bwg_change_compuct_album_view_type();
      bwg_show_search_box();
      jQuery("#bwg_pro_version").html('Compact Album');
      jQuery("#bwg_pro_version_link").attr("href", "http://wpdemo.web-dorado.com/compact-album-view/");
      break;

    }
    case 'album_extended_preview': {
      jQuery("#tr_album").css('display', '');
      jQuery("#tr_sort_by").css('display', '');
      jQuery("#tr_order_by").css('display', '');
      jQuery("#tr_show_search_box").css('display', '');
      jQuery("#tr_extended_albums_per_page").css('display', '');
      jQuery("#tr_extended_album_height").css('display', '');
      jQuery("#tr_extended_album_description_enable").css('display', '');
      jQuery("#tr_extended_album_view_type").css('display', '');
      jQuery("#tr_extended_album_thumb_width_height").css('display', '');
      jQuery("#tr_extended_album_image_column_number").css('display', '');
      jQuery("#tr_extended_album_images_per_page").css('display', '');
      jQuery("#tr_extended_album_image_title").css('display', '');
      jQuery("#tr_extended_album_image_thumb_width_height").css('display', '');
      jQuery("#tr_extended_album_enable_page").css('display', '');
      bwg_change_extended_album_view_type();
      bwg_show_search_box();
      jQuery("#bwg_pro_version").html('Extended Album');
      jQuery("#bwg_pro_version_link").attr("href", "http://wpdemo.web-dorado.com/extended-album-view/");
      break;

    }
    case 'blog_style': {
      jQuery("#tr_gallery").css('display', '');
      jQuery("#tr_sort_by").css('display', '');
      jQuery("#tr_order_by").css('display', '');
      jQuery("#tr_show_search_box").css('display', '');
      jQuery("#tr_blog_style_width_height").css('display', '');
      jQuery("#tr_blog_style_title_enable").css('display', '');
      jQuery("#tr_blog_style_images_per_page").css('display', '');
      jQuery("#tr_blog_style_enable_page").css('display', '');
      bwg_show_search_box();
      break;
    }
  }
  if (gallery_type != 'slideshow') {
    jQuery("#tbody_popup_other").css('display', '');
    jQuery("#tbody_popup").css('display', '');
    jQuery("#tr_popup_width_height").css('display', '');
    jQuery("#tr_popup_effect").css('display', '');
    jQuery("#tr_popup_interval").css('display', '');
    jQuery("#tr_popup_enable_filmstrip").css('display', '');
    if (jQuery("input[name=popup_enable_filmstrip]:checked").val() == 1) {
      bwg_enable_disable('', 'tr_popup_filmstrip_height', 'popup_filmstrip_yes');
    }
    else {
      bwg_enable_disable('none', 'tr_popup_filmstrip_height', 'popup_filmstrip_no');
    }
    jQuery("#tr_popup_enable_ctrl_btn").css('display', '');
    if (jQuery("input[name=popup_enable_ctrl_btn]:checked").val() == 1) {
      bwg_enable_disable('', 'tbody_popup_ctrl_btn', 'popup_ctrl_btn_yes');
    }
    else {
      bwg_enable_disable('none', 'tbody_popup_ctrl_btn', 'popup_ctrl_btn_no');
    }
    jQuery("#tr_popup_enable_fullscreen").css('display', '');
    jQuery("#tr_popup_enable_info").css('display', '');
    jQuery("#tr_popup_enable_rate").css('display', '');
    jQuery("#tr_popup_enable_comment").css('display', '');
    jQuery("#tr_popup_enable_facebook").css('display', '');
    jQuery("#tr_popup_enable_twitter").css('display', '');
    jQuery("#tr_popup_enable_google").css('display', '');
    jQuery("#tr_popup_enable_pinterest").css('display', '');
    jQuery("#tr_popup_enable_tumblr").css('display', '');
    bwg_popup_fullscreen();
    bwg_thumb_click_action();
  }
}

function bwg_onKeyDown(e) {
  var e = e || window.event;
  var chCode1 = e.which || e.paramlist_keyCode;
  if (chCode1 != 37 && chCode1 != 38 && chCode1 != 39 && chCode1 != 40) {
    if ((!e.ctrlKey && !e.metaKey) || (chCode1 != 86 && chCode1 != 67 && chCode1 != 65 && chCode1 != 88)) {
      e.preventDefault();
    }
  }
}
