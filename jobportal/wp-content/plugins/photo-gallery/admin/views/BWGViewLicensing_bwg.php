<?php

class BWGViewLicensing_bwg {
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
    ?>
    <div style="text-align:center; float: left;">
      <table class="data-bordered">
        <thead>
          <tr>
            <th class="top first" nowrap="nowrap" scope="col">Features of the Gallery</th>
            <th class="top notranslate" nowrap="nowrap" scope="col">Free</th>
            <th class="top notranslate" nowrap="nowrap" scope="col">Pro Version</th>
          </tr>
        </thead>
        <tbody>
          <tr class="alt">
            <td>WordPress 3.4+ ready</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr>
            <td>SEO-friendly</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr class="alt">
            <td>Responsive Design and Layout</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr>
            <td>Full Back End Management</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr class="alt">
            <td>Watermarking Possibility</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr>
            <td>Advertising Possibility</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr class="alt">
            <td>Image Download</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr>
            <td>Standard Thumbnails View</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr class="alt">
            <td>Standard Slideshow View</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr>
            <td>Standard Image Browser View</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr class="alt">
            <td>Standard Compact Album</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr>
            <td>Standard Extended Album</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr class="alt">
            <td>Basic Tag Cloud Widget</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr>
            <td>Photo Gallery Slideshow Widget</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr class="alt">
            <td>Photo Gallery Widget</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr>
            <td>Slideshow/Lightbox Effects</td>
            <td style="text-align:center;">1</td>
            <td style="text-align:center;">15</td>
          </tr>
          <tr class="alt">
            <td>Possibility of Editing/Creating Themes</td>
            <td class="icon-replace no">no</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr>
            <td>Pro Masonry View </td>
            <td class="icon-replace no">no</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr class="alt">
            <td>Pro Blog Style View</td>
            <td class="icon-replace no">no</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr>
            <td>Pro Thumbnails View</td>
            <td class="icon-replace no">no</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr class="alt">
            <td>Pro Slideshow View</td>
            <td class="icon-replace no">no</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr>
            <td>Pro Image Browser View</td>
            <td class="icon-replace no">no</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr class="alt">
            <td>Pro Compact Album</td>
            <td class="icon-replace no">no</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr>
            <td>Pro Extended Album</td>
            <td class="icon-replace no">no</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr class="alt">
            <td>Image Commenting</td>
            <td class="icon-replace no">no</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr>
            <td>Image Social Sharing</td>
            <td class="icon-replace no">no</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr class="alt">
            <td>Photo Gallery Tags Cloud Widget</td>
            <td class="icon-replace no">no</td>
            <td class="icon-replace yes">yes</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div style="float: right; text-align: right;">
        <a style="text-decoration: none;" target="_blank" href="http://web-dorado.com/products/wordpress-photo-gallery-plugin.html">
          <img width="215" border="0" alt="web-dorado.com" src="<?php echo WD_BWG_URL . '/images/logo.png'; ?>" />
        </a>
      </div>
    <div style="float: left; clear: both;">
      <p>After the purchasing the commercial version follow this steps:</p>
      <ol>
        <li>Deactivate Photo Gallery plugin.</li>
        <li>Delete Photo Gallery plugin.</li>
        <li>Install the downloaded commercial version of the plugin.</li>
      </ol>
    </div>
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