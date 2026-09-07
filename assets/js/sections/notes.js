$(function () {
	var $note = $('#noteContent');
	if (!$note.length || typeof $note.summernote !== 'function') {
		return;
	}

	$note.summernote({
		placeholder: 'Compose an epic...',
		height: Math.max(480, Math.round(window.innerHeight * 0.55)),
		dialogsInBody: true,
		disableResizeEditor: false,
		toolbar: [
			['style', ['style']],
			['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
			['para', ['ul', 'ol', 'paragraph']],
			['table', ['table']],
			['insert', ['link', 'hr']],
			['view', ['fullscreen', 'codeview', 'undo', 'redo']]
		],
		callbacks: {
			onImageUpload: function () {
				return false;
			}
		}
	});

	$('#notes_add').on('submit', function () {
		$note.val($note.summernote('code'));
	});
});
