<?php
/**
 * Flex Slider specific markup, javascript, css and settings.
 */
class epsSliderContentClass extends epsSliderClass {

    protected $js_function = 'cslider';
    protected $js_path = 'js/jquery.cslider.js';
    protected $js_path_extra = 'js/modernizr.custom.28468.js';
    protected $css_path_extra = 'css/nojs.css';
    protected $css_path = 'css/style.css';
	protected $publiccss_path = 'css/public.css';
    protected $font_path = 'css/fonts';

    public function __construct($id) {
        parent::__construct($id);
    }


    /**
     * Include slider assets
     */
    public function eps_enqueue_scripts() {
        parent::eps_enqueue_scripts();
        if ($this->get_setting('printJs') == 'true') {

        }
    }

    /**
     * Build the HTML for a slider.
     *
     * @return string slider markup.
     */
    protected function eps_get_html() {
        $return_value = "";
        foreach ($this->slides as $slide) {
            $return_value .= "\n " . $slide . "\n";
        }
        return $return_value;
    }
}
?>