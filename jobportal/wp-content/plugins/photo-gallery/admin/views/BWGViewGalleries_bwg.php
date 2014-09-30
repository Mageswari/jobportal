﻿<?php

class BWGViewGalleries_bwg {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $model;


  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct($model) {
    $this->model = $model;
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function display() {
    global $WD_BWG_UPLOAD_DIR;
    $rows_data = $this->model->get_rows_data();
    $page_nav = $this->model->page_nav();
    $search_value = ((isset($_POST['search_value'])) ? esc_html(stripslashes($_POST['search_value'])) : '');
    $search_select_value = ((isset($_POST['search_select_value'])) ? (int) $_POST['search_select_value'] : 0);
    $asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html(stripslashes($_POST['asc_or_desc'])) : 'asc');
    $order_by = (isset($_POST['order_by']) ? esc_html(stripslashes($_POST['order_by'])) : 'order');
    $order_class = 'manage-column column-title sorted ' . $asc_or_desc;
    $ids_string = '';
    ?>
    <div style="clear: both; float: left; width: 99%;">
      <div style="float:left; font-size: 14px; font-weight: bold;">
        This section allows you to create, edit and delete galleries.
        <a style="color: blue; text-decoration: none;" target="_blank" href="http://web-dorado.com/wordpress-gallery-guide-step-2.html">Read More in User Manual</a>
      </div>
      <div style="float: right; text-align: right;">
        <a style="text-decoration: none;" target="_blank" href="http://web-dorado.com/products/wordpress-photo-gallery-plugin.html">
          <img width="215" border="0" alt="web-dorado.com" src="<?php echo WD_BWG_URL . '/images/logo.png'; ?>" />
        </a>
      </div>
    </div>
    <form class="wrap" id="galleries_form" method="post" action="admin.php?page=galleries_bwg" style="float: left; width: 99%;">
      <span class="gallery-icon"></span>
      <h2>
        Galleries
        <a href="" class="add-new-h2" onclick="spider_set_input_value('task', 'add');
                                               spider_form_submit(event, 'galleries_form')">Add new</a>
      </h2>
      <div id="draganddrop" class="updated" style="display:none;"><strong><p>Changes made in this table should be saved.</p></strong></div>
      <div class="buttons_div">
        <span class="button-secondary non_selectable" onclick="spider_check_all_items()">
          <input type="checkbox" id="check_all_items" name="check_all_items" onclick="spider_check_all_items_checkbox()" style="margin: 0; vertical-align: middle;" />
          <span style="vertical-align: middle;">Select All</span>
        </span>
        <input id="show_hide_weights"  class="button-secondary" type="button" onclick="spider_show_hide_weights();return false;" value="Hide order column" />
        <input class="button-secondary" type="submit" onclick="spider_set_input_value('task', 'save_order')" value="Save Order" />
        <input class="button-secondary" type="submit" onclick="spider_set_input_value('task', 'publish_all')" value="Publish" />
        <input class="button-secondary" type="submit" onclick="spider_set_input_value('task', 'unpublish_all')" value="Unpublish" />
        <input class="button-secondary" type="submit" onclick="if (confirm('Do you want to delete selected items?')) {
                                                       spider_set_input_value('task', 'delete_all');
                                                     } else {
                                                       return false;
                                                     }" value="Delete" />
      </div>
      <div class="tablenav top">
        <?php
        WDWLibrary::search('Name', $search_value, 'galleries_form');
        WDWLibrary::html_page_nav($page_nav['total'], $page_nav['limit'], 'galleries_form');
        ?>
      </div>
      <table class="wp-list-table widefat fixed pages">
        <thead>
          <th class="table_small_col"></th>
          <th class="manage-column column-cb check-column table_small_col"><input id="check_all" type="checkbox" onclick="spider_check_all(this)" style="margin:0;" /></th>
          <th class="table_small_col <?php if ($order_by == 'id') {echo $order_class;} ?>">
            <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('order_by', 'id');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'id') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_form_submit(event, 'galleries_form')" href="">
              <span>ID</span><span class="sorting-indicator"></span>
            </a>
          </th>
          <th class="table_extra_large_col">Thumbnail</th>
          <th class="<?php if ($order_by == 'name') {echo $order_class;} ?>">
            <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('order_by', 'name');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'name') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_form_submit(event, 'galleries_form')" href="">
              <span>Name</span><span class="sorting-indicator"></span>
            </a>
          </th>
          <th class="<?php if ($order_by == 'slug') {echo $order_class;} ?>">
            <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('order_by', 'slug');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'slug') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_form_submit(event, 'galleries_form')" href="">
              <span>Slug</span><span class="sorting-indicator"></span>
            </a>
          </th>
          <th class="<?php if ($order_by == 'display_name') {echo $order_class;} ?>">
            <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('order_by', 'display_name');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'display_name') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_form_submit(event, 'galleries_form')" href="">
              <span>Author</span><span class="sorting-indicator"></span>
            </a>
          </th>
					<th class="table_large_col">Images count</th>
          <th id="th_order" class="table_medium_col <?php if ($order_by == 'order') {echo $order_class;} ?>">
            <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('order_by', 'order');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'order') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_form_submit(event, 'galleries_form')" href="">
              <span>Order</span><span class="sorting-indicator"></span>
            </a>
          </th>
          <th class="table_big_col <?php if ($order_by == 'published') {echo $order_class;} ?>">
            <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('order_by', 'published');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'published') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_form_submit(event, 'galleries_form')" href="">
              <span>Published</span><span class="sorting-indicator"></span>
            </a>
          </th>
          <th class="table_big_col">Edit</th>
          <th class="table_big_col">Delete</th>
        </thead>
        <tbody id="tbody_arr">
          <?php
          if ($rows_data) {
            foreach ($rows_data as $row_data) {
              $alternate = (!isset($alternate) || $alternate == 'class="alternate"') ? '' : 'class="alternate"';
              $published_image = (($row_data->published) ? 'publish' : 'unpublish');
              $published = (($row_data->published) ? 'unpublish' : 'publish');
							$images_count = $this->model->get_images_count($row_data->id);
              if ($row_data->preview_image == '') {
                $preview_image = WD_BWG_URL . '/images/no-image.png';
              }
              else {
                $preview_image = site_url() . '/' . $WD_BWG_UPLOAD_DIR . $row_data->preview_image;
              }
              ?>
              <tr id="tr_<?php echo $row_data->id; ?>" <?php echo $alternate; ?>>
                <td class="connectedSortable table_small_col"><div title="Drag to re-order"class="handle" style="margin:5px auto 0 auto;"></div></td>
                <td class="table_small_col check-column"><input id="check_<?php echo $row_data->id; ?>" name="check_<?php echo $row_data->id; ?>" onclick="spider_check_all(this)" type="checkbox" /></td>
                <td class="table_small_col"><?php echo $row_data->id; ?></td>
                <td class="table_extra_large_col">
                  <img title="<?php echo $row_data->name; ?>" style="border: 1px solid #CCCCCC; max-width:60px; max-height:60px;" src="<?php echo $preview_image . '?date=' . date('Y-m-y H:i:s'); ?>">
                </td>
                <td><a onclick="spider_set_input_value('task', 'edit');
                                spider_set_input_value('page_number', '1');
                                spider_set_input_value('search_value', '');
                                spider_set_input_value('search_or_not', '');
                                spider_set_input_value('asc_or_desc', 'asc');
                                spider_set_input_value('order_by', 'order');
                                spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');
                                spider_form_submit(event, 'galleries_form')" href="" title="Edit"><?php echo $row_data->name; ?></a></td>
                <td><?php echo $row_data->slug; ?></td>
                <td><?php echo get_userdata($row_data->author)->display_name; ?></td>
								<td class="table_large_col"><?php echo $images_count; ?></td>
								<td class="spider_order table_medium_col"><input id="order_input_<?php echo $row_data->id; ?>" name="order_input_<?php echo $row_data->id; ?>" type="text" size="1" value="<?php echo $row_data->order; ?>" /></td>
                <td class="table_big_col"><a onclick="spider_set_input_value('task', '<?php echo $published; ?>');spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');spider_form_submit(event, 'galleries_form')" href=""><img src="<?php echo WD_BWG_URL . '/images/' . $published_image . '.png'; ?>"></img></a></td>
                <td class="table_big_col"><a onclick="spider_set_input_value('task', 'edit');
                                                      spider_set_input_value('page_number', '1');
                                                      spider_set_input_value('search_value', '');
                                                      spider_set_input_value('search_or_not', '');
                                                      spider_set_input_value('asc_or_desc', 'asc');
                                                      spider_set_input_value('order_by', 'order');
                                                      spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');
                                                      spider_form_submit(event, 'galleries_form')" href="">Edit</a></td>
                <td class="table_big_col"><a onclick="spider_set_input_value('task', 'delete');
                                                      spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');
                                                      spider_form_submit(event, 'galleries_form')" href="">Delete</a></td>
              </tr>
              <?php
              $ids_string .= $row_data->id . ',';
            }
          }
          ?>
        </tbody>
      </table>
      <input id="task" name="task" type="hidden" value="" />
      <input id="current_id" name="current_id" type="hidden" value="" />
      <input id="ids_string" name="ids_string" type="hidden" value="<?php echo $ids_string; ?>" />
      <input id="asc_or_desc" name="asc_or_desc" type="hidden" value="asc" />
      <input id="order_by" name="order_by" type="hidden" value="<?php echo $order_by; ?>" />
      <script>
        window.onload = spider_show_hide_weights;
      </script>
    </form>
    <?php
  }

  public function edit($id) {
    global $WD_BWG_UPLOAD_DIR;
    $row = $this->model->get_row_data($id);
    $option_row = $this->model->get_option_row_data();
    $page_title = (($id != 0) ? 'Edit gallery ' . $row->name : 'Create new gallery');
    ?>
    <div style="clear: both; float: left; width: 99%;">
      <div id="message_div" class="updated" style="display: none;"></div>
      <div style="float:left; font-size: 14px; font-weight: bold;">
        This section allows you to add/edit gallery.
        <a style="color: blue; text-decoration: none;" target="_blank" href="http://web-dorado.com/wordpress-gallery-guide-step-2.html">Read More in User Manual</a>
      </div>
      <div style="float: right; text-align: right;">
        <a style="text-decoration: none;" target="_blank" href="http://web-dorado.com/products/wordpress-photo-gallery-plugin.html">
          <img width="215" border="0" alt="web-dorado.com" src="<?php echo WD_BWG_URL . '/images/logo.png'; ?>" />
        </a>
      </div>
    </div>
    <script>
      function spider_set_href(a, number, type) {
        var image_url = document.getElementById("image_url_" + number).value;
        var thumb_url = document.getElementById("thumb_url_" + number).value;
        a.href='<?php echo add_query_arg(array('action' => 'editThumb', 'width' => '800', 'height' => '500'), admin_url('admin-ajax.php')); ?>&type=' + type + '&image_id=' + number + '&image_url=' + image_url + '&thumb_url=' + thumb_url + '&TB_iframe=1';
      }
      function bwg_add_preview_image(files) {
        document.getElementById("preview_image").value = files[0]['thumb_url'];
        document.getElementById("button_preview_image").style.display = "none";
        document.getElementById("delete_preview_image").style.display = "inline-block";
        if (document.getElementById("img_preview_image")) {
          document.getElementById("img_preview_image").src = files[0]['reliative_url'];
          document.getElementById("img_preview_image").style.display = "inline-block";
        }
      }
      var j_int = 0;
      var bwg_j = 'pr_' + j_int;
      function bwg_add_image(files) {
        var tbody = document.getElementById('tbody_arr');
        for (var i in files) {
          var is_video = files[i]['filetype'] == 'YOUTUBE' || files[i]['filetype'] == 'VIMEO';
          var tr = document.createElement('tr');
          tr.setAttribute('id', "tr_" + bwg_j);
          if (tbody.firstChild) {
            tbody.insertBefore(tr, tbody.firstChild);
          }
          else {
            tbody.appendChild(tr);
          }
          // Handle TD.
          var td_handle = document.createElement('td');
          td_handle.setAttribute('class', "connectedSortable table_small_col");
          td_handle.setAttribute('title', "Drag to re-order");
          tr.appendChild(td_handle);
          var div_handle = document.createElement('div');
          div_handle.setAttribute('class', "handle connectedSortable");
          div_handle.setAttribute('style', "margin: 5px auto 0px;");
          td_handle.appendChild(div_handle);
          // Checkbox TD.
          var td_checkbox = document.createElement('td');
          td_checkbox.setAttribute('class', "table_small_col check-column");
          td_checkbox.setAttribute('onclick', "spider_check_all(this)");
          tr.appendChild(td_checkbox);
          var input_checkbox = document.createElement('input');
          input_checkbox.setAttribute('id', "check_" + bwg_j);
          input_checkbox.setAttribute('name', "check_" + bwg_j);
          input_checkbox.setAttribute('type', "checkbox");
          td_checkbox.appendChild(input_checkbox);
          // Numbering TD.
          var td_numbering = document.createElement('td');
          td_numbering.setAttribute('class', "table_small_col");
          td_numbering.innerHTML = "";
          tr.appendChild(td_numbering);
          // Thumb TD.
          var td_thumb = document.createElement('td');
          td_thumb.setAttribute('class', "table_extra_large_col");
          tr.appendChild(td_thumb);
          var a_thumb = document.createElement('a');
          a_thumb.setAttribute('class', "thickbox thickbox-preview");
          a_thumb.setAttribute('href', "<?php echo add_query_arg(array('action' => 'editThumb', 'type' => 'display'/*thumb_display*/, 'width' => '650', 'height' => '500'), admin_url('admin-ajax.php')); ?>&image_id=" + bwg_j + "&TB_iframe=1");
          a_thumb.setAttribute('title', files[i]['name']);
          td_thumb.appendChild(a_thumb);
          var img_thumb = document.createElement('img');
          img_thumb.setAttribute('id', "image_thumb_" + bwg_j);
          img_thumb.setAttribute('class', "thumb");
          img_thumb.setAttribute('src', files[i]['thumb']);
          a_thumb.appendChild(img_thumb);
          // Filename TD.
          var td_filename = document.createElement('td');
          td_filename.setAttribute('class', "table_extra_large_col");
          tr.appendChild(td_filename);
          var div_filename = document.createElement('div');
          div_filename.setAttribute('class', "filename");
          div_filename.setAttribute('id', "filename_" + bwg_j);
          td_filename.appendChild(div_filename);
          var strong_filename = document.createElement('strong');
          div_filename.appendChild(strong_filename);
          var a_filename = document.createElement('a');
          a_filename.setAttribute('href', "<?php echo add_query_arg(array('action' => 'editThumb', 'type' => 'display', 'width' => '800', 'height' => '500'), admin_url('admin-ajax.php')); ?>&image_id=" + bwg_j + "&TB_iframe=1");
          a_filename.setAttribute('class', "spider_word_wrap thickbox thickbox-preview");
          a_filename.setAttribute('title', files[i]['filename']);
          a_filename.innerHTML = files[i]['filename'];
          strong_filename.appendChild(a_filename);
          var div_date_modified = document.createElement('div');
          div_date_modified.setAttribute('class', "fileDescription");
          div_date_modified.setAttribute('title', "Date modified");
          div_date_modified.setAttribute('id', "date_modified_" + bwg_j);
          div_date_modified.innerHTML = files[i]['date_modified'];
          td_filename.appendChild(div_date_modified);
          var div_fileresolution = document.createElement('div');
          div_fileresolution.setAttribute('class', "fileDescription");
          div_fileresolution.setAttribute('title', "Image Resolution");
          div_fileresolution.setAttribute('id', "fileresolution" + bwg_j);
          div_fileresolution.innerHTML = files[i]['resolution'];
          td_filename.appendChild(div_fileresolution);
          var div_filesize = document.createElement('div');
          div_filesize.setAttribute('class', "fileDescription");
          div_filesize.setAttribute('title', (!is_video) ? "Image size" : "Duration");
          div_filesize.setAttribute('id', "filesize" + bwg_j);
          div_filesize.innerHTML = files[i]['size'];
          td_filename.appendChild(div_filesize);
          var div_filetype = document.createElement('div');
          div_filetype.setAttribute('class', "fileDescription");
          div_filetype.setAttribute('title', "Type");
          div_filetype.setAttribute('id', "filetype" + bwg_j);
          div_filetype.innerHTML = files[i]['filetype'];
          td_filename.appendChild(div_filetype);
          if (!is_video) {
            var div_edit = document.createElement('div');
            td_filename.appendChild(div_edit);
            var span_edit_crop = document.createElement('span');
            span_edit_crop.setAttribute('class', "edit_thumb");
            div_edit.appendChild(span_edit_crop);
            var a_crop = document.createElement('a');
            a_crop.setAttribute('class', "thickbox thickbox-preview");
            a_crop.setAttribute('onclick', "spider_set_href(this, '" + bwg_j + "', 'crop');");
            a_crop.innerHTML = "Crop";
            span_edit_crop.appendChild(a_crop);
            div_edit.innerHTML += " | ";
            var span_edit_rotate = document.createElement('span');
            span_edit_rotate.setAttribute('class', "edit_thumb");
            div_edit.appendChild(span_edit_rotate);
            var a_rotate = document.createElement('a');
            a_rotate.setAttribute('class', "thickbox thickbox-preview");
            a_rotate.setAttribute('onclick', "spider_set_href(this, '" + bwg_j + "', 'rotate');");
            a_rotate.innerHTML = "Rotate";
            span_edit_rotate.appendChild(a_rotate);
            div_edit.innerHTML += " | "
            var span_edit_recover = document.createElement('span');
            span_edit_recover.setAttribute('class', "edit_thumb");
            div_edit.appendChild(span_edit_recover);
            var a_recover = document.createElement('a');
            a_recover.setAttribute('onclick', 'if (confirm("Do you want to reset the image?")) { spider_set_input_value("ajax_task", "recover"); spider_set_input_value("image_current_id", "' + bwg_j + '"); spider_ajax_save("galleries_form");} return false;');
            a_recover.innerHTML = "Reset";
            span_edit_recover.appendChild(a_recover);
          }
          var input_image_url = document.createElement('input');
          input_image_url.setAttribute('id', "image_url_" + bwg_j);
          input_image_url.setAttribute('name', "image_url_" + bwg_j);
          input_image_url.setAttribute('type', "hidden");
          input_image_url.setAttribute('value', files[i]['url']);
          td_filename.appendChild(input_image_url);
          var input_thumb_url = document.createElement('input');
          input_thumb_url.setAttribute('id', "thumb_url_" + bwg_j);
          input_thumb_url.setAttribute('name', "thumb_url_" + bwg_j);
          input_thumb_url.setAttribute('type', "hidden");
          input_thumb_url.setAttribute('value', files[i]['thumb_url']);
          td_filename.appendChild(input_thumb_url);
          var input_filename = document.createElement('input');
          input_filename.setAttribute('id', "input_filename_" + bwg_j);
          input_filename.setAttribute('name', "input_filename_" + bwg_j);
          input_filename.setAttribute('type', "hidden");
          input_filename.setAttribute('value', files[i]['filename']);
          td_filename.appendChild(input_filename);
          var input_date_modified = document.createElement('input');
          input_date_modified.setAttribute('id', "input_date_modified_" + bwg_j);
          input_date_modified.setAttribute('name', "input_date_modified_" + bwg_j);
          input_date_modified.setAttribute('type', "hidden");
          input_date_modified.setAttribute('value', files[i]['date_modified']);
          td_filename.appendChild(input_date_modified);
          var input_resolution = document.createElement('input');
          input_resolution.setAttribute('id', "input_resolution_" + bwg_j);
          input_resolution.setAttribute('name', "input_resolution_" + bwg_j);
          input_resolution.setAttribute('type', "hidden");
          input_resolution.setAttribute('value', files[i]['resolution']);
          td_filename.appendChild(input_resolution);
          var input_size = document.createElement('input');
          input_size.setAttribute('id', "input_size_" + bwg_j);
          input_size.setAttribute('name', "input_size_" + bwg_j);
          input_size.setAttribute('type', "hidden");
          input_size.setAttribute('value', files[i]['size']);
          td_filename.appendChild(input_size);
          var input_filetype = document.createElement('input');
          input_filetype.setAttribute('id', "input_filetype_" + bwg_j);
          input_filetype.setAttribute('name', "input_filetype_" + bwg_j);
          input_filetype.setAttribute('type', "hidden");
          input_filetype.setAttribute('value', files[i]['filetype']);
          td_filename.appendChild(input_filetype);
          // Alt/Title TD.
          var td_alt = document.createElement('td');
          td_alt.setAttribute('class', "table_extra_large_col");
          tr.appendChild(td_alt);
          var input_alt = document.createElement('input');
          input_alt.setAttribute('id', "image_alt_text_" + bwg_j);
          input_alt.setAttribute('name', "image_alt_text_" + bwg_j);
          input_alt.setAttribute('type', "text");
          input_alt.setAttribute('size', "24");
          if (is_video) {
            input_alt.setAttribute('value', files[i]['name']);
          }
          else {
            input_alt.setAttribute('value', files[i]['filename']);
          }
          td_alt.appendChild(input_alt);

          <?php if ($option_row->thumb_click_action != 'open_lightbox') { ?>
          //Redirect url
          input_alt = document.createElement('input');
          input_alt.setAttribute('id', "redirect_url_" + bwg_j);
          input_alt.setAttribute('name', "redirect_url_" + bwg_j);
          input_alt.setAttribute('type', "text");
          input_alt.setAttribute('size', "24");
          td_alt.appendChild(input_alt);
          <?php } ?>
          // Description TD.
          var td_desc = document.createElement('td');
          td_desc.setAttribute('class', "table_extra_large_col");
          tr.appendChild(td_desc);
          var textarea_desc = document.createElement('textarea');
          textarea_desc.setAttribute('id', "image_description_" + bwg_j);
          textarea_desc.setAttribute('name', "image_description_" + bwg_j);
          textarea_desc.setAttribute('rows', "2");
          textarea_desc.setAttribute('cols', "20");
          textarea_desc.setAttribute('style', "resize:vertical;");
          if (is_video) {
            textarea_desc.innerHTML = files[i]['description'];
          }
          td_desc.appendChild(textarea_desc);
          // Tag TD.
          var td_tag = document.createElement('td');
          td_tag.setAttribute('class', "table_extra_large_col");
          tr.appendChild(td_tag);
          var a_tag = document.createElement('a');
          a_tag.setAttribute('class', "button button-small button-primary thickbox thickbox-preview");
          a_tag.setAttribute('href', "<?php echo add_query_arg(array('action' => 'addTags', 'width' => '650', 'height' => '500'), admin_url('admin-ajax.php')); ?>&image_id=" + bwg_j + "&TB_iframe=1");
          a_tag.innerHTML = 'Add tag';
          td_tag.appendChild(a_tag);
          var div_tag = document.createElement('div');
          div_tag.setAttribute('class', "tags_div");
          div_tag.setAttribute('id', "tags_div_" + bwg_j);
          td_tag.appendChild(div_tag);
          var hidden_tag = document.createElement('input');
          hidden_tag.setAttribute('type', "hidden");
          hidden_tag.setAttribute('id', "tags_" + bwg_j);
          hidden_tag.setAttribute('name', "tags_" + bwg_j);
          hidden_tag.setAttribute('value', "");
          td_tag.appendChild(hidden_tag);
          // Order TD.
          var td_order = document.createElement('td');
          td_order.setAttribute('class', "spider_order table_medium_col");
          td_order.setAttribute('style', "display: none;");
          tr.appendChild(td_order);
          var input_order = document.createElement('input');
          input_order.setAttribute('id', "order_input_" + bwg_j);
          input_order.setAttribute('name', "order_input_" + bwg_j);
          input_order.setAttribute('type', "text");
          input_order.setAttribute('value', 0 - j_int);
          input_order.setAttribute('size', "1");
          td_order.appendChild(input_order);
          // Publish TD.
          var td_publish = document.createElement('td');
          td_publish.setAttribute('class', "table_big_col");
          tr.appendChild(td_publish);
          var a_publish = document.createElement('a');
          a_publish.setAttribute('onclick', "spider_set_input_value('ajax_task', 'image_unpublish');spider_set_input_value('image_current_id', '" + bwg_j + "');spider_ajax_save('galleries_form');");
          td_publish.appendChild(a_publish);
          var img_publish = document.createElement('img');
          img_publish.setAttribute('src', "<?php echo WD_BWG_URL . '/images/publish.png'; ?>");
          a_publish.appendChild(img_publish);
          // Delete TD.
          var td_delete = document.createElement('td');
          td_delete.setAttribute('class', "table_big_col");
          tr.appendChild(td_delete);
          var a_delete = document.createElement('a');
          a_delete.setAttribute('onclick', "spider_set_input_value('ajax_task', 'image_delete');spider_set_input_value('image_current_id', '" + bwg_j + "');spider_ajax_save('galleries_form');");
          a_delete.innerHTML = 'Delete';
          td_delete.appendChild(a_delete);
          document.getElementById("ids_string").value += bwg_j + ',';
          j_int++;
          bwg_j = 'pr_' + j_int;
        }
        jQuery("#show_hide_weights").val("Hide order column");
        spider_show_hide_weights();
      }
    </script>
    <form class="wrap" method="post" id="galleries_form" action="admin.php?page=galleries_bwg" style="float: left; width: 99%;">
      <span class="gallery-icon"></span>
      <h2><?php echo $page_title; ?></h2>
      <div style="float:right;">
        <input class="button-secondary" type="button" onclick="if (spider_check_required('name', 'Name')) {return false;};
                                                     spider_set_input_value('page_number', '1');
                                                     spider_set_input_value('ajax_task', 'ajax_save');
                                                     spider_ajax_save('galleries_form');
                                                     spider_set_input_value('task', 'save')" value="Save" />
        <input class="button-secondary" type="button" onclick="if (spider_check_required('name', 'Name')) {return false;};
                                                     spider_set_input_value('ajax_task', 'ajax_apply');
                                                     spider_ajax_save('galleries_form')" value="Apply" />
        <input class="button-secondary" type="submit" onclick="spider_set_input_value('page_number', '1');
                                                     spider_set_input_value('task', 'cancel')" value="Cancel" />
      </div>
      <table style="clear:both;">
        <tbody>
          <tr>
            <td class="spider_label"><label for="name">Name: <span style="color:#FF0000;">*</span> </label></td>
            <td><input type="text" id="name" name="name" value="<?php echo $row->name; ?>" size="39" /></td>
          </tr>
          <tr>
            <td class="spider_label"><label for="slug">Slug: </label></td>
            <td><input type="text" id="slug" name="slug" value="<?php echo $row->slug; ?>" size="39" /></td>
          </tr>
          <tr>
            <td class="spider_label"><label for="description">Description: </label></td>
            <td>
              <div style="width:500px;">
              <?php
              if (user_can_richedit()) {
                wp_editor($row->description, 'description', array('teeny' => FALSE, 'textarea_name' => 'description', 'media_buttons' => FALSE, 'textarea_rows' => 5));
              }
              else {
              ?>
              <textarea cols="36" rows="5" id="description" name="description" style="resize:vertical">
                <?php echo $row->description; ?>
              </textarea>
              <?php
              }
              ?>
              </div>
            </td>
          </tr>
          <tr>
            <td class="spider_label"><label>Author: </label></td>
            <td><?php echo get_userdata($row->author)->display_name; ?></td>
          </tr>
          <tr>
            <td class="spider_label"><label>Published: </label></td>
            <td>
              <input type="radio" class="inputbox" id="published0" name="published" <?php echo (($row->published) ? '' : 'checked="checked"'); ?> value="0" >
              <label for="published0">No</label>
              <input type="radio" class="inputbox" id="published1" name="published" <?php echo (($row->published) ? 'checked="checked"' : ''); ?> value="1" >
              <label for="published1">Yes</label>
            </td>
          </tr>
          <tr>
            <td class="spider_label"><label for="url">Preview image: </label></td>
            <td>
              <a href="<?php echo add_query_arg(array('action' => 'addImages', 'width' => '700', 'height' => '550', 'extensions' => 'jpg,jpeg,png,gif', 'callback' => 'bwg_add_preview_image', 'TB_iframe' => '1'), admin_url('admin-ajax.php')); ?>"
                 id="button_preview_image"
                 class="button-primary thickbox thickbox-preview"
                 title="Add Preview Image"
                 onclick="return false;"
                 style="margin-bottom:5px; display:none;">
                Add Preview Image
              </a>
              <input type="hidden" id="preview_image" name="preview_image" value="<?php echo $row->preview_image; ?>" style="display:inline-block;"/>
              <img id="img_preview_image"
                   style="max-height:90px; max-width:120px; vertical-align:middle;"
                   src="<?php echo site_url() . '/' . $WD_BWG_UPLOAD_DIR . $row->preview_image; ?>">
              <span id="delete_preview_image" class="spider_delete_img"
                    onclick="spider_remove_url('button_preview_image', 'preview_image', 'delete_preview_image', 'img_preview_image')"></span>
            </td>
          </tr>
          <tr>
            <td colspan=2>
              <?php
              echo $this->image_display($id);
              ?>
            </td>
          </tr>
        </tbody>
      </table>
      <input id="task" name="task" type="hidden" value="" />
      <input id="current_id" name="current_id" type="hidden" value="<?php echo $row->id; ?>" />
      <script>
        <?php
        if ($row->preview_image == '') {
          ?>
          spider_remove_url('button_preview_image', 'preview_image', 'delete_preview_image', 'img_preview_image');
          <?php
        }
        ?>
      </script>
      <div id="opacity_div" style="display: none; background-color: rgba(0, 0, 0, 0.2); position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 99998;"></div>
      <div id="loading_div" style="display:none; text-align: center; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 99999;">
        <img src="<?php echo WD_BWG_URL . '/images/ajax_loader.png'; ?>" class="spider_ajax_loading" style="margin-top: 200px; width:50px;">
      </div>
    </form>
    <?php
  }
  
  public function image_display($id) {
    global $WD_BWG_UPLOAD_DIR;
    $rows_data = $this->model->get_image_rows_data($id);
    $page_nav = $this->model->image_page_nav($id);
    $option_row = $this->model->get_option_row_data();
    $search_value = ((isset($_POST['search_value'])) ? esc_html(stripslashes($_POST['search_value'])) : '');
    $asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html(stripslashes($_POST['asc_or_desc'])) : 'asc');
    $image_order_by = (isset($_POST['image_order_by']) ? esc_html(stripslashes($_POST['image_order_by'])) : 'order');
    $order_class = 'manage-column column-title sorted ' . $asc_or_desc;
    $page_number = (isset($_POST['page_number']) ? esc_html(stripslashes($_POST['page_number'])) : 1);
    $ids_string = '';
    ?>
      <div id="draganddrop" class="updated" style="display:none;"><strong><p>Changes made in this table should be saved.</p></strong></div>
      <div class="buttons_div_left">
        <a href="<?php echo add_query_arg(array('action' => 'addImages', 'width' => '700', 'height' => '550', 'extensions' => 'jpg,jpeg,png,gif', 'callback' => 'bwg_add_image', 'TB_iframe' => '1'), admin_url('admin-ajax.php')); ?>" class="button-primary thickbox thickbox-preview" id="content-add_media" title="Add Images" onclick="return false;" style="margin-bottom:5px;">
          Add Images
        </a>
        <input id="show_add_video" class="button-primary" type="button" onclick="jQuery('.opacity_add_video').show(); return false;" value="Add Video" />
      </div>
      <div class="buttons_div_right">
        <span class="button-secondary non_selectable" onclick="spider_check_all_items()">
          <input type="checkbox" id="check_all_items" name="check_all_items" onclick="spider_check_all_items_checkbox()" style="margin: 0; vertical-align: middle;" />
          <span style="vertical-align: middle;">Select All</span>
        </span>
        <input id="show_hide_weights"  class="button-secondary" type="button" onclick="spider_show_hide_weights();return false;" value="Hide order column" />
        <input class="button-primary" type="submit" onclick="spider_set_input_value('ajax_task', 'image_set_watermark');
                                                             spider_ajax_save('galleries_form');
                                                             return false;" value="Set Watermark" />
        <input class="button-secondary" type="submit" onclick="jQuery('.opacity_resize_image').show(); return false;" value="Resize" />
        <input class="button-secondary" type="submit" onclick="spider_set_input_value('ajax_task', 'image_recover_all');
                                                             spider_ajax_save('galleries_form');
                                                             return false;" value="Reset" />
        <a onclick="return bwg_check_checkboxes();" href="<?php echo add_query_arg(array('action' => 'addTags', 'width' => '650', 'height' => '500'), admin_url('admin-ajax.php')); ?>&TB_iframe=1" class="button-primary thickbox thickbox-preview">Add tag</a>
        <input class="button-secondary" type="submit" onclick="spider_set_input_value('ajax_task', 'image_publish_all');
                                                     spider_ajax_save('galleries_form');
                                                     return false;" value="Publish" />
        <input class="button-secondary" type="submit" onclick="spider_set_input_value('ajax_task', 'image_unpublish_all');
                                                     spider_ajax_save('galleries_form');
                                                     return false;" value="Unpublish" />
        <input class="button-secondary" type="submit" onclick="if (confirm('Do you want to delete selected items?')) {
                                                       spider_set_input_value('ajax_task', 'image_delete_all');
                                                       spider_ajax_save('galleries_form');
                                                       return false;
                                                     } else {
                                                       return false;
                                                     }" value="Delete" />
      </div>
      <div id="opacity_add_video" class="opacity_resize_image opacity_add_video bwg_opacity_video" onclick="jQuery('.opacity_add_video').hide();jQuery('.opacity_resize_image').hide();"></div>
      <div id="add_video" class="opacity_add_video bwg_add_video">
        <input type="text" id="video_url" name="video_url" value="" />
        <input class="button-primary" type="button" onclick="if (bwg_get_video_info('video_url')) {jQuery('.opacity_add_video').hide();} return false;" value="Add to gallery" />
        <input class="button-secondary" type="button" onclick="jQuery('.opacity_add_video').hide(); return false;" value="Cancel" />
        <div class="spider_description">Enter YouTube or Vimeo link here.</div>
      </div>
      <div id="" class="opacity_resize_image bwg_resize_image">
        Resize images to: 
        <input type="text" name="image_width" id="image_width" value="1600" /> x 
        <input type="text" name="image_height" id="image_height" value="1200" /> px
        <input class="button-primary" type="button" onclick="spider_set_input_value('ajax_task', 'image_resize');
                                                             spider_ajax_save('galleries_form');
                                                             jQuery('.opacity_resize_image').hide();
                                                             return false;" value="Resize" />
        <input class="button-secondary" type="button" onclick="jQuery('.opacity_resize_image').hide(); return false;" value="Cancel" />
        <div class="spider_description">The maximum size of resized image.</div>
      </div>
      <div class="tablenav top">
        <?php
        WDWLibrary::ajax_search('Filename', $search_value, 'galleries_form');
        WDWLibrary::ajax_html_page_nav($page_nav['total'], $page_nav['limit'], 'galleries_form');
        ?>
      </div>

      <table id="images_table" class="wp-list-table widefat fixed pages">
        <thead>
          <th class="check-column table_small_col"></th>
          <th class="manage-column column-cb check-column table_small_col"><input id="check_all" type="checkbox" onclick="spider_check_all(this)" style="margin:0;" /></th>
          <th class="table_small_col">#</th>
          <th class="table_extra_large_col">Thumbnail</th>
          <th class="table_extra_large_col <?php if ($image_order_by == 'filename') {echo $order_class;} ?>">
            <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('image_order_by', 'filename');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['image_order_by']) && (esc_html(stripslashes($_POST['image_order_by'])) == 'filename') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_ajax_save('galleries_form');">
              <span>Filename</span><span class="sorting-indicator"></span>
            </a>
          </th>
          <th class="table_extra_large_col <?php if ($image_order_by == 'alt') {echo $order_class;} ?>">
            <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('image_order_by', 'alt');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['image_order_by']) && (esc_html(stripslashes($_POST['image_order_by'])) == 'alt') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_ajax_save('galleries_form');">
              <span>Alt/Title<?php if ($option_row->thumb_click_action != 'open_lightbox') { ?><br />Redirect URL<?php } ?></span><span class="sorting-indicator"></span>
            </a>
          </th>
          <th class="table_extra_large_col <?php if ($image_order_by == 'description') {echo $order_class;} ?>">
            <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('image_order_by', 'description');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['image_order_by']) && (esc_html(stripslashes($_POST['image_order_by'])) == 'description') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_ajax_save('galleries_form');">
              <span>Description</span><span class="sorting-indicator"></span>
            </a>
          </th>
          <th class="table_extra_large_col">Tags</th>
          <th id="th_order" class="table_medium_col <?php if ($image_order_by == 'order') {echo $order_class;} ?>">
            <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('image_order_by', 'order');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['image_order_by']) && (esc_html(stripslashes($_POST['image_order_by'])) == 'order') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_ajax_save('galleries_form');">
              <span>Order</span><span class="sorting-indicator"></span>
            </a>
          </th>
          <th class="table_big_col <?php if ($image_order_by == 'published') {echo $order_class;} ?>">
            <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('image_order_by', 'published');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['image_order_by']) && (esc_html(stripslashes($_POST['image_order_by'])) == 'published') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_ajax_save('galleries_form');">
              <span>Published</span><span class="sorting-indicator"></span>
            </a>
          </th>
          <th class="table_big_col">Delete</th>
        </thead>
        <tbody id="tbody_arr">
          <?php
          $i = ($page_number - 1) * 20;
          if ($rows_data) {
            foreach ($rows_data as $row_data) {
              $is_video = $row_data->filetype == 'YOUTUBE' || $row_data->filetype == 'VIMEO';
              $alternate = (!isset($alternate) || $alternate == 'class="alternate"') ? '' : 'class="alternate"';
              $rows_tag_data = $this->model->get_tag_rows_data($row_data->id);
              $published_image = (($row_data->published) ? 'publish' : 'unpublish');
              $published = (($row_data->published) ? 'unpublish' : 'publish');
              ?>
              <tr id="tr_<?php echo $row_data->id; ?>" <?php echo $alternate; ?>>
                <td class="connectedSortable table_small_col"><div title="Drag to re-order" class="handle" style="margin:5px auto 0 auto;"></div></td>
                <td class="table_small_col check-column"><input id="check_<?php echo $row_data->id; ?>" name="check_<?php echo $row_data->id; ?>" onclick="spider_check_all(this)" type="checkbox" /></td>
                <td class="table_small_col"><?php echo ++$i; ?></td>
                <td class="table_extra_large_col">
                  <a class="thickbox thickbox-preview" title="<?php echo $row_data->alt; ?>" href="<?php echo add_query_arg(array('action' => 'editThumb', 'type' => 'display'/*thumb_display*/, 'image_id' => $row_data->id, 'width' => '800', 'height' => '500', 'TB_iframe' => '1'), admin_url('admin-ajax.php')); ?>">
                    <img id="image_thumb_<?php echo $row_data->id; ?>" class="thumb" src="<?php echo (!$is_video ? site_url() . '/' . $WD_BWG_UPLOAD_DIR : "") . $row_data->thumb_url . '?date=' . date('Y-m-y H:i:s'); ?>">
                  </a>
                </td>
                <td class="table_extra_large_col">
                  <div class="filename" id="filename_<?php echo $row_data->id; ?>">
                    <strong><a title="<?php echo $row_data->alt; ?>" class="spider_word_wrap thickbox thickbox-preview" href="<?php echo add_query_arg(array('action' => 'editThumb', 'type' => 'display', 'image_id' => $row_data->id, 'width' => '800', 'height' => '500', 'TB_iframe' => '1'), admin_url('admin-ajax.php')); ?>"><?php echo $row_data->filename; ?></a></strong>
                  </div>
                  <div class="fileDescription" title="Date modified" id="date_modified_<?php echo $row_data->id; ?>"><?php echo date("d F Y, H:i", strtotime($row_data->date)); ?></div>
                  <div class="fileDescription" title="Image Resolution" id="fileresolution_<?php echo $row_data->id; ?>"><?php echo $row_data->resolution; ?></div>
                  <div class="fileDescription" title="<?php echo (!$is_video ? "Image size" : "Duration")?>" id="filesize_<?php echo $row_data->id; ?>"><?php echo $row_data->size; ?></div>
                  <div class="fileDescription" title="Type" id="filetype_<?php echo $row_data->id; ?>"><?php echo $row_data->filetype; ?></div>
                  <?php if(!$is_video) {?>
                  <div>
                    <span class="edit_thumb"><a class="thickbox thickbox-preview" href="<?php echo add_query_arg(array('action' => 'editThumb', 'type' => 'crop', 'image_id' => $row_data->id, 'TB_iframe' => '1', 'width' => '800', 'height' => '500'), admin_url('admin-ajax.php')); ?>">Crop</a></span> | 
                    <span class="edit_thumb"><a class="thickbox thickbox-preview" href="<?php echo add_query_arg(array('action' => 'editThumb', 'type' => 'rotate', 'image_id' => $row_data->id, 'width' => '800', 'height' => '500', 'TB_iframe' => '1'), admin_url('admin-ajax.php')); ?>">Rotate</a></span> | 
                    <span class="edit_thumb"><a onclick="if (confirm('Do you want to reset the image?')) {
                                                          spider_set_input_value('ajax_task', 'recover');
                                                          spider_set_input_value('image_current_id', '<?php echo $row_data->id; ?>');
                                                          spider_ajax_save('galleries_form');
                                                         }
                                                         return false;">Reset</a></span>
                  </div>
                  <?php } ?>
                  <input type="hidden" id="image_url_<?php echo $row_data->id; ?>" name="image_url_<?php echo $row_data->id; ?>" value="<?php echo $row_data->image_url; ?>" />
                  <input type="hidden" id="thumb_url_<?php echo $row_data->id; ?>" name="thumb_url_<?php echo $row_data->id; ?>" value="<?php echo $row_data->thumb_url; ?>" />
                  <input type="hidden" id="input_filename_<?php echo $row_data->id; ?>" name="input_filename_<?php echo $row_data->id; ?>" value="<?php echo $row_data->filename; ?>" />
                  <input type="hidden" id="input_date_modified_<?php echo $row_data->id; ?>" name="input_date_modified_<?php echo $row_data->id; ?>" value="<?php echo $row_data->date; ?>" />
                  <input type="hidden" id="input_resolution_<?php echo $row_data->id; ?>" name="input_resolution_<?php echo $row_data->id; ?>" value="<?php echo $row_data->resolution; ?>" />
                  <input type="hidden" id="input_size_<?php echo $row_data->id; ?>" name="input_size_<?php echo $row_data->id; ?>" value="<?php echo $row_data->size; ?>" />
                  <input type="hidden" id="input_filetype_<?php echo $row_data->id; ?>" name="input_filetype_<?php echo $row_data->id; ?>" value="<?php echo $row_data->filetype; ?>" />
                </td>
                <td class="table_extra_large_col">
                  <input size="24" type="text" id="image_alt_text_<?php echo $row_data->id; ?>" name="image_alt_text_<?php echo $row_data->id; ?>" value="<?php echo $row_data->alt; ?>" />
                  <?php if ($option_row->thumb_click_action != 'open_lightbox') { ?>
                  <input size="24" type="text" id="redirect_url_<?php echo $row_data->id; ?>" name="redirect_url_<?php echo $row_data->id; ?>" value="<?php echo $row_data->redirect_url; ?>" />
                  <?php } ?>
                </td>
                <td class="table_extra_large_col">
                  <textarea cols="20" rows="2" id="image_description_<?php echo $row_data->id; ?>" name="image_description_<?php echo $row_data->id; ?>" style="resize:vertical;"><?php echo $row_data->description; ?></textarea>
                </td>
                <td class="table_extra_large_col">
                  <a href="<?php echo add_query_arg(array('action' => 'addTags', 'image_id' => $row_data->id, 'width' => '650', 'height' => '500', 'TB_iframe' => '1'), admin_url('admin-ajax.php')); ?>" class="button button-small button-primary thickbox thickbox-preview">Add tag</a>
                  <div class="tags_div" id="tags_div_<?php echo $row_data->id; ?>">
                  <?php
                  $tags_id_string = '';
                  if ($rows_tag_data) {
                    foreach($rows_tag_data as $row_tag_data) {
                      ?>
                      <div class="tag_div" id="<?php echo $row_data->id; ?>_tag_<?php echo $row_tag_data->term_id; ?>">
                        <span class="tag_name"><?php echo $row_tag_data->name; ?></span>
                        <span style="float:right;" class="spider_delete_img_small" onclick="bwg_remove_tag('<?php echo $row_tag_data->term_id; ?>', '<?php echo $row_data->id; ?>')" />
                      </div>
                      <?php
                      $tags_id_string .= $row_tag_data->term_id . ',';
                    }
                  }
                  ?>
                  </div>
                  <input type="hidden" value="<?php echo $tags_id_string; ?>" id="tags_<?php echo $row_data->id; ?>" name="tags_<?php echo $row_data->id; ?>"/>
                </td>
                <td class="spider_order table_medium_col"><input id="order_input_<?php echo $row_data->id; ?>" name="order_input_<?php echo $row_data->id; ?>" type="text" size="1" value="<?php echo $row_data->order; ?>" /></td>
                <td class="table_big_col"><a onclick="spider_set_input_value('ajax_task', 'image_<?php echo $published; ?>');
                                                      spider_set_input_value('image_current_id', '<?php echo $row_data->id; ?>');
                                                      spider_ajax_save('galleries_form');"><img src="<?php echo WD_BWG_URL . '/images/' . $published_image . '.png'; ?>"></img></a></td>
                <td class="table_big_col"><a onclick="spider_set_input_value('ajax_task', 'image_delete');
                                                      spider_set_input_value('image_current_id', '<?php echo $row_data->id; ?>');
                                                      spider_ajax_save('galleries_form');">Delete</a></td>
              </tr>
              <?php
              $ids_string .= $row_data->id . ',';
            }
          }
          ?>
          <input id="ids_string" name="ids_string" type="hidden" value="<?php echo $ids_string; ?>" />
          <input id="asc_or_desc" name="asc_or_desc" type="hidden" value="asc" />
          <input id="image_order_by" name="image_order_by" type="hidden" value="<?php echo $image_order_by; ?>" />
          <input id="ajax_task" name="ajax_task" type="hidden" value="" />
          <input id="image_current_id" name="image_current_id" type="hidden" value="" />
          <input id="added_tags_select_all" name="added_tags_select_all" type="hidden" value="" />
        </tbody>
      </table>
      <script>
        window.onload = spider_show_hide_weights;
      </script>
    <?php
  }
  
  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}