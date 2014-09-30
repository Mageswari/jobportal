function apply_aacordion(data){
    var icons = {
        header: "ui-icon-circle-arrow-e",
        activeHeader: "ui-icon-circle-arrow-s"
    };
    jQuery(data).accordion({
        icons: icons,
        collapsible: true,
        active:false,
        activate: function( event, ui ){
            var $this=jQuery(this);
            $this.find('.check_google_font').each(function(){
                show_google_font(jQuery(this));
            });
        }
    });
}
function show_google_font(ele){
    var show= ele.attr('show-attr')
    var other= ele.attr('other-attr')
    if(ele.val()=='Google Font'){
        jQuery('.'+show).show();
        jQuery('.'+other).hide();
    }else if(ele.val()=='Other'){
        jQuery('.'+other).show();
        jQuery('.'+show).hide();
    } else{
        jQuery('.'+other).hide();
        jQuery('.'+show).hide();
    }
    jQuery('.eps-slider-settings-tbl tr:visible:even').css('background-color', '#F1F1F1');
    jQuery('.eps-slider-settings-tbl tr:visible:odd').css('background-color', '#F9F9F9');
}
jQuery(document).ready(function($) {
    apply_aacordion(".eps-colapsable-slider");
    jQuery('.check_google_font').each(function(){
        show_google_font(jQuery(this));
    });
    jQuery('.check_google_font').live('change',function(){
        show_google_font(jQuery(this));
    });
    // Enable the correct options for this slider type
    var switchType = function(slider) {
        jQuery('.easy-parallax-slider .option:not(.' + slider + ')').attr('disabled', 'disabled').parents('tr').hide();
        jQuery('.easy-parallax-slider .option.' + slider).removeAttr('disabled').parents('tr').show();

        // slides - set red background on incompatible slides
        jQuery('.easy-parallax-slider .slide:not(.' + slider + ')').css('background', '#FFD9D9');
        jQuery('.easy-parallax-slider .slide.' + slider).css('background', '');
    };

    // return a helper with preserved width of cells
    var helper = function(e, ui) {
        ui.children().each(function() {
            jQuery(this).width(jQuery(this).width());
        });
        return ui;
    };

    // drag and drop slides, update the slide order on drop
    jQuery(".easy-parallax-slider .left table tbody").sortable({
        helper: helper,
        handle: 'td.col-1',
        placeholder: "ui-state-highlight",
        stop: function() {
            jQuery(".easy-parallax-slider .left table").trigger('updateSlideOrder');
        }
    });

    // bind an event to the slides table to update the menu order of each slide
    jQuery('.easy-parallax-slider .left table').bind('updateSlideOrder', function(event) {
        jQuery('tr', this).each(function() {
            jQuery('input.menu_order', jQuery(this)).val(jQuery(this).index());
        });
    });

    // show the confirm dialogue
    jQuery(".confirm").live('click', function() {
        return confirm(epsscript.confirm);
    });

    $('.useWithCaution').change(function(){
        if(!this.checked) {
            return alert(epsscript.useWithCaution);
        }
    });

    // show the confirm dialogue
    jQuery(".toggle").live('click', function(e) {
        e.preventDefault();
        jQuery(this).next('.message').toggle();
    });

    // helptext tooltips
    jQuery(".easy-parallax-slider .tipsy-tooltip").tipsy({className: 'msTipsy', live: true, delayIn: 500, html: true, fade: true, gravity: 'e'});
    jQuery(".easy-parallax-slider .tipsy-tooltip-top").tipsy({live: true, delayIn: 500, html: true, fade: true, gravity: 'se'});

    // Select input field contents when clicked
    jQuery(".easy-parallax-slider .shortcode input").click(function() {
        this.select();
    });

    // show the spinner while slides are being added
    function checkPendingRequest() {
        if (jQuery.active > 0) {
            jQuery(".easy-parallax-slider .spinner").show();
            jQuery(".easy-parallax-slider input[type=submit]").attr('disabled', 'disabled');
        } else {
            jQuery(".easy-parallax-slider .spinner").hide();
            jQuery(".easy-parallax-slider input[type=submit]").removeAttr('disabled');
        }

        setTimeout(checkPendingRequest, 1000);
    }

    checkPendingRequest();

    // return lightbox width
    var getLightboxWidth = function() {
        var widthval=jQuery('input.width').val();
        var width = parseInt(widthval, 10) + 'px';
        if(widthval=='' || widthval==0){
            width='80%';
        }
        if (jQuery('#carouselMode').is(':checked')) {
            width = '75%';
        }

        return width;
    };

    // return lightbox height
    var getLightboxHeight = function() {
        var height = parseInt(jQuery('input.height').val(), 10);

        if (!isNaN(height)) {
            height = height + 30 + 'px'
        } else {
            height = '70%';
        }

        return height;
    };

    // AJAX save & preview
    jQuery(".easy-parallax-slider form").find("input[type=submit]").click(function(e) {

        e.preventDefault();

        // update slide order
        jQuery(".easy-parallax-slider .left table").trigger('updateSlideOrder');

        // get some values from elements on the page:
        var the_form = jQuery(this).parents("form");
        var data = the_form.serialize();
        var url = the_form.attr( 'action' );
        var button = e.target;

        jQuery(".easy-parallax-slider .spinner").show();
        jQuery(".easy-parallax-slider input[type=submit]").attr('disabled', 'disabled');

        jQuery.ajax({
            type: "POST",
            data : data,
            cache: false,
            url: url,
            success: function(data) {

                // update the slides with the response html
                $(".easy-parallax-slider .left tbody").html($(".easy-parallax-slider .left tbody", data).html());
                apply_aacordion(jQuery('.easy-parallax-slider').find('.eps-colapsable-slider'));

                jQuery('.easy-parallax-slider').find('.settingColorSelector').wpColorPicker();
                if (button.id === 'preview') {
                    jQuery.colorbox({
                        iframe: true,
                        href: epsscript.iframeurl + "?slider_id=" + jQuery(button).data("slider_id"),
                        transition: "elastic",
                        innerHeight: getLightboxHeight(),
                        innerWidth: getLightboxWidth(),
                        scrolling: false,
                        fastIframe: false
                    });
                }
            }
        });
    });


    jQuery('.settingColorSelector').wpColorPicker();

    jQuery('.eps-slider-settings-tbl tr:visible:even').css('background-color', '#F1F1F1');
});
