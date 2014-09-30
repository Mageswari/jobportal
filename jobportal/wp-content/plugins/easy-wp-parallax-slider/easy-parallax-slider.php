<?php
/*
  Plugin Name: Easy Parallax Slider
  Plugin URI: http://www.oscitasthemes.com
  Description: Easy Parallax Slider provides layered slider feature.
  Version: 2.1.0
  Author: oscitas
  Author URI: http://www.oscitasthemes.com
  License: Under the GPL v2 or later
*/

define('EPS_VERSION', '2.1.0');
define('EPS_BASE_URL', plugins_url('',__FILE__));
define('EPS_ASSETS_URL', EPS_BASE_URL . '/assets/');
define('EPS_BASE_DIR_LONG', dirname(__FILE__));
define('EPS_INC_DIR', EPS_BASE_DIR_LONG . '/inc/');
$eps_google_font_family=array("ABeeZee", "Abel", "Abril Fatface", "Aclonica", "Acme", "Actor", "Adamina", "Advent Pro", "Aguafina Script", "Akronim", "Aladin", "Aldrich", "Alegreya", "Alegreya SC", "Alex Brush", "Alfa Slab One", "Alice", "Alike", "Alike Angular", "Allan", "Allerta", "Allerta Stencil", "Allura", "Almendra", "Almendra Display", "Almendra SC", "Amarante", "Amaranth", "Amatic SC", "Amethysta", "Anaheim", "Andada", "Andika", "Angkor", "Annie Use Your Telescope", "Anonymous Pro", "Antic", "Antic Didone", "Antic Slab", "Anton", "Arapey", "Arbutus", "Arbutus Slab", "Architects Daughter", "Archivo Black", "Archivo Narrow", "Arimo", "Arizonia", "Armata", "Artifika", "Arvo", "Asap", "Asset", "Astloch", "Asul", "Atomic Age", "Aubrey", "Audiowide", "Autour One", "Average", "Average Sans", "Averia Gruesa Libre", "Averia Libre", "Averia Sans Libre", "Averia Serif Libre", "Bad Script", "Balthazar", "Bangers", "Basic", "Battambang", "Baumans", "Bayon", "Belgrano", "Belleza", "BenchNine", "Bentham", "Berkshire Swash", "Bevan", "Bigelow Rules", "Bigshot One", "Bilbo", "Bilbo Swash Caps", "Bitter", "Black Ops One", "Bokor", "Bonbon", "Boogaloo", "Bowlby One", "Bowlby One SC", "Brawler", "Bree Serif", "Bubblegum Sans", "Bubbler One", "Buda", "Buenard", "Butcherman", "Butterfly Kids", "Cabin", "Cabin Condensed", "Cabin Sketch", "Caesar Dressing", "Cagliostro", "Calligraffitti", "Cambo", "Candal", "Cantarell", "Cantata One", "Cantora One", "Capriola", "Cardo", "Carme", "Carrois Gothic", "Carrois Gothic SC", "Carter One", "Caudex", "Cedarville Cursive", "Ceviche One", "Changa One", "Chango", "Chau Philomene One", "Chela One", "Chelsea Market", "Chenla", "Cherry Cream Soda", "Cherry Swash", "Chewy", "Chicle", "Chivo", "Cinzel", "Cinzel Decorative", "Clicker Script", "Coda", "Coda Caption", "Codystar", "Combo", "Comfortaa", "Coming Soon", "Concert One", "Condiment", "Content", "Contrail One", "Convergence", "Cookie", "Copse", "Corben", "Courgette", "Cousine", "Coustard", "Covered By Your Grace", "Crafty Girls", "Creepster", "Crete Round", "Crimson Text", "Croissant One", "Crushed", "Cuprum", "Cutive", "Cutive Mono", "Damion", "Dancing Script", "Dangrek", "Dawning of a New Day", "Days One", "Delius", "Delius Swash Caps", "Delius Unicase", "Della Respira", "Denk One", "Devonshire", "Didact Gothic", "Diplomata", "Diplomata SC", "Domine", "Donegal One", "Doppio One", "Dorsa", "Dosis", "Dr Sugiyama", "Droid Sans", "Droid Sans Mono", "Droid Serif", "Duru Sans", "Dynalight", "EB Garamond", "Eagle Lake", "Eater", "Economica", "Electrolize", "Elsie", "Elsie Swash Caps", "Emblema One", "Emilys Candy", "Engagement", "Englebert", "Enriqueta", "Erica One", "Esteban", "Euphoria Script", "Ewert", "Exo", "Expletus Sans", "Fanwood Text", "Fascinate", "Fascinate Inline", "Faster One", "Fasthand", "Federant", "Federo", "Felipa", "Fenix", "Finger Paint", "Fjalla One", "Fjord One", "Flamenco", "Flavors", "Fondamento", "Fontdiner Swanky", "Forum", "Francois One", "Freckle Face", "Fredericka the Great", "Fredoka One", "Freehand", "Fresca", "Frijole", "Fruktur", "Fugaz One", "GFS Didot", "GFS Neohellenic", "Gabriela", "Gafata", "Galdeano", "Galindo", "Gentium Basic", "Gentium Book Basic", "Geo", "Geostar", "Geostar Fill", "Germania One", "Gilda Display", "Give You Glory", "Glass Antiqua", "Glegoo", "Gloria Hallelujah", "Goblin One", "Gochi Hand", "Gorditas", "Goudy Bookletter 1911", "Graduate", "Grand Hotel", "Gravitas One", "Great Vibes", "Griffy", "Gruppo", "Gudea", "Habibi", "Hammersmith One", "Hanalei", "Hanalei Fill", "Handlee", "Hanuman", "Happy Monkey", "Headland One", "Henny Penny", "Herr Von Muellerhoff", "Holtwood One SC", "Homemade Apple", "Homenaje", "IM Fell DW Pica", "IM Fell DW Pica SC", "IM Fell Double Pica", "IM Fell Double Pica SC", "IM Fell English", "IM Fell English SC", "IM Fell French Canon", "IM Fell French Canon SC", "IM Fell Great Primer", "IM Fell Great Primer SC", "Iceberg", "Iceland", "Imprima", "Inconsolata", "Inder", "Indie Flower", "Inika", "Irish Grover", "Istok Web", "Italiana", "Italianno", "Jacques Francois", "Jacques Francois Shadow", "Jim Nightshade", "Jockey One", "Jolly Lodger", "Josefin Sans", "Josefin Slab", "Joti One", "Judson", "Julee", "Julius Sans One", "Junge", "Jura", "Just Another Hand", "Just Me Again Down Here", "Kameron", "Karla", "Kaushan Script", "Kavoon", "Keania One", "Kelly Slab", "Kenia", "Khmer", "Kite One", "Knewave", "Kotta One", "Koulen", "Kranky", "Kreon", "Kristi", "Krona One", "La Belle Aurore", "Lancelot", "Lato", "League Script", "Leckerli One", "Ledger", "Lekton", "Lemon", "Libre Baskerville", "Life Savers", "Lilita One", "Limelight", "Linden Hill", "Lobster", "Lobster Two", "Londrina Outline", "Londrina Shadow", "Londrina Sketch", "Londrina Solid", "Lora", "Love Ya Like A Sister", "Loved by the King", "Lovers Quarrel", "Luckiest Guy", "Lusitana", "Lustria", "Macondo", "Macondo Swash Caps", "Magra", "Maiden Orange", "Mako", "Marcellus", "Marcellus SC", "Marck Script", "Margarine", "Marko One", "Marmelad", "Marvel", "Mate", "Mate SC", "Maven Pro", "McLaren", "Meddon", "MedievalSharp", "Medula One", "Megrim", "Meie Script", "Merienda", "Merienda One", "Merriweather", "Merriweather Sans", "Metal", "Metal Mania", "Metamorphous", "Metrophobic", "Michroma", "Milonga", "Miltonian", "Miltonian Tattoo", "Miniver", "Miss Fajardose", "Modern Antiqua", "Molengo", "Molle", "Monda", "Monofett", "Monoton", "Monsieur La Doulaise", "Montaga", "Montez", "Montserrat", "Montserrat Alternates", "Montserrat Subrayada", "Moul", "Moulpali", "Mountains of Christmas", "Mouse Memoirs", "Mr Bedfort", "Mr Dafoe", "Mr De Haviland", "Mrs Saint Delafield", "Mrs Sheppards", "Muli", "Mystery Quest", "Neucha", "Neuton", "New Rocker", "News Cycle", "Niconne", "Nixie One", "Nobile", "Nokora", "Norican", "Nosifer", "Nothing You Could Do", "Noticia Text", "Nova Cut", "Nova Flat", "Nova Mono", "Nova Oval", "Nova Round", "Nova Script", "Nova Slim", "Nova Square", "Numans", "Nunito", "Odor Mean Chey", "Offside", "Old Standard TT", "Oldenburg", "Oleo Script", "Oleo Script Swash Caps", "Open Sans", "Open Sans Condensed", "Oranienbaum", "Orbitron", "Oregano", "Orienta", "Original Surfer", "Oswald", "Over the Rainbow", "Overlock", "Overlock SC", "Ovo", "Oxygen", "Oxygen Mono", "PT Mono", "PT Sans", "PT Sans Caption", "PT Sans Narrow", "PT Serif", "PT Serif Caption", "Pacifico", "Paprika", "Parisienne", "Passero One", "Passion One", "Patrick Hand", "Patrick Hand SC", "Patua One", "Paytone One", "Peralta", "Permanent Marker", "Petit Formal Script", "Petrona", "Philosopher", "Piedra", "Pinyon Script", "Pirata One", "Plaster", "Play", "Playball", "Playfair Display", "Playfair Display SC", "Podkova", "Poiret One", "Poller One", "Poly", "Pompiere", "Pontano Sans", "Port Lligat Sans", "Port Lligat Slab", "Prata", "Preahvihear", "Press Start 2P", "Princess Sofia", "Prociono", "Prosto One", "Puritan", "Purple Purse", "Quando", "Quantico", "Quattrocento", "Quattrocento Sans", "Questrial", "Quicksand", "Quintessential", "Qwigley", "Racing Sans One", "Radley", "Raleway", "Raleway Dots", "Rambla", "Rammetto One", "Ranchers", "Rancho", "Rationale", "Redressed", "Reenie Beanie", "Revalia", "Ribeye", "Ribeye Marrow", "Righteous", "Risque", "Roboto", "Roboto Condensed", "Rochester", "Rock Salt", "Rokkitt", "Romanesco", "Ropa Sans", "Rosario", "Rosarivo", "Rouge Script", "Ruda", "Rufina", "Ruge Boogie", "Ruluko", "Rum Raisin", "Ruslan Display", "Russo One", "Ruthie", "Rye", "Sacramento", "Sail", "Salsa", "Sanchez", "Sancreek", "Sansita One", "Sarina", "Satisfy", "Scada", "Schoolbell", "Seaweed Script", "Sevillana", "Seymour One", "Shadows Into Light", "Shadows Into Light Two", "Shanti", "Share", "Share Tech", "Share Tech Mono", "Shojumaru", "Short Stack", "Siemreap", "Sigmar One", "Signika", "Signika Negative", "Simonetta", "Sintony", "Sirin Stencil", "Six Caps", "Skranji", "Slackey", "Smokum", "Smythe", "Sniglet", "Snippet", "Snowburst One", "Sofadi One", "Sofia", "Sonsie One", "Sorts Mill Goudy", "Source Code Pro", "Source Sans Pro", "Special Elite", "Spicy Rice", "Spinnaker", "Spirax", "Squada One", "Stalemate", "Stalinist One", "Stardos Stencil", "Stint Ultra Condensed", "Stint Ultra Expanded", "Stoke", "Strait", "Sue Ellen Francisco", "Sunshiney", "Supermercado One", "Suwannaphum", "Swanky and Moo Moo", "Syncopate", "Tangerine", "Taprom", "Tauri", "Telex", "Tenor Sans", "Text Me One", "The Girl Next Door", "Tienne", "Tinos", "Titan One", "Titillium Web", "Trade Winds", "Trocchi", "Trochut", "Trykker", "Tulpen One", "Ubuntu", "Ubuntu Condensed", "Ubuntu Mono", "Ultra", "Uncial Antiqua", "Underdog", "Unica One", "UnifrakturCook", "UnifrakturMaguntia", "Unkempt", "Unlock", "Unna", "VT323", "Vampiro One", "Varela", "Varela Round", "Vast Shadow", "Vibur", "Vidaloka", "Viga", "Voces", "Volkhov", "Vollkorn", "Voltaire", "Waiting for the Sunrise", "Wallpoet", "Walter Turncoat", "Warnes", "Wellfleet", "Wendy One", "Wire One", "Yanone Kaffeesatz", "Yellowtail", "Yeseva One", "Yesteryear", "Zeyada");

