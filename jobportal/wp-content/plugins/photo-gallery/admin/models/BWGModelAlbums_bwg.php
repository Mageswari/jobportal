<?php

class BWGModelAlbums_bwg {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct() {
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function get_rows_data() {
    global $wpdb;
    if (!current_user_can('manage_options') && $wpdb->get_var("SELECT gallery_role FROM " . $wpdb->prefix . "bwg_option")) {
      $where = " WHERE author=" . get_current_user_id();
    }
    else {
      $where = " WHERE author>=0 ";
    }
    $where .= ((isset($_POST['search_value'])) ? ' AND name LIKE "%' . esc_html(stripslashes($_POST['search_value'])) . '%"' : '');
    $asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html(stripslashes($_POST['asc_or_desc'])) : 'asc');
    $order_by = ' ORDER BY `' . ((isset($_POST['order_by']) && esc_html(stripslashes($_POST['order_by'])) != '') ? esc_html(stripslashes($_POST['order_by'])) : 'order') . '` ' . $asc_or_desc;
    if (isset($_POST['page_number']) && $_POST['page_number']) {
      $limit = ((int) $_POST['page_number'] - 1) * 20;
    }
    else {
      $limit = 0;
    }
    $query = "SELECT * FROM " . $wpdb->prefix . "bwg_album " . $where . $order_by . " LIMIT " . $limit . ",20";
    $rows = $wpdb->get_results($query);
    return $rows;
  }
  
  public function get_row_data($id) {
    global $wpdb;
    if ($id != 0) {
      if (!current_user_can('manage_options') && $wpdb->get_var("SELECT gallery_role FROM " . $wpdb->prefix . "bwg_option")) {
        $where = " WHERE author=" . get_current_user_id();
      }
      else {
        $where = " WHERE author>=0 ";
      }
      $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwg_album ' . $where . ' AND id="%d"', $id));
    }
    else {
      $row = new stdClass();
      $row->id = 0;
      $row->name = '';
      $row->slug = '';
      $row->description = '';
      $row->preview_image = '';
      $row->order = 0;
      $row->author = get_current_user_id();
      $row->published = 1;
    }
    return $row;
  }
  
  public function get_albums_galleries_rows_data($album_id) {
    global $wpdb;
    if (!current_user_can('manage_options') && $wpdb->get_var("SELECT gallery_role FROM " . $wpdb->prefix . "bwg_option")) {
      $where = " AND author=" . get_current_user_id();
    }
    else {
      $where = " AND author>=0 ";
    }
    $row = $wpdb->get_results("SELECT t1.id, t2.name, t2.slug, t1.is_album, t1.alb_gal_id, t1.order FROM " . $wpdb->prefix . "bwg_album_gallery as t1 INNER JOIN " . $wpdb->prefix . "bwg_album as t2 on t1.alb_gal_id = t2.id where t1.is_album='1'" . $where . " AND t1.album_id='" . $album_id . "' union SELECT t1.id, t2.name, t2.slug, t1.is_album, t1.alb_gal_id, t1.order FROM " . $wpdb->prefix . "bwg_album_gallery as t1 INNER JOIN " . $wpdb->prefix . "bwg_gallery as t2 on t1.alb_gal_id = t2.id where t1.is_album='0'" . $where . " AND t1.album_id='" . $album_id . "' ORDER BY `order`");
    return $row;
  }
  
  public function page_nav() {
    global $wpdb;
    $where = ((isset($_POST['search_value']) && (esc_html(stripslashes($_POST['search_value'])) != '')) ? 'WHERE name LIKE "%' . esc_html(stripslashes($_POST['search_value'])) . '%"'  : '');
    $query = "SELECT COUNT(*) FROM " . $wpdb->prefix . "bwg_album " . $where;
    $total = $wpdb->get_var($query);
    $page_nav['total'] = $total;
    if (isset($_POST['page_number']) && $_POST['page_number']) {
      $limit = ((int) $_POST['page_number'] - 1) * 20;
    }
    else {
      $limit = 0;
    }
    $page_nav['limit'] = (int) ($limit / 20 + 1);
    return $page_nav;
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