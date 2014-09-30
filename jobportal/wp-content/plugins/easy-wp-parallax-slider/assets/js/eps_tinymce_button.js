(function() {
var obj=[];
    var post=jQuery.parseJSON(epsposts);
    jQuery(post).each(function(ind,val){
        if(typeof val=='object'){
            var n={text:val['title'], onclick : function() {
                tinyMCE.activeEditor.selection.setContent('[epsshortcode id='+val['id']+']');
            }}
            obj.push(n)

        }
    });

    tinymce.create('tinymce.plugins.eps_editor_icon', {

        init : function(ed, url) {

            ed.addButton( 'eps_editor_icon_button', {
                type: 'splitbutton',
                title: "Easy WP Parallax Slider",
                icon:'eps_image_icon',
                image: epsassetsurl+'images/osc-icon-dropdown.png',
                class: "osc_eps_dropdown",
                onclick: function(e) {
                },
                menu: obj

            });

        }


    });

    tinymce.PluginManager.add('eps_editor_icon', tinymce.plugins.eps_editor_icon);
})();
jQuery(window).load(function(){
    console.log(jQuery('.mce-ico.mce-i-eps_editor_icon_button'));
    jQuery('.mce-ico.mce-i-eps_image_icon').parents('div').addClass('eps_custom_class');

});