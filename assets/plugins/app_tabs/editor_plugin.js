// http://tinymce.moxiecode.com/wiki.php/API3:class.tinymce.Plugin

(function() {

	tinymce.create('tinymce.plugins.KuAsTabs', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished its initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {

			//this command will be executed when the button in the toolbar is clicked
			ed.addCommand('mceKuAsTabs', function() {

				selection = tinyMCE.activeEditor.selection.getContent();

				//prompt for a tag to use


				var kuastabs = '';
				kuastabs += '[kuastabs width="" initialtab=1 autoplayinterval=0 color="yellow"]<br/>';
				kuastabs += '[kuastabs_tab_container]<br/>';
				kuastabs += '[kuastabs_tab]--Tab Satu--[/kuastabs_tab]<br/>' + '[kuastabs_tab]--Tab Dua--[/kuastabs_tab]<br/>' + '[kuastabs_tab]--Tab Tiga--[/kuastabs_tab]<br/>';
				kuastabs += '[/kuastabs_tab_container]<br/>[kuastabs_content_container]<br/>';
				kuastabs += '[kuastabs_content]<br/>';
				kuastabs += '[kuastabs_content_head]--Judul Satu--[/kuastabs_content_head]<br/>';
				kuastabs += '[kuastabs_inner_content]--Konten tab kesatu--[/kuastabs_inner_content][/kuastabs_content]<br/>';
				kuastabs += '[kuastabs_content]<br/>';
				kuastabs += '[kuastabs_content_head]--Judul Dua--[/kuastabs_content_head]<br/>';
				kuastabs += '[kuastabs_inner_content]--Konten tab kedua--[/kuastabs_inner_content][/kuastabs_content]<br/>';
				kuastabs += '[kuastabs_content]<br/>';
				kuastabs += '[kuastabs_content_head]--Judul Tiga--[/kuastabs_content_head]<br/>';
				kuastabs += '[kuastabs_inner_content]--Konten tab ketiga--[/kuastabs_inner_content][/kuastabs_content]<br/>';
				kuastabs += '[/kuastabs_content_container]<br/>[/kuastabs]';

				tinyMCE.activeEditor.selection.setContent(kuastabs);

			});

			ed.addButton('KuAsTabs', {
				title : 'KuAsTabs',
				cmd : 'mceKuAsTabs',
				image : url + '/tab_new.png'
			});

		},

	});

	// Register plugin
	tinymce.PluginManager.add('KuAsTabs', tinymce.plugins.KuAsTabs);
})();