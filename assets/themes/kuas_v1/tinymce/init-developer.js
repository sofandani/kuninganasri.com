jQuery(function() {
	var localize = kuas_ajax_var.kuas_locale.split('_');
    jQuery('textarea#FrontPressContent').tinymce({
    script_url : kuas_ajax_var.kuas_domain+'/assets/themes/kuas_v1/tinymce/tinymce.min.js',
    language : localize[0], theme: "modern", width: 430, height: 150, 
    plugins: ["advlist autolink link lists print preview anchor pagebreak", "searchreplace wordcount fullscreen media code emoticons", "table contextmenu directionality emoticons paste textcolor image"], menubar: "file edit insert tools table",
    toolbar: "bold italic underline strikethrough | superscript subscript | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image emoticons | preview fullscreen code",
    style_formats: [{title: 'Bold text', inline: '<strong>'}, {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}}, {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}}, {title: 'Example 1', inline: 'span', classes: 'example1'}, {title: 'Example 2', inline: 'span', classes: 'example2'}, {title: 'Table styles'}, {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}]
    });
});