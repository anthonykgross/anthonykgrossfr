$(document).ready(function(){
    $('.easyadmin #page_content').ckeditor();
    $('.easyadmin #page_abstract').ckeditor();

    // CKEDITOR.config.contentsCss = [
    //     '/build/css/app.css',
    //     '/build/css/no-js.css'
    // ];
    CKEDITOR.config.bodyClass = "contentViewport";
    CKEDITOR.config.allowedContent = true;
    // CKEDITOR.config.filebrowserImageBrowseUrl = filebrowserBrowseUrl;
    // CKEDITOR.config.filebrowserImageUploadUrl = filebrowserUploadUrl;
    CKEDITOR.config.height = '800px';

    // $('.field-tab textarea').ckeditor();
    //
    // $(document).on('click', '.field-collection-action', function() {
    //     $('.field-tab:last textarea').ckeditor();
    // });
});