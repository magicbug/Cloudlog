var quill = new Quill('#quillArea', { 
	placeholder: 'Compose an epic...',
	theme: 'snow'
});

$("#notes_add").on("submit",function(){
	$("#hiddenArea").val(quill.root.innerHTML);
})