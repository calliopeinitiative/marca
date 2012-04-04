function findValue(li,eid) {
if( li == null ) {return alert("No match!");}
else {var sValue = li;}
var nEditor = CKEDITOR.instances.eDoc_body;
var marked = nEditor.getSelection().getNative();
var url = '../documents/ppu-track_markup?eid=' + eid + '&amp;tag=' + sValue + '&amp;marked=' + marked + '&amp;type=1';
var error = CKEDITOR.dom.element.createFromHtml('<span title="'+ sValue + '" class="eDoc_error '+ sValue + '">' + nEditor.getSelection().getNative() + ' </span>');
nEditor.insertElement( error );
}
function quick_link(tag, eid){
var nEditor = CKEDITOR.instances.eDoc_body;
var sValue = tag;
var marked = nEditor.getSelection().getNative();
var url = '../documents/ppu-track_markup?eid=' + eid + '&amp;tag=' + sValue + '&amp;marked=' + marked + '&amp;type=1';
var error = CKEDITOR.dom.element.createFromHtml('<span title="'+ sValue + '" class="eDoc_error '+ sValue + '">' + nEditor.getSelection().getNative() + ' </span>');
nEditor.insertElement( error );
}
function markup(tag, eid){
var nEditor = CKEDITOR.instances.eDoc_body;
var sValue = tag;
var marked = nEditor.getSelection().getNative();
var url = '../documents/ppu-track_markup?eid=' + eid + '&amp;tag=' + sValue + '&amp;marked=' + marked + '&amp;type=2';
var markup = CKEDITOR.dom.element.createFromHtml('<span title="'+ sValue + '" class="'+ sValue + '">' + nEditor.getSelection().getNative() + ' </span>');
nEditor.insertElement( markup );
}


