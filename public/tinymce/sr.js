var bg_mode = (sessionStorage['background'] == 'dark') ? 'dark' : 'default';
var skin    = (sessionStorage['background'] == 'dark') ? 'oxide-dark' : 'oxide';
tinymce.init({
  forced_root_block : "",
  selector: 'textarea.editor',
  skin: skin,
  content_css: bg_mode,
  plugins: 'preview paste searchreplace autolink image directionality code visualblocks visualchars link media codesample table charmap hr pagebreak nonbreaking toc insertdatetime advlist lists wordcount imagetools textpattern noneditable charmap quickbars emoticons',
  imagetools_cors_hosts: ['picsum.photos'],
  menubar: 'file edit view insert format tools table',
  toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview | insertfile image media link codesample | ltr rtl',
  toolbar_sticky: false,
  image_advtab: true,
  height: 470,
  image_caption: true,
  quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quicktable',
  noneditable_noneditable_class: "mceNonEditable",
  toolbar_mode: 'sliding',
  contextmenu: "link image imagetools table",
 });