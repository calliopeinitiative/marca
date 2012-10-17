function findValue(li) {
if( li == null ) {alert("No match!");}
else {var sValue = li;}
var nEditor = CKEDITOR.instances.marca_docbundle_doctype_body;
var marked = nEditor.getSelection().getNative();
var error = CKEDITOR.dom.element.createFromHtml('<span title="'+ sValue + '" class="eDoc_error '+ sValue + '">' + nEditor.getSelection().getNative() + ' </span>');
nEditor.insertElement( error );
}
function quick_link(tag){
var nEditor = CKEDITOR.instances.marca_docbundle_doctype_body;
var sValue = tag;
var marked = nEditor.getSelection().getNative();
var error = CKEDITOR.dom.element.createFromHtml('<span title="'+ sValue + '" class="eDoc_error '+ sValue + '">' + nEditor.getSelection().getNative() + ' </span>');
nEditor.insertElement( error );
}
function markup(tag, color){
var nEditor = CKEDITOR.instances.marca_docbundle_doctype_body;
var tag = tag;
var color = color;
var marked = nEditor.getSelection().getNative();
var markup = CKEDITOR.dom.element.createFromHtml('<span title="'+ tag + '" class="'+ tag + ' markup ' + color +'">' + nEditor.getSelection().getNative() + ' </span>');
nEditor.insertElement( markup );
}
function note()
{
var note_id = new Date().getTime();
var nEditor = CKEDITOR.instances.marca_docbundle_doctype_body;
if ( nEditor.mode == 'wysiwyg' )
{
var hightlight = CKEDITOR.dom.element.createFromHtml('<span class="eDoc_highlight ' + note_id + '">' + nEditor.getSelection().getNative() + ' </span>');
nEditor.insertElement( hightlight );
var note = CKEDITOR.dom.element.createFromHtml( '<span class="eDoc_note ' + note_id + '"> '+ document.getElementById('insert_value').value + ' </span>');
nEditor.insertElement( note );$( "form#note_insert_form" )[ 0 ].reset();
}
else
alert( 'You must be on WYSIWYG mode!' );
}
$(function(){
 $('#note_insert_submit').click(function(){
  note();
 });
});


