<?php

class BWGViewAddAlbumsGalleries {
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
    $album_id = ((isset($_GET['album_id'])) ? esc_html(stripslashes($_GET['album_id'])) : ((isset($_POST['album_id'])) ? esc_html(stripslashes($_POST['album_id'])) : ''));
    $rows_data = $this->model->get_rows_data($album_id);
    $page_nav = $this->model->page_nav($album_id);
    $search_value = ((isset($_POST['search_value'])) ? esc_html(stripslashes($_POST['search_value'])) : '');
    $asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html(stripslashes($_POST['asc_or_desc'])) : 'asc');
    $order_by = (isset($_POST['order_by']) ? esc_html(stripslashes($_POST['order_by'])) : 'name');
    $order_class = 'manage-column column-title sorted ' . $asc_or_desc;
    wp_print_scripts('jquery');
    ?>
    <link media="all" type="text/css" href="<?php echo get_admin_url(); ?>load-styles.php?c=1&amp;dir=ltr&amp;load=admin-bar,dashicons,wp-admin,buttons,wp-auth-check" rel="stylesheet">
    <?php if (get_bloginfo('version') < '3.9') { ?>
    <link media="all" type="text/css" href="<?php echo get_admin_url(); ?>css/colors<?php echo ((get_bloginfo('version') < '3.8') ? '-fresh' : ''); ?>.min.css" id="colors-css" rel="stylesheet">
    <?php } ?>
    <link media="all" type="text/css" href="<?php echo WD_BWG_URL . '/css/bwg_tables.css'; ?>" id="spider_audio_player_tables-css" rel="stylesheet">
    <script src="<?php echo WD_BWG_URL . '/js/bwg.js'; ?>" type="text/javascript"></script>
    <form class="wrap wp-core-ui" id="albums_galleries_form" method="post" action="<?php echo add_query_arg(array('action' => 'addAlbumsGalleries', 'width' => '700', 'height' => '550', 'callback' => 'bwg_add_items', 'TB_iframe' => '1'), admin_url('admin-ajax.php')); ?>" style="width:95%; margin: 0 auto;">
      <h2 style="width:200px;float:left">Albums/Galleries</h2>
      <a href="" class="thickbox thickbox-preview" id="content-add_media" title="Add Album/Gallery" onclick="spider_get_items(event);" style="float:right; padding: 9px 0px 4px 0">
        <img src="<?php echo WD_BWG_URL . '/images/add_but.png'; ?>" style="border:none;" />
      </a>
      <div class="tablenav top">
        <?php
        WDWLibrary::search('Name', $search_value, 'albums_galleries_form');
        WDWLibrary::html_page_nav($page_nav['total'], $page_nav['limit'], 'albums_galleries_form');
        ?>
      </div>
      <table class="wp-list-table widefat fixed pages">
        <thead>
          <th class="manage-column column-cb check-column table_small_col"><input id="check_all" type="checkbox" style="margin:0;" /></th>
          <th class="table_small_col <?php if ($order_by == 'id') {echo $order_class;} ?>">
            <a onclick="spider_set_input_value('order_by', 'id');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'id') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_form_submit(event, 'albums_galleries_form')" href="">
              <span>ID</span><span class="sorting-indicator"></span>
            </a>
          </th>
          <th class="table_medium_col_uncenter <?php if ($order_by == 'is_album') {echo $order_class;} ?>">
            <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('order_by', 'is_album');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'is_album') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_form_submit(event, 'albums_galleries_form')" href="">
              <span>Type</span><span class="sorting-indicator"></span>
            </a>
          </th>
          <th class="<?php if ($order_by == 'name') {echo $order_class;} ?>">
            <a onclick="spider_set_input_value('order_by', 'name');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'name') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_form_submit(event, 'albums_galleries_form')" href="">
              <span>Name</span><span class="sorting-indicator"></span>
            </a>
          </th>          
        </thead>
        <tbody id="tbody_albums_galleries">
          <?php
          if ($rows_data) {
            $iterator = 0;
            foreach ($rows_data as $row_data) {
              $alternate = (!isset($alternate) || $alternate == 'class="alternate"') ? '' : 'class="alternate"';
              ?>
              <tr id="tr_<?php echo $iterator; ?>" <?php echo $alternate; ?>>
                <td class="table_small_col check-column"><input id="check_<?php echo $iterator; ?>" name="check_<?php echo $iterator; ?>" type="checkbox" /></td>
                <td id="id_<?php echo $iterator; ?>" class="table_small_col"><?php echo $row_data->id; ?></td>
                <td id="url_<?php echo $iterator; ?>" class="table_medium_col_uncenter"><?php echo ($row_data->is_album ? "Album" : "Gallery") ; ?></td>
                <td>
                  <a onclick="window.parent.bwg_add_items(['<?php echo $row_data->id?>'],['<?php echo htmlspecialchars(addslashes($row_data->name))?>'], ['<?php echo htmlspecialchars(addslashes($row_data->is_album))?>'])" id="a_<?php echo $iterator; ?>" style="cursor:pointer;">
                    <?php echo $row_data->name?>
                  </a>
                </td>
              </tr>
              <?php
              $iterator++;
            }
          }
          ?>
        </tbody>
      </table>
      <input id="asc_or_desc" name="asc_or_desc" type="hidden" value="asc" />
      <input id="order_by" name="order_by" type="hidden" value="<?php echo $order_by; ?>" />
      <input id="album_id" name="album_id" type="hidden" value="<?php echo $album_id; ?>" />
    </form>
    <script src="<?php echo get_admin_url(); ?>load-scripts.php?c=1&load%5B%5D=common,admin-bar" type="text/javascript"></script>
    <?php
    die();
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