$eps_google_enqueue_scripts=array();
global $eps_google_font_family,$eps_google_enqueue_scripts;
require_once('classes/epsSliderClass.php');
require_once('classes/epsSliderContentClass.php');
require_once('classes/slider/epsSliderImageClass.php');
require_once('classes/slider/epsAdminSliderClass.php');
require_once('classes/image/epsImageHelperClass.php');



class easyParallaxSlider {

    public $slider = null;
    private $filename= null;

    /**
     * Constructor
     */
    public function __construct() {
        if(!session_id())
            @session_start();
        $pluginmenu=explode('/',plugin_basename(__FILE__));
        $this->filename=$pluginmenu[0];
        // create the admin menu/page
        add_action('init', array($this, 'eps_register_post_type'));
        add_action('init', array($this, 'eps_register_taxonomy'));
        add_action('admin_menu', array($this, 'eps_register_admin_menu'));
        add_action('admin_head', array($this,'ajaxurl'));

        add_shortcode('epsshortcode', array($this, 'eps_register_eps_shortcode'));
        add_shortcode('eps-slider', array($this, 'eps_register_eps_shortcode'));
        add_action('init', array($this,'osc_add_eps_button_to_tinymce'));
        add_action('admin_print_styles', array($this, 'add_my_tinymce_button_css'));
        $this->eps_register_slide_types();
    }
    function osc_add_eps_button_to_tinymce() {

        if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
            return;

        if (get_user_option('rich_editing') == 'true') {

            if($this->eps_get_current_post_type()!='eps-slider'){
                add_filter("mce_external_plugins", array($this,"osc_add_eps_plugin"));
                add_filter('mce_buttons', array($this,'osc_register_eps_editor_button'),2654.276);
            }
        }
    }
    public  function osc_add_eps_plugin($plugin_array) {
        $version=floatval(get_bloginfo('version'));
        if($version<3.9){
            $plugin_array['eps_editor_icon']=EPS_ASSETS_URL.'js/eps_tinymce_def.js';
        } else{
            $plugin_array['eps_editor_icon']=EPS_ASSETS_URL.'js/eps_tinymce_button.js';
        }
        //$plugin_array['eps_editor_icon']=EPS_ASSETS_URL.'js/eps_tinymce_button.js';
        return $plugin_array;
    }
    public function osc_register_eps_editor_button($buttons){
        $buttons[] = 'eps_editor_icon_button';
        return $buttons;

    }
    public function eps_get_current_post_type() {
        global $post, $typenow, $current_screen, $pagenow;
        if ($post && $post->post_type)
            return $post->post_type;
        elseif ($typenow)
            return $typenow;
        elseif ($current_screen && $current_screen->post_type)
            return $current_screen->post_type;
        elseif (isset($_REQUEST['post_type']))
            return sanitize_key($_REQUEST['post_type']);
        elseif (isset($_REQUEST['post']))
            return get_post_type($_REQUEST['post']);
        elseif (in_array($pagenow, array('post-new.php')) && isset($_REQUEST['post_type']) && $_REQUEST['post_type']== '')
            return 'post';
        return null;
    }
    public function ajaxurl() {
        $postlist = get_posts(array('post_type'=>'eps-slider','posts_per_page'=>-1));
        $epsposts = array();
        foreach ($postlist as $post) {
            $epsposts[] = array('id'=>$post->ID,'title'=>$post->post_title);
        }
        $epspost=json_encode($epsposts);
        ?>
        <script type="text/javascript">
            var epsajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
            var epsassetsurl = '<?php echo EPS_ASSETS_URL; ?>';
            var epsposts = '<?php echo $epspost; ?>';
        </script>

        <style type="text/css">
            .osc_eps_dropdown .mceFirst a {
                width: auto !important;
            }
            .osc_eps_dropdown img {
                padding: 3px !important;
            }
        </style>
    <?php
    }

