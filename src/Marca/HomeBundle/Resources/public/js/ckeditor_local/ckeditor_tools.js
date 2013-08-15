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
function markup(id, tag, color){
var nEditor = CKEDITOR.instances.marca_docbundle_doctype_body;
var id = id;
var tag = tag;
var color = color;
var marked = nEditor.getSelection().getNative();
var markup = CKEDITOR.dom.element.createFromHtml('<span data-id="' + id +'" title="'+ tag + '" class="markup ' + color +'">' + nEditor.getSelection().getNative() + ' </span>');
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
nEditor.insertElement( note );$('#insert_value').val('');
}
else
alert( 'You must be on WYSIWYG mode!' );
}
$(function(){
 $('#note_insert_submit').click(function(){
  note();
 });
});
function insertRubric()
{
    var page = '<div class="eDoc_rubric">' + 'test' + '</div>';
    var nEditor = CKEDITOR.instances.marca_docbundle_doctype_body;
    if ( nEditor.mode == 'wysiwyg' )
    {
        var rubric = CKEDITOR.dom.element.createFromHtml( page);
        nEditor.insertElement( rubric );
    }
    else
        alert( 'You must be on WYSIWYG mode!' );
}



