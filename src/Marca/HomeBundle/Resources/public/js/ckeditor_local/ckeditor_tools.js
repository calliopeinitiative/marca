function findValue(li) {
if( li == null ) {return alert("No match!");}
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
function markup(tag){
var nEditor = CKEDITOR.instances.marca_docbundle_doctype_body;
var sValue = tag;
var marked = nEditor.getSelection().getNative();
var markup = CKEDITOR.dom.element.createFromHtml('<span title="'+ sValue + '" class="'+ sValue + '">' + nEditor.getSelection().getNative() + ' </span>');
nEditor.insertElement( markup );
}
function InsertHTML()
{
var note_id = new Date().getTime();
var mouseover = "noteHightlight('" + note_id + "')";
var mouseout = "fadeHightlight('" + note_id + "')";
var nEditor = CKEDITOR.instances.eDoc_body;
if ( nEditor.mode == 'wysiwyg' )
{
var hightlight = CKEDITOR.dom.element.createFromHtml('<span  onmouseover="' + mouseover + '" class="m_highlight s' + note_id + '">' + nEditor.getSelection().getNative() + ' </span>');
nEditor.insertElement( hightlight );
var note = CKEDITOR.dom.element.createFromHtml( '<span class="eDoc_m_note n' + note_id + '"> '+ document.getElementById('insert_value').value + ' </span>');
nEditor.insertElement( note );$( "form#note_insert_form" )[ 0 ].reset();
}
else
alert( 'You must be on WYSIWYG mode!' );
}


