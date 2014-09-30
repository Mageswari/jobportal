(function(){
    tinymce.create('tinymce.plugins.eps_editor_icon', {
        init: function(ed, url){},
        createControl: function(button, e){
            if(button == "eps_editor_icon_button"){
                var current_object = this;
                var button = e.createSplitButton('eps_editor_button', {
                    title: "Easy WP Parallax Slider",
                    image: epsassetsurl+'images/osc-icon-dropdown.png',
                    icons: true,
                    role: 'presentation',
                    "class": "osc_eps_dropdown",
                    onclick:function(){
                    }
                });
                button.onRenderMenu.add(function(c, b){
                    var post=jQuery.parseJSON(epsposts);
                    //Design Elements
                    jQuery(post).each(function(ind,val){
                        if(typeof val=='object'){
                            current_object.eps_element_call(b, val['title'], val['id']);
                        }
                    })
                });
                return button;
            }
            return null;
        },
        eps_element_call: function(ed, title, value){
            ed.add({
                title: title,
                icons: false,
                role: 'presentation',
                onclick: function (){
                    tinyMCE.activeEditor.selection.setContent('[epsshortcode id='+value+']');
                    return false;
                }
            })
        }

    });
    tinymce.PluginManager.add("eps_editor_icon", tinymce.plugins.eps_editor_icon);
})();