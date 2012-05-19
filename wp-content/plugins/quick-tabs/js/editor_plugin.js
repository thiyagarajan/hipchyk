 
(function() {

    tinymce.create('tinymce.plugins.wpqt_tinyplugin', {

        init : function(ed, url){            
            ed.addCommand('mcequicktabs', function() {
                                ed.windowManager.open({
                                        title: 'Quick Tabs',
                                        file : 'admin.php?page=quick-tabs&execute=wpqt_tinymce_button',
                                        height: 200,
                                        width:400,                                        
                                        inline : 1
                                }, {
                                        plugin_url : url, // Plugin absolute URL
                                        some_custom_arg : 'custom arg' // Custom argument
                                });
                        });
            
            ed.addButton('wpqt_tinyplugin', {
                title : 'Quick Tabs: Insert Tab',
                cmd : 'mcequicktabs',
                image: url + "/img/tabs16.png"
            });
        },

        getInfo : function() {
            return {
                longname : 'Quick Tabs - TinyMCE Button Add-on',
                author : 'Shaon',
                authorurl : 'http://www.wpdownloadmanager.com',
                infourl : 'http://www.wpdownloadmanager.com',
                version : "1.0"
            };
        }
    });

    tinymce.PluginManager.add('wpqt_tinyplugin', tinymce.plugins.wpqt_tinyplugin);
    
})();