    function add_my_tinymce_button_css() {

        wp_register_style('my_tinymce_eps_button_css', EPS_ASSETS_URL.'css/editor.css', array());

        wp_enqueue_style('my_tinymce_eps_button_css');


        wp_enqueue_style('dashicons');

    }


    /**
     * Add the menu page
     */
    private function eps_register_slide_types() {
        $image = new epsAdminSlider();
    }

    public function eps_register_admin_menu() {
        $title = apply_filters('eps_menu_title', "EPS Settings");

        $page = add_menu_page(
            $title,
            $title,
            'edit_others_posts',
            $this->filename,
            array( $this, 'eps_render_admin_page'),
            EPS_ASSETS_URL . 'images/osc-icon.png'
        );

        // ensure our JavaScript is only loaded on the easy Slider admin page
        add_action('admin_print_scripts-' . $page, array($this, 'eps_register_admin_scripts'));
        add_action('admin_print_styles-' . $page, array($this, 'eps_register_admin_styles'));
    }

    function eps_render_admin_page() {

        $this->eps_admin_process();
        include (dirname(__FILE__)."/templates/eps_admin_page.php");

    }
    /**
     * Handle slide uploads/changes
     */
    public function eps_admin_process() {

        // default to the latest slider
        $slider_id = $this->eps_find_slider('modified', 'DESC');

        // delete a slider
        if (isset($_GET['delete'])) {
            $this->eps_delete_slider(intval($_GET['delete']));
            $slider_id = $this->eps_find_slider('date', 'DESC');
        }

        // create a new slider
        if (isset($_GET['add'])) {
            $this->eps_add_slider();
            $slider_id = $this->eps_find_slider('date', 'DESC');

        }
        if(isset($_SESSION['slider_added']) && $_SESSION['slider_added']==1){
            $slider_id = $this->eps_find_slider('date', 'DESC');
            $_SESSION['slider_added']='';
        }
        if (isset($_REQUEST['id'])) {
            $slider_id = $_REQUEST['id'];
        }

        $this->eps_set_slider($slider_id);
    }
    /**
     * Create a new slider
     */
    public function eps_curPageURL() {
        $pageURL = 'http';
        if (isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }
    private function eps_add_slider() {

        $defaults = array();
        // if possible, take a copy of the last edited slider settings in place of default settings
        if ($last_modified = $this->eps_find_slider('modified', 'DESC')) {
            $defaults = get_post_meta($last_modified, 'eps-slider_settings', true);
        }

        // insert the post
        $id = wp_insert_post(array(
            'post_title' => __("New Slider", $this->filename),
            'post_status' => 'publish',
            'post_type' => 'eps-slider'
        ));

//		 use the default settings if we can't find anything more suitable.
        if (empty($defaults)) {
            $slider = new epsSliderClass($id);
            $defaults = $slider->_default_settings();
        }


        // insert the post meta
        add_post_meta($id, 'eps-slider_settings', $defaults, true);

        // create the taxonomy term, the term is the ID of the slider itself
        wp_insert_term($id, 'eps-slider');
        $redirect=$this->eps_curPageURL();
        $redirect=add_query_arg('add',false,$redirect);
        $_SESSION['slider_added']=1;
        ?>
        <script type="text/javascript">window.location.href='<?php echo $redirect;?>'</script>
    <?php

    }

    /**
     * Delete a slider (send it to trash)
     */
    private function eps_delete_slider($id) {
        $slide = array(
            'ID' => $id,
            'post_status' => 'trash'
        );

        wp_update_post($slide);
    }

    /**
     * Find a single slider ID. For example, last edited, or first published.
     *
     * @param string $orderby field to order.
     * @param string $order direction (ASC or DESC).
     * @return int slider ID.
     */
    private function eps_find_slider($orderby, $order) {
        $args = array(
            'force_no_custom_order' => true,
            'post_type' => 'eps-slider',
            'num_posts' => 1,
            'post_status' => 'publish',
            'orderby' => $orderby,
            'order' => $order
        );

        $the_query = new WP_Query($args);

        while ($the_query->have_posts()) {
            $the_query->the_post();
            return $the_query->post->ID;
        }

        return false;
    }
    /**
     * Set the current slider
     */
    public function eps_set_slider($id) {
        $this->slider = $this->eps_create_slider($id);
    }

    /**
     * Create a new slider based on the sliders type setting
     */
    private function eps_create_slider($id) {

        return new epsSliderContentClass($id);
    }

    private function eps_all_easy_sliders() {
        $sliders = false;

        // list the tabs
        $args = array(
            'post_type' => 'eps-slider',
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'ASC',
            'posts_per_page' => -1
        );

        $the_query = new WP_Query($args);

        while ($the_query->have_posts()) {
            $the_query->the_post();

            $active = $this->slider->id == $the_query->post->ID ? true : false;

            $sliders[] = array(
                'active' => $active,
                'title' => get_the_title(),
                'id' => $the_query->post->ID
            );
        }

        return $sliders;
    }
    function eps_register_admin_scripts() {
        wp_enqueue_media();

        // plugin dependencies

        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script('jquery-ui-core', array('jquery'));
        wp_enqueue_script('jquery-ui-sortable', array('jquery', 'jquery-ui-core'));
        wp_enqueue_script('eps-tipsy', EPS_ASSETS_URL . 'js/jquery.tipsy.js', array('jquery'), EPS_VERSION);
//		wp_enqueue_script('eps-colorpicker', EPS_ASSETS_URL . 'js/colorpicker.js', array('jquery', 'jquery-ui-core'), EPS_VERSION);
//		wp_enqueue_script('eps-cslider', EPS_ASSETS_URL . 'js/jquery.cslider.js', array('jquery', 'jquery-ui-core'), EPS_VERSION);
        wp_enqueue_script('eps-admin-script', EPS_ASSETS_URL . 'js/admin.js', array('jquery', 'eps-tipsy', 'media-upload'), EPS_VERSION);
        wp_enqueue_script('eps-admin-addslide', EPS_ASSETS_URL . 'images/image.js', array('eps-admin-script'), EPS_VERSION);
        wp_enqueue_script('eps-colorbox', EPS_ASSETS_URL . 'js/jquery.colorbox-min.js', array('jquery'), EPS_VERSION);
        wp_enqueue_script('eps-accordion', EPS_ASSETS_URL . 'js/accordion.js', array('jquery'), EPS_VERSION);

        // localise the JS
        wp_localize_script( 'eps-admin-script', 'epsscript', array(
            'url' => __("URL", $this->filename),
            'heading' => __("Heading", $this->filename),
            'content' => __("Content", $this->filename),
            'new_window' => __("New Window", $this->filename),
            'confirm' => __("Are you sure?", $this->filename),
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'iframeurl' => plugins_url('',__FILE__) . '/templates/eps_preview.php',
            'useWithCaution' => __("Caution: This setting is for advanced developers only. If you're unsure, leave it checked.", $this->filename)
        ));
    }

    function eps_register_admin_styles() {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_style('eps-admin-styles', EPS_ASSETS_URL . 'css/admin.css', false, EPS_VERSION);
//        wp_enqueue_style('eps-colorbox-styles', EPS_ASSETS_URL . 'colorbox/colorbox.css', false, EPS_VERSION);
//        wp_enqueue_style('eps-colorpicker', EPS_ASSETS_URL . 'css/colorpicker.css', false, EPS_VERSION);
        wp_enqueue_style('eps-tipsy-styles', EPS_ASSETS_URL . 'css/tipsy.css', false, EPS_VERSION);
        wp_enqueue_style('eps-colorbox', EPS_ASSETS_URL . 'css/colorbox.css', false, EPS_VERSION);
        wp_enqueue_style('eps-accordion', EPS_ASSETS_URL . 'css/accordion.css', false, EPS_VERSION);

        do_action('eps_register_admin_styles');

    }

    /**
     * Get sliders. Returns a nicely formatted array of currently
     * published sliders.
     *
     * @return array all published sliders
     */
    private function eps_all_sliders() {
        $sliders = false;

        // list the tabs
        $args = array(
            'post_type' => 'eps-slider',
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'ASC',
            'posts_per_page' => -1
        );

        $the_query = new WP_Query($args);

        while ($the_query->have_posts()) {
            $the_query->the_post();
            $active = $this->slider->id == $the_query->post->ID ? true : false;


            $sliders[] = array(
                'active' => $active,
                'title' => get_the_title(),
                'id' => $the_query->post->ID
            );
        }

        return $sliders;
    }

    /**
     * Register EPS post type
     */
    public function eps_register_post_type() {
        register_post_type('eps-slider', array(
            'query_var' => false,
            'rewrite' => false
        ));
    }

    /**
     * Register taxonomy to store slider => slides relationship
     */
    public function eps_register_taxonomy() {
        register_taxonomy( 'eps-slider', 'attachment', array(
            'hierarchical' => true,
            'public' => false,
            'query_var' => false,
            'rewrite' => false
        ));
    }

    /**
     * Initialise translations
     */
    public function eps_load_plugin_textdomain() {
        load_plugin_textdomain('eps', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    function eps_register_eps_shortcode($atts) {

        extract(shortcode_atts(array('id' => null), $atts));

        if ($id == null) return;

        // we have an ID to work with
        $slider = get_post($id);

        // check the slider is published
        if ($slider->post_status != 'publish') return false;


        // lets go
//        print_r($this->slider->eps_google_enqueue_scripts);
        $this->eps_set_slider($id);
        $this->slider->eps_enqueue_scripts();
        return $this->slider->eps_render_public_slides();
    }

}

$easyParallaxSlider = new easyParallaxSlider();
