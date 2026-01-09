(function() {
  // Wire up delete confirmation modal for notes list/view pages
  var deleteModal = document.getElementById('deleteNoteModal');
  if (!deleteModal) return;

  deleteModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    if (!button) return;
    var noteId = button.getAttribute('data-note-id');
    var noteTitle = button.getAttribute('data-note-title') || '';

    var idField = deleteModal.querySelector('#deleteNoteId');
    if (idField) idField.value = noteId || '';

    var titleSpan = deleteModal.querySelector('#deleteNoteTitle');
    if (titleSpan) titleSpan.textContent = noteTitle ? '"' + noteTitle + '"' : '';
  });
})();
